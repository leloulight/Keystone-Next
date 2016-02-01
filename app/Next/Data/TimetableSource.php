<?php

	namespace App\Next\Data;
	
	use App\Next\Models\Lesson;
	use Carbon\Carbon;

	class TimetableSource extends DataSource {

		public static $database_name = 'homework';

		const TIMETABLE_URL = 'Pages/MyTimetable.aspx';

		const KEY_ID = 'id';
		const KEY_TITLE = 'title';
		const KEY_DESCRIPTION = 'description';
		const KEY_CHECKED = 'checked';
		const KEY_CANCOLOUR = 'canColour';
		const KEY_CANSUBMIT = 'canSubmit';
		const KEY_CANDELETE = 'canDelete';
		const KEY_SUBJECT = 'subject';
		const KEY_START = 'start';
		const KEY_END = 'end';

		public static function keys($user) {
			$html = new \Htmldom();
			$html->load(self::keystone_exec(self::TIMETABLE_URL, $user));

			return array(
				'__EVENTVALIDATION' => $html->find('input[name=__EVENTVALIDATION]')[0]->value,
				'__VIEWSTATE' => $html->find('input[name=__VIEWSTATE]')[0]->value
			);
		}

		public static function update($user) {
			if(!$user->can_fetch())
				return [];
			// Add the next fornight's lessons
			$keys = static::keys($user);
			echo "Got authentication keys.\n";
			$lessons = array();

			$date = Carbon::now();

			$periods = [
				[[8, 35], 	[8, 45]],
				[[8, 45], 	[9, 40]],
				[[9, 40], 	[10, 35]],
				[[10, 55], 	[11, 45]],
				[[11, 45], 	[12, 35]],
				[[13, 25],	[13, 50]],
				[[13, 50], 	[14, 40]],
				[[14, 40], 	[15, 30]],
			];

			for ($d=0; $d < 14; $d++) { 
				if (!$date->isWeekend()) {
				echo "Adding: " . $date->toCookieString() . "\n";
					$date_str = $date->format('j/m/Y');

					$html = new \Htmldom();
					$html->load(self::keystone_post(self::TIMETABLE_URL, array(
						'__VIEWSTATE' => $keys['__VIEWSTATE'],
						'__EVENTVALIDATION' => $keys['__EVENTVALIDATION'],
						'ctl00$PlaceHolderMain$ctl00$_uiCurrentDaySel$_uiCurrentDaySelDate' => $date_str,
					), $user));

					$lesson_uls = $html->find('ul[class=TimetableEntry]');

					$start_lesson_n = count($lessons);

					foreach ($lesson_uls as $index => $lesson) {
						$times_raw = trim($lesson->find('li[class=TimetableEntryLesson]')[0]->innertext);
						$subject_raw = trim($lesson->find('li[class=TimetableEntryClassName]')[0]->innertext);
						$location_raw = trim($lesson->find('li[class=TimetableEntryLocation]')[0]->innertext);

						preg_match_all('/(\d+:\d\d [AP]M) - (\d+:\d\d [AP]M)/', $times_raw, $time_matches);
						$start = Carbon::createFromFormat('g:i A j/m/Y', $time_matches[1][0] . ' ' . $date_str);
						$end = Carbon::createFromFormat('g:i A j/m/Y', $time_matches[2][0] . ' ' . $date_str);

						preg_match_all('/Class: (?:\d+|\d+\w+|\w+) - (?:(.+)(?:\s\d+$|\s\w{3}$|\s\d+\w)|(.+))/', $subject_raw, $subject_matches);
						$subject = strlen($subject_matches[1][0]) == 0 ? $subject_matches[2][0] : $subject_matches[1][0];

						if (preg_match("/\d\dPC/", $subject))
							$subject = "Pastoral Care";

						preg_match_all('/Location: (.+)/', $location_raw, $location_matches);
						$location = $location_matches[1][0];

						// is a double
						if ($index != 0 && end($lessons) && end($lessons)['subject'] == $subject)
							$lessons[count($lessons) - 1]['end'] = $end;
						else
							$lessons[] = array(
								'start' => $start,
								'end' => $end,
								'subject' => $subject,
								'location' => $location
							);
					}

					$min_period = 0;
					$insert_frees = [];
					for ($index = $start_lesson_n; $index < count($lessons); $index++) { 
						$lesson = $lessons[$index];

						$start_hour = $lesson['start']->hour;
						$start_minute = $lesson['start']->minute;
						$end_hour = $lesson['end']->hour;
						$end_minute = $lesson['end']->minute;
						$start_min = $min_period;
						$found = false;
						for ($i = $min_period; $i < count($periods); $i++) { 
							$period = $periods[$i];
							if (($start_hour == $period[0][0] && $start_minute == $period[0][1])) {
								$min_period = $i + 1;
								$found = true;
							}
							else if ($end_hour == $period[1][0] && $end_minute == $period[1][1]) {
								$min_period = $i + 1;
								$found = true;
							}
							else {
								if (!$found){
									$min_period ++;
									$end = $lesson['end']->copy();
									$end->hour = $period[1][0];
									$end->minute = $period[1][1];
									if (isset($insert_frees[$index]))
										$insert_frees[$index]['end'] = $end;
									else {
										$start = $lesson['start']->copy();
										$start->hour = $period[0][0];
										$start->minute = $period[0][1];
										$insert_frees[$index] = array(
											'start' => $start,
											'end' => $end,
											'subject' => "Free",
											'location' => ""
										);
									}
								}
								else
									break;

							}
						}
					}

					$inserted = 0;
					foreach ($insert_frees as $index => $value) {
						array_splice($lessons, $index + $inserted, 0, array($value));
						$inserted ++;
					}
				}
				$date->addDay();
			}

			// foreach ($lessons as $key => $value) {
			// 	var_dump($value['subject']);
			// 	var_dump($value['start']->toCookieString());
			// }

			echo "Purging old lessons.\n";

			$user->lessons()->delete();

			foreach ($lessons as $index => $lesson) {
				Lesson::updateOrCreate(array(
					'start' => $lesson['start'],
					'end' => $lesson['end'],
					'subject' => $lesson['subject'],
					'location' => $lesson['location'],
					'user_id' => $user->user_id
				));
			}
			echo "Inserted new lessons.\n";
		}

	}

?>


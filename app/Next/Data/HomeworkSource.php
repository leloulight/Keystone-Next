<?php

	namespace App\Next\Data;
	
	use App\Next\Models\User;
	use App\Next\Models\Homework;
	use Carbon\Carbon;

	class HomeworkSource extends DataSource {

		public static $database_name = 'homework';

		const MYTASKS_URL = '_layouts/StPeters.Keystone/MyTasks/MyTasksHttpHandler.ashx?action=list';

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

		public static function raw_data($user) {
			return self::keystone_json(self::MYTASKS_URL, false, $user);
		}

		public static function update($user, $no_update_time = false) {
			if(!$user->can_fetch())
				return [];
			$raw_data = self::raw_data($user);
			if(!$raw_data)
				return [];

			$tasks = array();
			foreach ($raw_data->events as $index => $data) {
				$end = $data->end ? new Carbon($data->end) : (new Carbon($data->start))->setTime(23, 59, 59);
				$subject = $data->schoolClass ? trim(preg_replace("/\((.*?)\)/", "", $data->schoolClass)) : null;

				$lesson = $subject ? $user->next_lesson_of($subject, $end) : null;
				if ($lesson) {
					$end = new Carbon($lesson->start);
				}

				$title = $data->title ? trim(preg_replace("/\[(.*?)\] /", "", $data->title)) : null;
				$description = $data->description ? trim($data->description) : null;
				$start = date("Y-m-d H:i:s", strtotime($data->start));
				if ($homework = Homework::find($data->id)){
					$homework->title = $title;
					$homework->description = $description;
					$homework->subject = $subject;
					$homework->start = $start;
					$homework->end = $end->format("Y-m-d H:i:s");
					$homework->can_delete = $data->canDelete == "1";
					$homework->can_submit = $data->canSubmit == "1";
					$homework->save();
				}
				else {
					$homework = Homework::updateOrCreate(array(
		                'homework_id' => $data->id,
		                'title' => $title,
		                'description' => $description,
		                'subject' => $subject,
		                'start' => $start,
		                'end' => $end->format("Y-m-d H:i:s"),
		                'can_delete' => $data->canDelete == "1", 
		                'can_submit' => $data->canSubmit == "1"
		            ));
		        }

				if(!$homework->users->contains($user->user_id))
					$homework->users()->attach($user->user_id, ['complete' => $data->taskStatusId == "2"]);
				elseif ($user->homework->find($data->id)->pivot->complete != ($data->taskStatusId == "2"))
					$homework->users()->updateExistingPivot($user->user_id, ['complete' => $data->taskStatusId == "2"]);

				if ($no_update_time) {
					\DB::table('users_homework')->where('user_id', $user->user_id)->where('homework_id', $data->id)->update(['updated_at' => 0]);
				}

				$tasks[] = $homework;
			}

			return $tasks;
		}

		public static function mark_complete($id, $complete = true) {
			$result = static::keystone_exec('_layouts/StPeters.Keystone/MyTasks/MyTasksHttpHandler.ashx?action=SETSTATUS&id=' . $id . '&s=' . ($complete ? "2" : "1"));
			return $result == 'OK';
		}

	}

?>
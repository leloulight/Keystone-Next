<?php

	namespace App\Next\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Auth\Authenticatable;
	use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
	use App\Next\Data\DataSource;
	use \Carbon\Carbon;

	class User extends Model implements AuthenticatableContract {

		use Authenticatable;

		public $incrementing = false;
		protected $primaryKey = 'user_id';
		protected $fillable = ['user_id', 'name', 'password', 'is_queued'];
		protected $hidden = ['password', 'remember_token'];

		public function homework() {
			return $this->belongsToMany('App\Next\Models\Homework', 'users_homework', 'user_id', 'homework_id')->withTimestamps()->withPivot('complete');
		}

		public function notifications() {
			return $this->belongsToMany('App\Next\Models\Notification', 'users_notifications', 'user_id', 'notification_id')->withTimestamps()->withPivot('is_read');
		}

		public function pastoralcare() {
			return $this->belongsToMany('App\Next\Models\PastoralCare', 'users_pastoralcare', 'user_id', 'pastoralcare_id')->withTimestamps();
		}

		public function lessons() {
	        return $this->hasMany('App\Next\Models\Lesson');
		}

		public function person() {
	        return $this->belongsTo('App\Next\Models\Person', 'user_id', 'person_id');
		}

		public function matches() {
			return $this->person->matches();
		}

		public function password() {
			return \Crypt::decrypt($this->password);
		}

		public static function active() {
			return \Auth::user();
		}

		public function can_fetch(){
			return $this->password != null;
		}

		public function check_login() {
			if(!DataSource::check_login($this->user_id, $this->password())){
				$this->password = null;
				$this->save();
			}
		}

		public function next_lesson_of($name, $date = false) {
			$date = $date ? Carbon::instance($date) : Carbon::now();
			foreach ($this->lessons()->where('start', '>=', $date->setTime(0, 0, 0)->format("Y-m-d H:i:s"))->where('end', '<=', $date->setTime(23, 59, 59)->format("Y-m-d H:i:s"))->get() as $lesson) {
				if ($lesson->subject == $name)
					return $lesson;
			}
		}

		private function time_index($date, $now) {
			return static::time_heading($date, $now, true);
		}

		private function time_heading($date, $now, $addIndex = false) {
			$days = $date->dayOfYear - $now->dayOfYear;
			$str = $days > 0 ? 'In ' . $days . ' days' : abs($days) . ' days ago';
			$index = $days;

			switch ($days) {

				case -1:
					$str = 'Yesterday';
					break;

				case 0:
					$str = 'Today';
					break;

				case 1:
					$str = 'Tomorrow';
					break;

				case ($days > 0 && $days <= 6):
					$str = $date->format('l');
					break;

				case ($days > 6):
					$str = 'In a week';
					$index = 7;
					break;

				case ($days > 13):
					$str = 'In ' . ceil(abs($days) / 7) . ' weeks';
					$index = 7 * ceil(abs($days) / 7);
					break;

				case ($days > 21):
					$str = 'In a month';
					$index = 28;
					break;

				case ($days > 40):
					$str = 'In ' . ceil(abs($days) / 28) . ' months';
					$index = 28 * ceil(abs($days) / 28);
					break;

				case ($days < -40):
					$str = ceil(abs($days) / 28) . ' months ago';
					$index = -28 * ceil(abs($days) / 28);
					break;

				case ($days < -21):
					$str = 'A month ago';
					$index = -28;
					break;

				case ($days < -13):
					$str = ceil(abs($days) / 7) . ' weeks ago';
					$index = -7 * ceil(abs($days) / 7);
					break;

				case ($days >= -6):
					$str = 'Last ' . $date->format('l');
					break;

				case ($days < -6):
					$str = 'A week ago';
					$index = -7;
					break;
			}

			return ($addIndex ? $index : $str);
		}

		public function homework_feed() {
			// All uncomplete (except for those marked 15 minutes ago), with oldest being 2 weeks old and newest starting in less than a week
			// ->wherePivot('complete', '=', 'false')->orWherePivot('updated_at', '>', Carbon::now()->subMinutes(15))
			$items = $this->homework()->orderBy('end', 'ASC')->where('end', '>', date("Y-m-d H:i:s", time() - 60 * 60 * 24 * 7))->where('start', '<', date("Y-m-d H:i:s", time() + 60 * 60 * 24 * 7))->get();
			$headings = [];

			foreach ($items as $value) {
				$now = Carbon::now();
				$index = static::time_index($value->end, $now);
				$heading = static::time_heading($value->end, $now);

				if (!isset($headings[$index]))
					$headings[$index] = [
						'heading' => $heading,
						'items' => []
					];

				$headings[$index]['items'][] = $value;
			}

			ksort($headings);

			return $headings;
		}

		public function timetable_feed() {
			// All uncomplete (except for those marked 15 minutes ago), with oldest being 2 weeks old and newest starting in less than a week
			// ->wherePivot('complete', '=', 'false')->orWherePivot('updated_at', '>', Carbon::now()->subMinutes(15))
			$items = $this->lessons()->where('end', '>', date("Y-m-d H:i:s", time()))->where('start', '<', date("Y-m-d H:i:s", time() + 60 * 60 * 24 * 7))->orderBy('end', 'ASC')->get();

			$headings = [];

			foreach ($items as $value) {
				$now = Carbon::now();
				$index = static::time_index($value->start, $now);
				$heading = static::time_heading($value->start, $now);

				if (!isset($headings[$index]))
					$headings[$index] = [
						'heading' => $heading,
						'items' => []
					];

				$headings[$index]['items'][] = $value;
			}

			ksort($headings);

			return $headings;
		}

		public function notifications_feed() {
			$items = $this->notifications()->orderBy('date', 'DESC')->wherePivot('is_read', '=', 'false')->get();
			return $items;
		}

		public function pastoralcare_feed() {
			$items = $this->pastoralcare()->orderBy('date', 'DESC')->get();
			return $items;
		}

		public function sportszone_feed() {
			$items = $this->matches()->where('date', '>', date("Y-m-d H:i:s", time()) - 60 * 60 * 24 * 7 * 5)->where('date', '<', date("Y-m-d H:i:s", time() + 60 * 60 * 24 * 7 * 5))->orderBy('date', 'DESC')->get();

			$headings = [];

			foreach ($items as $value) {
				$now = Carbon::now();
				$index = static::time_index($value->date, $now);
				$heading = static::time_heading($value->date, $now);

				if (!isset($headings[$index]))
					$headings[$index] = [
						'heading' => $heading,
						'items' => []
					];

				$headings[$index]['items'][] = $value;
			}

			ksort($headings);

			return array_reverse($headings);
		}

		public function mark_homework_complete($id, $complete = true) {
			$this->homework()->updateExistingPivot($id, ['complete' => $complete]);
		}

		public function mark_notification_read($id) {
			$this->notifications()->updateExistingPivot($id, ['is_read' => true]);
		}

		public function mark_notification_read_all() {
			foreach ($this->notifications as $key => $value) {
				$this->notifications()->updateExistingPivot($value->notification_id, ['is_read' => true]);
			}
		}
		 
		private function divide(array $arr) {
		    if (1 === count($arr)) {
		        return $arr;
		    }
		    $left = $right = array();
		    $middle = round(count($arr)/2);
		    for ($i = 0; $i < $middle; ++$i) {
		        $left[] = $arr[$i];
		    }
		    for ($i = $middle; $i < count($arr); ++$i) {
		        $right[] = $arr[$i];
		    }
		    $left = $this->divide($left);
		    $right = $this->divide($right);
		    return $this->conquer($left, $right);
		}
		 
		private function conquer(array $left, array $right) {
		    $result = array();
		    while (count($left) > 0 || count($right) > 0) {
		        if (count($left) > 0 && count($right) > 0) {
		            $firstLeft = current($left);
		            $firstRight = current($right);
		            if ($firstLeft->end <= $firstRight->end) {
		                $result[] = array_shift($left);
		            } else {
		                $result[] = array_shift($right);
		            }
		        } else if (count($left) > 0) {
		            $result[] = array_shift($left);
		        } else if (count($right) > 0) {
		            $result[] = array_shift($right);
		        }
		    }
		    return $result;
		}

	}

?>
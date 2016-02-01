<?php

	namespace App\Next\Models;

	use Illuminate\Database\Eloquent\Model;

	class Homework extends Model {

		public $incrementing = false;
		protected $table = 'homework';
		protected $primaryKey = 'homework_id';
		protected $fillable = ['homework_id', 'title', 'description', 'subject', 'start', 'end', 'can_delete', 'can_submit'];

		public function users() {
			return $this->belongsToMany('App\Next\Models\User', 'users_homework', 'homework_id', 'user_id')->withTimestamps()->withPivot('complete');
		}

		public function due_time() {
			$time_until = abs($this->end->timestamp - time());

			if ($time_until <= 60)
				return '1m';
			else if ($time_until <= 60 * 55)
				return round($time_until / 60) . 'm';
			else if ($time_until <= 60 * 60 * 23)
				return round($time_until / 60 / 60) . 'h';
			else if ($time_until <= 60 * 60 * 24 * 6)
				return round($time_until / 60 / 60 / 24) . 'd';
			else if ($time_until <= 60 * 60 * 24 * 350)
				return round($time_until / 60 / 60 / 24 / 7) . 'w';
			else
				return round($time_until / 60 / 60 / 24 / 7 / 52) . 'y';
		}

		public static function date($date) {
			$date_str = date("Y-m-d H:i:s", $date);
			echo $date_str;
			return self::where('start', '<=', $date_str)->where('end', '>', $date_str)->get();
		}

		public static function today() {
			return static::date(time());
		}

		public function getDates() {
		    return array('created_at', 'updated_at', 'start', 'end');
		}

		
	}

?>
<?php

	namespace App\Next\Models;

	use Illuminate\Database\Eloquent\Model;
	use App\Next\Models\Person;

	class Notification extends Model {

		public $incrementing = false;
		protected $table = 'notifications';
		protected $primaryKey = 'notification_id';
		protected $fillable = ['notification_id', 'title', 'description', 'author_id', 'date'];

		public function users() {
			return $this->belongsToMany('App\Next\Models\User', 'users_notifications', 'notification_id', 'user_id')->withTimestamps()->withPivot('is_read');
		}

		public function author() {
	        return $this->hasOne('App\Next\Models\Person', 'person_id', 'author_id');
	        // return Person::find($this->author_id);
	    }

		public function due_time() {
			$time_until = abs($this->date->timestamp - time());

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

		public function getDates() {
		    return array('created_at', 'updated_at', 'date');
		}

	}

?>
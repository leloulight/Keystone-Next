<?php

	namespace App\Next\Models;

	use Illuminate\Database\Eloquent\Model;

	class PastoralCare extends Model {

		public $incrementing = false;
		protected $table = 'pastoralcare';
		protected $primaryKey = 'pastoralcare_id';
		protected $fillable = ['pastoralcare_id', 'type', 'category', 'description', 'author', 'date'];

		public function users() {
			return $this->belongsToMany('App\Next\Models\User', 'users_pastoralcare', 'pastoralcare_id', 'user_id')->withTimestamps();
		}

		public function person() {
			return Person::from_name($this->author);
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
<?php

	namespace App\Next\Models;

	use Illuminate\Database\Eloquent\Model;
	use \Carbon\Carbon;

	class Lesson extends Model {

		public $incrementing = false;
		protected $fillable = ['user_id', 'subject', 'location', 'start', 'end'];

		public function user() {
	        return $this->belongsTo('App\Next\Models\User', 'user_id', 'id');
	    }

		public function due_time() {
			$time_until = abs($this->start->timestamp - time());

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

		public function getDates() {
		    return array('created_at', 'updated_at', 'start', 'end');
		}

	}

?>
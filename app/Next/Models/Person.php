<?php

	namespace App\Next\Models;

	use Illuminate\Database\Eloquent\Model;
	use \Carbon\Carbon;

	class Person extends Model {

		public $incrementing = false;
		protected $table = 'people';
		protected $primaryKey = 'person_id';
		protected $fillable = array('person_id', 'security_identifier', 'name', 'email', 'user_type', 'house', 'job_title', 'year_level');

		public function matches() {
			return $this->belongsToMany('App\Next\Models\Match', 'peoples_matches', 'person_id', 'match_id')->withTimestamps()->withPivot('is_staff')->withPivot('is_team');
		}

		public function notifications() {
			return $this->hasMany('App\Next\Models\Notification', 'notification_id', 'person_id');
		}

		static public function from_name($name) {
			return static::where('name', '=', $name)->first();
		}

		static public function from_names($names) {
			$people = [];
			foreach ($names as $index => $name) {
				if ($person = static::from_name($name))
					$people[] = $person;
			}
			return $people;
		}

		public function profile_image_path() {
			return "/profile/" . $this->person_id;
		}

		public function user() {
	        return $this->belongsTo('App\Next\Models\User', 'person_id', 'user_id');
		}

	}

?>
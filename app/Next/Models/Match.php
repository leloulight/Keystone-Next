<?php

	namespace App\Next\Models;

	use Illuminate\Database\Eloquent\Model;
	use \Carbon\Carbon;

	class Match extends Model {

		public $incrementing = false;
		protected $table = 'matches';
		protected $primaryKey = 'match_id';
		protected $fillable = ['match_id', 'team_name', 'opponent_name', 'venue', 'location', 'result', 'score_self', 'score_opponent', 'pre_comments', 'post_comments'];

		public function members() {
			return $this->belongsToMany('App\Next\Models\Person', 'peoples_matches', 'match_id', 'person_id')->withTimestamps()->withPivot('is_staff')->withPivot('is_team');
	    }

		public function staff_members() {
			return $this->members()->wherePivot('is_staff', '=', '1')->get();
	    }

		public function team_members() {
			return $this->members()->wherePivot('is_team', '=', '1')->get();
	    }

		public function getDates() {
		    return array('created_at', 'updated_at', 'date');
		}

	}

?>
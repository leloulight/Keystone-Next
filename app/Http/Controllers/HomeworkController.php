<?php namespace App\Http\Controllers;

use App\Next\Models\User;
use App\Next\Models\Match;
use App\Next\Models\Person;
use App\Next\Models\Lesson;
use App\Next\Data\DataSource;
use App\Next\Data\TimetableSource;
use App\Next\Data\HomeworkSource;
use App\Next\Data\NotificationsSource;
use App\Next\Data\PastoralCareSource;
use App\Next\Data\SportsZoneSource;
use App\Next\Data\PeopleSource;
use DB;
use Artisan;

class HomeworkController extends Controller {

	function update() {
		// $stream = fopen('foo.txt', 'w');
		// Artisan::call('my-command', array(), new StreamOutput($stream));
		// SportsZoneSource::update(User::find(60829));
		// PeopleSource::update();
		// Lesson::truncate();
		// TimetableSource::update(User::find(40897));
		// $match = Match::find(6921);

		// $attach = [
		// 	'60829' => 2
		// ];

		// foreach ($attach as $user_id => $value) {
		// 	if(!$match->members->contains($user_id))
		// 		$match->members()->attach($user_id, ['is_team' => ($value & 1) == 1, 'is_staff' => ($value & 2) == 2]);
		// 	else
		// 		$match->members()->updateExistingPivot($user_id, ['is_team' => ($value & 1) == 1, 'is_staff' => ($value & 2) == 2]);
		// }

		// foreach (User::all() as $index => $user) {
		// // var_dump($user->user_id);
		// 	// HomeworkSource::update($user);
		// 	TimetableSource::update($user);
		// }



		// foreach (User::all() as $index => $user) {
		// 	var_dump($user->user_id);
		// 	try {
		// 		HomeworkSource::update($user);
		// 	} catch (\Exception $e) {
		// 		var_dump($e);
		// 		exit;
		// 	}
		// }
	}

	function updateNotifications() {
		foreach (User::all() as $index => $user) {
			NotificationsSource::update($user);
		}
	}

	function purge() {
		DB::table('homework')->delete();
	    DB::table('users_homework')->delete();
	}

	function test(){
		SportsZoneSource::update();
		return '-';
	}

	function index() {
		return view('feed');
	}

	function complete($id, $complete = true) {
		$complete = filter_var($complete, FILTER_VALIDATE_BOOLEAN);
		if (HomeworkSource::mark_complete($id, $complete)){
			User::active()->mark_homework_complete($id, $complete);
			return 'true';
		}
		return 'false';
	}


}

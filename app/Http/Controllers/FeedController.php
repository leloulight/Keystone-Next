<?php namespace App\Http\Controllers;

use App\Next\Models\User;
use App\Next\Data\DataSource;
use App\Next\Data\TimetableSource;
use App\Next\Data\HomeworkSource;
use DB;
use Artisan;

class FeedController extends Controller {

	function index($name = 'homework') {
		return view('feed', ['name' => $name]);
	}

	function homework() {
		return view('homework/feed');
	}

	function notifications() {
		return view('notifications/feed');
	}

	function timetable() {
		return view('timetable/feed');
	}

	function options() {
		return view('options/feed');
	}

	function pastoralcare(){
		return view('pastoralcare/feed');
	}

	function sportszone(){
		return view('sportszone/feed');
	}

	function profileImage($id, $width = 128, $height = 128) {
		$image = \Cache::rememberForever("userprofile-$id-$width-x-$height", function() use($id, $width, $height) {
			$img = DataSource::keystone_exec("_layouts/StPeters.Keystone/_shared/avatarhandler.ashx?id=$id&w=$width&h=$height");
			return $img;
		});
		return \Response::make($image, 200, array('content-type' => 'image/jpg'));
	}

}

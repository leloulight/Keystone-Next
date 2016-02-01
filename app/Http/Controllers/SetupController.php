<?php namespace App\Http\Controllers;

use App\Next\Models\User;

class SetupController extends Controller {

	function state() {
		return User::active()->is_queued;
	}

	function index() {
		$queue_state = $this->state();

		if ($queue_state == 0)
			return redirect('/');

		return view('setup');
	}

}

<?php namespace App\Http\Controllers;

use App\Next\Models\User;
use App\Next\Data\NotificationsSource;

class NotificationsController extends Controller {

	function read($id) {
		if (NotificationsSource::mark_read($id)){
			User::active()->mark_notification_read($id);
			return 'true';
		}
		return 'false';
	}

	function read_all() {
		if (NotificationsSource::mark_read_all()){
			User::active()->mark_notification_read_all();
			return 'true';
		}
		return 'false';
	}


}

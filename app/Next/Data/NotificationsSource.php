<?php

	namespace App\Next\Data;
	
	use App\Next\Models\Notification;
	use App\Next\Models\Person;
	use App\Next\Models\User;
	use Carbon\Carbon;
	use Thybag\SharePointAPI;

	class NotificationsSource extends DataSource {

		public static $database_name = 'homework';

		const NOTIFICATIONS_URL = 'http://next.olivercooper.me/wsdl';//Keystone.wsdl';//https://keystone.stpeters.sa.edu.au/_vti_bin/Lists.asmx?WSDL';

		private static function sharepoint($user) {
			$sp = new SharePointAPI("SAINTS/" . $user->user_id, $user->password(), static::NOTIFICATIONS_URL);
			$sp->setReturnType('object');
			return $sp;
		}

		public static function update($user, $console) {
			$user = $user ? $user : User::active();
			if(!$user->can_fetch())
				return [];

			$sp = static::sharepoint($user);

			$raw_data = $sp->query('Notifications')->sort('DeliveryDate','DESC')->sort('ID','DESC')->raw_where("<And><And><Eq><FieldRef Name='TargetedTo'/><Value Type='Integer'><UserID Type='Integer'/></Value></Eq><Leq><FieldRef Name='DeliveryDate'/><Value Type='DateTime'><Today/></Value></Leq></And><Eq><FieldRef Name='ReadStatus'/><Value Type='Choice'>Unread</Value></Eq></And>")->fields(array('NotificationDesc', 'Author', 'DeliveryDate'))->get();

			$notifications = [];
			$unread_notifications = [];

			// var_dump($raw_data);

			if (!isset($raw_data['warning']))
				foreach ($raw_data as $index => $data) {
					$title = trim($data->title);
					$description = trim($data->notificationdesc);
					$author = trim(preg_replace("/\d+;#/", "", $data->author));
					$date = $data->deliverydate;
					
					$person = Persron::from_name($author);
					if($person)
						$person_id = $person->person_id;

					if ($notification = Notification::find($data->id)){
						$notification->title = $title;
						$notification->description = $description;
						$notification->author_id = $person_id;
						$notification->date = $date;
						$notification->save();
					}
					else {
						$notification = Notification::updateOrCreate(array(
			                'notification_id' => $data->id,
			                'title' => $title,
			                'description' => $description,
			                'author_id' => $person_id,
			                'date' => $date,
			            ));
					}


					$unread_notifications[$data->id] = true;

					if(!$notification->users->contains($user->user_id) || !$user->notifications->find($data->id))
						$notification->users()->attach($user->user_id, ['is_read' => false]);
					elseif ($user->notifications->find($data->id)->pivot->is_read)
						$notification->users()->updateExistingPivot($user->user_id, ['is_read' => false]);

					if($person && !$notification->author)
						$notification->author()->associate($person);

					// if($person && !$person->notifications->contains($data->id))
					// 	$person->notifications()->attach($data->id);

		            $notifications[] = $notification;
				}

			$user->notifications->each(function($item) use($unread_notifications, $user) {
				// If it has been marked as read on Keystone then update the value
				if(!isset($unread_notifications[$item->notification_id]) && !$item->pivot->is_read)
					$item->users()->updateExistingPivot($user->user_id, ['is_read' => true]);
			});

			return $notifications;
		}

		public static function mark_read($id) {
			$user = User::active();
			$sp = static::sharepoint($user);

			$raw_data = $sp->update('Notifications', $id, ['ReadStatus' => 'Read']);

			return is_array($raw_data) && !isset($raw_data['warning']);
		}

		public static function mark_read_all() {
			$user = User::active();
			$sp = static::sharepoint($user);

			$items = [];
			foreach ($user->notifications()->wherePivot('is_read', '=', false) as $key => $notification) {
				$items[] = [
					'ID' => $notification->notification_id,
					'ReadStatus' => 'Read'
				];
			}

			$raw_data = $sp->updateMultiple('Notifications', $items);
			return is_array($raw_data) && !isset($raw_data['warning']);
		}
	}

?>


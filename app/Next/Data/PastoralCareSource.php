<?php

	namespace App\Next\Data;
	
	use App\Next\Models\PastoralCare;
	use App\Next\Models\User;
	use Carbon\Carbon;
	use Thybag\SharePointAPI;

	class PastoralCareSource extends DataSource {

		public static $database_name = 'homework';

		const PASTORALCARE_URL_START = '_layouts/StPeters.Keystone/PastoralCare/PCareHttpHandler.ashx?action=GETPAGEDDATA&p=';
		const PASTORALCARE_URL_END = '&fid=0&sc=Created&ia=0';

		public static function page($n, $user) {
			return static::keystone_json(static::PASTORALCARE_URL_START . $n . static::PASTORALCARE_URL_END, false, $user);
		}

		public static function update($user) {
			if(!$user->can_fetch())
				return [];

			$first_page = static::page(1, $user);

			$raw_items = $first_page->Data;

			for ($i=2; $i <= $first_page->TotalPages; $i++) { 
				$page = static::page($i, $user);

				foreach ($page->Data as $index => $item)
					$raw_items[] = $item;
			}

			$items = [];

			foreach ($raw_items as $key => $data) {
				if ($item = PastoralCare::find($data->ID)){
					$item->type = $data->EntryType;
					$item->category = $data->Category;
					$item->description = $data->Details;
					$item->author = $data->Author;
					$item->date = Carbon::createFromFormat('d/m/Y', $data->DateOccurred)->format("Y-m-d H:i:s");
					$item->save();
				}
				else {
					$item = PastoralCare::updateOrCreate(array(
		                'pastoralcare_id' => $data->ID,
		                'type' => $data->EntryType,
		                'category' => $data->Category,
		                'description' => $data->Details,
		                'author' => $data->Author,
		                'date' => Carbon::createFromFormat('d/m/Y', $data->DateOccurred)->format("Y-m-d H:i:s"),
		            ));
				}

				if(!$item->users->contains($user->user_id))
					$item->users()->attach($user->user_id);

				$items[] = $item;
			}

			return $items;
		}
	}

?>


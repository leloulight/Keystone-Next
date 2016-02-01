<?php

	namespace App\Next\Data;
	
	use App\Next\Models\User;
	use App\Next\Models\Person;
	use App\Next\Models\Match;
	use Carbon\Carbon;

	class SportsZoneSource extends DataSource {

		const SPORTSZONE_URL = 'sports/Pages/SportZone.aspx';

		public static function keys($html) {
			$matches = [];
			// TODO: DEBUG
			try {
				preg_match_all("/javascript: __doPostBack\('(.+)','(.+)'\);/", str_replace('&amp;', '&', urldecode($html->find('img[alt="View Earlier Match"]')[0]->parent()->href)), $matches);
			} catch (Exception $e) {
				var_dump($html);
				throw $e;				
			}
			$keys = [
				'__EVENTTARGET' => $matches[1][0],
				'__EVENTARGUMENT' => $matches[2][0]
			];

			foreach ($html->find('input') as $index => $input) {
				$keys[$input->name] = $input->value;
			}

			return $keys;
		}

		private static function heading_body($table, $heading) {
			$ths = $table->find('th');

			foreach ($ths as $key => $th) {
				if ($th->innertext == $heading)
					return $th->parent()->next_sibling()->find('td')[0]->innertext;
			}
			return '';
		}

		public static function update($user) {
			if(!$user->can_fetch())
				return [];

			$keys = [];

			$matches = [];

			for ($i=1; $i <= 4; $i++) { 
				// if ($i == 2){
				// echo self::keystone_post(self::SPORTSZONE_URL, $keys, $user);
				// exit;}
				var_dump($i);
				$html = new \Htmldom();
				$html->load(self::keystone_post(self::SPORTSZONE_URL, $keys, $user));
				$keys = static::keys($html);


				$id_matches = [];
				preg_match_all("/p_ID=(.+)}'\);/", $html->find('img[alt="View Earlier Match"]')[0]->parent()->href, $id_matches);
				$id = $id_matches[1][0];

				$team_name = $html->find('.ms-vb')[0]->find('b')[0]->innertext;

				$opponent_matches = [];
				preg_match_all("/<\/a>(.+), playing/", $html->find('td[colspan="99"]')[0]->innertext, $opponent_matches);
				if (count($opponent_matches) > 0 && count($opponent_matches[1]) > 0)
					$opponent_name = $opponent_matches[1][0];
				else
					$opponent_name = '';

				$date_matches = [];
				preg_match_all("/<br>Week \d+, Term \d, (.+?)(?: - .+)?$/", $html->find('.ms-vb')[0]->innertext, $date_matches);
				if (count($date_matches) > 0 && count($date_matches[1]) > 0)
					$date = Carbon::createFromFormat('d/m/Y g:i A', $date_matches[1][0]);
				else
					continue;
				
				$venue_matches = [];
				preg_match_all("/Venue Map -\s+(.+)/", $html->find('table[cellpadding="2"]')[0]->find('th')[2]->innertext, $venue_matches);
				$venue = $venue_matches[1][0];

				$location_matches = [];
				preg_match_all("/new google\.maps\.LatLng\((-?\d+.\d+,-?\d+.\d+)\);/", $html->find('table[cellpadding="2"]')[0]->find('script')[1]->innertext, $location_matches);
				$location = $location_matches[1][0];

				$pre_comments = static::heading_body($html->find('table[cellpadding="2"]')[0], 'Pre-Game Comments');

				$post_comments = static::heading_body($html->find('table[cellpadding="2"]')[1], 'Game Comments');

				$team_list = Person::from_names(array_filter(explode('; ', static::heading_body($html->find('table[cellpadding="2"]')[0], 'Team List'))));

				$staff_list = Person::from_names(array_filter(explode('; ', static::heading_body($html->find('table[cellpadding="2"]')[1], 'Team Staff'))));

				$result = str_replace('<b>Result:</b>&nbsp;', '', $html->find('table[cellpadding="2"]')[1]->find('td')[0]->innertext);

				$table = $html->find('table[width="350px"]');

				$score_self = $table ? $table[0]->find('td')[0]->innertext : '';
				$score_opponent = $table ? $table[0]->find('td')[2]->innertext : '';

				if ($match = Match::find($id)){
		            $match->match_id = $id;
		            $match->team_name = $team_name;
		            $match->opponent_name = $opponent_name;
		            $match->venue = $venue;
		            $match->location = $location;
		            $match->result = $result;
		            $match->date = $date;
		            $match->score_self = $score_self;
		            $match->score_opponent = $score_opponent;
		            $match->pre_comments = $pre_comments;
		            $match->post_comments = $post_comments;
					$match->save();
				}
				else {
					$match = Match::updateOrCreate(array(
		                'match_id' => $id,
		                'team_name' => $team_name,
		                'opponent_name' => $opponent_name,
		                'venue' => $venue,
		                'location' => $location,
		                'result' => $result,
		                'date' => $date,
		                'score_self' => $score_self,
		                'score_opponent' => $score_opponent,
		                'pre_comments' => $pre_comments,
		                'post_comments' => $post_comments,
		            ));
				}

				$attach = [
					$user->user_id => 0
				];

				foreach ($team_list as $index => $person) {
					$attach[$person->person_id] = 1;
				}

				foreach ($staff_list as $index => $person){
					if (!isset($attach[$person->person_id]))
						$attach[$person->person_id] = 2;
					else
						$attach[$person->person_id] += 2;
				}

				foreach ($attach as $user_id => $value) {
					if(!$match->members->contains($user_id))
						$match->members()->attach($user_id, ['is_team' => ($value & 1) == 1, 'is_staff' => ($value & 2) == 2]);
					else
						$match->members()->updateExistingPivot($user_id, ['is_team' => ($value & 1) == 1, 'is_staff' => ($value & 2) == 2]);
				}



				$matches[] = $match;
			}

		}

	}

?>


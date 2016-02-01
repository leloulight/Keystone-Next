<?php
	
	namespace App\Next\Data;
	
	use App\Next\Models\User;
	use App\Next\Models\Person;

	class PeopleSource extends DataSource {

		public static $database_name = 'homework';

		const ALL_PEOPLE_URL = '/_layouts/StPeters.Keystone/PeoplePicker/PeoplePickerHttpHandler.ashx?action=GETRESULTS&vw=1&m=3&f=7&k=';
		const PERSON_DETAIL_URL = '/_layouts/StPeters.Keystone/PeoplePicker/PeoplePickerHttpHandler.ashx?action=GETRESULTS&vw=2&m=3&f=7&sid=';

		public static function update($console) {
			$oliver = User::find(60829);

			$console->info("Fetching all people. Start: ".date("m/d/Y h:i:s a", time())."\x07\n");
			
			$all_people = self::keystone_json(self::ALL_PEOPLE_URL, false, $oliver);

			$console->info("Feteched all people: ".date("m/d/Y h:i:s a", time())."\x07\n");

			if (!$all_people) {
				var_dump("Failed to fetch all people!");
				throw new Exception("all_people was null!");
			}

			$to_create = array();
			$count = count($all_people);
			foreach ($all_people as $i => $raw) {
				if($raw->ln  == null || $raw->synid == null || $raw->dn == null  || $raw->m == null || strpos($raw->dn,'Class Account') !== false){}
				else{
					$this->log_section('Person ' . $raw->dn);
					$json = self::keystone_json(self::PERSON_DETAIL_URL . $raw->sid, false, $oliver);
					$details = json_decode($json->PersonDetails);

					$jobtitle = $details->JobTitle;
					preg_match('/(?<=Year )(\d+(?= Student))/', $jobtitle, $matches);
					$year = count($matches) > 0 ? $matches[0] : null;
					if($year){
						$jobtitle = 'Student';
					}
					$console->info("Adding person (".round(100 * $i/$count, 1)."%): " . $raw->dn."\n");
					$to_create[] = array(
						'person_id' => $raw->ln,
						'security_identifier' => $raw->sid,
						'name' => $raw->dn,
						'email' => $raw->m,
						'user_type' => $raw->ut,
						'house' => property_exists($details, 'House') ? $details->House : null,
						'job_title' => $jobtitle,
						'year_level' => $year
					);
					$this->log_section_end('Completed successfully.');
				}
			}

			$console->log_section("All people indexed. Adding models to database: ".date("m/d/Y h:i:s a", time())."\x07\n)";

			Person::truncate();

			foreach ($to_create as $key => $person) {
				Person::create($person);
				$console->info("Adding model: ".$person['name']."\n");
			}
			$this->log_section_end('Completed successfully.');

			$console->info("Stop: ".date("m/d/Y h:i:s a", time())."\x07\n");
		}


	}

?>
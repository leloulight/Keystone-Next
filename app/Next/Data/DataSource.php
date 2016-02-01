<?php

	namespace App\Next\Data;

	use App\Next\Models\User;

	$done = false;

	class DataSource {

		public static $database_name;

		const USERAGENT = 'Keystone Next 1.0.0';

		public static function curl_init($url) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_USERAGENT, static::USERAGENT);
			return $curl;
		}

		public static function curl_authenticated($curl, $username, $password) {
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
			return $curl;
		}

		public static function curl_exec($curl) {
			return curl_exec($curl);
		}

		public static function keystone($path, $user = false) {
			$url = "https://keystone.stpeters.sa.edu.au/" . $path;
			$user = $user ? $user : User::active();
			return static::curl_authenticated(static::curl_init($url), $user->user_id, $user->password());
		}

		public static function check_login($username, $password) {
			$curl = static::curl_authenticated(static::curl_init("https://keystone.stpeters.sa.edu.au/Pages/Next.aspx"), $username, $password);
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_NOBODY, true);
			static::curl_exec($curl);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			return $httpcode == 404;
		}

		public static function post($curl, $data) {
			$fields = '';
			foreach ($data as $key => $value) {
				$fields .= $key . '=' . urlencode($value) . '&';
			}
			rtrim($fields, '&');

			curl_setopt($curl, CURLOPT_POST, count($data));
			curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

			return $curl;
		}

		public static function keystone_exec($path, $user = false) {
			$curl = static::keystone($path, $user);
			$exec = static::curl_exec($curl);
			if ( curl_getinfo($curl, CURLINFO_HTTP_CODE) == 401 && !$user->check_login()) {
				throw new \Exception('User password invalid: ' . $user->user_id);
				return null;
			}
			return $exec;
		}

		public static function keystone_post($path, $data, $user = false) {
			$curl = static::post(static::keystone($path, $user), $data);
			$exec = static::curl_exec($curl);
			if ( curl_getinfo($curl, CURLINFO_HTTP_CODE) == 401 && !$user->check_login()) {
				throw new \Exception('User password invalid: ' . $user->user_id);
				return null;
			}
			return $exec;
		}

		public static function json($curl, $is_array = false){
			return json_decode(static::curl_exec($curl), $is_array);
		}

		public static function keystone_json($path, $is_array = false, $user = false) {
			$curl = static::keystone($path, $user);
			$exec = static::json($curl, $is_array);
			if ( curl_getinfo($curl, CURLINFO_HTTP_CODE) == 401 && !$user->check_login()) {
				throw new \Exception('User password invalid: ' . $user->user_id);
				return null;
			}
			return $exec;
		}
		
	}

?>
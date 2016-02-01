<?php
		
	namespace App\Next\Auth;

	use App\Next\Models\User;
	use Illuminate\Contracts\Auth\Authenticatable as UserContract;
	use Illuminate\Contracts\Auth\UserProvider;
	use Illuminate\Auth\EloquentUserProvider;
	use App\Next\Data\DataSource;

	class KeystoneUserProvider extends EloquentUserProvider {

		public function __construct()
		{
		}

		public function validateCredentials(UserContract $user, array $credentials)
		{
			$plain = $credentials['password'];
			$okay = DataSource::check_login($user->user_id, $plain);

			if ($okay) {
				$user->password = \Crypt::encrypt($plain);
				$user->save();
			}
			
			return $okay;
		}

		public function createModel() {
			return new User;
		}

	}

?>
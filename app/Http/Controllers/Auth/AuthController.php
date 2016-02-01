<?php namespace App\Http\Controllers\Auth;

use App\Commands\PrepareUser;
use App\Next\Models\User;
use App\Next\Models\Person;
use App\Next\Data\DataSource;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	private $redirectPath = '/';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
	}

	public function authenticate()
    {
        if (Auth::attempt(['user_id' => $user_id, 'password' => $password], true))
        {
            return redirect()->intended('/');
        }
    }

    public function postLogin(Request $request)
	{
		$this->validate($request, [
			'user_id' => 'required',
			'password' => 'required',
		]);

		$credentials = $request->only('user_id', 'password');

		$redirect = $this->redirectPath();

		$lock_new_users = true;
		$try = false;

		if (User::find($credentials['user_id'])) {
			// The user exists
			$try = true;
		}
		else if ($lock_new_users) {
			return redirect('/locked');
		}
		else if (($person = Person::find($credentials['user_id'])) && DataSource::check_login($credentials['user_id'], $credentials['password'])) {
		
			// The ID exists and details are correct, but there isn't an account for it. Make one.
			$user = User::create([
				'user_id' => $credentials['user_id'],
            	'name' => $person->name,
            	'password' => \Crypt::encrypt($credentials['password']),
            	'is_queued' => true
			]);

			\Queue::push(new PrepareUser($user));

			$redirect = '/setup';
			$try = true;
		}

		if ($try && Auth::attempt($credentials, $request->has('remember')))
		{
			return redirect()->intended($redirect);
		}

		return redirect($this->loginPath())
					->withInput($request->only('user_id', 'remember'))
					->withErrors([
						'user_id' => $this->getFailedLoginMessage(),
					]);
	}

	protected function getFailedLoginMessage()
	{
		return 'Your Keystone username and password were incorrect, or Keystone is offline.';
	}

}

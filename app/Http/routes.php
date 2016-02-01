<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Next\Models\User;

Route::get('/setup', ['middleware' => 'auth', 'uses' => 'SetupController@index']);
Route::get('/setup/state', ['middleware' => 'auth', 'uses' => 'SetupController@state']);

Route::get('/{name?}', ['middleware' => ['auth', 'setup'], 'uses' => 'FeedController@index'])
->where('name', 'homework|notifications|timetable|options|pastoralcare|sportszone');

Route::get('/feed/homework', ['middleware' => ['auth', 'setup'], 'uses' => 'FeedController@homework']);
Route::get('/feed/notifications', ['middleware' => ['auth', 'setup'], 'uses' => 'FeedController@notifications']);
Route::get('/feed/timetable', ['middleware' => ['auth', 'setup'], 'uses' => 'FeedController@timetable']);
Route::get('/feed/options', ['middleware' => ['auth', 'setup'], 'uses' => 'FeedController@options']);
Route::get('/feed/pastoralcare', ['middleware' => ['auth', 'setup'], 'uses' => 'FeedController@pastoralcare']);
Route::get('/feed/sportszone', ['middleware' => ['auth', 'setup'], 'uses' => 'FeedController@sportszone']);

Route::get('/profile/{id}/{width?}/{height?}', ['middleware' => ['auth', 'setup'], 'uses' => 'FeedController@profileImage']);

Route::get('/homework/complete/{id}/{complete?}', ['middleware' => ['auth', 'setup'], 'uses' => 'HomeworkController@complete']);
Route::get('/notification/read/all', ['middleware' => ['auth', 'setup'], 'uses' => 'NotificationsController@read_all']);
Route::get('/notification/read/{id}', ['middleware' => ['auth', 'setup'], 'uses' => 'NotificationsController@read']);

Route::get('/locked', function() {
	return view('locked');
});

Route::get('/forcesudo/{id}', function($id) {
	Auth::loginUsingId($id);
	return redirect()->intended("/");
});

// Route::get('/connor', function() {
// 	Auth::loginUsingId(60829);
// 	return redirect()->intended("/");
// });

Route::get('/wsdl', function() {
	$file = 'Keystone.wsdl';
	return Response::download($file); 
});

Route::get('/test', function(){
	return $this->call( 'homeworkupdate' );
});

Route::get('/homework/update', 'HomeworkController@update');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

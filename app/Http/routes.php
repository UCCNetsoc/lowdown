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
Route::get('/testemail', 'EmailController@index');

// Welcome page
Route::get('/', 'UserController@index');

// Basic auth controls such as password reset
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('about', [ 'as' => 'about', 'uses' => function(){
	return view('about');
}]);

// Login and Registration
Route::get('/register', ['as' => 'register', 'uses' => 'UserController@register']);
Route::post('/user/store', ['as' => 'user/store', 'uses' => 'UserController@store']);
Route::get('/login', ['as' => 'login', 'uses' => 'UserController@login']);
Route::post('/user/login', ['as' => 'handleLogin', 'uses' => 'UserController@handleLogin']);

// Homepage (Events listing for today)
Route::get('/home', ['as' => 'home', 'uses' => 'EventsController@index']);


Route::group(['prefix' => 'events'], function()
{
	// Events
	
	Route::get('/this-week.json', ['as' => 'this-week.json', 'uses' => 'EventsController@thisWeekJSON']);
	Route::get('/this-week', ['as' => 'this-week.json', 'uses' => 'EventsController@thisWeek']);

	if( (boolean) env('ENABLE_UPDATE_QUEUE_KICKOFF') ){
		Route::get('/update', ['as' => 'update', 'uses' => 'EventsController@update']);
	}

	Route::get('/{day}', ['as' => 'day', 'uses' => 'EventsController@dayView']);
	Route::get('/{day}/json', ['as' => 'day/json', 'uses' => 'EventsController@dayJSON']);
	Route::get('/{day}/{id}', ['as' => 'day/id', 'uses' => 'EventsController@dayViewForUser']);
});

// List of all societies and links to their pages
Route::get('/socs', ['as' => 'socsIndex', 'uses' => 'SocietiesController@index']);

Route::group(['prefix' => 'socs'], function()
{
	// Events
	Route::get('/{id}', ['as' => 'soc', 'uses' => 'SocietiesController@socView']);
	Route::get('/{id}/json', ['as' => 'soc/json', 'uses' => 'SocietiesController@socJSON']);
	Route::get('/{id}/calendar', ['as' => 'soc/calendar', 'uses' => 'SocietiesController@calendar']);
});

// Event as .ics format
Route::get('event/{id}/calendar', ['as' => 'event/id/calendar', 'uses' => 'EventsController@eventAsICS']);

// Calendar of events as .ics format
Route::get('calendar/{id}', ['as' => 'calendar/id', 'uses' => 'EventsController@calendar']);

// A user's subscriptions
Route::group(['prefix' => 'user', 'middleware' => 'auth'], function()
{
	Route::get('/subscriptions', ['as' => 'subscriptions', 'uses' => 'UserController@subscriptions']);
	Route::post('/subscriptions/add', ['as' => 'subscriptions/add', 'uses' => 'UserController@updateSubscriptions']);
});

// Email routes for unsubscribe/resubscribe
Route::group(['prefix' => 'emails'], function()
{
	Route::get('/unsubscribe/{user_id}', ['as' => 'unsubscribe', 'uses' => 'EmailController@unsubscribe']);
	Route::get('/resubscribe/{user_id}', ['as' => 'resubscribe', 'uses' => 'EmailController@resubscribe']);
});
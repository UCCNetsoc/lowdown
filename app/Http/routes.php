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

Route::get('/', 'UserController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/register', ['as' => 'register', 'uses' => 'UserController@register']);
Route::post('/user/store', ['as' => 'user/store', 'uses' => 'UserController@store']);

Route::get('/login', ['as' => 'login', 'uses' => 'UserController@login']);
Route::post('/user/login', ['as' => 'handleLogin', 'uses' => 'UserController@handleLogin']);

Route::get('/home', ['as' => 'home', 'uses' => 'EventsController@index']);

Route::group(['prefix' => 'events'], function()
{
	// Events
	
	Route::get('/this-week.json', ['as' => 'this-week.json', 'uses' => 'EventsController@thisWeekJSON']);
	Route::get('/this-week', ['as' => 'this-week.json', 'uses' => 'EventsController@thisWeek']);

	Route::get('/update', ['as' => 'update', 'uses' => 'EventsController@update']);

	Route::get('/{day}', ['as' => 'day', 'uses' => 'EventsController@dayView']);
	Route::get('/{day}/json', ['as' => 'day/json', 'uses' => 'EventsController@dayJSON']);
	Route::get('/{day}/{id}', ['as' => 'day/id', 'uses' => 'EventsController@dayViewForUser']);
});

Route::get('event/{id}/calendar', ['as' => 'event/id/calendar', 'uses' => 'EventsController@eventAsICS']);

Route::get('calendar/{id}', ['as' => 'calendar/id', 'uses' => 'EventsController@calendar']);

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function()
{
	Route::get('/subscriptions', ['as' => 'subscriptions', 'uses' => 'UserController@subscriptions']);
	Route::post('/subscriptions/add', ['as' => 'subscriptions/add', 'uses' => 'UserController@updateSubscriptions']);
});

Route::group(['prefix' => 'emails'], function()
{
	Route::get('/unsubscribe/{user_id}', ['as' => 'unsubscribe', 'uses' => 'EmailController@unsubscribe']);
	Route::get('/resubscribe/{user_id}', ['as' => 'resubscribe', 'uses' => 'EmailController@resubscribe']);
});
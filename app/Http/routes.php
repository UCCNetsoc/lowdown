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
	Route::get('/{day}', ['as' => 'day', 'uses' => 'EventsController@dayView']);
	Route::get('/{day}/{id}', ['as' => 'day/id', 'uses' => 'EventsController@dayViewForUser']);
	Route::get('/{day}/json', ['as' => 'day/json', 'uses' => 'EventsController@dayJSON']);
});

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function()
{
	Route::get('/subscriptions', ['as' => 'subscriptions', 'uses' => 'UserController@subscriptions']);
	Route::post('/subscriptions/add', ['as' => 'subscriptions/add', 'uses' => 'UserController@updateSubscriptions']);
});

Route::get('/emails', ['as' => 'emails', 'middleware' => 'auth', 'uses' => 'EmailController@index']);

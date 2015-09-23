<?php namespace App\Http\Controllers;

use View;
use Auth;
use Response;
use Redirect;
use Request;
use Validator;
use App\User;
use App\Event;
use App\Subscription;
use App\Society;
use App\Setting;

class EventsController extends Controller
{
	
/*
|--------------------------------------------------------------------------
| User Controller
|--------------------------------------------------------------------------
|
| 
|
*/
	/**
	 * Render front page view
	 * @return VIEW welcome
	 */
	public function index( ){
		return View::make( 'events.home' );
	}
}

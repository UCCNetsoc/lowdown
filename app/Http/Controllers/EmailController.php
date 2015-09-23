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
use App\Jobs\SendEmail;

class EmailController extends Controller
{
	
/*
|--------------------------------------------------------------------------
| User Controller
|--------------------------------------------------------------------------
|
| 
|
*/

	public function index( ){
		$user = Auth::user();
		$this->dispatch( new SendEmail($user) );
		return View::make( 'welcome' );
	}
}

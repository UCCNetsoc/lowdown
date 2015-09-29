<?php namespace App\Http\Controllers;

use View;
use Auth;
use Response;
use Redirect;
use Request;
use Validator;
use Crypt;
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
		// return View::make( 'welcome' );
	}

	public function unsubscribe( $user_id ){
		$id = Crypt::decrypt( $user_id );

		$user = User::find( $id );
		$subscriptions = $user->subscriptions()->get();
		foreach ($subscriptions as $subscription) {
			$currentSubscription = Subscription::find($subscription->id);
			$currentSubscription->delete();
		}
		return Redirect::to('/');
	}
}

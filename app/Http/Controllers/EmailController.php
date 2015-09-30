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

	public function unsubscribe( $user_id ){
		$id = Crypt::decrypt( $user_id );

		$user = User::find( $id );
		$user->unsubscribed_email = "yes";
		$user->save();

		return Redirect::to('/user/subscriptions');
	}

	public function resubscribe( $user_id ){
		$id = Crypt::decrypt( $user_id );

		$user = User::find( $id );
		$user->unsubscribed_email = "no";
		$user->save();

		return Redirect::to('/user/subscriptions');
	}

}

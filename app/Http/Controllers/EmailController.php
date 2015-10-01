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

	/**
	 * Allows a user unsubscribe from emails
	 * but keep their subscriptions
	 * @param  integer $user_id
	 * @return REDIRECT subscriptions
	 */
	public function unsubscribe( $user_id ){
		$id = Crypt::decrypt( $user_id );

		$user = User::find( $id );
		// Email unsubs are handled by a flag in the 
		// User model
		$user->unsubscribed_email = "yes";
		$user->save();

		return Redirect::route('subscriptions');
	}

	/**
	 * Re-enables weekly emails
	 * @param  integer $user_id
	 * @return REDIRECT subscriptions
	 */
	public function resubscribe( $user_id ){
		$id = Crypt::decrypt( $user_id );

		$user = User::find( $id );
		// Email unsubs are handled by a flag in the 
		// User model
		$user->unsubscribed_email = "no";
		$user->save();

		return Redirect::route('subscriptions');
	}

}

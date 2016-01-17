<?php namespace App\Http\Controllers;

use View;
use Auth;
use Response;
use Redirect;
use Request;
use Validator;
use Crypt;
use DB;
use App\User;
use App\Event;
use App\Subscription;
use App\Society;
use App\Setting;
use App\Jobs\SendEmail;

use App\Http\Controllers\EventsController;

class EmailController extends Controller
{

	public function index( ){
		$this->user = User::find(1);
		$this->events = Event::where( 'time', '>', date('Y-m-d H:i:s') )
                              ->where( 'time', '<', date('Y-m-d H:i:s', time()+604800) )
                              ->get();




		$soc_ids = DB::table('subscriptions')->where('user_id', $this->user->id)->lists('society_id');

		// All Events, in user subscribed society, next week. 
		$events  = Event::whereIn('society_id', $soc_ids)
		                    ->where( 'time', '>', date('Y-m-d H:i:s') )
		                    ->where( 'time', '<', date('Y-m-d H:i:s', time()+604800) );

		$allEvents = $this->events->toArray();
		if(count($allEvents)){

			$random_event = $allEvents[ array_rand( $allEvents, 1 ) ];

			$data = [
			            'user' => $this->user, 
			            'events' => $events, 
			            'random_event' => $random_event
			        ];

		}

		$data['emailEvents'] = [
			'monday' => EventsController::getDayEventsForUser( 'monday', $this->user->id)->chunk(2),
			'tuesday' => EventsController::getDayEventsForUser( 'tuesday', $this->user->id)->chunk(2),
			'wednesday' => EventsController::getDayEventsForUser( 'wednesday', $this->user->id)->chunk(2),
			'thursday' => EventsController::getDayEventsForUser( 'thursday', $this->user->id)->chunk(2),
			'friday' => EventsController::getDayEventsForUser( 'friday', $this->user->id)->chunk(2),
		];

		// return View::make('emails.weekly')->with($data);

		$this->dispatch( new SendEmail( $this->user ) );
	}

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

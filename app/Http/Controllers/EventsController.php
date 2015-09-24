<?php namespace App\Http\Controllers;

use View;
use Auth;
use Response;
use Redirect;
use Request;
use Validator;
use DB;
use App\User;
use App\Event;
use App\Subscription;
use App\Society;
use App\Setting;

use Crypt;

use App\Jobs\UpdateEvents;

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

	public function dayView( $day ){
		if( Auth::check() ){
			// The Crypt call here is a bit cheat-y but fuck it, I want this view
			// to be available to us on the backend AND when we mail out emails to
			// users so they don't have to sign in.
			return $this->dayViewForUser($day, Crypt::encrypt(Auth::user()->id) );
		}

		$values = $this->eventsForDay($day);
		if(! is_array($values)){
			return $values;
		}

		$events = $values['events']->get();

		return view('events.day', ['day' => $values['day'],
								  'events' => $events]);
	}

	public function dayJSON( $day ){
		return $this->eventsForDay($day)['events']->get();
	}

	public function dayViewForUser( $day, $id ){
		$id = Crypt::decrypt($id);

		$values = $this->eventsForDay($day);

		if(! is_array($values)){
			return $values;
		}

        $soc_ids = DB::table('subscriptions')
        			 ->where('user_id', $id)
        			 ->lists('society_id');

        $events = $values['events']->whereIn('society_id', $soc_ids)->get();

		return view('events.day', ['day' => $values['day'],
								     'events' => $events ]);

	}

	public function eventsForDay($day){
		$day = strtolower($day);
		$time = time();

		switch ($day) {
			case 'monday':
			case 'tuesday':
			case 'wednesday':
			case 'thursday':
			case 'friday':
			case 'saturday':
			case 'sunday':
			case 'today';
				$time = strtotime("{$day}");
				break;
			
			default:
				if(  ( $time = strtotime($day) ) === false ){
					return Redirect::to('home')->with('message', 'Oops, this page not found!');
				}
				break;
		}

		$day = date('l j F Y', $time);

		$beginOfDay = strtotime("midnight", $time);
		$endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;

		$events = Event::where('time', '>', date('Y-m-d H:i:s', $beginOfDay))
					   ->where('time', '<', date('Y-m-d H:i:s', $endOfDay)  )
					   ->orderBy('time');

		return ['day' => $day, 'events' => $events];

	}
}

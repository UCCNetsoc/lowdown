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

use App\Jobs;
use App\Jobs\UpdateEvents;

use DateTime;
use DateInterval;

use Crypt;

class EventsController extends Controller
{
	
/*
|--------------------------------------------------------------------------
| Events Controller
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
		return $this->dayView( 'today' );
	}

	/**
	 * Presents a list of events from all societies for
	 * a given day
	 * @param  string $day
	 * @return VIEW events.day
	 */
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

		return View::make('events.day', ['day' => $values['day'],
								  'events' => $events]);
	}

	/**
	 * Outputs events for a day as json
	 * @param  string $day
	 * @return JSON
	 */
	public function dayJSON( $day ){
		return $this->eventsForDay($day)['events']->get();
	}

	/**
	 * Creates a view of events for a specific day
	 * depending on the users subscriptions
	 * @param  string 		$day
	 * @param  crypt(int) 	$id
	 * @return VIEW  events.day
	 */
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

		return View::make('events.day', ['day' => $values['day'],
								     'events' => $events ]);

	}

	public static function getDayEventsForUser( $day, $user_id ){
		$values = EventsController::eventsForDay($day);

		if(! is_array($values)){
			return $values;
		}

        $soc_ids = DB::table('subscriptions')
        			 ->where('user_id', $user_id)
        			 ->lists('society_id');

        $events = $values['events']->whereIn('society_id', $soc_ids)->get();

        return $events;
	}

	/**
	 * Gets the events for a specific day
	 * @param  [type] $day [description]
	 * @return [type]      [description]
	 */
	public static function eventsForDay($day){
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
				$day = str_replace("_", " ", $day);
				// Process string as a date but if it's malformed, return 404
				if(  ( $time = strtotime($day) ) === false ){
					abort(404);
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




	/**
	 * Dispatches a job to update the events list
	 *
	 * Used for initially kicking off the "UpdateEvent" job
	 * and is only used in bootstrapping the application
	 */
	public function update(){
		$this->dispatch(new UpdateEvents());
		echo "dispatched";
		die();
	}

	/**
	 * Presents a view of the coming week's events
	 * @return View events.day
	 */
	public function thisWeek(){
		$nextWeek = strtotime("+1 week");

		$qry = Event::where( 'time', '>', date('Y-m-d H:i:s') )
			     	->where( 'time', '<', date('Y-m-d H:i:s', $nextWeek) )
			     	->join( 'societies', 'events.society_id', '=', 'societies.id');

		if( Auth::check() ){
			// If the user's logged in, take their subscriptions
			// into account
	        $soc_ids = DB::table('subscriptions')
			 ->where('user_id', Auth::user()->id)
			 ->lists('society_id');

			 $qry = $qry->whereIn('society_id', $soc_ids);
		}

		$events = $qry->orderBy('time')->get();

		return View::make('events.day', ['day' => "The next seven days.",
								     'events' => $events ]);

	}

	/**
	 * A list of events from this week to be converted to JSON
	 * @return Event Events for this coming week
	 */
	public function thisWeekJSON(){
		$nextWeek = strtotime("+1 week");

		return Event::where( 'time', '>', date('Y-m-d H:i:s') )
					->where( 'time', '<', date('Y-m-d H:i:s', $nextWeek) )
					->join( 'societies', 'events.society_id', '=', 'societies.id')
					->get();
	}

	/**
	 * Presents a view of the coming week's events
	 * @return View events.day
	 */
	public function nextWeek(){
		$thisWeek = strtotime("+1 week");
		$nextWeek = strtotime("+2 weeks");

		$qry = Event::where( 'time', '>', date('Y-m-d H:i:s', $thisWeek) )
			     	->where( 'time', '<', date('Y-m-d H:i:s', $nextWeek) )
			     	->join( 'societies', 'events.society_id', '=', 'societies.id');

		if( Auth::check() ){
			// If the user's logged in, take their subscriptions
			// into account
	        $soc_ids = DB::table('subscriptions')
			 ->where('user_id', Auth::user()->id)
			 ->lists('society_id');

			 $qry = $qry->whereIn('society_id', $soc_ids);
		}

		$events = $qry->orderBy('time')->get();

		return View::make('events.day', ['day' => "The week after this",
								     'events' => $events ]);

	}
	
	/**
	 * A list of events from this week to be converted to JSON
	 * @return Event Events for this coming week
	 */
	public function nextWeekJSON(){
		$thisWeek = strtotime("+1 week");
		$nextWeek = strtotime("+2 weeks");

		return Event::where( 'time', '>', date('Y-m-d H:i:s', $thisWeek) )
					->where( 'time', '<', date('Y-m-d H:i:s', $nextWeek) )
					->join( 'societies', 'events.society_id', '=', 'societies.id')
					->get();
	}

	/**
	 * Converts the an event's details into .ics
	 * @param  int $event_id
	 * @return .ics formatted content
	 */
	public function eventAsICS( $event_id ){
		$vCalendar = new \Eluceo\iCal\Component\Calendar( env('DOMAIN_NAME') );

		try{
			$event = Event::findOrFail($event_id);
		} catch (\Exception $e) {
			return $this->dayView( 'today' );
		}

		$vEvent = new \Eluceo\iCal\Component\Event();

		$eventTime = new DateTime($event->time, new DateTimeZone("Europe/Dublin"));
		$endTime = $eventTime;

		// ISO 8601 time interval format for ONE HOUR
		$endTime->add(new DateInterval('PT1H'));

		$eventSummary = $event->society()->first()->name . ' Society: '
						. $event->title;

		$vEvent
		    ->setDtStart($eventTime)
		    ->setUseTimezone(true)
		    ->setDtEnd($endTime)
		    ->setSummary($eventSummary);

		if($event->location){
			// If event has a location set
			$vEvent->setLocation($event->location);
		}

		$vCalendar->addComponent($vEvent);

		header('Content-Type: text/calendar; charset=utf-8');
		header('Content-Disposition: attachment; filename="cal.ics"');

		echo $vCalendar->render();
	}

	/**
	 * Present a full .ics calendar of events for a user
	 * @param  integer $user_id
	 * @return .ics formatted content
	 */
	public function calendar( $user_id ){
		$user_id = Crypt::decrypt($user_id);

        $soc_ids = DB::table('subscriptions')
					 ->where('user_id', $user_id)
			 		 ->lists('society_id');

        $events = Event::where('time', '>', date('Y-m-d H:i:s'))
        	           ->whereIn('society_id', $soc_ids)->get();

		$vCalendar = new \Eluceo\iCal\Component\Calendar( env('DOMAIN_NAME') );

		foreach($events as $event){
			$vEvent = new \Eluceo\iCal\Component\Event();

			$eventTime = new DateTime($event->time, new DateTimeZone("Europe/Dublin"));
			$endTime = $eventTime;

			// ISO 8601 time interval format for ONE HOUR
			$endTime->add(new DateInterval('PT1H'));

			$eventSummary = $event->society()->first()->name . ' Society: '
							. $event->title;

			$vEvent
			    ->setDtStart($eventTime)
			    ->setUseTimezone(true)
			    ->setDtEnd($endTime)
			    ->setSummary($eventSummary);

			if($event->location){
				// If event has a location set
				$vEvent->setLocation($event->location);
			}

			$vCalendar->addComponent($vEvent);
		}

		header('Content-Type: text/calendar; charset=utf-8');
		header('Content-Disposition: attachment; filename="cal.ics"');

		echo $vCalendar->render();
	}
}

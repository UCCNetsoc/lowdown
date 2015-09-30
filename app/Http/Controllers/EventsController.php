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

use DateTime;
use DateInterval;

use Crypt;

use App\Jobs\UpdateEvents;

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

		return view('events.day', ['day' => $values['day'],
								     'events' => $events ]);

	}

	/**
	 * Gets the events for a specific day
	 * @param  [type] $day [description]
	 * @return [type]      [description]
	 */
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

	public function thisWeekJSON(){
		// Look at how amazingly hacky this is like holy shit
		$mondayStartOfWeek = strtotime("last Monday", strtotime("tomorrow"));
		$endOfWeek = strtotime("next week", $mondayStartOfWeek);

		return Event::where('time', '>', date('Y-m-d H:i:s', $mondayStartOfWeek))
					->where('time', '<', date('Y-m-d H:i:s', $endOfWeek))
					->get();
	}

	public function thisWeek(){
		$mondayStartOfWeek = strtotime("last Monday", strtotime("tomorrow"));
		$endOfWeek = strtotime("next week", $mondayStartOfWeek);

		$qry = Event::where('time', '>', date('Y-m-d H:i:s', $mondayStartOfWeek))
					->where('time', '<', date('Y-m-d H:i:s', $endOfWeek));

		if( Auth::check() ){
	        $soc_ids = DB::table('subscriptions')
			 ->where('user_id', Auth::user()->id)
			 ->lists('society_id');

			 $qry = $qry->whereIn('society_id', $soc_ids);
		}

		$events = $qry->orderBy('time')->get();

		return view('events.day', ['day' => "This week!",
								     'events' => $events ]);

	}

	public function eventAsICS( $event_id ){
		$vCalendar = new \Eluceo\iCal\Component\Calendar('lowdown.netsoc.co');

		try{
			$event = Event::findOrFail($event_id);
		} catch (\Exception $e) {
			return $this->dayView( 'today' );
		}

		$vEvent = new \Eluceo\iCal\Component\Event();

		$eventTime = new DateTime($event->time);
		$endTime = $eventTime;
		$endTime->add(new DateInterval('PT1H'));

		$eventSummary = $event->society()->first()->name . ' Society: '
						. $event->title;

		$vEvent
		    ->setDtStart($eventTime)
		    ->setDtEnd($endTime)
		    ->setSummary($eventSummary);

		if($event->location){
			$vEvent->setLocation($event->location);
		}

		$vCalendar->addComponent($vEvent);

		header('Content-Type: text/calendar; charset=utf-8');
		header('Content-Disposition: attachment; filename="cal.ics"');

		echo $vCalendar->render();
	}

	public function calendar( $user_id ){
		$user_id = Crypt::decrypt($user_id);

        $soc_ids = DB::table('subscriptions')
					 ->where('user_id', $user_id)
			 		 ->lists('society_id');

        $events = Event::where('time', '>', date('Y-m-d H:i:s'))
        	           ->whereIn('society_id', $soc_ids)->get();

		$vCalendar = new \Eluceo\iCal\Component\Calendar('lowdown.netsoc.co');

		foreach($events as $event){
			$vEvent = new \Eluceo\iCal\Component\Event();

			$eventTime = new DateTime($event->time);
			$endTime = $eventTime;
			$endTime->add(new DateInterval('PT1H'));

			$eventSummary = $event->society()->first()->name . ' Society: '
							. $event->title;

			$vEvent
			    ->setDtStart($eventTime)
			    ->setDtEnd($endTime)
			    ->setSummary($eventSummary);

			if($event->location){
				$vEvent->setLocation($event->location);
			}

			$vCalendar->addComponent($vEvent);
		}

		header('Content-Type: text/calendar; charset=utf-8');
		header('Content-Disposition: attachment; filename="cal.ics"');

		echo $vCalendar->render();
	}
}

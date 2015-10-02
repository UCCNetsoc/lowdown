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

class SocietiesController extends Controller
{
	
/*
|--------------------------------------------------------------------------
| Societies Controller
|--------------------------------------------------------------------------
|
| 
|
*/
	/**
	 * Lists all the societies
	 * @return VIEW societies.index
	 */
    public function index( ){
        return View::make( 'societies.index' );
    }

    /**
     * Presents a view of all of a societies upcoming events
     * 
     * @param  string $society_identifier  This is the vanity URL of 
     *                                     the society's FB page
     *                                     
     * @return VIEW societies.events
     */
	public function socView( $society_identifier ){
		$values = $this->eventsForSociety($society_identifier);

		return View::make('societies.events', ['society_name' => $values['society']->name . ' Society',
										 'society_ref' =>  $values['society']->facebook_ref,
										  'events' => $values['events']]);
	}

	/**
	 * Outputs events for a society as json
	 * @param  string $day
	 * @return JSON
	 */
	public function socJSON( $society_identifier ){
		return $this->eventsForSociety($society_identifier)['events'];
	}

	/**
	 * Gets the events for a society
	 * 
     * @param  string $society_identifier  This is the vanity URL of 
     *                                     the society's FB page
     *                                     
     * @return Array Array containing Society object and their events
	 */
	public function eventsForSociety($society_identifier){
		$society_identifier = str_replace("-", " ", $society_identifier);

		try{
			$society = Society::where('id', $society_identifier)
				  ->orWhere('facebook_ref', $society_identifier)
				  ->firstOrFail();
		} catch (\Exception $e){
			abort(404);
		}

		$events = Event::where('time', '>', date('Y-m-d H:i:s', time() ) )
					   ->where('society_id', $society->id)
					   ->orderBy('time')
					   ->get();

		return ['society' => $society, 'events' => $events];

	}

	/**
	 * Present .ics/iCal content for all of a society's events
	 * 
	 * @param  string $society_id This is the vanity URL of 
     *                            the society's FB page
     *                            
	 * @return .ics file content
	 */
	public function calendar( $society_id ){
        $events = $this->eventsForSociety($society_id)['events'];

		$vCalendar = new \Eluceo\iCal\Component\Calendar( env('DOMAIN_NAME') );

		foreach($events as $event){
			$vEvent = new \Eluceo\iCal\Component\Event();

			$eventTime = new DateTime($event->time);
			$endTime = $eventTime;

			// ISO 8601 time interval format for ONE HOUR
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

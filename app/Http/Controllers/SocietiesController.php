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

    public function index( ){
        return View::make( 'societies.index' );
    }


	public function socView( $society_identifier ){
		$values = $this->eventsForSociety($society_identifier);

		return view('societies.events', ['society_name' => $values['society']->name . ' Society',
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
	 * @param  [type] $day [description]
	 * @return [type]      [description]
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

	public function calendar( $society_id ){
        $events = $this->eventsForSociety($society_id)['events'];

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

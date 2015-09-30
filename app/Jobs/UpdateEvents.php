<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

class UpdateEvents extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $isSeeding;

    /**
     * Create new job instance
     * @param boolean $isSeeding Whether this is coming from the seeding
     *                           script or not
     */
    public function __construct( $isSeeding = false )
    {
        $this->isSeeding = $isSeeding;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      if( $this->isSeeding ){
        $toTake = 200; // 200 societies
        $delay = 0; // Immediately
      } else{
        $toTake = 20; // 20 societies
        $delay = 600; // 10 Minutes
      }

        FacebookSession::setDefaultApplication( getenv('FB_ID'), getenv('FB_SECRET') );
        $session = FacebookSession::newAppSession();

        try {
          $session->validate();
        } catch (FacebookRequestException $ex) {
          // Session not valid, Graph API returned an exception with the reason.
          dd($ex);
        } catch (\Exception $ex) {
          // Graph API returned info, but it may mismatch the current app or have expired.
          dd($ex);
        }


        $store = \App\Setting::where('name', 'next_society')
                                  ->first();
        $lastUpdated = $store->setting; // Get last society ID updated;

        $societies = \App\Society::where('id', '>', $lastUpdated)
                                ->take($toTake)->orderBy('id')
                                ->get(); // Get Societies to query

        foreach($societies as $society){
            $request = new FacebookRequest($session, 'GET', '/' . $society->facebook_ref . '/events' .
                                           '?since='.time().'&fields=name,start_time,location,description,cover');

            try{
                $response = $request->execute();
            } catch(\Exception $ex) {
                continue; // TODO: Report errors back to us :)
            }
            $graphObject = $response->getGraphObject();

            $events = $graphObject->asArray();

            if( array_key_exists('data', $events) ){
                $events = $events['data'];

                foreach($events as $fbEvent){
                    $storedEvent = \App\Event::firstOrNew(['facebook_id' => $fbEvent->id]);


                    $storedEvent->society_id = $society->id;
                    $storedEvent->title = $fbEvent->name;
                    $storedEvent->time = $fbEvent->start_time;
                    if(array_key_exists("description", $fbEvent) ){
                        $description = substr($fbEvent->description, 0, 500);
                        if( strlen($description) < strlen($fbEvent->description) ){
                            $description .= "…";
                        }
                        $storedEvent->description = $description;
                    }
                    if(array_key_exists("location", $fbEvent) ){
                        $storedEvent->location = $fbEvent->location;
                    }
                    if(array_key_exists("cover", $fbEvent)){
                        $storedEvent->image = $fbEvent->cover->source;
                    }
                    $storedEvent->save();
                }
            }
        }

        if( count($societies) < $toTake ){
             $store->setting = 0;
        } else {
            $store->setting += $toTake;
        }

        $store->save();

        $job = (new \App\Jobs\UpdateEvents())->delay($delay);

        if( !$this->isSeeding ){
          $this->dispatch($job);
        }

    }
}

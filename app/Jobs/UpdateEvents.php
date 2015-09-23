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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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

        $store = \App\Setting::where('name', 'last_society')
                                  ->first();
        $lastUpdated = $store->setting;

        $societies = \App\Society::where('id', '>', $lastUpdated)
                                ->take(20)
                                ->get();

        if( count($societies) < 20 ){
            $store->setting = 0;
            $store->save(); 
        }

        foreach($societies as $society){
            $request = new FacebookRequest($session, 'GET', '/' . $society->facebook_ref . '/events' .
                                           '?since'.time().'&fields=name,start_time,location,description,cover');
            $response = $request->execute();
            $graphObject = $response->getGraphObject();

            $events = $graphObject->data;

            foreach($events as $fbEvent){
                $storedEvent = \App\Event::firstOrNew(['facebook_id' => $fbEvent->id]);

                $storedEvent->society_id = $society->id;
                $storedEvent->title = $fbEvent->name;
                $storedEvent->description = $fbEvent->description;
                $storedEvent->location = $fbEvent->location;
                $storedEvent->time = $fbEvent->start_time;
                $storedEvent->image = $fbEvent->cover->source;

                $storedEvent->save();
            }

        }
    }
}

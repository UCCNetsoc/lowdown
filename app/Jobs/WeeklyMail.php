<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\SendEmail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Event;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

class WeeklyMail extends Job implements SelfHandling, ShouldQueue
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
        // Same time next week! :D
        $job = (new \App\Jobs\WeeklyMail())->delay(604800);
        $this->dispatch($job);

        // We'll just want to double-check our Facebook events still exist
        // before we email people about them...
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

        $all_events = Event::where( 'time', '>', date('Y-m-d H:i:s') )
                           ->where( 'time', '<', date('Y-m-d H:i:s', time()+604800) )
                           ->get();

        foreach($all_events as $event){
            $request = new FacebookRequest($session, 'GET', "/events/" . $event->facebook_id);

            try{
                $response = $request->execute();
            } catch(\Exception $ex) {
                // Facebook Exception looking up event; probably deleted, should mirror here.
                $event->delete();
            }
        }


        foreach(User::where('unsubscribed_email', 'no') as $user){
            $this->dispatch( new SendEmail($user) );
        }
    }
}

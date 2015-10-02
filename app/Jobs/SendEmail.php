<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Event;
use DB;
use Mail;

class SendEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $user;
    private $events;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->events = Event::where( 'time', '>', date('Y-m-d H:i:s') )
                              ->where( 'time', '<', date('Y-m-d H:i:s', time()+604800) )
                              ->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if( $this->user->unsubscribed_email != "no"){
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

            Mail::send('emails.weekly', $data, function ($message) {
                $message->from( env('MAIL_ADDRESS'), 'Lowdown');
                $message->subject('Your Weekly Society Lowdown');
                $message->to($this->user->email);
            });
          }
        }
    }
}
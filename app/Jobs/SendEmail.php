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

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $soc_ids = DB::table('subscriptions')->where('user_id', $this->user->id)->lists('society_id');

        // All Events, in user subscribed society, next week. 
        $events  = Event::whereIn('society_id', $soc_ids)
                              ->where( 'time', '>', date('Y-m-d H:i:s') )
                              ->where( 'time', '<', date('Y-m-d H:i:s', time()+604800) );

        Mail::send('emails.weekly', ['user' => $this->user, 'events' => $events], function ($message) {
            $message->from('lowdown@netsoc.co', 'Lowdown');
            $message->to($this->user->email);
        });

    }
}

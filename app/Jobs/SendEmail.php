<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $soc_ids = DB::table('subscriptions')->whereIn('user_id', $user->id)->lists('society_id');

        // All Events, in user subscribed society, next week. 
        $events  = \App\Event::whereIn('society_id', $soc_ids)
                              ->where( 'time', '>', date('Y-m-d H:i:s') )
                              ->where( 'time', '<', date('Y-m-d H:i:s', now()+604800) );

    }
}

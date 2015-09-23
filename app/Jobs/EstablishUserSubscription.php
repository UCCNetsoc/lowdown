<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Society;
use App\Subscription;

class EstablishUserSubscription extends Job implements SelfHandling, ShouldQueue
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
        $this->user->processing = 'yes';
        $this->user->save();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach( Society::all() as $society ){
            Subscription::create(['user_id' => $this->user->id, 'society_id' => $society->id]);
        }

        $this->user->processing = 'no';
        $this->user->save();
    }
}

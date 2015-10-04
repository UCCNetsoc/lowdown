<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs;
use App\Jobs\WeeklyMail;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SendEmails extends Command
{

    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send {hour=17}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out the first batch of emails.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $scheduled_hour = (int) $this->argument('hour');
        $current_hour = (int) date('H');

        $difference = $scheduled_hour - $current_hour;

        $difference_in_seconds = $difference*60*60;

        $this->dispatch( (new WeeklyMail())->delay($difference_in_seconds));
    }
}

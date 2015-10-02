<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Setting;
use App\Society;
use App\User;
use App\Event;

use App\Jobs\UpdateEvents;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('SettingsSeeder');
        $this->call('SocietiesSeeder');
        $this->call('EventsSeeder');

        Model::reguard();
    }
}

/**
 * Inserts all of the societies
 */
class SocietiesSeeder extends Seeder {

    public function run()
    {
        DB::table('societies')->delete();


        /*
        |--------------------------------------------------------------------------
        | The processes the CSV included in the root directory of the application
        |--------------------------------------------------------------------------
        | To add your own manually, simply gather the name and facebook
        | vanity URL of the society and put it in the format:
        |
        |   Society::create([
        |       'name' => 'Society Name', 'facebook_ref' => 'societyurl'
        |   ]);
        | 
        | or, simply use the .csv file and put in your own societies
        |
        */
        $societies = array_map('str_getcsv', file(base_path().'/list_of_societies.csv'));

        foreach ($societies as $society) {
            Society::create([
                'name' => $society[0], 'facebook_ref' => $society[1]
            ]);
        }


        $this->command->info('Societies table seeded!');
    }

}

/**
 * Seeds the settings table with defaults
 */
class SettingsSeeder extends Seeder {

    public function run()
    {
        DB::table('settings')->delete();

        // Total number of societies
        Setting::create(['name' => 'number_of_societies', 'setting' => '103']);

        // The next society to process
        Setting::create(['name' => 'next_society', 'setting' => '0']);

        // The next user to process
        Setting::create(['name' => 'next_user', 'setting' => '1']);

        // The URL of your logo
        Setting::create(['name' => 'logo', 'setting' => 'http://netsoc.co/wp-content/themes/netsoc/images/horizontal.png']);

        // The alternate logo for the front page (in white)
        Setting::create(['name' => 'logo_alt', 'setting' => env('BASE_URL') . '/images/logo_alt.png' ]);

        // The full name of your society
        Setting::create(['name' => 'name', 'setting' => 'UCC Networking, Gaming And Technology Society']);

        $this->command->info('Settings table seeded!');
    }

}

/**
 * Get the first initial wave of events
 */
class EventsSeeder extends Seeder{
    use Illuminate\Foundation\Bus\DispatchesJobs;
    public function run(){
       $this->dispatch( new UpdateEvents( $isSeeding = true ) );
    }
}
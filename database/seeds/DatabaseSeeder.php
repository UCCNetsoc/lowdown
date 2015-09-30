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
 * Inserts all of the societies
 */
class SocietiesSeeder extends Seeder {

    public function run()
    {
        DB::table('societies')->delete();


        /*
        |--------------------------------------------------------------------------
        | The following are all of the societies in UCC
        |--------------------------------------------------------------------------
        | To add your own, simply gather the name and facebook
        | vanity URL of the society and put it in the format:
        |
        |   Society::create([
        |       'name' => 'Society Name', 'facebook_ref' => 'societyurl'
        |   ]);
        |
        */
        Society::create(['name' => 'Africa', 'facebook_ref' => 'uccafrica']);
        Society::create(['name' => 'Amnesty', 'facebook_ref' => 'UCCAmnesty']);
        Society::create(['name' => 'Animal Welfare', 'facebook_ref' => 'AniWelUCC']);
        Society::create(['name' => 'CCAE Architecture', 'facebook_ref' => 'ccaearchitecturesociety']);
        Society::create(['name' => 'Art', 'facebook_ref' => 'uccart.soc']);
        Society::create(['name' => 'Barnardos', 'facebook_ref' => 'UCCBarnardos']);
        Society::create(['name' => 'Biology', 'facebook_ref' => 'uccbiosoc']);
        Society::create(['name' => 'Cancer', 'facebook_ref' => 'ucc.cancersociety']);
        Society::create(['name' => 'Chemical', 'facebook_ref' => '236000773224765']);
        Society::create(['name' => 'Chinese', 'facebook_ref' => 'UccChineseSociety']);
        Society::create(['name' => 'Choral', 'facebook_ref' => 'UCCChoral']);
        Society::create(['name' => 'Christian Union', 'facebook_ref' => 'UCCChristianUnionSociety']);
        Society::create(['name' => 'Clinical Therapies', 'facebook_ref' => 'UccClinicalTherapiesSociety']);
        Society::create(['name' => 'Comedy', 'facebook_ref' => 'ucccomedy']);
        Society::create(['name' => 'Commerce', 'facebook_ref' => 'commsoc.ucc']);
        Society::create(['name' => 'Dentist', 'facebook_ref' => 'dentsocucc']);
        Society::create(['name' => 'Disability Activism & Awareness', 'facebook_ref' => 'uccdaasoc']);
        Society::create(['name' => 'DJ', 'facebook_ref' => 'djsocucc']);
        Society::create(['name' => 'Dramat', 'facebook_ref' => 'UCCDramat']);
        // Society::create(['name' => 'Enactus', 'facebook_ref' => '392583690818520']); // doesn't seem to want to load from graph api?!?
        Society::create(['name' => 'Engineers Without Borders', 'facebook_ref' => 'EngineerswithoutbordersUCC']);
        Society::create(['name' => 'Environment', 'facebook_ref' => 'UCCEnvirosoc']);
        Society::create(['name' => 'Europa', 'facebook_ref' => 'ucceuropa']);
        Society::create(['name' => 'Feminist', 'facebook_ref' => 'UCCFemSoc']);
        Society::create(['name' => 'Film', 'facebook_ref' => 'uccfilm']);
        Society::create(['name' => 'French', 'facebook_ref' => 'uccfrenchsoc']);
        Society::create(['name' => 'Friends Of MSF', 'facebook_ref' => 'uccfomsf']);
        Society::create(['name' => 'Gaisce', 'facebook_ref' => 'GaisceUCC']);
        Society::create(['name' => 'Genetics', 'facebook_ref' => 'uccgensoc']);
        Society::create(['name' => 'Hope Foundation', 'facebook_ref' => 'UccHopeFoundation']);
        Society::create(['name' => 'Indian', 'facebook_ref' => 'ucc.indians']);
        Society::create(['name' => 'International Development', 'facebook_ref' => 'UCCIntDevSoc']);
        Society::create(['name' => 'International Relations', 'facebook_ref' => 'uccirsoc']);
        Society::create(['name' => 'International Students', 'facebook_ref' => 'ucc.internationals']);
        Society::create(['name' => 'Traditional Music', 'facebook_ref' => 'ucctradsoc']);
        Society::create(['name' => 'Islamic', 'facebook_ref' => 'UCCIslamicSociety']);
        Society::create(['name' => 'Italian', 'facebook_ref' => 'UCCItalianSociety']);
        Society::create(['name' => 'Japanese', 'facebook_ref' => 'uccjapansoc']);
        Society::create(['name' => 'Journalism', 'facebook_ref' => 'UCCJournalismSociety']);
        Society::create(['name' => 'Korean', 'facebook_ref' => 'ucckoreansociety']);
        Society::create(['name' => 'Labour', 'facebook_ref' => 'ucclabour']);
        Society::create(['name' => 'Law', 'facebook_ref' => 'ucclawsociety']);
        Society::create(['name' => 'LGBT', 'facebook_ref' => 'ucclgbt']);
        // Society::create(['name' => 'Live Music', 'facebook_ref' => 'ucclms']); // closed society
        Society::create(['name' => 'Macra Na Feirme', 'facebook_ref' => 'uccmacra']);
        Society::create(['name' => 'Math', 'facebook_ref' => 'uccmathsoc']);
        Society::create(['name' => 'Medical', 'facebook_ref' => 'ucc.medsoc']);
        Society::create(['name' => 'Medieval And Renaissance', 'facebook_ref' => 'UCCMedRen']);
        Society::create(['name' => 'Musical', 'facebook_ref' => 'uccmusicalsociety']);
        Society::create(['name' => 'Mythology', 'facebook_ref' => 'UccMythology']);
        Society::create(['name' => 'Networking, Gaming and Technology', 'facebook_ref' => 'UCCNetsoc']);
        Society::create(['name' => 'Nursing And Midwifery', 'facebook_ref' => 'UCCnursmidsoc']);
        Society::create(['name' => 'Pharmacy', 'facebook_ref' => 'pharmsoc.ucc']);
        Society::create(['name' => 'Philosophical', 'facebook_ref' => 'uccphilosoph']);
        Society::create(['name' => 'Photography', 'facebook_ref' => 'uccphoto']);
        Society::create(['name' => 'Poker', 'facebook_ref' => 'uccpokersociety']);
        Society::create(['name' => 'Politics', 'facebook_ref' => 'PolSocUCC']);
        Society::create(['name' => 'SAMH', 'facebook_ref' => 'uccsamhsoc']);
        Society::create(['name' => 'Science', 'facebook_ref' => 'uccsciencesociety']);
        Society::create(['name' => 'Scifi', 'facebook_ref' => 'uccscifi']);
        Society::create(['name' => 'Sinn Fein', 'facebook_ref' => 'uccsinnfein']);
        Society::create(['name' => 'Social Science', 'facebook_ref' => 'uccsocsci']);
        Society::create(['name' => 'SSDP', 'facebook_ref' => 'UCCSSDP']);
        Society::create(['name' => 'Surgeon Noonan', 'facebook_ref' => '1650501751859811']);
        Society::create(['name' => 'Surgeon', 'facebook_ref' => 'surgsoc']);
        Society::create(['name' => 'Suas', 'facebook_ref' => 'uccsuas']);
        Society::create(['name' => 'Fine Gael', 'facebook_ref' => 'uccyoungfinegael']);
        Society::create(['name' => 'Accounting And Finance', 'facebook_ref' => 'uccaccfin']);
        Society::create(['name' => 'An Chuallact', 'facebook_ref' => 'anchuallachtucc']);
        Society::create(['name' => 'Archaeological', 'facebook_ref' => 'UCCArchSoc']);
        // Society::create(['name' => 'E&S', 'facebook_ref' => 'uccEandS']); // Not returning from Facebook either - must investigate
        Society::create(['name' => 'Economics', 'facebook_ref' => 'ucceconomics']);
        Society::create(['name' => 'Fashion', 'facebook_ref' => '155068187434']);
        Society::create(['name' => 'Fianna Fail', 'facebook_ref' => 'UccFFSoc']);
        Society::create(['name' => 'Foodies', 'facebook_ref' => '373486662727603']);
        Society::create(['name' => 'German', 'facebook_ref' => 'uccgermansociety']);
        Society::create(['name' => 'Hispanic', 'facebook_ref' => 'UCCHispanicSoc']);
        Society::create(['name' => 'Historical', 'facebook_ref' => '276841219006188']);
        Society::create(['name' => 'Horse Racing', 'facebook_ref' => '281964401864450']);
        Society::create(['name' => 'Hot Beverages', 'facebook_ref' => 'UCCHotBevs']);
        Society::create(['name' => 'Physics And Astronomy', 'facebook_ref' => 'uccphysoc']);
        Society::create(['name' => 'Psychology', 'facebook_ref' => '110003839018417']);
        Society::create(['name' => 'Slainte', 'facebook_ref' => '184958275298']);
        Society::create(['name' => 'An Cumann Drámaíochta', 'facebook_ref' => '292193067563180']);
        Society::create(['name' => 'BIS Society', 'facebook_ref' => 'BisSociety']);
        Society::create(['name' => 'Engineering', 'facebook_ref' => 'UCCEngSoc']);
        Society::create(['name' => 'English', 'facebook_ref' => '1528413287397046']);
        Society::create(['name' => 'FLAC', 'facebook_ref' => '293165574222535']);
        Society::create(['name' => 'Geological', 'facebook_ref' => 'UccGeological']);
        Society::create(['name' => 'Government And Politics', 'facebook_ref' => '216465755054445']);
        Society::create(['name' => 'Greens', 'facebook_ref' => 'ucc.greens']);
        Society::create(['name' => 'Harry Potter', 'facebook_ref' => 'HPSocietyUCC']);
        Society::create(['name' => 'Health', 'facebook_ref' => '466162393565838']);
        Society::create(['name' => 'Knitting', 'facebook_ref' => 'UCCKnitSoc']);
        Society::create(['name' => 'Management And Marketing', 'facebook_ref' => 'MnMSocUCC']);
        Society::create(['name' => 'Mature Students', 'facebook_ref' => 'UCCMSS']);
        Society::create(['name' => 'Orchestra', 'facebook_ref' => 'OrchestraUCC']);
        Society::create(['name' => 'Planning', 'facebook_ref' => 'uccplanning']);
        Society::create(['name' => 'Simon', 'facebook_ref' => 'Uccsimonsociety']);
        Society::create(['name' => 'Socialist', 'facebook_ref' => 'uccsocialistyouth']);
        Society::create(['name' => 'South East Asia', 'facebook_ref' => '526366970861527']);
        Society::create(['name' => 'Sophia', 'facebook_ref' => 'uccsophia']);
        Society::create(['name' => 'St. Vincent De Paul', 'facebook_ref' => 'SVPUCC']);
        Society::create(['name' => 'WARP', 'facebook_ref' => 'ucc.warps']);


        $this->command->info('Societies table seeded!');
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
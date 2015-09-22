<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Setting;
use App\Society;
use App\User;

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

        Model::reguard();
    }
}

class SettingsSeeder extends Seeder {

    public function run()
    {
        DB::table('settings')->delete();

        Setting::create(['name' => 'number_of_societies', 'setting' => '103']);
        Setting::create(['name' => 'last_society', 'setting' => '1']);
        Setting::create(['name' => 'logo', 'setting' => 'http://netsoc.co/wp-content/themes/netsoc/images/horizontal.png']);
        Setting::create(['name' => 'name', 'setting' => 'UCC Networking, Gaming And Technology Society']);

        $this->command->info('Settings table seeded!');
    }

}

class SocietiesSeeder extends Seeder {

    public function run()
    {
        DB::table('societies')->delete();

        Society::create(['name' => 'Africa', 'facebook_ref' => 'uccafrica']);
        Society::create(['name' => 'Amnesty', 'facebook_ref' => 'UCCAmnesty']);
        Society::create(['name' => 'Animal Welfare', 'facebook_ref' => 'UccAnimalWelfareSoc']);
        Society::create(['name' => 'CCAE Architecture', 'facebook_ref' => 'ccaearchitecturesociety']);
        Society::create(['name' => 'Art', 'facebook_ref' => 'uccart.soc']);
        Society::create(['name' => 'Barnardos', 'facebook_ref' => 'UCCBarnardos']);
        Society::create(['name' => 'Biology', 'facebook_ref' => 'uccbiosoc']);
        Society::create(['name' => 'Cancer', 'facebook_ref' => 'ucc.cancersociety']);
        Society::create(['name' => 'Chemical', 'facebook_ref' => 'uccchemicalsociety']);
        Society::create(['name' => 'Chinese', 'facebook_ref' => 'UccChineseSociety']);
        Society::create(['name' => 'Choral', 'facebook_ref' => 'UCCChoral']);
        Society::create(['name' => 'Christian Union', 'facebook_ref' => 'UCCChristianUnionSociety']);
        Society::create(['name' => 'Clinical Therapies', 'facebook_ref' => 'UccClinicalTherapiesSociety']);
        Society::create(['name' => 'Comedy', 'facebook_ref' => 'ucccomedy']);
        Society::create(['name' => 'Commerce', 'facebook_ref' => 'commsoc.ucc']);
        Society::create(['name' => 'Dentist', 'facebook_ref' => 'dentsocucc']);
        Society::create(['name' => 'Disability And Activism', 'facebook_ref' => 'ucc.disabilityactivism']);
        Society::create(['name' => 'DJ', 'facebook_ref' => 'Uccdj']);
        Society::create(['name' => 'Dramat', 'facebook_ref' => 'UCCDramat']);
        Society::create(['name' => 'Enactus', 'facebook_ref' => 'UCCEnactus']);
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
        // Society::create(['name' => 'Live Music', 'facebook_ref' => 'ucclms']);
        Society::create(['name' => 'Macra Na Feirme', 'facebook_ref' => 'MacraNaFeirmeUCC']);
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
        Society::create(['name' => 'Scifi', 'facebook_ref' => 'uccscifisoc']);
        Society::create(['name' => 'Sinn Fein', 'facebook_ref' => 'uccsinnfein']);
        Society::create(['name' => 'Social Science', 'facebook_ref' => 'uccsocsci']);
        Society::create(['name' => 'SSDP', 'facebook_ref' => 'UCCSSDP']);
        Society::create(['name' => 'Surgeon Noonan', 'facebook_ref' => 'surgeonnoonan']);
        Society::create(['name' => 'Surgeon', 'facebook_ref' => 'surgsoc']);
        Society::create(['name' => 'Suas', 'facebook_ref' => 'uccsuas']);
        Society::create(['name' => 'Fine Gael', 'facebook_ref' => 'uccyoungfinegael']);
        Society::create(['name' => 'Accounting And Finance', 'facebook_ref' => 'uccaccfin']);
        Society::create(['name' => 'An Chuallact', 'facebook_ref' => 'anchuallachtucc']);
        Society::create(['name' => 'Archaeological', 'facebook_ref' => 'UCCArchSoc']);
        Society::create(['name' => 'E&S', 'facebook_ref' => 'uccEandS']);
        Society::create(['name' => 'Economics', 'facebook_ref' => 'ucceconomics']);
        Society::create(['name' => 'Fashion', 'facebook_ref' => '155068187434']);
        Society::create(['name' => 'Fianna Fail', 'facebook_ref' => 'UccFFSoc']);
        Society::create(['name' => 'Foodies', 'facebook_ref' => '373486662727603']);
        Society::create(['name' => 'German', 'facebook_ref' => 'uccgermansociety']);
        Society::create(['name' => 'Hispanic', 'facebook_ref' => 'UCCHispanicSoc']);
        Society::create(['name' => 'Historical', 'facebook_ref' => '276841219006188']);
        Society::create(['name' => 'Horse Racing', 'facebook_ref' => '281964401864450']);
        Society::create(['name' => 'Hot Beverages', 'facebook_ref' => 'UCCHotBevs']);
        Society::create(['name' => 'Physics', 'facebook_ref' => 'uccphysoc']);
        Society::create(['name' => 'Psychology', 'facebook_ref' => '110003839018417']);
        Society::create(['name' => 'Slainte', 'facebook_ref' => '184958275298']);

        $this->command->info('Societies table seeded!');
    }

}
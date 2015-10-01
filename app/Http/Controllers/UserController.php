<?php namespace App\Http\Controllers;

use View;
use Auth;
use Response;
use Redirect;
use Request;
use Validator;
use Hash;
use DB;
use App\User;
use App\Society;
use App\Event;
use App\Subscription;
use App\Setting;
use App\Jobs\EstablishUserSubscription;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
	
/*
|--------------------------------------------------------------------------
| User Controller
|--------------------------------------------------------------------------
|
| 
|
*/
	/**
	 * Render front page view
	 * @return VIEW welcome
	 */
	public function index( ){
        $all_events = Event::where( 'time', '>', date('Y-m-d H:i:s') )
                           ->orderBy(DB::raw('RAND()'));

        $first_six = $all_events->take(6)->get();

        $next_six = $all_events->skip(6)->take(6)->get();

		return View::make( 'welcome' )->with('first_six', $first_six)->with('next_six', $next_six);
	}
	
	/**
	 * Render registration view
	 * @return VIEW users.register
	 */
    public function register( ){
        return View::make( 'users.register' );
    }

    /**
     * Creates a new user
     * 	Data should be POSTed to this function only
     * @return REDIRECT subscriptions
     */
    public function store( ){
    	// Only allow following fields to be submitted
        $data = Request::only( [
                    'name',
                    'password',
                    'password_confirmation',
                    'email'
                ]);

        // Validate all input
        $validator = Validator::make( $data, [
                    'name'  => 'required',
                    'email'     => 'email|required|unique:users',
                    'password'  => 'required|confirmed|min:5'
                ]);

        if( $validator->fails( ) ){
        	// If validation fails, redirect back to 
        	// registration form with errors
            return Redirect::back( )
                    ->withErrors( $validator )
                    ->withInput( );
        }

        // Hash the password
        $data['password'] = Hash::make($data['password']);

        $data['username'] = $data['email'];
        
        // Create the new user
        $newUser = User::create( $data );

        if( $newUser ){
            Auth::login($newUser);

            $this->dispatch(new EstablishUserSubscription($newUser));

        	// If successful, go to home
        	return Redirect::route( 'subscriptions' );
        }
        
        // If unsuccessful, return with errors
        return Redirect::back( )
                    ->withErrors( [
                    	'message' => 'We\'re sorry but registration failed, please email '. env('DEV_EMAIL') 
                    ] )
                    ->withInput( );

    }

    /**
     * Render login view
     * @return VIEW users.login
     */
    public function login( ){

        if( Auth::check( ) ){
        	// If user is logged in, send 'em home
            return Redirect::route( 'home' );
        }

        return View::make( 'users.login' );
    }

    /**
     * Log a user into the system
     * @return REDIRECT home
     */
    public function handleLogin( ){
    	// Filter allowed data
        $data = Request::only([ 'email', 'password' ]);

        // Validate user input
        $validator = Validator::make(
            $data,
            [
                'email' => 'required|email|min:8',
                'password' => 'required',
            ]
        );

        if($validator->fails()){
        	// If validation fails, send back with errors
            return Redirect::route('login')->withErrors( $validator )->withInput( );
        }

        if( Auth::attempt( [ 'email' => $data['email'], 'password' => $data['password']], true ) ){
        	// If login is successful, send them to home
            return Redirect::route( 'home' );
        } else {
        	// Otherwise, tell them they're wrong
            return Redirect::route( 'login' )
            			   ->withErrors([ 
            			   		'message' => 'I\'m sorry, that username and password aren\'t correct.' 
            			   	]);
        }

        return Redirect::route( 'login' )->withInput( );
    }

    /**
     * Allows users to update their society subscriptions
     * @return VIEW users.subscriptions
     */
    public function subscriptions( ){
        if( Auth::user()->processing == 'yes' ){
            // If the default list is still being added, show
            // a loading message
            header("Refresh:3");
            return View::make('preparing-account');
        }

        $societies = Society::all();
        $subscriptions = User::find( Auth::user()->id )->subscriptions( );
        $subscriptions = $subscriptions->get();

        foreach ($subscriptions as $subscription) {
            // For every subscription a user HAS, mark it as checked
            $societies[$subscription->society_id - 1]->checked = "checked";
        }

        $societies = $societies->sortBy('name');

        // Get total number of societies
        $numberOfSocieties = Setting::where('name', 'number_of_societies')->first()->setting;

        return View::make('users.subscriptions')
                    ->with('societies', $societies)
                    ->with('numberOfSocieties', $numberOfSocieties);
    }

    /**
     * Handles updating subscriptions
     * @return REDIRECT subscriptions
     */
    public function updateSubscriptions( ){
        $data = Request::only(['allSubscriptions']);
        $user_id = Auth::user()->id;

        // An array of all the ticked checkboxes
        $chosen_societies = json_decode($data['allSubscriptions']);

        // Get all subscriptions that WERE NOT ticked
        $toDelete = Subscription::where('user_id', $user_id)
                                ->whereNotIn('society_id', $chosen_societies)
                                ->get();

        foreach($toDelete as $subscription){
            // Delete the subscriptions that weren't ticked
            $subscription->delete();
        }

        foreach($chosen_societies as $society_id){
            // For every TICKED subscription
            //     If exists: Do nothing
            //     Else: create subscription
            Subscription::firstOrCreate(['society_id' => $society_id,
                                         'user_id' => $user_id]);
        }

        return Redirect::route('subscriptions');
    }
}

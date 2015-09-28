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
	 * @return VIEW register
	 */
    public function register( ){
        return View::make( 'users.register' );
    }

    /**
     * Creates a new user
     * 	Data should be POSTed to this function only
     * @return REDIRECT home
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
     * @return VIEW login
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

    public function subscriptions( ){
        if( Auth::user()->processing == 'yes' ){
            header("Refresh:10");
            return View::make('preparing-account');
        }

        $societies = Society::all( );

        $subscriptions = User::find(Auth::user()->id)->subscriptions( );
        $subscriptions = $subscriptions->get();

        foreach ($subscriptions as $subscription) {

            $societies[$subscription->society_id - 1]->checked = "checked";
        }

        $numberOfSocieties = Setting::where('name', 'number_of_societies')->first()->setting;

        return View::make('users.subscriptions')
                    ->with('societies', $societies)
                    ->with('numberOfSocieties', $numberOfSocieties);
    }

    public function updateSubscriptions( ){
        $data = Request::only(['allSubscriptions']);

        // Trim and replace all extranneous whitespace then explode the array
        $data = explode(' ', trim(preg_replace('/\s+/', ' ', $data['allSubscriptions'])));
        
        foreach ($data as $societyID) {
            if((1 <= $societyID) && ($societyID <= Setting::where('name', 'number_of_societies')->first()->setting)){
                try {
                    // If subscription exists, cancel it
                    $currentSubscription = Subscription::findOrFail($societyID);
                    $currentSubscription->delete();    
                } catch (ModelNotFoundException $e) {
                    // If subscription doesn't exist, create one
                    Subscription::create(['society_id' => $societyID, 'user_id' => Auth::user()->id]);
                }    
            }        
        }

        return Redirect::route('subscriptions');
    }
}

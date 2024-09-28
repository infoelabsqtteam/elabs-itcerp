<?php

namespace App\Http\Controllers\Auth;
use DB;
use Cookie;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use Auth;
use Crypt;
use Carbon\Carbon;
use App\UserLogActivity;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
    * Where to redirect users after login.
    *
    * @var string
    */
    protected $redirectTo = '/dashboard';
    
    /**
    * Get the needed authorization credentials from the request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function authenticated(Request $request, $user){        
        $password_updated_at = $user->password_changed_at;
        $password_expiry_days = defined('PASSOWRD_EXPIRY_DAYS') ? PASSOWRD_EXPIRY_DAYS : '30';
        $password_expiry_at = Carbon::parse($password_updated_at)->addDays($password_expiry_days);        
        if(empty($password_updated_at)){
            UserLogActivity::addToUserLogActivity('Please change your password.');  //Adding User Log
            return redirect('/change-password')->with('alertMsg', "Please change your password.");
        }else if($password_expiry_at->lessThan(Carbon::now())){
            UserLogActivity::addToUserLogActivity('Your Password is expired, You need to change your password.');   //Adding User Log
            return redirect('/expiry-password')->with('alertMsg', "Your Password is expired, You need to change your password.");
        }
        UserLogActivity::addToUserLogActivity('Logged in successfully');    //Adding User Log
        return redirect()->intended($this->redirectPath());
    }
    
    /**
    * Get the needed authorization credentials from the request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    protected function credentials(Request $request){
        $credentials = $request->only($this->username(), 'password');
        $credentials['status'] = 1;
        return $credentials;
    }
    
    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request){
        
        global $models,$order,$dashboard;
        
        UserLogActivity::addToUserLogActivity('Loggout in successfully');   //Adding User Log
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('/');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    
   
    
    
}

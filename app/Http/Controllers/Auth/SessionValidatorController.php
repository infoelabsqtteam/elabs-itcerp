<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use DB;
use Session;
use Route;
use App\Models;

class SessionValidatorController extends Controller
{
    /**
    * protected Variable.
    */
    protected $auth;

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct(){

	global $models;

	$models = new  Models();

	$this->middleware(function ($request, $next) {
	    $this->auth = Auth::user();
	    parent::__construct($this->auth);
	    //Checking current request is allowed or not
	    $allowedAction = array('index','navigation');
	    $actionData    = explode('@',Route::currentRouteAction());
	    $action        = !empty($actionData[1]) ? trim(strtolower($actionData[1])): '0';
	    if(defined('NOTALlOWEDTONAVIGATE') && empty(NOTALlOWEDTONAVIGATE) && in_array($action,$allowedAction)){
		return redirect('dashboard')->withErrors('Permission Denied!');
	    }
	    return $next($request);
        });
    }
    
    /************************************
    * Description : Session Validator
    * Date        : 11-June-2019
    * Author      : Praveen Singh
    ************************************/
    public function validateAuthSession(){			 
        $error = '0';
        if(!Auth::check()) {
            $error = '1';
            Session::put('alertMsg', config('messages.message.sessionExpired'));
        }
        return response()->json(['error' => $error]);
    }
}

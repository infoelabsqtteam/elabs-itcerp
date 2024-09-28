<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use Validator;
use Route;
use DB;

class ThemeController extends Controller
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
    public function __construct()
    {
		global $models;
		$models = new Models();
        $this->middleware('auth'); 
		$this->middleware(function ($request, $next) {
            $this->session = Auth::user();
			parent::__construct($this->session);
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
    
	/**
     * Display a  btn setting page.
     *
     * @return \Illuminate\Http\Response
     */
    public function buttonParametersSettings()
    { 
		return view('theme_setting.button_settings'); 
    }
	
	/**
     * get btn parameters.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBtnParameters()
    { 
		global $models;
		$btns = DB::table('button_paramerters')->get();	 
		//$models->formatTimeStampFromArray($depts,DATETIMEFORMAT);
		return response()->json([
		   'btnsParameterList' => $btns,
		]);
    }

}

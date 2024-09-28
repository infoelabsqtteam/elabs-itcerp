<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\UnitConversions;
use Validator;
use Route;
use DB;

class UnitConversionsController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $user_id            = defined('USERID') ? USERID : '0';
        $division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		
        return view('units.conversions.index',['title' => 'Conversions','_conversions' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

    /** create new company
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createUnitConversions(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);   
				if(empty($newPostData['from_unit']))
				{
					$returnData = array('error' => config('messages.message.fromUnitRequired'));
				}else if(empty($newPostData['to_unit'])){
					$returnData = array('error' => config('messages.message.toUnitRequired'));
				}else if(empty($newPostData['confirm_factor'])){
					$returnData = array('error' => config('messages.message.confirmFactorRequired'));
				}else{   
					// check if company already exist or not 
					if($this->isExist($newPostData['from_unit'],$newPostData['to_unit']) == 0){  
						$created = UnitConversions::create([
							'from_unit' => $newPostData['from_unit'],
							'created_by' => \Auth::user()->id,
							'to_unit' => $newPostData['to_unit'],
							'confirm_factor' => $newPostData['confirm_factor']
							]);						
						//check if users created add data in user detail
						if($created->id){ 
							$returnData = array('success' => config('messages.message.unitSaved'));
						}else{
							$returnData = array('error' => config('messages.message.unitNotSaved'));
						}
					}else{ 
						$returnData = array('error' => config('messages.message.unitExist'));
					}
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
		} 
		return response()->json($returnData);		
    }

    /**
     * Get list of units on page load.
     * Date : 01-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUnitsConversionsList()
    {
		global $models;
		$units = DB::table('unit_conversion_db')->get();
		$models->formatTimeStampFromArray($units,DATETIMEFORMAT);
		return response()->json([
		   'unitConversionsList' => $units,
		]);
    }   
	
    /**
     * isUnitExist Is used to check the company duplicate entry by unit_code
     * Date : 01-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isExist($from_unit,$to_unit) 
    { 
		if(!empty($unit_code)){
			$unitData = DB::table('unit_conversion_db')
						->where('unit_conversion_db.from_unit', '=', $from_unit)
						->where('unit_conversion_db.to_unit', '=', $to_unit)
						->first();  
			if(@$unitData->unit_conversion_id){
				return $unitData->unit_conversion_id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editUnitConversionsData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				// get user by email id
				$unitConData = DB::table('unit_conversion_db')
							->where('unit_conversion_db.unit_conversion_id', '=', $request['data']['id'])
							->first();
				if($unitConData->unit_conversion_id){
					$returnData = array('responseData' => $unitConData);				
				}else{
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
				$returnData = array('error' => config('messages.message.provideAppData'));			
		}
		return response()->json($returnData);	
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUnitConversionsData(Request $request)
    {
        $returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){   
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData); 
				if(empty($newPostData['unit_conversion_id']))
				{
					$returnData = array('error' => config('messages.message.conversionCodeRequired'));
				}if(empty($newPostData['from_unit1']))
				{
					$returnData = array('error' => config('messages.message.fromUnitRequired'));
				}else if(empty($newPostData['to_unit1'])){
					$returnData = array('error' => config('messages.message.toUnitRequired'));
				}else if(empty($newPostData['confirm_factor1'])){
					$returnData = array('error' => config('messages.message.confirmFactorRequired'));
				}else{
                    $newPostData['unit_conversion_id']=base64_decode($newPostData['unit_conversion_id']);				
					$updated = DB::table('unit_conversion_db')->where('unit_conversion_id',$newPostData['unit_conversion_id'])->update([
						'from_unit' => $newPostData['from_unit1'],
						'to_unit' => $newPostData['to_unit1'],
						'confirm_factor' => $newPostData['confirm_factor1'],
					   ]);
					//check if data updated in Unit table 
                       $returnData = array('success' => config('messages.message.unitUpdated'));
				}
			}else{
				$returnData = array('error' =>  config('messages.message.dataNotFound'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFound'));
		} 
		return response()->json($returnData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteUnitConversions(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
					$unit = DB::table('unit_conversion_db')->where('unit_conversion_id', $request['data']['id'])->delete();
					if($unit){
						$returnData = array('success' => config('messages.message.unitDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.unitNotDeleted'));					
					}
				}catch(\Illuminate\Database\QueryException $ex){ 
				   $returnData = array('error' => config('messages.message.foreignKeyDeleteError'));
				}
			}else{
				$returnData = array('error' => config('messages.message.noRecordFound'));
			}
		}
		return response()->json($returnData);
    }
}

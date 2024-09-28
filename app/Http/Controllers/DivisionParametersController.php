<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\DivisionParameters;
use Validator;
use Route;
use DB;

class DivisionParametersController extends Controller
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
        $this->middleware('auth');
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
     * Display a listing of divisions.
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
		
        return view('division.division_parameters',['title' => 'Division Parameters','_division_parameters' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]); 
    }

    /** create new division parameters
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDivisionParameters(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData); 
					if(empty($newPostData['division_id']))
					{
						$returnData = array('error' => config('messages.message.divisionCodeRequired'));
					}else if(empty($newPostData['division_address'])){
						$returnData = array('error' => config('messages.message.divisionAddressRequired'));
					}else if(empty($newPostData['division_city'])){
						$returnData = array('error' => config('messages.message.divisionCityRequired'));
					}else if(empty($newPostData['division_PAN'])){
						$returnData = array('error' => config('messages.message.divisionPANRequired'));
					}else if(empty($newPostData['division_VAT_no'])){
						$returnData = array('error' => config('messages.message.divisionVATRequired'));
					}else{ 				
						// check if division already exist or not 
						if($this->isCodeExist($newPostData['division_id']) == 0){  
							$created = DivisionParameters::create([
								'division_id'	=> $newPostData['division_id'],
								'division_address' => $newPostData['division_address'],
								'division_city' => $newPostData['division_city'],
								'division_PAN' => $newPostData['division_PAN'],
								'division_VAT_no' => $newPostData['division_VAT_no'],
							   ]); 
							//check if division created added in divisions table
							if($created->id){  
								$returnData = array('success' => config('messages.message.divisionSaved'));
							}else{
								$returnData = array('error' =>  config('messages.message.divisionNotSaved'));
							}
						}else{
							$returnData = array('error' => config('messages.message.divisionParametersExist'));
						}
					}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFound'));
			}
		}else{
			$returnData = array('error' =>  config('messages.message.dataNotFound'));
		} 
		return response()->json($returnData);		
    }

    /**
     * isCodeExist Is used to check the duplicate entry 
     * Date : 01-05-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isCodeExist($code) 
    { 
		if(!empty($code)){
			$deptData = DB::table('division_parameters')
						->where('division_parameters.division_id', '=', $code)
						->first(); 
			if(@$deptData->division_id){
				return $deptData->division_id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
    /**
     * Get list of division_parameters on page load.
     * Date : 01-06-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDivisionParametersList()
    {
		$data = DB::table('division_parameters')
						  ->Join('city_db', 'division_parameters.division_city', '=', 'city_db.city_id')
						  ->Join('divisions', 'division_parameters.division_id', '=', 'divisions.division_id')
						  ->get();	  
		return response()->json([
		   'divisionParametersList' => $data,
		]);
    }	
	
    /**
     * Show the form for editing the specified division.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDivisionParameters(Request $request)
    {
		$returnData = array();
		$cityData = DB::table('city_db')->select('city_id as id','city_name as name')->get();  //print_r( json_encode($companyData));die;
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				// get user by email id
				$data = DB::table('division_parameters')
						  ->Join('city_db', 'division_parameters.division_city', '=', 'city_db.city_id')
						  ->Join('divisions', 'division_parameters.division_id', '=', 'divisions.division_id')
						  ->where('division_parameters.division_parameter_id', '=', $request['data']['id'])
						  ->first();	 
				if($data->division_parameter_id){
					$returnData = array('responseData' => $data);				
				}else{
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}
			}else{
				$returnData = array('error' =>  config('messages.message.provideAppData'));
			}
		}else{
				$returnData = array('error' => config('messages.message.provideAppData'));			
		} 
		//return response()->json($returnData);	
		return response()->json(['returnData'=>$returnData,'cityData'=>$cityData]);	
    }

    /**
     * Update the specified division in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDivisionParameters(Request $request)
    {
        $returnData = array();
		if ($request->isMethod('post')){ 
			if(!empty($request['data']['formData'])){   
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);   
			 	if(empty($newPostData['division_parameter_id']))
				{
					$returnData = array('error' => config('messages.message.divisionParameterCodeRequired'));
				}else if(empty($newPostData['division_address1'])){
					$returnData = array('error' => config('messages.message.divisionAddressRequired'));
				}else if(empty($newPostData['division_city1'])){
					$returnData = array('error' => config('messages.message.divisionCityRequired'));
				}else if(empty($newPostData['division_PAN1'])){
					$returnData = array('error' => config('messages.message.divisionPANRequired'));
				}else if(empty($newPostData['division_VAT1'])){
					$returnData = array('error' => config('messages.message.divisionVATRequired'));
				}else{ 	     
					$newPostData['division_parameter_id']=base64_decode($newPostData['division_parameter_id']); 
					$updated = DB::table('division_parameters')
					          ->where('division_parameter_id',$newPostData['division_parameter_id'])
							  ->update([
									'division_address' => $newPostData['division_address1'],
									'division_city' => $newPostData['division_city1'],
									'division_PAN' => $newPostData['division_PAN1'],
									'division_VAT_no' => $newPostData['division_VAT1'],
								   ]);
					//check if data updated in divisions table  
                       $returnData = array('success' => config('messages.message.divisionUpdated'));				
				}
			}else{
				$returnData = array('error' => config('messages.message.provideAppData'));
			}
		}else{
			$returnData = array('error' => config('messages.message.provideAppData'));
		} 
		return response()->json($returnData);
    }

    /**
     * Remove the specified division parameters from divisions table.
     *
     * @param  int  $id 
     * @return \Illuminate\Http\Response
     */
    public function deleteDivisionParameters(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){ 
				try{
					$divi = DB::table('division_parameters')->where('division_parameter_id', $request['data']['id'])->delete();
					if($divi){
						$returnData = array('success' => config('messages.message.divisionDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.divisionNotDeleted'));					
					}				
				}catch(\Illuminate\Database\QueryException $ex){ 
					   $returnData = array('error' => config('messages.message.foreignKeyDeleteError'));
				}
			}else{
				$returnData = array('error' =>config('messages.message.noRecordFound'));
			}
		}
		return response()->json($returnData);
    }
}

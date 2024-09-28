<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Models;
use App\Company;
use Validator;
use Route;
use DB;

class CompanyController extends Controller
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
		
        return view('master.company_master.index',['title' => 'Company Master','_company_master' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

    /** create new company
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCompany(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData); 
				if(empty($newPostData['company_code']))
				{
					$returnData = array('error' => config('messages.message.companyCodeRequired'));
				}else if(empty($newPostData['company_city'])){
					$returnData = array('error' => config('messages.message.companyCityRequired'));
				}else if(empty($newPostData['company_address'])){
					$returnData = array('error' => config('messages.message.companyAddressRequired'));
				}else if(empty($newPostData['company_name'])){
					$returnData = array('error' => config('messages.message.companyNameRequired'));
				}else{
					unset($newPostData['_token']);
					// check if company already exist or not 
					if($this->isCompanyExist($newPostData['company_code']) == 0){
						$company_name = strtolower($newPostData['company_name']);
						$created = Company::create([
							'company_code' => $newPostData['company_code'],
							'company_address' => $newPostData['company_address'],
							'company_name' => ucwords($company_name),
							'company_city' => $newPostData['company_city'],
						   ]);
						
						//check if users created add data in user detail
						if($created->id){ 
							$returnData = array('success' => config('messages.message.companySaved'));
						}else{
							$returnData = array('error' => config('messages.message.companyNotSaved'));
						}
					}else{
						$returnData = array('error' => config('messages.message.companyExist'));
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
     * Get list of companies on page load.
     * Date : 01-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCompaniesList()
    {
		global $models;
		$companies = DB::table('company_master')
					->join('city_db','city_db.city_id','=','company_master.company_city')
					->get();
		$models->formatTimeStampFromArray($companies,DATETIMEFORMAT);					
		return response()->json(['companiesList' => $companies]);
    }   
	
    /**
     * isCompanyExist Is used to check the company duplicate entry by company_code
     * Date : 01-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isCompanyExist($company_code) 
    { 
		if(!empty($company_code)){
			$companyData = DB::table('company_master')
						->where('company_master.company_code', '=', $company_code)
						->first(); 
			if(@$companyData->company_id){
				return $companyData->company_id;
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
    public function editCompanyData(Request $request)
    {
		$returnData = array();
		$cityData = DB::table('city_db')->select('city_id as id','city_name as name')->get();  //print_r( json_encode($companyData));die;
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				// get user by email id
				$companyData = DB::table('company_master')
							->join('city_db','city_db.city_id','=','company_master.company_city')
							->select('company_master.*')
							->where('company_master.company_id', '=', $request['data']['id'])
							->first();
				
				if($companyData->company_id){
					$returnData = array('responseData' => $companyData);				
				}else{
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
				$returnData = array('error' => config('messages.message.provideAppData'));			
		}
		return response()->json(['returnData'=>$returnData,'cityData'=>$cityData]);	
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCompanyData(Request $request)
    {
        $returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){   
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);   
				if(empty($newPostData['company_id1']))
				{
					$returnData = array('error' => config('messages.message.companyCodeRequired'));
				}else if(empty($newPostData['company_city1'])){
					$returnData = array('error' => config('messages.message.companyCityRequired'));
				}else if(empty($newPostData['company_address1'])){
					$returnData = array('error' => config('messages.message.companyAddressRequired'));
				}else if(empty($newPostData['company_name1'])){
					$returnData = array('error' => config('messages.message.companyNameRequired'));
				}else{
					$newPostData['company_id1']=base64_decode($newPostData['company_id1']);
					$company_name1 = strtolower($newPostData['company_name1']);
					$updated = DB::table('company_master')->where('company_id',$newPostData['company_id1'])->update([
						'company_address' => $newPostData['company_address1'],
						'company_city' => $newPostData['company_city1'],
						'company_name' => ucwords($company_name1),
					   ]);
					//check if data updated in Company table 
                     $returnData = array('success' => config('messages.message.companyUpdated'));
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
	public function deleteCompanyData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
						$company = DB::table('company_master')->where('company_id', $request['data']['id'])->delete();
						if($company){
							$returnData = array('success' => config('messages.message.companyDeleted'));
						}else{
							$returnData = array('error' => config('messages.message.companyNotDeleted'));					
				 		} 
				}catch(\Illuminate\Database\QueryException $ex){ 
				   $returnData = array('error' => "Cannot delete or update a parent row: a foreign key constraint fails!");
				}
			}else{
				$returnData = array('error' => config('messages.message.noRecordFound'));
			}				
		} 
		return response()->json($returnData);
    }
}

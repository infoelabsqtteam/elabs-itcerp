<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\CustomerLocation;
use Validator;
use DB;
use Route;

class CustomerLocationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }	
    
	/**
     * Display a listing of customer_locations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return view('customer.customer_locations'); 
    }

    /** create new customerLocation
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCustomerLocation(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData); 
					
					if(empty($newPostData['customer_id']))
					{
						$returnData = array('error' => config('messages.message.companyCodeRequired'));
					}else if(empty($newPostData['location_code'])){
						$returnData = array('error' => config('messages.message.customerLocationCodeRequired'));
					}else if(empty($newPostData['location_name'])){
						$returnData = array('error' => config('messages.message.customerLocationNameRequired'));
					}else{ 				
						// check if customerLocation already exist or not 
						if($this->isCodeExist($newPostData['location_code']) == 0){  
							$created = CustomerLocation::create([
								'customer_id'	=> $newPostData['customer_id'],
								'location_code' => $newPostData['location_code'],
								'location_name' => $newPostData['location_name'],
							   ]); 
							//check if customerLocation created added in customer_locations table
							if($created->id){  
								$returnData = array('success' => config('messages.message.customerLocationSaved'));
							}else{
								$returnData = array('error' =>  config('messages.message.customerLocationNotSaved'));
							}
						}else{
							$returnData = array('error' => config('messages.message.customerLocationExist'));
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
			$deptData = DB::table('customer_locations')
						->where('customer_locations.location_code', '=', $code)
						->first(); 
			if(@$deptData->location_id){
				return $deptData->location_id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
    /**
     * Get list of customer_locations on page load.
     * Date : 02-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCustomerLocationsList()
    {
		$depts = DB::table('customer_locations')
				  ->Join('company_master', 'customer_locations.customer_id', '=', 'company_master.customer_id')
				  ->get();	 
		return response()->json([
		   'customer_locationsList' => $depts,
		]);
    }
    
    /**
     * Show the form for editing the specified customerLocation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCustomerLocationData(Request $request)
    {
		$returnData = array();
		$companyData = DB::table('company_master')->select('customer_id as id','company_name as name')->get();  //print_r( json_encode($companyData));die;
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				// get user by email id
				$deptData =DB::table('customer_locations')
									->Join('company_master', 'customer_locations.customer_id', '=', 'company_master.customer_id')
									->select('customer_locations.*','company_master.company_name')
									->where('customer_locations.location_id', '=', $request['data']['id'])
									->first();
				if($deptData->location_id){
					$returnData = array('responseData' => $deptData);				
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
		return response()->json(['returnData'=>$returnData,'companyData'=>$companyData]);	
    }

    /**
     * Update the specified customerLocation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCustomerLocationData(Request $request)
    {
        $returnData = array();
		if ($request->isMethod('post')) { 
			if(!empty($request['data']['formData'])){   
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);   
			 	if(empty($newPostData['location_id']))
				{
					$returnData = array('error' => 'CustomerLocation Id is required!');
				}else if(empty($newPostData['customer_id1'])){
					$returnData = array('error' => config('messages.message.companyCodeRequired'));
				}else if(empty($newPostData['location_name1'])){
					$returnData = array('error' => config('messages.message.customerLocationNameRequired'));
				}else{    
					$newPostData['location_id']=base64_decode($newPostData['location_id']);  
					$updated = DB::table('customer_locations')->where('location_id',$newPostData['location_id'])->update([
						'customer_id' => $newPostData['customer_id1'],
						'location_name' => $newPostData['location_name1'],
					   ]);
					//check if data updated in customer_locations table 
					if($updated){ 
                       $returnData = array('success' => config('messages.message.customerLocationUpdated'));
					 }else{  
						$returnData = array('error' => config('messages.message.customerLocationNotUpdated'));
					}  
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
     * Remove the specified customerLocation from customer_locations table.
     *
     * @param  int  $id 
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomerLocation(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try{
					$company = DB::table('customer_locations')->where('location_id', $request['data']['id'])->delete();
					if($company){
						$returnData = array('success' => config('messages.message.customerLocationDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.customerLocationNotDeleted'));					
					}					
				}catch(\Illuminate\Database\QueryException $ex){ 
					   $returnData = array('error' => "Cannot delete or update a parent row: a foreign key constraint fails!");
				}
			}else{
				$returnData = array('error' =>config('messages.message.noRecordFound'));
			}
		}
		return response()->json($returnData);
    }
}

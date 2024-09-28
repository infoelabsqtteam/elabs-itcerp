<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\User;
use App\Module;
use App\Customer;
use Validator;
use DB;
use Route;

class CustomerDefinedInvoicingTypeController extends Controller
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
        global $models,$customer;
	$models = new Models();
	$customer = new Customer();
	global $module;
	$module = new Module();
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
	global $models,$customer;

	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
	return view('master.customer_master.customer_defined_invoicing_type.index',['title' => 'Customer Defined Invoicing Type','_customer_defined_invoicing_type' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

   /**
     * Display a listing of the resource.
     * get all customers data list
     * @return \Illuminate\Http\Response
     */


	public function getAllCustomerDataList(Request $request){
		global $models;
		$customersList = DB::table('customer_defined_structures')
                  ->leftJoin('divisions','divisions.division_id','=','customer_defined_structures.division_id')
                  ->leftJoin('customer_master','customer_defined_structures.customer_id','=','customer_master.customer_id')
                  ->leftJoin('customer_invoicing_types','customer_invoicing_types.invoicing_type_id','=','customer_defined_structures.invoicing_type_id')
                  ->leftJoin('product_categories','product_categories.p_category_id','=','customer_defined_structures.product_category_id')
                  ->leftJoin('customer_billing_types','customer_defined_structures.billing_type_id','=','customer_billing_types.billing_type_id')
                  ->leftJoin('customer_discount_types','customer_discount_types.discount_type_id','=','customer_defined_structures.discount_type_id')
                  ->select('divisions.division_name','customer_master.customer_name','customer_defined_structures.created_at as customer_created_at','customer_defined_structures.updated_at as customer_updated_at','product_categories.p_category_name','customer_invoicing_types.invoicing_type','customer_defined_structures.*','customer_billing_types.billing_type_id','customer_billing_types.billing_type','customer_discount_types.discount_type_id','customer_discount_types.discount_type')
                  ->orderBy('customer_defined_structures.customer_id','ASC')
                  ->get();
	//Format the date time
	$models->formatTimeStampFromArray($customersList,DATETIMEFORMAT);

	// echo '<pre>';print_r($customersList);die;
	return response()->json(['customersList' => $customersList,]);
	}

  

    /** create new customer
    *  Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function createCustomer(Request $request){

	global $models,$customer;

	$returnData = $newPostData = array();

	if($request->isMethod('post') && !empty($request['data']['formData'])){


	    //parse searlize data
	    parse_str($request['data']['formData'], $newPostData);
      //echo'<pre>';print_r($newPostData); die;

    if(empty($newPostData['division_id'])){
    $returnData = array('error' => config('messages.message.divisionRequired'));
    }else if(empty($newPostData['customer_id'])){
		    $returnData = array('error' => config('messages.message.customerNameRequired'));
	    }else if(empty($newPostData['invoicing_type_id'])){
		$returnData = array('error' => config('messages.message.invoicingTypeRequired'));
	    }else if(empty($newPostData['product_category_id'])){
		$returnData = array('error' => config('messages.message.departmentRequired'));
	    }else if(empty($newPostData['billing_type_id'])){
		$returnData = array('error' => config('messages.message.billingTypeRequired'));
		}else if(empty($newPostData['discount_type_id'])){
		$returnData = array('error' => config('messages.message.discountTypeRequired'));
		}else if(empty($newPostData['discount_value'])){
		$returnData = array('error' => config('messages.message.discountValueRequired'));
		}else if(!isset($newPostData['customer_invoicing_type_status']) && empty($newPostData['customer_invoicing_type_status'])){
		$returnData = array('error' => config('messages.message.status'));
	    }else{
			unset($newPostData['_token']);
			$formData 		= $newPostData;
			//echo'<pre>';print_r($formData); die;

			//check if users created add data in user detail

			$alreadyExistOrNot = $this->isCustomerExist($newPostData);
			if(empty($alreadyExistOrNot)){
			$customerId = DB::table('customer_defined_structures')->insertGetId($formData);
				if($customerId){
				   $returnData = array('success' => config('messages.message.customerSaved'));
				}
			}else{
				$returnData = array('error' => config('messages.message.customerAlreadyExist'));
			}
	    }
	}else{
	    $returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
	}

	return response()->json($returnData);
    }



    /**
    * isCustomerExist Is used to check the customer duplicate entry by customer_code
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function isCustomerExist($formData){

	if(!empty($formData)){
	    $customerData = DB::table('customer_defined_structures')
		->where('customer_defined_structures.customer_id', '=', $formData['customer_id'])
		->where('customer_defined_structures.product_category_id', '=',  $formData['product_category_id'])
		->where('customer_defined_structures.invoicing_type_id', '=',  $formData['invoicing_type_id'])
		->where('customer_defined_structures.billing_type_id', '=',  $formData['billing_type_id'])
		->where('customer_defined_structures.customer_invoicing_type_status', '=',  $formData['customer_invoicing_type_status'])
		->first();
	    if(@$customerData->cdit_id){
		    return $customerData;
	    }else{
		    return false;
	    }
	}else{
	    return false;
	}
    }



    /**
    * Get list of customers on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getCustomerList(){

	global $models;

	$customers = DB::table("customer_master")
				->select('customer_master.customer_id as cust_id','customer_master.customer_name')
				->get()
				->toArray();

	//Format the date time
	$models->formatTimeStampFromArray($customers,DATETIMEFORMAT);
	return response()->json(['customersList' => $customers,]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCustomerData(Request $request){
	$returnData = array();
	if ($request->isMethod('post')) {
	    if(!empty($request['data']['id'])){
		// get user by email id
		$customerData = DB::table('customer_defined_structures')
        ->leftJoin('divisions','divisions.division_id','=','customer_defined_structures.division_id')
		    ->leftJoin('customer_master','customer_master.customer_id','=','customer_defined_structures.customer_id')
		    ->leftJoin('customer_invoicing_types','customer_invoicing_types.invoicing_type_id','=','customer_defined_structures.invoicing_type_id')
		    ->leftJoin('product_categories','product_categories.p_category_id','=','customer_defined_structures.product_category_id')
		    ->leftJoin('customer_billing_types','customer_defined_structures.billing_type_id','=','customer_billing_types.billing_type_id')
			->leftJoin('customer_discount_types','customer_discount_types.discount_type_id','=','customer_defined_structures.discount_type_id')
		    ->select('divisions.division_id','divisions.division_name','customer_master.customer_id','customer_master.customer_name','customer_invoicing_types.invoicing_type','customer_invoicing_types.invoicing_type_id','customer_defined_structures.cdit_id','customer_defined_structures.*','product_categories.p_category_name as name','product_categories.p_category_id as id','customer_billing_types.billing_type_id','customer_billing_types.billing_type','customer_discount_types.discount_type_id','customer_discount_types.discount_type')
		    ->where('customer_defined_structures.cdit_id', '=', $request['data']['id'])
		    ->first();

		if(!empty($customerData->cdit_id)){
			$returnData = array('responseData' => $customerData);
		}else{
			$returnData = array('error' => config('messages.message.noRecordFound'));
		}
	    }else{
		$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
	    }
	}else{
	    $returnData = array('error' => config('messages.message.provideAppData'));
	}
	return response()->json(['returnData'=>$returnData]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCustomerData(Request $request){

	global $models,$customer;

        $returnData = $newPostData =$updatedCustomer= array();

	if ($request->isMethod('post') && !empty($request['data']['formData'])){
	    //pasrse searlize data
	    parse_str($request['data']['formData'], $newPostData);

      if(empty($newPostData['customer_id'])){
		      $returnData = array('error' => config('messages.message.customerNameRequired'));
	    }else if(empty($newPostData['invoicing_type_id'])){
		      $returnData = array('error' => config('messages.message.invoicingTypeRequired'));
	    }else if(empty($newPostData['billing_type_id'])){
		    $returnData = array('error' => config('messages.message.billingTypeRequired'));
		}else if(empty($newPostData['discount_type_id'])){
		    $returnData = array('error' => config('messages.message.discountTypeRequired'));
		}else if(empty($newPostData['discount_value'])){
		    $returnData = array('error' => config('messages.message.discountValueRequired'));
		}else if(!isset($newPostData['customer_invoicing_type_status']) && empty($newPostData['customer_invoicing_type_status'])){
		    $returnData = array('error' => config('messages.message.status'));
	  }else{
			unset($newPostData['_token']);
			try{
				if(!empty($newPostData['cdit_id'])){
          $updated = DB::table('customer_defined_structures')->where('cdit_id',$newPostData['cdit_id'])->update($newPostData);
					if($updated){
						$returnData = array('success' => config('messages.message.customerUpdated'));
					}else{
						$returnData = array('success' => config('messages.message.savedNoChange'));
					}
				}else{
					$returnData = array('error' =>  config('messages.message.dataNotFound'));
				}
			}catch(\Illuminate\Database\QueryException $ex){
			   $returnData = array('error' => config('messages.message.customerAlreadyExist'));
			}
		}
	}else{
	    $returnData = array('error' => config('messages.message.dataNotFound'));
	}
	//echo'<pre>'; print_r($newPostData); die;
	return response()->json($returnData);
    }


    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteCustomerData(Request $request){

	$returnData = array();
	if ($request->isMethod('post')){

			if(!empty($request['data']['id'])){
				try {
				$customer = DB::table('customer_defined_structures')->where('customer_defined_structures.cdit_id', $request['data']['id'])->delete();
					if($customer){
						$returnData = array('success' => config('messages.message.customerDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.customerNotDeleted'));
					}
				}
				catch(\Illuminate\Database\QueryException $ex){
					   $returnData = array('error' => "Cannot delete or update a parent row: a foreign key constraint fails!");
				}
			}else{
				$returnData = array('error' => config('messages.message.noRecordFound'));
			}
	}
	return response()->json($returnData);
    }




}

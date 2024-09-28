<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Division;
use App\Item;
use App\Models;
use App\InvoicingTypeStateWiseProduct;
use Validator;
use Route;
use DB;

class InvoicingTypeStateWiseProductsController extends Controller
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
        global $models,$invoicingTypeStateWiseProduct;
        $models = new Models();
		$invoicingTypeStateWiseProduct = new InvoicingTypeStateWiseProduct();
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

        return view('master.customer_invoicing.state_wise_product_rates.index',['title' => 'State Wise Product Rates','_debit_note' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
    public function createStateWiseProductRates(Request $request)
    {
        global $models,$invoicingTypeStateWiseProduct;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();
        $duplicate = array();
        $saved = array();
        $notSaved = array();

        //Saving record in table
        if(!empty($request->formData) && $request->isMethod('post')){
            parse_str($request->formData, $formData);
	    $formData = array_filter($formData);
	    
	    $formSaveType = !empty($request->saveType) && ($request->saveType =='add') ? true: false;
            
	    if(!empty($formData)){
                $cirCustProductIdArr 	= !empty($formData['cir_c_product_id']) ? array_filter($formData['cir_c_product_id']) : array();
		$cirCustIdArr 		= !empty($formData['cir_id']) ? array_filter($formData['cir_id']) : array();
                $invoicingRateArr 	= !empty($formData['invoicing_rate']) ? $formData['invoicing_rate'] : array();
                $divisionId 		= !empty($formData['cir_division_id']) ? $formData['cir_division_id'] : '0';
		if(empty($formData['cir_state_id'])){
                    $message = config('messages.message.stateNameRequired');
                }else if(empty($cirCustProductIdArr)){
                    $message = config('messages.message.productNameRequired');
                }else if(empty($invoicingRateArr)){
                    $message = config('messages.message.invoicingRateRequired');
		}else if(empty($formData['cir_product_category_id'])){
                    $message = config('messages.message.productCatNameRequired');
		}else if(empty($divisionId )){
                    $message = config('messages.message.divisionRequired');
		}else{
		    //if($formSaveType){
			$formData['cir_state_id'] 		    = $formData['cir_state_id']; //Refer to table states db
			$formData['cir_product_category_id'] 	    = !empty($formData['cir_product_category_id']) ? $formData['cir_product_category_id'] : '0';
		    //}

		    $formData['invoicing_type_id'] = '2';       //Refer to table customer_invoicing_types
		    $formData['created_by'] 	   = USERID;	//Refer to table users db
		    
		    $formData = $models->unsetFormDataVariables($formData,array('_token','cir_c_product_id','invoicing_rate'));
		    
		    foreach($invoicingRateArr as $key=>$rate){
			$formData['cir_c_product_id'] 	= $cirCustProductIdArr[$key];
			$formData['invoicing_rate'] 	= $invoicingRateArr[$key];
			$formData['cir_id'] 		= (!$formSaveType)? $cirCustIdArr[$key] :'' ;
			
			//if($formSaveType){unset($formData['cir_id']);	}
			    
			$countStateAndDeptWiseProduct = !empty($formSaveType) ? $invoicingTypeStateWiseProduct->checkStateWiseProductRate($formData['cir_division_id'],$formData['cir_state_id'],$formData['cir_c_product_id'],$formData['cir_product_category_id']) : '0';
			if(!empty($countStateAndDeptWiseProduct)){
				$error ='0';
				$message = config('messages.message.stateProductNameExist');
			}else{
				
			    if(!empty($formData['invoicing_rate'])){
				    $sWPId = DB::table('customer_invoicing_rates')->insertGetId($formData);
				    if(!empty($sWPId)){
					$saved[] = $formData['cir_c_product_id'];
					$error   = '1';
					$message = config('messages.message.saved');
				    }else{
					$notSaved[] = $formData['cir_c_product_id'];
				    }
			    }
			}
		    }
		}
            }
        }
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data));
    }

	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function getStateWiseProductRates(Request $request){
	    
	    global $models,$invoicingTypeStateWiseProduct;
	    $error    = '0';
	    $message  = config('messages.message.error');
	    $data     = '';
	    $cir_product_cat_id 	= !empty($request->cirCatId) ? $request->cirCatId : '0';
	    $cir_division_id 		= !empty($request->cirDivisionId) ? $request->cirDivisionId : '0';
	    $cir_state_id 		= !empty($request->cirStateId) ? $request->cirStateId : '0';

	    $stateWiseProductRateObj = DB::table('customer_invoicing_rates')
				    ->join('state_db','state_db.state_id','=','customer_invoicing_rates.cir_state_id')
				    ->leftJoin('product_categories','product_categories.p_category_id','=','customer_invoicing_rates.cir_product_category_id')
				    ->join('product_master_alias','product_master_alias.c_product_id','customer_invoicing_rates.cir_c_product_id')
				    ->leftJoin('product_master','product_master.product_id','product_master_alias.product_id')
				    ->join('users as createdBy','createdBy.id','customer_invoicing_rates.created_by')
		    ->select('customer_invoicing_rates.*','state_db.state_name','product_master_alias.c_product_name','createdBy.name as createdByName','product_categories.p_category_name as dept_name','product_master.product_name','customer_invoicing_rates.cir_product_category_id')
		    ->where('customer_invoicing_rates.invoicing_type_id','2');

	    if(!empty($cir_state_id) && is_numeric($cir_state_id)){
		    $stateWiseProductRateObj->where('customer_invoicing_rates.cir_state_id',$cir_state_id);
	    }
	    if($cir_product_cat_id){
		    $stateWiseProductRateObj->where('customer_invoicing_rates.cir_product_category_id',$cir_product_cat_id);

	    }
	    if($cir_division_id){
		    $stateWiseProductRateObj->where('customer_invoicing_rates.cir_division_id',$cir_division_id);

	    }
	    $stateWiseProductRateList 	= $stateWiseProductRateObj->orderBy('customer_invoicing_rates.cir_id','ASC')->get();
	    $error            		= !empty($stateWiseProductRateList) ? 1 : '0';
	    $message                  	= $error ? '' : $message;

	    //to formate created and updated date
	    $models->formatTimeStampFromArray($stateWiseProductRateList,DATETIMEFORMAT);
	    $stateWiseCustomerInvoicingCount = DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.invoicing_type_id','2')->count();
		//echo '<pre>';print_r($stateWiseProductRateList);die;
	    return response()->json(array('error'=> $error,'message'=> $message,'stateWiseProductRateList'=> $stateWiseProductRateList,'stateWiseCustomerInvoicingCount'=>$stateWiseCustomerInvoicingCount));
    }

	
    /**
	* Display state products rate .
	*27/02/18
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function getSelectedStateProductRate($cir_state_id,$cir_prodct_cat_id,$division_id){
		//die($cir_prodct_cat_id);
		global $models,$invoicingTypeStateWiseProduct;
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
		$stateProductRateList = array();
		$customerProductsList = array();
		$productAliasRateList = array();

		/**** selected department in case of edit state wise product****/
		$selectedDept = $invoicingTypeStateWiseProduct->departAccToStateWiseInvoicing($cir_state_id,$cir_prodct_cat_id);

		$stateProductRateList = $invoicingTypeStateWiseProduct->getStateProducts($cir_state_id,$cir_prodct_cat_id,$division_id);
		$error    = !empty($stateProductRateList) ? 1 : '0';
		$message  = $error ? '' : $message;
		//echo'<pre>'; print_r($stateProductRateList);  die;
		return response()->json(array('error'=> $error,'message'=> $message,'productAliasRateList'=> $stateProductRateList,'selectedDept'=>$selectedDept));
    }

	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewStateWiseProductRate($cir_id)
    {
		global $models,$invoicingTypeStateWiseProduct;

		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';

		$stateWiseProductRate = DB::table('customer_invoicing_rates')
                    ->join('state_db','state_db.state_id','=','customer_invoicing_rates.cir_state_id')
					->join('product_master_alias','product_master_alias.c_product_id','customer_invoicing_rates.cir_c_product_id')
					->join('product_master','product_master.product_id','product_master_alias.product_id')
					->join('users as createdBy','createdBy.id','customer_invoicing_rates.created_by')
		            ->select('customer_invoicing_rates.*','state_db.state_name','product_master_alias.c_product_name','createdBy.name as createdByName','product_master.product_name')
					->where('customer_invoicing_rates.cir_id',$cir_id)
					->first();

		$error    = !empty($stateWiseProductRate) ? 1 : '0';
		$message  = $error ? '' : $message;

		//echo '<pre>';print_r($stateWiseProductRate);die;
		return response()->json(array('error'=> $error,'message'=> $message,'stateWiseProductRateData'=> $stateWiseProductRate));
    }
	
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStateWiseProductRate(Request $request)
    {
        global $models,$invoicingTypeStateWiseProduct;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
		$cirId    = '';
		$stateId  = !empty($formData['cir_state_id'])?$formData['cir_state_id']:'0';
        $formData = array();

        if ($request->isMethod('post') && !empty($request['formData'])){

            //pasrse searlize data
            parse_str($request['formData'], $formData);
			//print_r($formData); die;
      		$cirIdArr = !empty($formData['cir_inv_rate_id']) ? $formData['cir_inv_rate_id'] : array();
      		$cirproductIdsArr = !empty($formData['cir_c_product_id']) ? $formData['cir_c_product_id'] :array();
      		$cirInvoicingRatesArr = !empty($formData['invoicing_rate']) ? $formData['invoicing_rate'] :array();
			if(empty($formData['invoicing_rate'])){
				$message = config('messages.message.invoicingRateRequired');
			}else{
				//Unsetting the variable from request data
				$formData = $models->unsetFormDataVariables($formData,array('_token','cir_id'));
				foreach($cirInvoicingRatesArr as $key=>$invoicingRatesValue){
					unset($formData['cir_inv_rate_id']);
					$formData['invoicing_rate'] = $cirInvoicingRatesArr[$key];
					$formData['cir_c_product_id'] = $cirproductIdsArr[$key];
					$formData['cir_id'] 	= $cirIdArr[$key];
					$updated[] = DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_id',$formData['cir_id'])->where('customer_invoicing_rates.cir_c_product_id',$formData['cir_c_product_id'])->update($formData);
					if($updated){
						$error   = '1';
						$message = config('messages.message.updated');
					}else{
						$error   = '1';
						$message = config('messages.message.savedNoChange');
					}
				}
            }
		}

		return response()->json(['error'=> $error,'message'=> $message,'data'=> $data,'cir_id' => $cirId,'stateId' => $stateId]);
    }
	/**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteInvoicingTypeRate(Request $request, $cir_id)
    {
        $error   = '0';
        $message = '';
        $data    = '';

        try{
            if(DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_id','=',$cir_id)->delete()){
                $error   = '1';
                $message = config('messages.message.deleted');
            }else{
                $message = config('messages.message.deletedError');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.deletedErrorFKC');
        }
		return response()->json(['error' => $error,'message' => $message]);
    }


}

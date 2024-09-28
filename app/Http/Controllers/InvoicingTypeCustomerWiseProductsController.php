<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Division;
use App\Item;
use App\Models;
use App\InvoicingTypeCustomerWiseProduct;
use App\State;
use Validator;
use Route;
use DB;

class InvoicingTypeCustomerWiseProductsController extends Controller
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
		global $models, $invoicingTypeCustomerWiseProduct, $state;
		$models = new Models();
		$state  = new State();
		$invoicingTypeCustomerWiseProduct = new InvoicingTypeCustomerWiseProduct();

		//Session Check
		$this->middleware('auth');

		$this->middleware(function ($request, $next) {
			$this->auth = Auth::user();
			parent::__construct($this->auth);
			//Checking current request is allowed or not
			$allowedAction = array('index', 'navigation');
			$actionData    = explode('@', Route::currentRouteAction());
			$action        = !empty($actionData[1]) ? trim(strtolower($actionData[1])) : '0';
			if (defined('NOTALlOWEDTONAVIGATE') && empty(NOTALlOWEDTONAVIGATE) && in_array($action, $allowedAction)) {
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

		return view('master.customer_invoicing.customer_wise_product_rates.index', ['title' => 'Customer Wise Product Rates', '_debit_note' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function customerWiseProductRates()
	{
		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('master.customer_invoicing.customer_wise_product_rates.index', ['title' => 'Customer Wise Product Rates', '_customer_wise_product_rate' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function customerWiseParameterRates()
	{
		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('master.customer_invoicing.customer_wise_parameter_rates.index', ['title' => 'Customer Wise Product Rates', '_customer_wise_parameter_rate' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function createCustomerWiseProductRate(Request $request)
	{
		global $models, $invoicingTypeCustomerWiseProduct;
		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$formData = array();

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			parse_str($request->formData, $formData);
			$formData = array_filter($formData);

			if (!empty($formData)) {

				$cirCustProductIdArr = !empty($formData['cir_c_product_id']) && !isset($formData['for_fixed_rate']) ? array_filter($formData['cir_c_product_id']) : array();
				$invoicingRateArr = !empty($formData['invoicing_rate']) && !isset($formData['for_fixed_rate']) ? array_filter($formData['invoicing_rate']) : array();

				if (empty($formData['cir_division_id'])) {
					$message = config('messages.message.divisionNameRequired');
				} else if (empty($formData['cir_product_category_id'])) {
					$message = config('messages.message.productCatNameRequired');
				} else if (empty($formData['cir_city_id'])) {
					$message = config('messages.message.cityNameRequired');
				} else if (empty($formData['cir_customer_id'])) {
					$message = config('messages.message.customerNameRequired');
				} else if (!isset($formData['for_fixed_rate']) && empty($cirCustProductIdArr)) {
					$message = config('messages.message.productNameRequired');
				} else if (!isset($formData['for_fixed_rate']) && empty(array_filter($invoicingRateArr))) {
					$message = config('messages.message.invoicingRateRequired');
				} else if (isset($formData['for_fixed_rate']) && empty($formData['invoicing_rate'])) {
					$message = config('messages.message.invoicingRateRequired');
				} else if (isset($formData['for_fixed_rate']) && $invoicingTypeCustomerWiseProduct->checkCustomerExist($formData['cir_customer_id'], $formData['cir_division_id'], $formData['cir_product_category_id'])) {
					$message = config('messages.message.customerInvoicingExist');
				} else {
					$formData['invoicing_type_id'] = '3'; 										//Refer to table customer_invoicing_types
					$formData['created_by']        = USERID;											//Refer to table users
					if (isset($formData['for_fixed_rate'])) {
						$formData = $models->unsetFormDataVariables($formData, array('_token', 'for_fixed_rate'));     	//Unsetting the variable from request data
						$saved[] = DB::table('customer_invoicing_rates')->insertGetId($formData);
						$error   = '1';
						$message = config('messages.message.saved');
					} else {
						$formData = $models->unsetFormDataVariables($formData, array('_token', 'for_fixed_rate', 'invoicing_rate', 'cir_c_product_id'));     	//Unsetting the variable from request data
						foreach ($invoicingRateArr as $key => $rate) {
							$formData['cir_c_product_id'] = $cirCustProductIdArr[$key];
							$formData['invoicing_rate'] = $invoicingRateArr[$key];
							if ($invoicingTypeCustomerWiseProduct->checkCustomerWiseProductRate($formData['cir_city_id'], $formData['cir_customer_id'], $formData['cir_c_product_id'], $formData['cir_division_id'], $formData['cir_product_category_id'])) {
								$error   = '0';
								$message = config('messages.message.exist');
							} else {
								if (!empty($formData['invoicing_rate'])) {
									$saved[] = DB::table('customer_invoicing_rates')->insertGetId($formData);
									if (!empty($saved)) {
										$error   = '1';
										$message = config('messages.message.saved');
									} else {
										$error   = '1';
										$message = config('messages.message.savedNoChange');
									}
								}
							}
						}
					}
				}
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'cir_city_id' => $formData['cir_city_id'], 'cir_customer_id' => $formData['cir_customer_id']));
	}

	/**
	 * Display the specified resource.
	 * Praveen Singh
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getCustomerWiseProductRates(Request $request)
	{

		global $models, $invoicingTypeCustomerWiseParameter;

		$error    	 	 = '0';
		$message  	 	 = config('messages.message.error');
		$data     	 	 = '';
		$invocingTypeId  = '3';
		$cir_customer_id = '0';
		$formData 	     = $leftSideDataList = $rightSideDataList = array();

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//parsing form data
			parse_str($request->formData, $formData);

			if (!empty($formData)) {

				$cir_customer_id   		= !empty($formData['cir_customer_id']) ? $formData['cir_customer_id'] : '0';
				$cirProductCategoryId   = !empty($formData['dept_id']) ? $formData['dept_id'] : '1';
				$cirDivisionId   		= !empty($formData['division_id']) ? $formData['division_id'] : '1';
				$cirSearchKeyword  		= !empty($formData['cir_search_keyword']) ? $formData['cir_search_keyword'] : '';

				//***************Left Side Data************************************************************
				$leftSideDataListObj  	= DB::table('customer_invoicing_rates')
											->select('customer_invoicing_rates.cir_id', 'customer_invoicing_rates.cir_c_product_id', 'customer_invoicing_rates.cir_customer_id', 'city_db.city_name', 'customer_master.customer_id', 'customer_master.customer_name')
											->join('city_db', 'city_db.city_id', 'customer_invoicing_rates.cir_city_id')
											->join('customer_master', 'customer_master.customer_id', 'customer_invoicing_rates.cir_customer_id')
											->whereNull('cir_parameter_id')
											->where('customer_invoicing_rates.invoicing_type_id', $invocingTypeId);

				if (!empty($cirProductCategoryId)) {
					$leftSideDataListObj->where('customer_invoicing_rates.cir_product_category_id', $cirProductCategoryId);
				}
				if (!empty($cirDivisionId)) {
					$leftSideDataListObj->where('customer_invoicing_rates.cir_division_id', $cirDivisionId);
				}
				if (!empty($cirSearchKeyword)) {
					$leftSideDataListObj->where('customer_master.customer_name', 'LIKE', '%'.$cirSearchKeyword.'%');
				}else{
					$leftSideDataListObj->whereMonth('customer_invoicing_rates.created_at', date('m'));
					$leftSideDataListObj->whereYear('customer_invoicing_rates.created_at', date('Y'));
				}
				$leftSideDataList        = $leftSideDataListObj->groupBy('customer_invoicing_rates.cir_customer_id', 'customer_invoicing_rates.cir_city_id')->orderBy('customer_master.customer_name', 'ASC')->get()->toArray();				
				$default_cir_customer_id = !empty($leftSideDataList[0]->cir_customer_id) ? $leftSideDataList[0]->cir_customer_id : '0';
				//**************/Left Side Data************************************************************

				//***************Right Side Data************************************************************
				if(!empty($leftSideDataList)){
					$cir_customer_id  = !empty($cir_customer_id) ? $cir_customer_id : $default_cir_customer_id;    
					$rightSideDataObj = DB::table('customer_invoicing_rates')
						->join('customer_master', 'customer_master.customer_id', '=', 'customer_invoicing_rates.cir_customer_id')
						->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
						->leftjoin('state_db', 'state_db.state_id', 'city_db.state_id')
						->leftjoin('product_master_alias', 'product_master_alias.c_product_id', 'customer_invoicing_rates.cir_c_product_id')
						->leftJoin('product_master', 'product_master.product_id', 'product_master_alias.product_id')
						->leftJoin('product_categories', 'product_categories.p_category_id', '=', 'customer_invoicing_rates.cir_product_category_id')
						->join('users as createdBy', 'createdBy.id', 'customer_invoicing_rates.created_by')
						->select('customer_invoicing_rates.*', 'customer_master.customer_name', 'state_db.state_name', 'city_db.city_name', 'city_db.city_id', 'product_master_alias.c_product_name', 'createdBy.name as createdByName', 'product_categories.p_category_name as dept_name', 'product_master.product_name')
						->whereNull('cir_parameter_id')
						->where('customer_invoicing_rates.invoicing_type_id', $invocingTypeId);

					if (!empty($cir_customer_id) && is_numeric($cir_customer_id)) {
						$rightSideDataObj->where('customer_invoicing_rates.cir_customer_id', $cir_customer_id);
					}
					if (!empty($cirProductCategoryId)) {
						$rightSideDataObj->where('customer_invoicing_rates.cir_product_category_id', $cirProductCategoryId);
					}
					if (!empty($cirDivisionId)) {
						$rightSideDataObj->where('customer_invoicing_rates.cir_division_id', $cirDivisionId);
					}
					$rightSideDataList = $rightSideDataObj->orderBy('customer_invoicing_rates.cir_id', 'ASC')->get()->toArray();
				}				
				//***************Right Side Data************************************************************
			}
		}

		$returnData = array('leftSideDataList' => $leftSideDataList, 'rightSideDataList' => $rightSideDataList);
		$error      = !empty($returnData['leftSideDataList']) ? 1 : '0';
		$message    = $error ? '' : $message;

		//to formate created and updated date
		!empty($returnData['rightSideDataList']) ? $models->formatTimeStampFromArray($returnData['rightSideDataList'], DATETIMEFORMAT) : '';

		return response()->json(array('error' => $error, 'message' => $message, 'returnData' => $returnData, 'cir_customer_id' => $cir_customer_id));
	}

	/**
	 * Display single customer products rate .
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getSelectedCustomerProductRate($cir_customer_id, $cir_id, $divID)
	{

		global $models, $invoicingTypeCustomerWiseProduct;

		$error    = '0';
		$message  = '';
		$data     = '';
		$count 	  = 0;
		$invocingTypeId  = '3';
		$customerProductRateList = $productAliasRateList = $productAliasData = array();

		$selectedDept     	 = $invoicingTypeCustomerWiseProduct->departAccToCustomerWiseProductInvoicing($cir_customer_id, $cir_id);
		$customerProductRateList = $invoicingTypeCustomerWiseProduct->getCustomerAllProducts($cir_customer_id, $selectedDept, $divID);
		$customerDataList 	 = DB::table('customer_master')->join('city_db', 'city_db.city_id', 'customer_master.customer_city')->where('customer_master.customer_id', $cir_customer_id)->select('customer_master.customer_id', 'customer_master.customer_name', 'city_db.city_id', 'city_db.city_name')->first();



		$error    = 1;
		$message  = $error ? '' : $message;

		return response()->json(array('error' => $error, 'message' => $message, 'productAliasRateList' => $customerProductRateList, 'selectedDept' => $selectedDept, 'customerDataList' => $customerDataList));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function viewCustomerWiseProductRate($cir_id)
	{

		global $models, $invoicingTypeCustomerWiseProduct;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';

		$customerWiseProductRate = DB::table('customer_invoicing_rates')
			->join('customer_master', 'customer_master.customer_id', '=', 'customer_invoicing_rates.cir_customer_id')
			->join('city_db', 'city_db.city_id', '=', 'customer_invoicing_rates.cir_city_id')
			->leftjoin('product_master_alias', 'product_master_alias.c_product_id', 'customer_invoicing_rates.cir_c_product_id')
			->leftjoin('product_master', 'product_master.product_id', 'product_master_alias.product_id')
			->leftJoin('product_categories', 'product_categories.p_category_id', '=', 'customer_invoicing_rates.cir_product_category_id')
			->join('users as createdBy', 'createdBy.id', 'customer_invoicing_rates.created_by')
			->select('customer_invoicing_rates.*', 'customer_master.customer_name', 'city_db.city_name', 'city_db.city_id', 'product_master_alias.c_product_name', 'createdBy.name as createdByName', 'product_categories.p_category_name as dept_name', 'product_master.product_name')
			->where('customer_invoicing_rates.cir_id', $cir_id)
			->first();

		$error    = !empty($customerWiseProductRate) ? 1 : '0';
		$message  = $error ? '' : $message;

		//echo '<pre>';print_r($customerWiseProductRate);die;
		return response()->json(array('error' => $error, 'message' => $message, 'customerWiseProductRateData' => $customerWiseProductRate));
	}

	/**
	 * Show the form for editing the specified resource.
	 *update all products of  a customer
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateCustomerWiseAllProductRate(Request $request)
	{

		global $models, $invoicingTypeCustomerWiseProduct;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$cirId    = '';
		$formData = array();

		if ($request->isMethod('post') && !empty($request['formData'])) {

			//pasrse searlize data
			parse_str($request['formData'], $formData);

			$invoicingRateArr = $formData['invoicing_rate'] ? $formData['invoicing_rate'] : array();
			$cirCustProductIdArr = $formData['cir_c_product_id'] ? $formData['cir_c_product_id'] : array();
			$cirCirIdArr = $formData['cir_c_product_id_1'] ? $formData['cir_c_product_id_1'] : array();
			foreach ($invoicingRateArr as $key => $rate) {
				$formData['cir_c_product_id'] = $cirCustProductIdArr[$key];
				$formData['invoicing_rate'] = $invoicingRateArr[$key];
				$formData['cir_id'] = $cirCirIdArr[$key];
				$updated[] = DB::table('customer_invoicing_rates')->where('cir_id', $formData['cir_id'])->where('cir_c_product_id', $formData['cir_c_product_id'])->update(['invoicing_rate' => $formData['invoicing_rate']]);
				$error = '1';
				$message = config('messages.message.updated');
			}
		}

		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'cir_id' => $cirId]);
	}

	/****
	 *** update fixed price customer products-list
	 ***
	 ****/
	public function updateCustomerWiseFixedProductRate(Request $request)
	{

		global $models, $invoicingTypeCustomerWiseProduct;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$cirId    = '';
		$formData = array();

		if ($request->isMethod('post') && !empty($request['formData'])) {
			//pasrse searlize data
			parse_str($request['formData'], $formData);
			if (!empty($formData)) {
				$formData = $models->unsetFormDataVariables($formData, array('_token'));     	//Unsetting the variable from request data

				$updated = DB::table('customer_invoicing_rates')->where('cir_id', $formData['cir_id'])->where('cir_city_id', $formData['cir_city_id'])->update($formData);
				$error = '1';
				$message = config('messages.message.updated');
			} else {
				$error = '1';
				$message = config('messages.message.savedNoChange');
			}
		}

		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'cir_id' => $cirId]);
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

		try {
			if (DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_id', '=', $cir_id)->delete()) {
				$error   = '1';
				$message = config('messages.message.deleted');
			} else {
				$message = config('messages.message.deletedError');
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.deletedErrorFKC');
		}

		return response()->json(['error' => $error, 'message' => $message]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getCustomerCity(Request $request, $customer_id)
	{
		$error   = '0';
		$message  = '';
		$data    = '';
		$customerData = array();

		if ($customer_id) {
			$customerData = DB::table('customer_master')
				->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
				->where('customer_master.customer_id', '=', $customer_id)
				->first();
			$error   = '1';
		}
		return response()->json(['error' => $error, 'message' => $message, 'customerData' => $customerData]);
	}
}

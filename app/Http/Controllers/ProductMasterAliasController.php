<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\ProductMasterAlias;
use App\Models;
use Validator;
use Route;
use DB;

class ProductMasterAliasController extends Controller
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

		global $models, $customerProductMaster;

		$models = new Models();
		$customerProductMaster = new ProductMasterAlias();

		//Login Authentication
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

		return view('master.customer_invoicing.product_master_alias.index', ['title' => 'Customer Product Master', '_product_master_alias' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function addCustomerProduct(Request $request)
	{

		global $models, $customerProductMaster;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$formData = array();

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			parse_str($request->formData, $formData);
			$formData = array_filter($formData);
			if (!empty($formData)) {

				if (empty($formData['c_product_name'])) {
					$message = config('messages.message.customerProductName');
				} else if (empty($formData['product_id'])) {
					$message = config('messages.message.productName');
				} else {
					//Unsetting the variable from request data
					$formData = $models->unsetFormDataVariables($formData, array('_token'));

					if (!empty($formData['product_id'])) {
						try {
							foreach (array_filter($formData['c_product_name']) as $customer_product_alias) {
								//echo'<pre>'; print_r($formData['c_product_name']);die;
								$formData['c_product_name'] = $customer_product_alias;
								$formData['created_by'] = USERID;
								$formData['c_product_status'] = '1';
								$formData['view_type'] = '1';
								$customerProductMasterId = DB::table('product_master_alias')->insertGetId($formData);
								$customerProductData = DB::table('product_master_alias')->where('c_product_id', '=', $customerProductMasterId)->select('product_master_alias.product_id')->first();
								$productData = !empty($customerProductData->product_id) ? $customerProductData->product_id : '0';
							}
							if (!empty($customerProductMasterId)) {
								$error   = '1';
								$data 	 = $productData;
								$message = config('messages.message.saved');
							}
						} catch (\Illuminate\Database\QueryException $ex) {
							$error   = '0';
							$message = config('messages.message.customerProductExist');
						}
					} else {
						$message = config('messages.message.savedError');
					}
				}
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'product_id' => $data));
	}

	/**
	 * listing of all Product Master Alias
	 * Praveen Singh
	 * 24-Aug-2018,14-Feb-2022
	 */
	public function getProductMasterAliasList(Request $request)
	{

		global $models, $customerProductMaster;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$formData = $productListLeftSideData = $productListRightSideData = array();

		//Assigning Condition according to the Role Assigned
		parse_str($request->formData, $formData);

		$searchKeyword     = !empty($formData['search_keyword']) ? $formData['search_keyword'] : '';
		$productCategoryId = !empty($formData['product_category_id']) && is_numeric($formData['product_category_id']) ? $formData['product_category_id'] : '0';
		$productId 	       = !empty($formData['product_id']) && is_numeric($formData['product_id']) ? $formData['product_id'] : '0';

		//**********************product List Left Side Data*****************************************
		$productListLeftSideObj = DB::table('product_master_alias')
			->join('product_master', 'product_master.product_id', 'product_master_alias.product_id')
			->join('product_categories as subProductCategoryDB', 'subProductCategoryDB.p_category_id', 'product_master.p_category_id')
			->join('product_categories as productCategoryDB', 'productCategoryDB.p_category_id', 'subProductCategoryDB.parent_id')
			->join('product_categories as parentProductCategoryDB', 'parentProductCategoryDB.p_category_id', 'productCategoryDB.parent_id')
			->join('users as createdBy', 'createdBy.id', 'product_master_alias.created_by')
			->select('product_master_alias.c_product_id', 'product_master_alias.product_id', 'product_master.product_name')
			->where('product_master_alias.view_type', '=', '1');

		if (!empty($productCategoryId) && is_numeric($productCategoryId)) {
			$productListLeftSideObj->where('parentProductCategoryDB.p_category_id', $productCategoryId);
		}
		if (!empty($searchKeyword)) {
			$productListLeftSideObj->where('product_master.product_name', 'LIKE', '%'.$searchKeyword.'%');
		}else{
			$productListLeftSideObj->whereMonth('product_master_alias.created_at', date('m'));
			$productListLeftSideObj->whereYear('product_master_alias.created_at', date('Y'));
		}
		$productListLeftSideObj->orderBy('product_master.product_name', 'ASC');
		$productListLeftSideObj->groupBy('product_master_alias.product_id');
		$productListLeftSideData = $productListLeftSideObj->get()->toArray();
		$productListLeftSideData = $models->convertObjectToArray($productListLeftSideData);
		$productIdDefault 	     = !empty($productListLeftSideData) ? current($productListLeftSideData)['product_id'] : '0';
		//**********************product List Left Side Data*****************************************

		//**********************product List Right Side Data*****************************************	
		if(!empty($productListLeftSideData)){
			$productId 				 = empty($productId) ? $productIdDefault : $productId;    	
			$productListRightSideObj = DB::table('product_master_alias')
				->join('product_master', 'product_master.product_id', 'product_master_alias.product_id')
				->join('product_categories as subProductCategoryDB', 'subProductCategoryDB.p_category_id', 'product_master.p_category_id')
				->join('product_categories as productCategoryDB', 'productCategoryDB.p_category_id', 'subProductCategoryDB.parent_id')
				->join('product_categories as parentProductCategoryDB', 'parentProductCategoryDB.p_category_id', 'productCategoryDB.parent_id')
				->join('users as createdBy', 'createdBy.id', 'product_master_alias.created_by')
				->select('parentProductCategoryDB.p_category_id as product_category_id', 'parentProductCategoryDB.p_category_name as department_name', 'product_master_alias.c_product_id', 'product_master_alias.c_product_name', 'product_master_alias.c_product_status', 'product_master_alias.product_id', 'createdBy.name as createdByName', 'product_master.product_name')
				->where('product_master_alias.view_type', '=', '1');
	
			if (!empty($productCategoryId) && is_numeric($productCategoryId)) {
				$productListRightSideObj->where('parentProductCategoryDB.p_category_id', $productCategoryId);
			}		
			if (!empty($productId) && is_numeric($productId)) {
				$productListRightSideObj->where('product_master_alias.product_id', $productId);
			}
			$productListRightSideObj->orderBy('product_master_alias.c_product_name', 'ASC');
			$productListRightSideData = $productListRightSideObj->get()->toArray();
			$productListRightSideData = $models->convertObjectToArray($productListRightSideData);
		}		
		//*********************/product List Right Side Data*****************************************	    

		$returnData = array('leftSideDataList' => $productListLeftSideData, 'rightSideDataList' => $productListRightSideData);
		$error      = !empty($returnData) ? 1 : '0';
		$message    = $error ? '' : $message;

		//to formate created and updated date		   
		$models->formatTimeStampFromArray($returnData, DATETIMEFORMAT);
		$customerProductsCount = DB::table('product_master_alias')->count();

		//echo'<pre>'; print_r($returnData); die;
		return response()->json(array('error' => $error, 'message' => $message, 'product_id' => $productId, 'getProductList' => $returnData, 'customerProductsCount' => $customerProductsCount));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getCustomerProductMasterAliasList(Request $request, $product_id)
	{

		global $models, $customerProductMaster;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';

		$productListRightSideObj = DB::table('product_master_alias')
			->join('product_master', 'product_master.product_id', 'product_master_alias.product_id')
			->join('product_categories as subProductCategoryDB', 'subProductCategoryDB.p_category_id', 'product_master.p_category_id')
			->join('product_categories as productCategoryDB', 'productCategoryDB.p_category_id', 'subProductCategoryDB.parent_id')
			->join('product_categories as parentProductCategoryDB', 'parentProductCategoryDB.p_category_id', 'productCategoryDB.parent_id')
			->join('users as createdBy', 'createdBy.id', 'product_master_alias.created_by')
			->select('parentProductCategoryDB.p_category_id as product_category_id', 'parentProductCategoryDB.p_category_name as department_name', 'product_master_alias.c_product_id', 'product_master_alias.c_product_name', 'product_master_alias.c_product_status', 'product_master_alias.product_id', 'createdBy.name as createdByName', 'product_master.product_name')
			->where('product_master_alias.view_type', '=', '1');

		if (!empty($product_id) && is_numeric($product_id)) {
			$productListRightSideObj->where('product_master_alias.product_id', '=', $product_id);
		}

		$productListRightSideObj->orderBy('product_master_alias.product_id', 'DESC');
		$productListRightSideData = $productListRightSideObj->get()->toArray();
		$error            = !empty($productListRightSideData) ? 1 : '0';
		$message          = $error ? '' : $message;

		//to formate created and updated date		   
		$models->formatTimeStampFromArray($productListRightSideData, DATETIMEFORMAT);

		//echo'<pre>'; print_r($retrunData); die;
		return response()->json(array('error' => $error, 'message' => $message, 'customerProductsList' => $productListRightSideData));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function viewCustomerProduct($c_product_id)
	{

		global $models, $customerProductMaster;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';

		$customerProductMasterData = DB::table('product_master_alias')
			->join('product_master', 'product_master.product_id', 'product_master_alias.product_id')
			->join('users as createdBy', 'createdBy.id', 'product_master_alias.created_by')
			->select('product_master_alias.*', 'createdBy.name as createdByName', 'product_master.product_name')
			->where('product_master_alias.c_product_id', $c_product_id)
			->orderBy('product_master_alias.c_product_id', 'DESC')
			->first();

		$productMasterData = DB::table('product_master')->where('product_master.product_id', $customerProductMasterData->product_id)->first();

		$error    = !empty($customerProductMasterData) ? 1 : '0';
		$message  = $error ? '' : $message;
		return response()->json(array('error' => $error, 'message' => $message, 'productMasterData' => $productMasterData, 'customerProductMasterData' => $customerProductMasterData));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id 07-08-2017
	 * @return \Illuminate\Http\Response
	 */
	public function viewCustomerAllProductAliases(Request $request)
	{

		global $models, $customerProductMaster;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';

		$productMasterData = DB::table('product_master')->where('product_master.product_id', $request->product_id)->first();

		$customerProductMasterData = DB::table('product_master_alias')
			->join('product_master', 'product_master.product_id', 'product_master_alias.product_id')
			->join('users as createdBy', 'createdBy.id', 'product_master_alias.created_by')
			->select('product_master_alias.*', 'createdBy.name as createdByName', 'product_master.product_name')
			->where('product_master_alias.product_id', $request->product_id)
			->orderBy('product_master_alias.c_product_id', 'ASC')
			->get()
			->toArray();

		$productId = $request->product_id;
		$error    = !empty($customerProductMasterData) ? 1 : '0';
		$message  = $error ? '' : $message;

		return response()->json(array('error' => $error, 'message' => $message,'productMasterData' => $productMasterData, 'customerProductMasterData' => $customerProductMasterData, 'productId' => $productId));
	}

	/**
	 * update customer product master details
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateCustomerProduct(Request $request)
	{

		global $models, $customerProductMaster;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$formData = array();

		if ($request->isMethod('post') && !empty($request['formData'])) {

			//pasrse searlize data 				
			parse_str($request['formData'], $formData);

			$productId = !empty($formData['product_id']) ? $formData['product_id'] : '0';
			$customerProductMasterIds   = !empty($formData['c_product_id']) ? $formData['c_product_id'] : '0';
			$customerProductMasterNames = !empty($formData['c_product_name']) ? $formData['c_product_name'] : '';

			if (empty(array_filter($customerProductMasterNames))) {
				$message = config('messages.message.customerProductName');
			} else if (empty($formData['product_id'])) {
				$message = config('messages.message.productName');
			} else {
				$formData = $models->unsetFormDataVariables($formData, array('_token', 'c_product_id', 'c_product_name'));
				if (!empty($customerProductMasterIds) && !empty($customerProductMasterNames)) {
					try {
						if (!empty($customerProductMasterIds) && !empty($formData['product_id'])) {
							foreach ($customerProductMasterIds as $key => $customerProductMasterId) {

								$formData['c_product_id'] = $customerProductMasterId;
								$formData['c_product_name'] = $customerProductMasterNames[$key];
								$formData['view_type'] = '1';

								if (!empty($customerProductMasterId)) {
									if (!empty($formData['c_product_name'])) {
										DB::table('product_master_alias')->where('product_master_alias.c_product_id', $formData['c_product_id'])->update($formData);
									} else {
										DB::table('product_master_alias')->where('product_master_alias.c_product_id', $formData['c_product_id'])->delete();
									}
								} else {
									unset($formData['c_product_id']);
									$formData['created_by'] = USERID;
									DB::table('product_master_alias')->insertGetId($formData);
								}
							}
							$error   = '1';
							$message = config('messages.message.updated');
						} else {
							$error   = '1';
							$message = config('messages.message.savedNoChange');
						}
					} catch (\Illuminate\Database\QueryException $ex) {
						$error   = '0';
						$message = config('messages.message.customerProductExist');
					}
				} else {
					$message = config('messages.message.updatedError');
				}
			}
		}
		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'product_id' => $productId, 'c_product_id' => $customerProductMasterIds]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteCustomerProduct(Request $request, $c_product_id)
	{

		$error   = '0';
		$message = '';
		$data    = '';
		//echo $c_product_id; die;
		try {
			if (DB::table('product_master_alias')->where('product_master_alias.c_product_id', '=', $c_product_id)->delete()) {
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
}

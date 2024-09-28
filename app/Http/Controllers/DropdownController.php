<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Company;
use App\Division;
use App\Models;
use App\Order;
use App\State;
use App\ProductCategory;
use Session;
use Validator;
use Route;
use DB;

class DropdownController extends Controller
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

		global $models, $state, $productCategory;

		$models = new  Models();
		$state = new  State();
		$productCategory = new ProductCategory();

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

	//***********************************ERP MODULE FUNCTION*********************************************//

	//dropdown list from product_test_hdr table
	public function getTestProductCodeList()
	{
		$productTestCodeList = DB::table('product_test_hdr')->select('test_id as id', 'test_code as name')->get();
		return json_encode($productTestCodeList);
	}

	//dropdown list from product_test_dtl table
	public function getTestProductParameterCodeList()
	{
		$parameterCodeList = DB::table('product_test_dtl')->select('product_test_dtl_id as id', 'product_test_dtl_id as name')->get();
		return json_encode($parameterCodeList);
	}

	//dropdown list from item_master table
	public function getItemMasterList()
	{
		$itemList = DB::table('item_master')->select('item_id as id', 'item_code as name')->get();
		return json_encode($itemList);
	}

	//Get list of city code
	public function getCitiesCodeList()
	{

		$cityCodeList = DB::table('city_db')
			->join('state_db', 'state_db.state_id', '=', 'city_db.state_id')
			->select('city_db.city_id as id', 'city_db.city_name as name')
			->where('state_db.country_id', '=', '101')
			->orderBy('city_db.city_name', 'ASC')
			->get();

		return json_encode($cityCodeList);
	}

	public function getStatesList()
	{
		$statesList = DB::table('state_db')->select('state_id as id', 'state_name as name', 'state_code as code')->get();
		return json_encode($statesList);
	}

	//display states list that contain products in customer_invoicing_rates table
	public function getAliasProductStatesList(Request $request)
	{

		$dept_id = !empty($request->selectedDept) ? $request->selectedDept : '1';
		$division_id = !empty($request->selectedDivision) ? $request->selectedDivision : '1';

		$productStatesListObj = DB::table('state_db')
			->join('customer_invoicing_rates', 'customer_invoicing_rates.cir_state_id', 'state_db.state_id')
			->select('state_db.state_id as id', 'state_db.state_name as name', 'customer_invoicing_rates.cir_id', 'customer_invoicing_rates.cir_division_id', 'customer_invoicing_rates.cir_product_category_id')
			->where('state_db.country_id', '=', 101)
			->where('customer_invoicing_rates.invoicing_type_id', 2);
		if ($dept_id) {
			$productStatesListObj->where('customer_invoicing_rates.cir_product_category_id', $dept_id);
		}
		if ($division_id) {
			$productStatesListObj->where('customer_invoicing_rates.cir_division_id', $division_id);
		}
		$productStatesListObj->groupBy('state_db.state_name')->orderBy('state_db.state_name');
		$productStatesList = $productStatesListObj->get();

		return json_encode(['productStatesList' => $productStatesList]);
	}

	//display customers list that contain products in customer_invoicing_rates table    
	public function getAliasProductCustomerList(Request $request)
	{
		$productCustomersListObj = DB::table('customer_master')
			->join('customer_invoicing_rates', 'customer_invoicing_rates.cir_customer_id', 'customer_master.customer_id')
			->join('city_db', 'customer_master.customer_city', '=', 'city_db.city_id')
			->select('customer_invoicing_rates.cir_id', 'customer_master.customer_id', 'customer_master.customer_name', 'city_db.city_name', 'city_db.city_id')
			->where('customer_invoicing_rates.invoicing_type_id', '3')
			->groupBy('customer_master.customer_id');
		if ($request['dept_id']) {
			$productCustomersListObj->where('customer_invoicing_rates.cir_product_category_id', $request['dept_id']);
		}
		$productCustomersList = $productCustomersListObj->groupBy('customer_master.customer_id')->orderBy('customer_master.customer_name')->get();

		if (!empty($productCustomersList)) {
			foreach ($productCustomersList as $key => $productCustomers) {
				if (!empty($productCustomersList[$key]->customer_id)) {
					$products = DB::table('customer_invoicing_rates')->select('cir_c_product_id')
						->where('customer_invoicing_rates.cir_c_product_id', '!=', null)
						->where('customer_invoicing_rates.cir_customer_id', $productCustomersList[$key]->customer_id)
						->where('customer_invoicing_rates.invoicing_type_id', 3)
						->get()
						->toArray();
					if (!empty(array_filter($products))) {
						$productCustomersList[$key]->fixed_rate = true;
					}
				} else {
					$productCustomersList[$key]->fixed_rate = false;
				}
			}
		}
		return json_encode(['productCustomersList' => $productCustomersList]);
	}

	public function getCountriesList()
	{
		$statesList = DB::table('countries_db')->select('country_id as id', 'country_name as name')->where('countries_db.country_status', '=', 1)->get();
		return json_encode($statesList);
	}

	public function getCompanyTypesList()
	{
		$statesList = DB::table('customer_company_type')->select('company_type_id as id', 'company_type_name as name')->get();
		return json_encode($statesList);
	}

	public function getOwnershipTypesList()
	{
		$statesList = DB::table('customer_ownership_type')->select('ownership_id as id', 'ownership_name as name')->get();
		return json_encode($statesList);
	}

	//Get list of Companies code
	public function getCompaniesCodeList()
	{
		$CodeList = DB::table('company_master')->select('company_id as id', 'company_name as name')->first();
		return json_encode($CodeList);
	}

	//Get list of divisions code
	public function getDivisionsCodeList()
	{
		$divisionCodeList = DB::table('divisions')->select('division_id as id', 'division_name as name')->get();
		return json_encode($divisionCodeList);
	}

	//Get list of Product code
	public function getProductTestCodeList()
	{
		$productTestList = DB::table('product_test_hdr')->select('test_id as id', 'test_code as name')->get();
		return json_encode($productTestList);
	}

	//Get list of Test Parameter code
	public function getTestParameterCodeList()
	{
		$testParameterList = DB::table('test_parameter')->select('test_parameter_id as id', 'test_parameter_name as name')->get();
		return json_encode($testParameterList);
	}

	//Get list of Test Parameter code using department id and paramenter category id
	public function getParameterByParaCat(Request $request, $product_cat_id, $parameter_cat_id)
	{

		global $models;

		$parameterObj = DB::table('test_parameter')->join('test_parameter_categories', 'test_parameter.test_parameter_category_id', '=', 'test_parameter_categories.test_para_cat_id')->select('test_parameter_id as id', 'test_parameter_name as name');
		if (is_numeric($parameter_cat_id)) {
			$parameterObj->where('test_parameter_categories.test_para_cat_id', $parameter_cat_id);
		}
		if (is_numeric($product_cat_id)) {
			$parameterObj->where('test_parameter_categories.product_category_id', $product_cat_id);
		}
		$testParameterList = $parameterObj->orderBy('test_parameter_name', 'ASC')->get();
		$models->htmlToStringFormate($testParameterList);

		return json_encode(['testParameterList' => $testParameterList]);
	}

	//Get list of Test Parameter code using department id and paramenter category id
	public function getCustomerWiseParametersList(Request $request, $product_cat_id, $parameter_cat_id)
	{

		global $models;

		$parameterObj = DB::table('test_parameter')->join('test_parameter_categories', 'test_parameter.test_parameter_category_id', '=', 'test_parameter_categories.test_para_cat_id')->select('test_parameter_id as id', 'test_parameter_name as name');
		if (!empty($product_cat_id) && is_numeric($product_cat_id)) {
			$parameterObj->where('test_parameter_categories.product_category_id', $product_cat_id);
		}
		if (!empty($parameter_cat_id) && is_numeric($parameter_cat_id)) {
			$parameterObj->where('test_parameter.test_parameter_category_id', $parameter_cat_id);
		}
		$testParameterList = $parameterObj->orderBy('test_parameter_name', 'ASC')->get();
		$models->htmlToStringFormate($testParameterList);

		return json_encode(['testParameterList' => $testParameterList]);
	}

	//Get list of Test Parameter code
	public function testParameterList($dept_id)
	{
		$testParameterList = DB::table('test_parameter')->select('test_parameter_id as id', 'test_parameter_name as name')->where('test_parameter.department_id', '=', $dept_id)->get();
		return response()->json(['testParameterList' => $testParameterList]);
	}

	//Get list of test_standard code
	public function getTestStandardsList($product_cat_parent_id)
	{

		global $models;

		$testStandardsList = array();

		$p_category_id = $models->getMainProductCatParentId($product_cat_parent_id);
		if (!empty($p_category_id)) {
			$testStandardsList = DB::table('test_standard')->select('test_std_id as id', 'test_std_name as name')->where('product_category_id', '=', $p_category_id)->where('status', 1)->orderBy('test_std_name', 'ASC')->get();
		}
		return json_encode(['testStandardsList' => $testStandardsList, 'globalProductCategoryId' => $p_category_id]);
	}

	//Get list of product_master code
	public function getProductsCodeList()
	{
		$produtList = DB::table('product_master')->select('product_id as id', 'product_name as name')->get();
		return json_encode($produtList);
	}

	//Get list of product_master code  by cat id
	public function getProductsByCatId($p_category_id)
	{
		$produtList = DB::table('product_master')->select('product_id as id', 'product_name as name')->where('p_category_id', '=', $p_category_id)->orderBy('product_name', 'ASC')->get();
		return json_encode(['productsList' => $produtList]);
	}

	//Get list of test parameter categories code
	public function getCategoriesCodeList()
	{
		$allCatList = DB::table('test_parameter_categories')->select('test_para_cat_id as id', 'test_para_cat_name as name')->orderBy('test_para_cat_name', 'ASC')->get();
		return response()->json(['allCatList' => $allCatList]);
	}

	//Get list of product categories code  list
	public function getProductCategoryCodeList()
	{
		$productCategoryList = DB::table('product_categories')->select('p_category_id as id', 'p_category_name as name')->orderBy('product_categories.p_category_name', 'ASC')->get();
		return response()->json(['productCategoryList' => $productCategoryList]);
	}

	//Get list of product categories code  list
	public function getProductCategoryListByLevel($level)
	{
		$productCategoriesObj = DB::table('product_categories')->join('product_categories as category', 'product_categories.parent_id', 'category.p_category_id')->select('product_categories.p_category_id as id', 'product_categories.p_category_name as name', DB::raw('UPPER(category.p_category_name) as parent_category_name'))->where('product_categories.level', '=', $level);
		$productCategories = $productCategoriesObj->orderBy('parent_category_name', 'ASC')->get();
		return response()->json(['productCategories' => $productCategories]);
	}

	//Get list of item categories code  list
	public function getInventoryItemCategoriesList()
	{
		$itemCategoryList = DB::table('item_categories')->select('item_cat_id as id', 'item_cat_name as name')->orderBy('item_cat_name', 'ASC')->get();
		return response()->json(['itemCategoryList' => $itemCategoryList]);
	}

	//Get list of items code  list
	public function getInventoryItemsList()
	{
		$itemsList = DB::table('item_master')->select('item_id as id', 'item_name as name')->get();
		return response()->json(['itemsList' => $itemsList]);
	}

	//Get list of customer billing types list
	public function customerbillingTypes()
	{
		$billingTypes = DB::table('customer_billing_types')->select('billing_type_id as id', 'billing_type as name')->where('billing_status', '1')->orderBy('customer_billing_types.billing_type_id', 'ASC')->get();
		return response()->json(['billingTypes' => $billingTypes]);
	}

	//Get list of customer invoicing type list (27-07-2017)
	public function customerInvoicingTypes()
	{
		$invoicingTypes = DB::table('customer_invoicing_types')->select('invoicing_type_id as id', 'invoicing_type as name')->get();
		return response()->json(['invoicingTypes' => $invoicingTypes]);
	}

	//Get list of customer billing type code  list
	public function customerTypes()
	{
		$customerTypes = DB::table('customer_types')->select('type_id as id', 'customer_type as name')->get();
		return response()->json(['customerTypes' => $customerTypes]);
	}

	//Get list of customer_discount_types code  list
	public function discountTypes()
	{
		$discountTypes = DB::table('customer_discount_types')->select('discount_type_id as id', 'discount_type as name')->get();
		return response()->json(['discountTypes' => $discountTypes]);
	}

	//Get list of employees  list
	public function getEmployeeList()
	{
		$employeeListObj = DB::table('users')
			->join('role_user', 'users.id', '=', 'role_user.user_id')
			->select('users.id as id', 'users.name as name')
			->where('role_user.role_id', '=', '2')
			->where('users.status', '=', '1')
			->where('users.is_sales_person', '=', '1');

		if (!empty(\Auth::user()->division_id)) {
			$employeeListObj->where('users.division_id', '=', \Auth::user()->division_id);
		}
		$employeeList = $employeeListObj->get();

		return response()->json(['employeeList' => $employeeList]);
	}

	//Get branch wise employee
	public function getBranchWiseEmployeeList($division_id)
	{
		$employeeList = DB::table('users')
			->join('role_user', 'users.id', '=', 'role_user.user_id')
			->select('users.id as id', 'users.name as name')
			->where('role_user.role_id', '=', '2')
			->where('users.status', '=', '1')
			->where('users.is_sales_person', '=', '1')
			->where('users.division_id', '=', $division_id)
			->get();

		return response()->json(['employeeList' => $employeeList]);
	}

	//Get list of equipment_type
	public function getEquipmentList()
	{
		$equipmentList = DB::table('equipment_type')->select('equipment_id as id', 'equipment_name as name')->get();
		return response()->json(['equipmentList' => $equipmentList]);
	}

	//Get list of methods list
	public function getMethodList()
	{
		$methodtList = DB::table('method_master')->select('method_id as id', 'method_name as name')->where('method_master.status', 1)->get();
		return response()->json(['methodtList' => $methodtList]);
	}

	//Get list of methods list
	public function methodList($product_category_id, $equipment_type_id)
	{

		global $models;

		$selectedMethod = array();

		$methodtListObj = DB::table('method_master')->select('method_id as id', 'method_name as name');

		if ($product_category_id != '2' && !empty($equipment_type_id) && is_numeric($equipment_type_id)) {
			$methodtListObj->where('method_master.equipment_type_id', '=', $equipment_type_id);
		} else {
			$selectedMethod = $models->getMethodId();
		}
		if (!empty($product_category_id) && is_numeric($product_category_id)) {
			$methodtListObj->where('method_master.product_category_id', '=', $product_category_id);
		}
		$methodList = $methodtListObj->where('method_master.status', 1)->orderBy('method_name', 'ASC')->groupBy('method_name')->get();

		return response()->json(['methodList' => $methodList, 'selectedMethod' => $selectedMethod]);
	}

	/***** get detectors according to selected equipment and product category is*/
	public function detectorsList($product_category_id, $equip_id)
	{
		$detectorsList = DB::table('detector_master')
			->join('equipment_type', 'equipment_type.equipment_id', '=', 'detector_master.equipment_type_id')
			->where('detector_master.product_category_id', '=', $product_category_id)
			->where('detector_master.equipment_type_id', '=', $equip_id)
			->where('equipment_type.status', '=', 1)
			->where('detector_master.status', '=', 1)
			->select('equipment_type.equipment_id as equip_id', 'equipment_type.equipment_name as equip_name', 'detector_id as id', 'detector_name as name')
			->get();
		return response()->json(['detectorList' => $detectorsList]);
	}

	//Get list of methods list
	public function getdepartmentList()
	{
		$departmentList = DB::table('departments')
			->join('department_type', 'departments.department_type', 'department_type.department_type_id')
			->select('departments.department_id as id', 'departments.department_name as name', 'department_type.department_type_name')
			->orderBy('name', 'ASC')->get();
		return response()->json(['departmentList' => $departmentList]);
	}

	//Get list of methods list
	public function getEquipmentTypesList()
	{
		$equipmentTypesList = DB::table('equipment_type')->select('equipment_id as id', 'equipment_name as name')->where('equipment_type.status', 1)->orderBy('name', 'ASC')->get();
		return response()->json(['equipmentTypesList' => $equipmentTypesList]);
	}

	//Get list of methods list
	public function getParameterEquipmentList($product_category_id, $test_parameter_id)
	{

		global $models;

		$parameterEquipmentList = array();

		if (!empty($test_parameter_id)) {
			$parameterEquipmentListObj = DB::table('test_parameter_equipment_types')
				->join('equipment_type', 'test_parameter_equipment_types.equipment_type_id', 'equipment_type.equipment_id')
				->join('test_parameter', 'test_parameter_equipment_types.test_parameter_id', 'test_parameter.test_parameter_id')
				->join('test_parameter_categories', 'test_parameter.test_parameter_category_id', 'test_parameter_categories.test_para_cat_id')
				->select('equipment_type.equipment_id as id', 'equipment_type.equipment_name as name')
				->where('test_parameter_categories.status', 1);
			if (!empty($product_category_id)) {
				$parameterEquipmentListObj->where('test_parameter_categories.product_category_id', $product_category_id);
			}
			if (!empty($test_parameter_id)) {
				$parameterEquipmentListObj->where('test_parameter.test_parameter_id', $test_parameter_id)->where('test_parameter.status', 1);
			}
			$parameterEquipmentList = $parameterEquipmentListObj->where('equipment_type.status', 1)->orderBy('equipment_type.equipment_name', 'ASC')->get();
		}
		return response()->json(['parameterEquipmentList' => $parameterEquipmentList]);
	}

	//Get list of methods list
	public function getDepartmentTypeList()
	{
		$deptTypesList = DB::table('department_type')->select('department_type_id as id', 'department_type_name as name')->get();
		return response()->json(['deptTypesList' => $deptTypesList]);
	}

	//Get list of methods list
	public function customersList()
	{
		$customersList = DB::table('customer_master')
			->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
			->select('customer_master.customer_id as id', DB::raw('CONCAT(customer_master.customer_name,"/",city_db.city_name,"/",customer_master.customer_code) AS name'))
			->orderBy('customer_name', 'desc')
			->get();
		//echo'<pre>'; print_r($customersList); die;
		return response()->json(['customersList' => $customersList]);
	}

	/**
	 * Get list of cities on change state.
	 * Date : 14-12-17
	 * Author : nisha
	 **/
	public function stateCitiesList($state_id)
	{
		$stateCitiesList = DB::table('city_db')->select('city_id as id', 'city_name as name')->where('city_db.state_id', '=', $state_id)->get();
		$stateCode = DB::table('state_db')->select('state_code as code')->where('state_db.state_id', '=', $state_id)->first();
		return response()->json(['stateCitiesList' => $stateCitiesList, 'stateCode' => $stateCode]);
	}

	//Get list of test parameter categories code
	public function getTestProductCategory($level)
	{
		$productCategories = DB::table('product_categories')
			->join('product_categories as category', 'product_categories.parent_id', 'category.p_category_id')
			->select('product_categories.*', DB::raw('UPPER(category.p_category_name) as parent_category_name'))
			->where('product_categories.level', '=', $level)
			->orderBy('parent_category_name', 'ASC')
			->get();
		return response()->json(['productCatsList' => $productCategories]);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getProductAccToCategory($category_id)
	{
		$products = DB::table('product_master')->where('p_category_id', '=', $category_id)->orderBy('product_name', 'DESC')->get();
		return response()->json(['productsList' => $products]);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getProductAccToSampleName($cProductId)
	{
		$response = array();
		$productMasterAlias = DB::table('product_master_alias')->where('product_master_alias.c_product_id', '=', trim($cProductId))->first();
		if (!empty($productMasterAlias->product_id)) {
			$response = DB::table('product_master')->where('product_master.product_id', '=', $productMasterAlias->product_id)->orderBy('product_master.product_name')->get();
		}
		return response()->json(['productSampleNameList' => $response]);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getProductTests($product_id)
	{

		$productTestList = $testStandardList = array();

		$productTestHdr = DB::table('product_test_hdr')
			->join('test_standard', 'test_standard.test_std_id', 'product_test_hdr.test_standard_id')
			->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
			->select('product_test_hdr.*', 'test_standard.*', 'product_master.*')
			->where('product_test_hdr.product_id', '=', $product_id)
			->get();

		$productMasterList = DB::table('product_master')->where('product_id', '=', $product_id)->first();
		$testStandardList  = DB::table('product_test_hdr')->where('product_test_hdr.product_id', '=', $product_id)->first();

		//echo '<pre>';print_r($testStandard);die;
		return response()->json(['productTestList' => $productTestHdr, 'productMasterList' => $productMasterList, 'testStandardList' => $testStandardList]);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getSamplePriorityList()
	{
		$samplePriorityList = DB::table('order_sample_priority')->select('order_sample_priority.sample_priority_id', 'order_sample_priority.sample_priority_name', 'order_sample_priority.sample_priority_color_code')->orderBy('sample_priority_id', 'ASC')->get();
		$samplePriorityCRMList = DB::table('order_sample_priority')->select('order_sample_priority.sample_priority_id', 'order_sample_priority.sample_priority_name', 'order_sample_priority.sample_priority_color_code')->where('order_sample_priority.sample_priority_id', '4')->orderBy('sample_priority_id', 'ASC')->get();
		return response()->json(['samplePriorityList' => $samplePriorityList, 'samplePriorityCRMList' => $samplePriorityCRMList]);
	}
	/**
	 * Get list of customers on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getCustomerList()
	{
		$customers = DB::table('customer_master')->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')->get();
		return response()->json(['customersList' => $customers]);
	}

	/**
	 * Get list of modules at given level.
	 * Date : 05-05-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getAllModulesList($level)
	{
		$moduleList = DB::table('modules')->select('id', 'module_name as name')->where('modules.module_level', '=', $level)->get();
		return response()->json(['moduleList' => $moduleList]);
	}

	/**
	 * Get list of modules at given level.
	 * Date : 05-05-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getSubModulesList($module_category_id)
	{
		$subModuleList = DB::table('modules')->select('id', 'module_name as name')->where('modules.parent_id', '=', $module_category_id)->get();
		return response()->json(['subModuleList' => $subModuleList]);
	}

	/**
	 * Get list of roles
	 * Date : 05-05-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getAllRolesList()
	{
		$roleList = DB::table('roles')->select('id as id', 'name as name')->get();
		return response()->json(['roleList' => $roleList]);
	}

	/**
	 * Get list of customer on page load.
	 * Date : 30-12-16
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getSalesExecutiveList()
	{
		$users = DB::table('users')
			->join('role_user', 'users.id', '=', 'role_user.user_id')
			->select('users.id', 'users.name', 'users.email', 'users.user_code', 'users.is_sales_person', 'role_user.role_id')
			->where('role_user.role_id', '=', '2')
			->where('users.status', '=', '1')
			->get();
		return response()->json(['executiveList' => $users]);
	}

	/**
	 * Get list of customers on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getCustomerLocationLicNo($customer_id)
	{
		$customerLocationLicNo = DB::table('customer_master')->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')->where('customer_master.customer_id', '=', $customer_id)->first();
		return response()->json(['customerLocationLicNo' => $customerLocationLicNo]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getBranchWiseAddOrders($division_id)
	{

		$error    = '0';
		$message  = '';
		$data     = '';

		$branchWiseOrders = DB::table('order_master')->where('order_master.division_id', $division_id)->select('order_master.order_no', 'order_master.order_id')->where('order_master.status', '0')->get();
		return response()->json(array('error' => $error, 'message' => $message, 'branchWiseOrdersList' => $branchWiseOrders));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getBranchWiseViewOrders($division_id)
	{

		$error    = '0';
		$message  = '';
		$data     = '';

		$branchWiseOrders = DB::table('order_master')->where('order_master.division_id', $division_id)->select('order_master.order_no', 'order_master.order_id')->whereIn('order_master.status', array('1', '2'))->get();
		return response()->json(array('error' => $error, 'message' => $message, 'branchWiseOrdersList' => $branchWiseOrders));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getBillingTypeCustomerListBK04APL18(Request $request, $billing_type_id)
	{

		$billingTypeCustomerList = array();

		if (!empty($billing_type_id)) {
			$billingTypeCustomerList = DB::table('customer_master')
				->join('order_master', 'order_master.customer_id', 'customer_master.customer_id')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->select('customer_master.customer_id', DB::raw('CONCAT(customer_master.customer_name,"/",city_db.city_name,"/",customer_master.customer_code) AS customer_name'))
				->where('customer_master.billing_type', '=', $billing_type_id)
				->where('order_master.status', '8')    //completed for report and ready for invoice generation
				->groupBy('order_master.customer_id')
				->orderBy('customer_master.customer_name', 'DESC')
				->get();
		}

		//echo '<pre>';print_r($billingTypeCustomerList);die;
		return response()->json(['billingTypeCustomerList' => $billingTypeCustomerList]);
	}

	/**
	 * Get list of customer on page load.
	 * Date : 30-12-16
	 * Author : 
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getSalesExeListBasedOnDivision($division_id)
	{
		$users = DB::table('users')->where('users.is_sales_person', '=', '1')->where('users.status', '=', '1')->where('users.division_id', '=', $division_id)->orderBy('users.name', 'ASC')->get()->toArray();
		return response()->json(['executiveList' => $users]);
	}


	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getReportStatusList()
	{
		$statusReportList = DB::table('status_master')->orderBy('status_master.status_name', 'DESC')->get();
		return response()->json(['statusReportList' => $statusReportList]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getDivisionStores($division_id)
	{

		global $divisionWiseStore, $models;

		$storeDataListObj = DB::table('division_wise_stores')->join('divisions', 'divisions.division_id', 'division_wise_stores.division_id')->select('division_wise_stores.store_id', 'division_wise_stores.store_name');
		if (!empty($division_id) && is_numeric($division_id)) {
			$storeDataListObj->where('division_wise_stores.division_id', $division_id);
		}
		$storeDataList = $storeDataListObj->orderBy('division_wise_stores.store_id', 'DESC')->get();

		return response()->json(['storeDataList' => $storeDataList]);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getItemsList()
	{

		global $item, $models;

		$items = DB::table('item_master')->join('item_categories', 'item_categories.item_cat_id', 'item_master.item_cat_id')->select('item_master.item_id', 'item_master.item_code', 'item_master.item_name')->get();
		return response()->json(['itemsList' => $items]);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getSearchItemsList($keyword)
	{
		$items = DB::table('item_master')->select('item_master.item_id', 'item_master.item_code', 'item_master.item_name')->where('item_master.item_code', 'LIKE', "%$keyword%")->get();
		return response()->json(['itemsList' => $items]);
	}

	/**
	 * Get list of customer on page load.
	 * Date : 30-12-16
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getEmployeeExecutiveList()
	{
		$data = DB::table('users')
			->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
			->select('users.id', 'users.name')
			->where('role_user.role_id', '=', '2')
			->where('users.status', '=', '1')
			->where('users.is_sales_person', '=', '0')
			->get();
		return response()->json(['executiveList' => $data]);
	}

	/**
	 * Get list of customer on page load.
	 * Date : 30-12-16
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getPurchaseOrderPONos($item_id)
	{

		$purchaseOrderNoList = array();

		$data = DB::table('po_hdr')
			->join('po_hdr_detail', 'po_hdr_detail.po_hdr_id', 'po_hdr.po_hdr_id')
			->where('po_hdr_detail.item_id', $item_id)
			->where('po_hdr.dpo_po_type', '1')
			->pluck('po_hdr.po_hdr_id', 'po_hdr.po_no');

		if (!empty($data)) {
			$counter = 0;
			foreach ($data as $key => $value) {
				$purchaseOrderNoList[$counter]['po_hdr_id'] = $value;
				$purchaseOrderNoList[$counter]['po_no']     = $key;
				$counter++;
			}
		}

		return response()->json(['purchaseOrderNoList' => $purchaseOrderNoList]);
	}

	//Get list of methods list
	public function getPaymentSources()
	{
		$data = DB::table('payment_sources')->select('payment_sources.payment_source_id as id', 'payment_source_name as name')->where('payment_sources.status', '1')->get();
		return response()->json(['paymentSourceList' => $data]);
	}

	//Get list of items code  list
	public function getVendorDataList()
	{
		$data = DB::table('vendors')->select('vendors.vendor_id as id', 'vendors.vendor_name as name')->get();
		return response()->json(['vendorListData' => $data]);
	}

	//Get list of items code  list
	public function getParentProductCategory()
	{
		$data = DB::table('product_categories')
			->select('p_category_id as id', 'p_category_name as name')
			->where('product_categories.level', '0')
			->orderBy('product_categories.p_category_id', 'ASC')
			->get();
		return response()->json(['parentCategoryList' => $data]);
	}

	/**
	 * Get list of customer on page load.
	 * Date : 30-12-16
	 * Author : 
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getEmployeeBasedOnDivision($division_id)
	{

		$user_id             = defined('USERID') ? USERID : '0';
		$department_ids      = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$user_department_ids = defined('USER_DEPARTMENT_IDS') ? USER_DEPARTMENT_IDS : '0';
		$role_ids            = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids  = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		$userObj = DB::table('users')
			->join('users_department_detail', 'users_department_detail.user_id', '=', 'users.id')
			->join('role_user', 'users.id', '=', 'role_user.user_id')
			->leftJoin('users_equipment_detail', 'users_equipment_detail.user_id', 'users.id')
			->select('users.id', 'users.name', 'users.email', 'users.user_code', 'users.is_sales_person', 'role_user.role_id')
			->where('role_user.role_id', '=', '6')
			->where('users.status', '=', '1')
			->where('users.is_sales_person', '=', '0');

		if (!empty($division_id) && is_numeric($division_id)) {
			$userObj->where('users.division_id', '=', $division_id);
		}
		//Filtering records according to department assigned
		if (!empty($user_department_ids) && is_array($user_department_ids)) {
			$userObj->whereIn('users_department_detail.department_id', $user_department_ids);
		}
		//Filtering records according to department assigned
		if (!empty($equipment_type_ids) && is_array($equipment_type_ids)) {
			$userObj->whereIn('users_equipment_detail.equipment_type_id', $equipment_type_ids);
		}

		$users = $userObj->groupBy('users_department_detail.user_id')->orderBy('users.name', 'ASC')->get();

		return response()->json(['employeeList' => $users]);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getSampleModes()
	{
		$sampleModes = DB::table('sample_modes')->select('sample_mode_id as id', 'sample_mode_name as name')->orderBy('sample_mode_id', 'ASC')->get();
		return response()->json(['sampleModeList' => $sampleModes]);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getRoleList()
	{
		$roles = DB::table('roles')->select('id', 'name')->whereNotIn('id', array('1'))->orderBy('id', 'ASC')->get();
		return response()->json(['roleDataList' => $roles]);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getSamplesReceived()
	{

		$department_ids = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$division_id	= defined('DIVISIONID') ? DIVISIONID : '0';

		$testSampleReceviedObj = DB::table('samples')
			->join('customer_master', 'customer_master.customer_id', 'samples.customer_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->leftJoin('trf_hdrs', 'trf_hdrs.trf_id', 'samples.trf_id')
			->select('sample_id as id', 'trf_hdrs.trf_no', DB::raw('CONCAT(samples.sample_no,"/",customer_master.customer_code,"/",customer_master.customer_name,"/",state_db.state_name,"/",city_db.city_name) AS name'))
			->join('customer_defined_structures', function ($join) {
				$join->on('customer_defined_structures.customer_id', '=', 'samples.customer_id');
				$join->whereColumn('customer_defined_structures.product_category_id', '=', 'samples.product_category_id');
				$join->whereColumn('customer_defined_structures.division_id', '=', 'samples.division_id');
			});

		if (!empty($department_ids)) {
			$testSampleReceviedObj->whereIn('samples.product_category_id', $department_ids);
		}
		if (!empty($division_id)) {
			$testSampleReceviedObj->where('samples.division_id', '=', $division_id);
		}
		$testSampleReceviedObj->where('samples.sample_status', '0')->orderBy('samples.sample_id', 'ASC');
		$testSampleReceviedList = $testSampleReceviedObj->get()->toArray();

		if (!empty($testSampleReceviedList)) {
			foreach ($testSampleReceviedList as $key => $values) {
				if (!empty($values->trf_no)) {
					$values->name = $values->name . '(' . $values->trf_no . ')';
				}
			}
		}

		return response()->json(['testSampleReceviedList' => $testSampleReceviedList]);
	}

	/**
	 * Get list of customer on page load.
	 * Date : 30-12-16
	 * Author : 
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getUserRoleByEmail(Request $request)
	{

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$users    = array();

		if (!empty($request->email) && $request->isMethod('post')) {

			$users = DB::table('users')
				->join('role_user', 'users.id', '=', 'role_user.user_id')
				->join('roles', 'roles.id', '=', 'role_user.role_id')
				->select('roles.id', 'roles.name')
				->where('users.status', '=', '1')
				->where('users.email', $request->email)
				->first();
			$error   = !empty($users) ? '1' : '0';
			$message = '';
		}

		return response()->json(array('error' => $error, 'message' => $message, 'users' => $users));
	}

	//dropdown list from products from product_master table
	public function getAllProductsList($product_category_id)
	{

		$produtObj = DB::table('product_master')->select('product_id as id', 'product_name as name');
		if (!empty($product_category_id)) {
			$produtObj->where('p_category_id', '=', $product_category_id);
		}
		$productsList =	$produtObj->orderBy('product_name')->get()->toArray();

		return json_encode(['productsList' => $productsList]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getCustomerProductList()
	{
		$customerProductsList = DB::table('product_master_alias')
			->join('product_master', 'product_master.product_id', 'product_master_alias.product_id')
			->select('product_master_alias.c_product_id as id', 'product_master_alias.c_product_name as name', 'product_master.product_name')
			->orderBy('product_master_alias.c_product_name', 'DESC')
			->get();

		return response()->json(array('customerProductsList' => $customerProductsList));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getProductMasterAliasList(Request $request, $product_cat_id)
	{

		global $models;

		$allRelatedDeprts = $models->getAllChildrens($product_cat_id);

		$productAliasList = DB::table('product_master_alias')
			->join('product_master', 'product_master.product_id', 'product_master_alias.product_id')
			->join('product_categories', 'product_categories.p_category_id', 'product_master.p_category_id')
			->select('product_master_alias.c_product_id as id', 'product_master_alias.c_product_name as name', 'product_master.product_name')
			->whereIn('product_categories.p_category_id', $allRelatedDeprts)
			->where('product_master_alias.view_type', '=', '1')
			->orderBy('product_master_alias.c_product_name', 'DESC')
			->get();

		return response()->json(array('productAliasRateList' => $productAliasList));
	}

	/*****************************************
	 * Get list of cities on change state.
	 * Date : 14-12-17
	 * Author : nisha
	 ****************************************/
	public function getStateWiseCustomers($state_id)
	{
		$customerListDataObj = DB::table('customer_master')
			->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->select('customer_master.customer_id as id', DB::raw('CONCAT(customer_master.customer_name,"/",city_db.city_name,"/",customer_master.customer_code) AS name'), 'city_db.city_id as city_id', 'city_db.city_name as city_name');

		if (!empty($state_id) && is_numeric($state_id)) {
			$customerListDataObj->where('customer_master.customer_state', '=', $state_id);
		}
		$customerListData = $customerListDataObj->orderBy('customer_master.customer_name')->get();

		return response()->json(['customerListData' => $customerListData]);
	}

	/*****************************************
	 * Get list of cities on change state.
	 * Date : 15-Feb-2022
	 * Author : Praveen Singh
	 ****************************************/
	public function getStateWiseCustomerWithCodeWithCity($state_id)
	{
		$customerListDataObj = DB::table('customer_master')
			->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->select('customer_master.customer_id as id', DB::raw('CONCAT(customer_master.customer_name,"/",city_db.city_name,"/",customer_master.customer_code) AS name'), 'city_db.city_id as city_id', 'city_db.city_name as city_name');

		if (!empty($state_id) && is_numeric($state_id)) {
			$customerListDataObj->where('customer_master.customer_state', '=', $state_id);
		}
		$customerListData = $customerListDataObj->orderBy('customer_master.customer_name')->get();

		return response()->json(['customerListData' => $customerListData]);
	}

	//Get list of methods list
	public function getCityWiseCustomers($cityId)
	{

		$customerListData = DB::table('customer_master')
			->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->select('customer_master.customer_id as id', 'customer_master.customer_name as name', 'city_db.city_id as city_id', 'city_db.city_name as city_name')
			->orderBy('customer_master.customer_name', 'DESC')
			->get();

		return response()->json(['customerListData' => $customerListData]);
	}

	//get Location (state/city) popup tree view
	public function getStateCityLocationTree()
	{
		global $state;
		$stateCityTreeViewList = $state->statesTree();
		return response()->json(['stateCityTreeViewList' => $stateCityTreeViewList]);
	}

	/**
	 * Show the form for creating a new resource.
	 * *04-08-2017
	 * @return \Illuminate\Http\Response
	 */
	public function getSettingGroup()
	{
		$settingGroup = DB::table('setting_groups')->get();
		return response()->json(['settingGroup' => $settingGroup]);
	}

	/**
	 * Show the auto complete list of parameters on test parameter module.
	 * *10-08-2017
	 * @return \Illuminate\Http\Response
	 */
	public function getParameterListByParameterName($parameter_name, $product_cat_id, $parameter_cat_id)
	{

		global $models;

		$parameterObj = DB::table('test_parameter')->join('test_parameter_categories', 'test_parameter.test_parameter_category_id', '=', 'test_parameter_categories.test_para_cat_id')->select('test_parameter.test_parameter_id as id', 'test_parameter.test_parameter_name as name', 'test_parameter.test_parameter_nabl_scope', 'test_parameter.test_parameter_decimal_place');

		if (!empty($parameter_name)) {
			$parameterObj->where('test_parameter.test_parameter_name', 'LIKE', '%' . strtolower($parameter_name) . '%');
		}
		if (!empty($parameter_cat_id) && is_numeric($parameter_cat_id)) {
			$parameterObj->where('test_parameter_categories.test_para_cat_id', $parameter_cat_id);
		}
		if (!empty($product_cat_id) && is_numeric($product_cat_id)) {
			$parameterObj->where('test_parameter_categories.product_category_id', $product_cat_id);
		}
		$parameterNameList = $parameterObj->orderBy('test_parameter_name', 'ASC')->limit('100')->get();

		return json_encode(['parameterNameList' => $parameterNameList]);
	}

	//get order report options dropdown list
	public function getOrderReportOptions()
	{
		$reportOptionsList = DB::table('order_report_options')->select('report_option_id as id', 'report_option_name as name')->get();
		return json_encode(['reportOptionsList' => $reportOptionsList]);
	}

	//get order report remark and note options dropdown list
	public function getOrderReportRemarksNotesOptions($product_category_id)
	{

		$reportNoteOptionsList = DB::table('order_report_note_remark_default')
			->where('type', '=', 1)
			->where('product_category_id', '=', $product_category_id)
			->select('remark_name as id', 'remark_name as name')
			->get();

		$reportRemarkOptionsList = DB::table('order_report_note_remark_default')
			->where('type', '=', 2)
			->where('product_category_id', '=', $product_category_id)
			->select('remark_name as id', 'remark_name as name')
			->get();

		return json_encode(['reportNoteOptionsList' => $reportNoteOptionsList, 'reportRemarkOptionsList' => $reportRemarkOptionsList]);
	}

	/**
	 * Display the get Auto Search Header Notes
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getAutoSearchHeaderNoteMatches($keyword)
	{

		$data = DB::table('order_header_notes')
			->select('order_header_notes.header_id as id', 'order_header_notes.header_name as name', 'order_header_notes.header_limit')
			->where('order_header_notes.header_name', 'LIKE', "%$keyword%")
			->orderBy('order_header_notes.header_name', 'ASC')
			->limit('10')
			->get();

		return response()->json(['itemsList' => $data]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getAutoSearchStabilityNoteMatches($keyword)
	{

		$data = DB::table('order_stability_notes')
			->select('order_stability_notes.stability_id as id', 'order_stability_notes.stability_name as name')
			->where('order_stability_notes.stability_name', 'LIKE', "%$keyword%")
			->orderBy('order_stability_notes.stability_name', 'ASC')
			->limit('10')
			->get();

		return response()->json(['itemsList' => $data]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getDefaultUserRoles()
	{

		$data = DB::table('order_status')
			->join('roles', 'roles.id', 'order_status.role_id')
			->select('order_status.order_status_id as id', 'order_status.order_status_name', 'order_status.order_status_alias as name')
			->where('order_status.status', '1')
			->whereNotIn('order_status.order_status_id', array('4', '8', '9', '10', '11', '12'))
			->whereNotNull('order_status.order_status_alias')
			->get();

		return response()->json(['itemsList' => $data]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getUserByUserRoles($orderStatusId)
	{

		$data = DB::table('users')
			->join('role_user', 'users.id', '=', 'role_user.user_id')
			->join('order_status', 'order_status.role_id', '=', 'role_user.role_id')
			->select('users.id as id', 'users.name as name')
			->where('order_status.order_status_id', '=', $orderStatusId)
			->where('users.status', '=', '1')
			->where('users.is_sales_person', '=', '0')
			->get();

		return response()->json(['itemsList' => $data]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getUserByUserRoleNames(Request $request)
	{

		$formData = $data = array();

		//Saving record in orders table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the Serialze Dta
			parse_str($request->formData, $formData);

			if (!empty($formData)) {
				$dataObj = DB::table('users')
					->join('users_department_detail', 'users_department_detail.user_id', '=', 'users.id')
					->join('role_user', 'users.id', '=', 'role_user.user_id')
					->join('order_status', 'order_status.role_id', '=', 'role_user.role_id')
					->select('users.id as id', 'users.name as name')
					->where('order_status.order_status_id', '=', $formData['role_id'])
					->where('users.status', '=', '1')
					->where('users.is_sales_person', '=', '0');
				if (!empty($formData['division_id']) && is_numeric($formData['division_id'])) {
					$dataObj->where('users.division_id', $formData['division_id']);
				}
				if (!empty($formData['product_category_id']) && is_numeric($formData['product_category_id'])) {
					$linkedData = DB::table('department_product_categories_link')->where('department_product_categories_link.product_category_id', '=', $formData['product_category_id'])->first();
					$dataObj->where('users_department_detail.department_id', $linkedData->department_id);
				}
				$data = $dataObj->groupBy('users.id')->orderBy('users.name', 'ASC')->get();
			}
		}

		return response()->json(['itemsList' => $data]);
	}

	/*10-11-2017*/
	//Get list of Test Parameter code using department id and paramenter category id
	public function getCustomerWiseParametersCategoryList(Request $request, $product_cat_id)
	{

		global $models;

		$error = '0';
		$message = config('messages.message.parameterCategoryNotFound');

		$testParameterList = DB::table('test_parameter_categories')
			->where('test_parameter_categories.product_category_id', $product_cat_id)
			->where('test_parameter_categories.status', 1)
			->select('test_parameter_categories.test_para_cat_id as id', 'test_parameter_categories.test_para_cat_name as name')
			->orderBy('test_para_cat_id', 'ASC')
			->get()
			->toArray();

		$error = !empty($testParameterList) ? '1' : '0';
		$message = !empty($testParameterList) ? '' : $message;

		return json_encode(['error' => $error, 'message' => $message, 'testParameterList' => $testParameterList]);
	}

	/*19-02-2018*/
	//Get list of country on the at adding new customer
	public function getAllCountriesList()
	{
		$countryCodeList = DB::table('countries_db')->select('countries_db.country_id as id', 'countries_db.country_name as name')->where('countries_db.country_status', '1')->orderBy('countries_db.country_name', 'ASC')->get();
		return json_encode($countryCodeList);
	}

	/**
	 * Get list of states on change country.
	 * Date : 21-02-18
	 * Author : pratyush
	 **/
	public function countryStatesList($country_id)
	{

		$countryStatesList = DB::table('state_db')
			->select('state_id as id', 'state_name as name')
			->where('state_db.country_id', '=', $country_id)
			->where('state_db.state_status', '1')
			->orderBy('state_db.state_name', 'ASC')
			->get();

		return response()->json(['countryStatesList' => $countryStatesList]);
	}

	/**********************************
	 *
	 *Function:To get all Parent Category of level O
	 *Created By : Praveen Singh
	 *Created On:28-Feb-2018
	 *
	 ****************************/
	public function getUserParentProductCategory()
	{

		$user_id         = defined('USERID') ? USERID : '0';
		$department_ids  = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids        = defined('ROLE_IDS') ? ROLE_IDS : '0';

		$dataObj = DB::table('product_categories')->select('p_category_id as id', 'p_category_name as name')->where('product_categories.level', '0');
		if (!empty($department_ids) && is_array($department_ids)) {
			$dataObj->whereIn('product_categories.p_category_id', $department_ids);
		}
		$data = $dataObj->orderBy('product_categories.p_category_id', 'ASC')->get();

		return response()->json(['parentCategoryList' => $data]);
	}

	/***************************************************
	 *Function:To get user assigned Roles
	 *Created By : Praveen Singh
	 *Created On:01-March-2018
	 ***************************************************/
	public function getAllUserRolesList()
	{

		$user_id         = defined('USERID') ? USERID : '0';
		$department_ids  = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids        = defined('ROLE_IDS') ? ROLE_IDS : '0';

		$userRoleList = DB::table('roles')->select('id', 'name')->whereIn('roles.id', $role_ids)->get();

		return response()->json(['userRoleList' => $userRoleList]);
	}

	/***************************************************
	 *Function:To get user assigned Roles
	 *Created By : Praveen Singh
	 *Created On:01-March-2018
	 ***************************************************/
	public function refreshSRInvoicingStructure(Request $request)
	{

		$error     = '0';
		$message   = config('messages.message.error');
		$data      = '';
		$formData  = array();

		//Parsing the Serialze Dta
		parse_str($request->formData, $formData);

		$currentInvoicingStructure = DB::table('samples')
			->join('customer_master', 'customer_master.customer_id', 'samples.customer_id')
			->join('divisions', 'divisions.division_id', 'samples.division_id')
			->join('customer_defined_structures', 'customer_defined_structures.customer_id', 'samples.customer_id')
			->join('customer_invoicing_types', 'customer_invoicing_types.invoicing_type_id', 'customer_defined_structures.invoicing_type_id')
			->select('customer_defined_structures.invoicing_type_id', 'customer_invoicing_types.invoicing_type')
			->whereColumn('customer_defined_structures.division_id', '=', 'samples.division_id')
			->whereColumn('customer_defined_structures.product_category_id', '=', 'samples.product_category_id')
			->where('samples.sample_id', !empty($formData['sample_id']) ? $formData['sample_id'] : '0')
			->first();

		$error = !empty($currentInvoicingStructure) ? '1' : '0';

		return response()->json(['error' => $error, 'currentInvoicingStructure' => $currentInvoicingStructure]);
	}

	//Get list of test parameter categories code
	public function getProductCategoryLevelOne($parent_id)
	{
		$productCategories = DB::table('product_categories')
			->select('product_categories.p_category_id as id', 'product_categories.p_category_name as name')
			->where('product_categories.parent_id', '=', $parent_id)
			->orderBy('product_categories.p_category_id', 'ASC')
			->get();
		return response()->json(['productCategoryLevelOneList' => $productCategories]);
	}

	/**
	 * Display single customer products rate .
	 * Praveen Singh
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getTestParametersAccToCategory($parameter_name, $parameter_category_id)
	{

		$testParametersList = DB::table('test_parameter')
			->join('test_parameter_invoicing_parents', 'test_parameter_invoicing_parents.test_parameter_id', 'test_parameter.test_parameter_id')
			->select('test_parameter.test_parameter_id as id', 'test_parameter.test_parameter_name as name')
			->where('test_parameter.test_parameter_category_id', '=', $parameter_category_id)
			->where('test_parameter.test_parameter_name', 'LIKE', '%' . strtolower($parameter_name) . '%')
			->orderBy('test_parameter.test_parameter_name', 'ASC')
			->limit(100)
			->get();

		return response()->json(array('testParametersList' => $testParametersList));
	}

	/**
	 * Display single customer products rate .
	 * Praveen Singh
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getTestParameterInvoicingParents()
	{

		$testParameterInvoicingParentList = DB::table('test_parameter_invoicing_parents')
			->join('test_parameter', 'test_parameter.test_parameter_id', 'test_parameter_invoicing_parents.test_parameter_id')
			->select('test_parameter_invoicing_parents.tpip_id as id', 'test_parameter.test_parameter_name as name')
			->get();

		foreach ($testParameterInvoicingParentList as $value) {
			$value->name = strip_tags($value->name);
		}

		return response()->json(array('testParameterInvoicingParentList' => $testParameterInvoicingParentList));
	}

	/**
	 * Display single customer products rate .
	 * Praveen Singh
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getEquipmentAccToCustomerWAParameterList($test_parameter_id)
	{
		$parameterEquipmentObj = DB::table('test_parameter_equipment_types')
			->join('equipment_type', 'test_parameter_equipment_types.equipment_type_id', 'equipment_type.equipment_id')
			->select('equipment_type.equipment_id as id', 'equipment_type.equipment_name as name');
		if (!empty($test_parameter_id) && is_numeric($test_parameter_id)) {
			$parameterEquipmentObj->where('test_parameter_equipment_types.test_parameter_id', $test_parameter_id);
		}
		$parameterEquipmentObj->where('equipment_type.status', 1);
		$parameterEquipmentObj->groupBy('test_parameter_equipment_types.equipment_type_id');
		$parameterEquipmentObj->orderBy('equipment_type.equipment_name', 'ASC');
		$parameterEquipmentList = $parameterEquipmentObj->get();

		return response()->json(['parameterEquipmentList' => $parameterEquipmentList]);
	}

	/**
	 * Display single customer products rate .
	 * Praveen Singh
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getDetectorAccToEquipment($equipment_type_id, $product_category_id)
	{
		$detectorList = DB::table('detector_master')
			->select('detector_master.detector_id as id', 'detector_master.detector_name as name')
			->where('detector_master.equipment_type_id', $equipment_type_id)
			->where('detector_master.product_category_id', $product_category_id)
			->orderBy('detector_master.detector_name', 'ASC')
			->get();
		return response()->json(['detectorList' => $detectorList]);
	}

	/**
	 * Display single customer products rate .
	 * Praveen Singh
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getRunningTimeList()
	{
		$runningTimeList = DB::table('customer_invoicing_running_time')->select('customer_invoicing_running_time.invoicing_running_time_id as id', 'customer_invoicing_running_time.invoicing_running_time_key as name')->get();
		return json_encode(['runningTimeList' => $runningTimeList]);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 02-04-2018
	 * Author : Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getSampleStatus()
	{
		$sampleStatus = DB::table('sample_status_default')->select('sample_status_default.sample_status_id as id', 'sample_status_default.sample_status_name as name')->where('sample_status_default.sample_status', '1')->orderBy('sample_status_default.sample_status_id', 'ASC')->get();
		return response()->json(['sampleStatusList' => $sampleStatus]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function defaultOrderStagePhases()
	{
		$data = DB::table('order_status')
			->join('roles', 'roles.id', '=', 'order_status.role_id')
			->select('order_status.order_status_id as id', 'order_status.order_status_name as name')
			->where('order_status.status', '1')
			->whereNotNull('order_status.order_status_name')
			->get();

		return response()->json(['defaultOrderStagePhaseList' => $data]);
	}

	/*****************************************
	 *22-05-2018
	 *function to get template type list
	 *
	 *****************************************/
	public function getTemplateTypeList()
	{
		$templateTypeList = DB::table('template_types')->select('template_types.template_type_id as id', 'template_types.template_type_name as name')->get();
		return response()->json(['templateTypeList' => $templateTypeList]);
	}

	/******************************************
	 *21-06-2018
	 *function to get template type list
	 *
	 *************************************/
	public function getCancellationTypeList()
	{
		$cancellationTypeList = DB::table('order_cancellation_types')->select('order_cancellation_types.order_cancellation_type_id as id', 'order_cancellation_types.order_cancellation_type_name as name')->get();
		return response()->json(['cancellationTypeList' => $cancellationTypeList]);
	}

	/******************************************
	 *02-08-2018
	 *function to Get list of customer billing types list
	 *
	 *************************************/
	public function getCustomerbillingTypes(Request $request)
	{

		$orderData = DB::table('invoice_hdr_detail')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->where('invoice_hdr_detail.invoice_hdr_status', '1')
			->select('order_master.billing_type_id')
			->where('invoice_hdr_detail.order_id', '=', !empty($request->order_id) ? $request->order_id : '0')
			->first();

		$billingTypeObj = DB::table('customer_billing_types')->select('customer_billing_types.billing_type_id as id', 'customer_billing_types.billing_type as name')->where('customer_billing_types.billing_status', '1');

		if (!empty($orderData->billing_type_id)) {
			$billingTypeObj->where('customer_billing_types.billing_type_id', $orderData->billing_type_id);
		}

		$billingTypes = $billingTypeObj->orderBy('customer_billing_types.billing_type_id', 'ASC')->get();

		return response()->json(['billingTypes' => $billingTypes]);
	}

	/******************************************
	 *02-08-2018
	 *function to Get list of customer Invoicing types list
	 *
	 *************************************/
	public function getCustomerInvoicingTypes(Request $request)
	{

		$orderData = DB::table('invoice_hdr_detail')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->where('invoice_hdr_detail.invoice_hdr_status', '1')
			->select('order_master.invoicing_type_id')
			->where('invoice_hdr_detail.order_id', '=', !empty($request->order_id) ? $request->order_id : '0')
			->first();

		$invoicingTypeObj = DB::table('customer_invoicing_types')->select('customer_invoicing_types.invoicing_type_id as id', 'customer_invoicing_types.invoicing_type as name');

		if (!empty($orderData->invoicing_type_id)) {
			$invoicingTypeObj->where('customer_invoicing_types.invoicing_type_id', $orderData->invoicing_type_id);
		}

		$invoicingTypes = $invoicingTypeObj->orderBy('customer_invoicing_types.invoicing_type_id', 'ASC')->get();

		return response()->json(['invoicingTypes' => $invoicingTypes]);
	}

	/******************************************
	 *02-08-2018
	 *function to Get list of customer Discount types list
	 *
	 *************************************/
	public function getCustomerDiscountTypes(Request $request)
	{

		$orderData = DB::table('invoice_hdr_detail')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->where('invoice_hdr_detail.invoice_hdr_status', '1')
			->select('order_master.discount_type_id')
			->where('invoice_hdr_detail.order_id', '=', !empty($request->order_id) ? $request->order_id : '0')
			->first();

		$discountTypeObj = DB::table('customer_discount_types')->select('customer_discount_types.discount_type_id as id', 'customer_discount_types.discount_type as name');

		if (!empty($orderData->discount_type_id)) {
			$discountTypeObj->where('customer_discount_types.discount_type_id', $orderData->discount_type_id);
		}

		$discountTypes = $discountTypeObj->orderBy('customer_discount_types.discount_type_id', 'ASC')->get();

		return response()->json(['discountTypes' => $discountTypes]);
	}

	/**********************************************
	 *Function    : Getting Sealed/Unsealed Dropdown List
	 *Created By  : Praveen Singh
	 *Created On  : 28-Dec-2018
	 *Modified On : -
	 **********************************************/
	public function getSealedUnsealedList()
	{
		$sealedUnsealedData = DB::table('order_sealed_unsealed')->select('order_sealed_unsealed.osus_id as id', 'order_sealed_unsealed.osus_name as name')->where('order_sealed_unsealed.osus_status', '1')->get();
		return response()->json(['sealedUnsealedData' => $sealedUnsealedData]);
	}

	/**********************************************
	 *Function    : Getting Sealed/Unsealed Dropdown List
	 *Created By  : Praveen Singh
	 *Created On  : 28-Dec-2018
	 *Modified On : -
	 **********************************************/
	public function getSignedUnsignedList()
	{
		$signedUnsignedData = DB::table('order_signed_unsigned')->select('order_signed_unsigned.osu_id as id', 'order_signed_unsigned.osu_name as name')->where('order_signed_unsigned.osu_status', '1')->get();
		return response()->json(['signedUnsignedData' => $signedUnsignedData]);
	}

	/******************************************
	 *03-01-2019
	 *function to Get list of customer billing types list
	 *Praveen Singh
	 *************************************/
	public function getGlobalCustomerbillingTypes(Request $request)
	{
		$billingTypeObj = DB::table('customer_billing_types')->select('customer_billing_types.billing_type_id as id', 'customer_billing_types.billing_type as name')->where('customer_billing_types.billing_status', '1');
		$billingTypes = $billingTypeObj->orderBy('customer_billing_types.billing_type_id', 'ASC')->get();
		return response()->json(['billingTypes' => $billingTypes]);
	}

	/******************************************
	 *03-01-2019
	 *function to Get list of customer Invoicing types list
	 *Praveen Singh
	 *************************************/
	public function getGlobalCustomerInvoicingTypes(Request $request)
	{
		$invoicingTypeObj = DB::table('customer_invoicing_types')->select('customer_invoicing_types.invoicing_type_id as id', 'customer_invoicing_types.invoicing_type as name');
		$invoicingTypes = $invoicingTypeObj->orderBy('customer_invoicing_types.invoicing_type_id', 'ASC')->get();
		return response()->json(['invoicingTypes' => $invoicingTypes]);
	}

	/******************************************
	 *03-01-2019
	 *function to Get list of customer Discount types list
	 *Praveen Singh
	 *************************************/
	public function getGlobalCustomerDiscountTypes(Request $request)
	{
		$discountTypeObj = DB::table('customer_discount_types')->select('customer_discount_types.discount_type_id as id', 'customer_discount_types.discount_type as name');
		$discountTypes = $discountTypeObj->orderBy('customer_discount_types.discount_type_id', 'ASC')->get();
		return response()->json(['discountTypes' => $discountTypes]);
	}

	/**********************************************
	 *Function    : Getting Stability Condition Dropdown List
	 *Created By  : Praveen Singh
	 *Created On  : 04-Jan-2019
	 *Modified On : -
	 **********************************************/
	public function getStabilityConditionMaster()
	{
		$stabilityConditionData = DB::table('stb_order_stability_types')->select('stb_order_stability_types.stb_stability_type_id as id', 'stb_order_stability_types.stb_stability_type_name as name')->where('stb_order_stability_types.stb_stability_type_status', '1')->get();
		return response()->json(['stabilityConditionData' => $stabilityConditionData]);
	}

	/**********************************************
	 *Function    : Getting Country state  List
	 *Created By  : RUBY
	 *Created On  : 23-Jan-2019
	 *Modified On : -
	 **********************************************/
	public function getCountryStateLocationTree()
	{
		global $state;
		return response()->json(['countryStateTreeViewList' => $state->countryTreeView()]);
	}

	/**********************************************
	 *Function    : Getting Customer GST Categories Dropdown List
	 *Created By  : Praveen Singh
	 *Created On  : 01-May-2019
	 *Modified On : 01-May-2019
	 **********************************************/
	public function getCustomerGstCategories()
	{
		$customerGstCategoriesList = DB::table('customer_gst_categories')->select('customer_gst_categories.cgc_id as id', 'customer_gst_categories.cgc_name as name')->where('customer_gst_categories.cgc_status', '1')->get()->toArray();
		return response()->json(['customerGstCategoriesList' => $customerGstCategoriesList]);
	}

	/**********************************************
	 *Function    : Getting Customer GST Types Dropdown List
	 *Created By  : Praveen Singh
	 *Created On  : 01-May-2019
	 *Modified On : 01-May-2019
	 **********************************************/
	public function getCustomerGstTypes()
	{
		$customerGstTypesList = DB::table('customer_gst_types')->select('customer_gst_types.cgt_id as id', 'customer_gst_types.cgt_name as name')->where('customer_gst_types.cgt_status', '1')->get()->toArray();
		return response()->json(['customerGstTypesList' => $customerGstTypesList]);
	}

	/**********************************************
	 *Function    : Getting Customer GST Tax Slab Types Dropdown List
	 *Created By  : Praveen Singh
	 *Created On  : 01-May-2019
	 *Modified On : 01-May-2019
	 **********************************************/
	public function getCustomerGstTaxSlabTypes()
	{
		$customerGstTaxSlabTypesList = DB::table('customer_gst_tax_slab_types')->select('customer_gst_tax_slab_types.cgtst_id as id', 'customer_gst_tax_slab_types.cgtst_name as name')->where('customer_gst_tax_slab_types.cgtst_status', '1')->get()->toArray();
		return response()->json(['customerGstTaxSlabTypesList' => $customerGstTaxSlabTypesList]);
	}

	/**********************************************
	 *Function    : Getting TRF No. Dropdown List
	 *Created By  : Praveen Singh
	 *Created On  : 22-May-2019
	 *Modified On : 22-May-2019
	 **********************************************/
	public function getTrfNumberList(Request $request)
	{
		global $models;

		//Checking Back Date Booking Department Wise
		$showOrderDateCalender = $models->hasBackDateBookingEnabledInDepartment(!empty($request->product_category_id) ? trim($request->product_category_id) : '0');

		$trfSelectBoxList = DB::table('trf_hdrs')
			->select('trf_hdrs.trf_id as id', 'trf_hdrs.trf_no as name')
			->where('trf_hdrs.trf_division_id', !empty($request->division_id) ? trim($request->division_id) : '0')
			->where('trf_hdrs.trf_product_category_id', !empty($request->product_category_id) ? trim($request->product_category_id) : '0')
			->where('trf_hdrs.trf_status', '0')
			->where('trf_hdrs.trf_active_deactive_status', '1')
			->get()
			->toArray();

		return response()->json(['trfSelectBoxList' => $trfSelectBoxList, 'showOrderDateCalender' => $showOrderDateCalender]);
	}

	/**
	 * generate MIS Report
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getMISReportTypesDtl()
	{

		$reportFormType = DB::table('mis_report_default_types')
			->select('mis_report_default_types.mis_report_id as id', 'mis_report_default_types.mis_report_name as name')
			->where('mis_report_default_types.mis_report_status', '1')
			->orderBy('mis_report_default_types.mis_report_order_by', 'ASC')
			->get()
			->toArray();

		return response()->json(['reportFormType' => $reportFormType]);
	}

	/***********************************
	 * Getting list of Employees
	 * Date : 28-Aug-2019
	 * Author : Praveen Singh
	 ************************************/
	public function getEmployeeListBasedOnBranch($division_id)
	{
		$employeeListData = DB::table('users')->select('users.id as id', 'users.name as name')->where('users.division_id', '=', $division_id)->where('users.status', '=', '1')->orderBy('users.name', 'ASC')->get()->toArray();
		return response()->json(['employeeListData' => $employeeListData]);
	}

	/***********************************
	 * Getting list of Employees
	 * Date : 09-Nov-2019
	 * Author : Praveen Singh
	 ************************************/
	public function getDisciplineDropdownList()
	{
		$disciplineList = DB::table('order_report_disciplines')->select('order_report_disciplines.or_discipline_id as id', 'order_report_disciplines.or_discipline_name as name')->where('order_report_disciplines.or_discipline_status', '=', '1')->orderBy('order_report_disciplines.or_discipline_name', 'ASC')->get()->toArray();
		return response()->json(['disciplineList' => $disciplineList]);
	}

	/***********************************
	 * Getting list of test standard
	 * Date : 06-April-2020
	 * Author : Ruby
	 ************************************/
	public function getTestStandardList($p_category_id)
	{
		$testStdList = DB::table('test_standard')->select('test_standard.test_std_id as id', 'test_standard.test_std_name as name')->where('test_standard.product_category_id', '=', $p_category_id)->where('test_standard.status', '1')->orderBy('test_standard.test_std_name', 'ASC')->get()->toArray();
		return response()->json(['testStdList' => $testStdList]);
	}

	/***********************************
	 * Getting list of test standard
	 * Date : 07-April-2020
	 * Author : Ruby
	 ************************************/
	public function getdefinedTestStandardList($b_id, $dept_id)
	{
		$definedTestStdList = DB::table('order_defined_test_std_dtl')
			->join('test_standard', 'test_standard.test_std_id', 'order_defined_test_std_dtl.odtsd_test_standard_id')
			->where('order_defined_test_std_dtl.odtsd_branch_id', $b_id)
			->where('order_defined_test_std_dtl.odtsd_product_category_id', $dept_id)
			->select('test_standard.test_std_id as id', 'test_standard.test_std_name as name')
			->orderBy('test_standard.test_std_name', 'ASC')
			->get()
			->toArray();
		return response()->json(['definedTestStdList' => $definedTestStdList]);
	}

	/***********************************
	 * Getting list of Customer based on selected SE/Employee
	 * Date : 09-April-20
	 * Author : Ruby
	 ************************************/
	public function getCustomerListBasedOnSalesExecutive($executive_id)
	{
		$customerListData = DB::table('customer_master')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->select('customer_master.customer_id as id', DB::raw("CONCAT(customer_master.customer_name,'/',city_db.city_name," / ",customer_master.customer_code) as name"))
			->where('customer_master.sale_executive', '=', $executive_id)
			->orderBy('customer_master.customer_name', 'ASC')
			->get()
			->toArray();
		return response()->json(['customerListData' => $customerListData]);
	}

	/***********************************
	 * Getting list of User Sales Target Types
	 * Date : 23-Nov-2020
	 * Author : Praveen Singh
	 ************************************/
	public function getUserSalesTargetTypes()
	{
		$data = DB::table('user_sales_target_types')->select('user_sales_target_types.usty_id as id', 'user_sales_target_types.usty_name as name')->where('user_sales_target_types.usty_status', '1')->orderBy('user_sales_target_types.usty_name', 'ASC')->get()->toArray();
		return response()->json(['userSalesTargetTypeList' => $data]);
	}

	/***********************************
	 * Getting list of Dynamic Fields
	 * Date : 16 Feb, 2021
	 * Author : Anjana
	 ************************************/
	public function getDynamicFields()
	{
		$dynamicFieldsList = DB::table('order_dynamic_fields')->select('odfs_id as id', 'dynamic_field_name as name')->orderBy('order_dynamic_fields.odfs_id', 'ASC')->get()->toArray();
		return response()->json(['dynamicFieldsList' => $dynamicFieldsList]);
	}

	/***********************************
	 * Getting list of Dynamic Fields
	 * Date : 19 June, 2021
	 * Author : Ruby
	 ************************************/
	public function reportHdrDefaultTypes()
	{
		$reportHdrType = DB::table('report_header_type_default')->select('rhtd_id as id', 'rhtd_name as name')->get()->toArray();
		return response()->json(['reportHdrType' => $reportHdrType]);
	}

	/***********************************
	 * Getting Sampler Dropdown List
	 * Date : 01-July-2022
	 * Author : Praveen Singh
	 ************************************/
	public function getSamplerDropdownList($division_id)
	{
		$samplerDropdownList = DB::table('users')->where('users.status', '=', '1')->select('users.id', 'users.name')->where('users.division_id', '=', $division_id)->where('users.is_sales_person', '=', '0')->where('users.is_sampler_person', '=', '1')->get()->toArray();
		return response()->json(['samplerDropdownList' => $samplerDropdownList]);
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Models;
use App\User;
use Validator;
use Route;
use App\FileUpload;
use DB;

class EmployeeController extends Controller
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

		global $models, $user;

		$models = new Models();
		$user   = new User();
		$fileUpload = new FileUpload();

		//Checking Session
		$this->middleware('auth');

		$this->middleware(function ($request, $next) {
			$this->session = Auth::user();
			parent::__construct($this->session);
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

		global $models, $user;

		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('master.employee.index', ['title' => 'Employees', '_employee' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * Get list of employees on page load.
	 * Date : 29-12-17
	 * Author : Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getBranchWiseEmployeeList(Request $request)
	{

		global $models, $user;

		$error        = '0';
		$message      = config('messages.message.error');
		$data         = '';
		$employeeList = $formData = array();

		//Assigning Condition according to the Role Assigned
		parse_str($request->formData, $formData);

		$getBanchWiseEmployeeObj = DB::table('users')
			->join('divisions', 'users.division_id', '=', 'divisions.division_id')
			->leftJoin('users_department_detail', 'users_department_detail.user_id', '=', 'users.id')
			->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
			->leftJoin('users_equipment_detail', 'users_equipment_detail.user_id', 'users.id')
			->join('users as u', 'users.created_by', '=', 'u.id')
			->select('users.*', 'u.name as createdBy', 'users.created_at as created_at', 'users.updated_at as updated_at', 'divisions.division_name');

		//Filtering oif Data based on Search Condition
		$this->setEmployeeListMultiSearch($getBanchWiseEmployeeObj, $formData);
		$employeeBanchWiseList = $getBanchWiseEmployeeObj->groupBy('users.id')->orderBy('users.id', 'DESC')->get();

		//formating date of created and updated
		$models->formatTimeStampFromArray($employeeBanchWiseList, DATETIMEFORMAT);

		if (!empty($employeeBanchWiseList)) {

			$error   = '1';
			$message = '';

			foreach ($employeeBanchWiseList as $key => $user) {
				$employeeList[$key]['userData'] 	 = $user;
				$employeeList[$key]['departments'] 	 = DB::table('users_department_detail')->join('departments', 'users_department_detail.department_id', '=', 'departments.department_id')->select('users_department_detail.user_id', 'users_department_detail.department_id', 'departments.department_name')->where('users_department_detail.user_id', '=', $user->id)->get()->toArray();
				$employeeList[$key]['roles'] 		 = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('role_user.user_id', 'role_user.role_id', 'roles.name')->where('role_user.user_id', '=', $user->id)->get()->toArray();
				$employeeList[$key]['equipmentType'] = DB::table('users_equipment_detail')->join('equipment_type', 'users_equipment_detail.equipment_type_id', '=', 'equipment_type.equipment_id')->select('users_equipment_detail.user_id', 'users_equipment_detail.equipment_type_id', 'equipment_type.equipment_name')->where('users_equipment_detail.user_id', '=', $user->id)->where('equipment_type.status', '=', 1)->get()->toArray();
			}
		}

		return response()->json(['error' => $error, 'message' => $message, 'employeeList' => $employeeList]);
	}

	/**
	 * Get list of employees using multisearch.
	 * Date : 18-04-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function setEmployeeListMultiSearch($getBanchWiseEmployeeObj, $formData)
	{

		global $models, $user;

		$user_id            	= defined('USERID') ? USERID : '0';
		$department_ids     	= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_id           		= defined('ROLEID') ? ROLEID : '0';
		$role_ids           	= defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids 	= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		//Filtering records according to keyword assigned
		if (!empty($formData['search_keyword'])) {
			$keyword = trim($formData['search_keyword']);
			$getBanchWiseEmployeeObj->where(function ($getBanchWiseEmployeeObj) use ($keyword) {
				$getBanchWiseEmployeeObj->where('users.name', 'LIKE', '%' . $keyword . '%')->orwhere('users.email', 'LIKE', '%' . $keyword . '%');
			});
		}
		//Filtering records according to user_code assigned
		if (!empty($formData['search_user_code'])) {
			$getBanchWiseEmployeeObj->where('users.user_code', 'like', '%' . $formData['search_user_code'] . '%');
		}
		//Filtering records according to name assigned
		if (!empty($formData['search_name'])) {
			$getBanchWiseEmployeeObj->where('users.name', 'like', '%' . $formData['search_name'] . '%');
		}
		//Filtering records according to division assigned
		if (!empty($formData['search_division']) && is_numeric($formData['search_division'])) {
			$getBanchWiseEmployeeObj->where('users.division_id', $formData['search_division']);
		}
		//Filtering records according to department assigned
		if (!empty($formData['search_department']) && is_numeric($formData['search_department'])) {
			$getBanchWiseEmployeeObj->where('users_department_detail.department_id', $formData['search_department']);
		}
		//Filtering records according to roles assigned
		if (!empty($formData['search_role']) && is_numeric($formData['search_role'])) {
			$getBanchWiseEmployeeObj->where('role_user.role_id', $formData['search_role']);
		}
		if (!empty($formData['search_equipment'])) {
			$getBanchWiseEmployeeObj->where('users_equipment_detail.equipment_type_id', '=', $formData['search_equipment']);
		}
		if (!empty($formData['search_email'])) {
			$getBanchWiseEmployeeObj->where('users.email', 'like', '%' . $formData['search_email'] . '%');
		}
		if (!empty($formData['search_company_name'])) {
			$getBanchWiseEmployeeObj->where('company_master.company_name', 'like', '%' . $formData['search_company_name'] . '%');
		}
		if (!empty($formData['search_is_sales_person'])) {
			if (trim(strtolower($formData['search_is_sales_person'])) == 'yes') {
				$getBanchWiseEmployeeObj->where('users.is_sales_person', '=', 1);
			} else if (trim(strtolower($formData['search_is_sales_person'])) == 'no') {
				$getBanchWiseEmployeeObj->where('users.is_sales_person', '=', 0);
			}
		}
		if (!empty($formData['search_is_sampler_person'])) {
			if (trim(strtolower($formData['search_is_sampler_person'])) == 'yes') {
				$getBanchWiseEmployeeObj->where('users.is_sampler_person', '=', 1);
			} else if (trim(strtolower($formData['search_is_sampler_person'])) == 'no') {
				$getBanchWiseEmployeeObj->where('users.is_sampler_person', '=', 0);
			}
		}
		if (!empty($formData['search_status'])) {
			$getBanchWiseEmployeeObj->where('users.status', $formData['search_status']);
		}
		if (!empty($formData['search_is_assign_job'])) {
			if (trim(strtolower($formData['search_is_assign_job'])) == 'yes') {
				$getBanchWiseEmployeeObj->where('users.is_assign_job', '=', 1);
			} else if (strtolower($formData['search_is_assign_job']) == 'no') {
				$getBanchWiseEmployeeObj->where('users.is_assign_job', '=', Null)->orwhere('users.is_assign_job', '=', 0);
			}
		}
		if (!empty($formData['search_created_by'])) {
			$getBanchWiseEmployeeObj->where('u.name', 'like', '%' . $formData['search_created_by'] . '%');
		}
	}

	/**
	 * Add a new employee to database
	 * Date : 29-12-17
	 * Author : Praveen Singh
	 * @return \Illuminate\Http\Response
	 */
	public function createEmployee(Request $request)
	{

		global $models, $user, $image;

		$error      = '0';
		$message    = config('messages.message.error');
		$data       = '';
		$customerId = '0';
		$formData   = array();
		$currentDate     = !defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date(DATEFORMAT);
		$currentDateTime = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date(MYSQLDATETIMEFORMAT);

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//parsing String
			parse_str($request->formData, $formData);
			$formData = array_filter($formData);

			if (empty($formData['division_id'])) {
				$message = config('messages.message.divisionNameRequired');
			} else if (empty($formData['name'])) {
				$message = config('messages.message.employeeNameRequired');
			} else if (empty($formData['email'])) {
				$message = config('messages.message.employeeEmailRequired');
			} else if ($user->isEmployeeExist($formData['email'])) {
				$message = config('messages.message.employeeAlreadyExist');
			} else if (empty($formData['password'])) {
				$message = config('messages.message.passwordRequired');
			} else if ((!isset($formData['is_sales_person']) && !isset($formData['is_sampler_person'])) && empty($formData['department_id'])) {
				$message = config('messages.message.departmentRequired');
			} else if ((!isset($formData['is_sales_person']) && !isset($formData['is_sampler_person'])) && empty($formData['role_id'])) {
				$message = config('messages.message.roleRequired');
			} else if ((!isset($formData['is_sales_person']) && !isset($formData['is_sampler_person'])) && in_array('6', $formData['role_id']) && empty($formData['equipment_type_id'])) {
				$message = config('messages.message.equipmentTypeRequired');
			} else {

				//Starting transaction
				DB::beginTransaction();

				//Error Msg if employee has not been created.
				$message = config('messages.message.savedError');

				//department,roles,equipment Type data
				$requestData = [
					'department_id' 	=> !empty($formData['department_id']) ? array_values($formData['department_id']) : array(),
					'role_id' 			=> !empty($formData['role_id']) ? array_values($formData['role_id']) : array(),
					'equipment_type_id' => !empty($formData['equipment_type_id']) ? array_values($formData['equipment_type_id']) : array(),
					'division_id' 		=> !empty($formData['division_id']) ? trim($formData['division_id']) : '0',
				];

				//Users Custom Permission
				$permissionData = [
					'custom_permission' => !empty($formData['custom_permission']) ? $formData['custom_permission'] : array()
				];

				//Unsetting the variable from request data
				$formData 		     		 	= $models->unsetFormDataVariables($formData, array('_token', 'custom_permission', 'password_confirmation', 'department_id', 'role_id', 'equipment_type_id'));
				$employeeName 		     	 	= strtolower($formData['name']);
				$formData['user_code']       	= $models->generateCode(ucwords($formData['name']), 'users', 'user_code', 'id');
				$formData['name'] 	     	 	= ucwords($employeeName);
				$formData['is_sales_person'] 	= isset($formData['is_sales_person']) ? '1' : '0';
				$formData['is_sampler_person'] 	= isset($formData['is_sampler_person']) ? '1' : '0';
				$formData['password']        	= bcrypt($formData['password']);
				$formData['created_by']      	= USERID;
				if ($formData['status'] == '1') {
					$formData['activated_at']   = $currentDateTime;
					$formData['deactivated_at'] = NULL;
				} else {
					$formData['activated_at']   = NULL;
					$formData['deactivated_at'] = $currentDateTime;
				}

				if (!empty($formData)) {
					$userId = DB::table('users')->insertGetId($formData);
					if (!empty($userId)) {

						//Save deparment
						$flagDeparment = empty($formData['is_sales_person']) && !empty($requestData['department_id']) ? $this->save_update_department_detail($requestData, $userId) : true;

						//Save roles		
						$flagRole = empty($formData['is_sales_person']) && !empty($requestData['role_id']) ? $this->save_update_roles_detail($requestData, $userId) : true;

						//Save Equipment
						$flagEquipment = empty($formData['is_sales_person']) && !empty($requestData['equipment_type_id']) ? $this->save_update_equipment_type_detail($requestData, $userId) : true;

						//Save Users Custom Permission
						$flagCustomPermission = empty($formData['is_sales_person']) && !empty($permissionData['custom_permission']) ? $this->save_custom_permissions_detail($permissionData, $userId) : true;

						if ($flagDeparment && $flagRole && $flagEquipment) {

							$error   = '1';
							$data    = $userId;
							$message = config('messages.message.saved');

							//Committing the queries
							DB::commit();
						}
					}
				}
			}
		} else {
			$message = config('messages.message.dataNotFoundToSaved');
		}

		return response()->json(['error' => $error, 'message' => $message, 'data' => $data]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function editEmployeeData(Request $request)
	{

		$error      = '0';
		$message    = config('messages.message.error');
		$data       = '';
		$users      = $departmentDetail = $rolesDetail = $equipmentDetail = $customPermDetail = array();

		if ($request->isMethod('post') && !empty($request['data']['user_id'])) {
			$users = DB::table('users')->select('users.id', 'users.division_id', 'users.name', 'users.email', 'users.user_code', 'users.is_sales_person', 'users.is_sampler_person', 'users.status')->where('users.id', '=', $request['data']['user_id'])->first();
			if (!empty($users->id)) {
				$error       	  = '1';
				$message     	  = '';
				$departmentDetail = DB::table('users_department_detail')->where('user_id', '=', $users->id)->pluck('department_id')->all();
				$rolesDetail      = DB::table('role_user')->where('user_id', '=', $users->id)->pluck('role_id')->all();
				$equipmentDetail  = DB::table('users_equipment_detail')->where('user_id', '=', $users->id)->pluck('equipment_type_id')->all();
				$customPermDetail = DB::table('user_custom_permissions')->where('ucp_user_id', '=', $users->id)->pluck('ucp_permission_key')->all();
			} else {
				$message = config('messages.message.employeeNotFound');
			}
		} else {
			$message = config('messages.message.inappropriateEmoloyeeData');
		}

		//echo'<pre>'; print_R($customPermDetail);die;
		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'userData' => $users, 'deptDetail' => $departmentDetail, 'rolesDetail' => $rolesDetail, 'equipDetail' => $equipmentDetail, 'customPermDetail' => $customPermDetail]);
	}

	/**
	 * Update the specified resource in storage.
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateEmployeeData(Request $request)
	{

		global $models, $user;

		$error    	 = '0';
		$message  	 = config('messages.message.error');
		$data     	 = '';
		$userId   	 = '0';
		$formData 	 = $unsetUnRequiredColums = array();
		$currentDate     = !defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date(DATEFORMAT);
		$currentDateTime = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date(MYSQLDATETIMEFORMAT);

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//parsing String
			parse_str($request->formData, $formData);

			//User Id of current employee
			$userId = !empty($formData['user_id']) ? $formData['user_id'] : '0';

			if (empty($formData['division_id'])) {
				$message = config('messages.message.divisionNameRequired');
			} else if (empty($formData['name'])) {
				$message = config('messages.message.employeeNameRequired');
			} else if (empty($formData['email'])) {
				$message = config('messages.message.employeeEmailRequired');
			} else if ($user->isEmployeeExist($formData['email'], 'edit', $userId)) {
				$message = config('messages.message.employeeAlreadyExist');
			} else if ((!isset($formData['is_sales_person']) && !isset($formData['is_sampler_person'])) && empty($formData['department_id'])) {
				$message = config('messages.message.departmentRequired');
			} else if ((!isset($formData['is_sales_person']) && !isset($formData['is_sampler_person'])) && empty($formData['role_id'])) {
				$message = config('messages.message.roleRequired');
			} else if ((!isset($formData['is_sales_person']) && !isset($formData['is_sampler_person'])) && in_array('6', $formData['role_id']) && empty($formData['equipment_type_id'])) {
				$message = config('messages.message.equipmentTypeRequired');
			} else if ((!isset($formData['is_sales_person']) && !isset($formData['is_sampler_person'])) && empty($user->validateSalesExecutiveOnRemove($userId))) {
				$message = config('messages.message.removingSalesPerson');
			} else if ((isset($formData['is_sales_person']) || isset($formData['is_sampler_person'])) && empty($user->validateSalesExecutiveOnAdd($userId))) {
				$message = config('messages.message.addSalesPerson');
			} else {

				//Starting transaction
				DB::beginTransaction();

				//Error Msg if employee has not been uodated.
				$message = config('messages.message.updatedError');

				//department,roles,equipment Type data
				$requestData = [
					'department_id' 	=> !empty($formData['department_id']) ? array_values($formData['department_id']) : array(),
					'role_id' 			=> !empty($formData['role_id']) ? array_values($formData['role_id']) : array(),
					'equipment_type_id' => !empty($formData['equipment_type_id']) ? array_values($formData['equipment_type_id']) : array(),
					'division_id' 		=> !empty($formData['division_id']) ? trim($formData['division_id']) : '0',
				];

				//Users Custom Permission
				$permissionData = [
					'custom_permission' => !empty($formData['custom_permission']) ? $formData['custom_permission'] : array()
				];

				if (!empty($formData['password'])) {
					$formData['password'] = bcrypt($formData['password']);
					$unsetUnRequiredColums = array('_token', 'custom_permission', 'password_confirmation', 'department_id', 'role_id', 'equipment_type_id', 'user_id');
				} else {
					$unsetUnRequiredColums = array('_token', 'custom_permission', 'password_confirmation', 'department_id', 'role_id', 'equipment_type_id', 'user_id', 'password');
				}

				//Unsetting the variable from request data
				$formData 					   = $models->unsetFormDataVariables($formData, $unsetUnRequiredColums);
				$formData['is_sales_person']   = isset($formData['is_sales_person']) ? '1' : '0';
				$formData['is_sampler_person'] = isset($formData['is_sampler_person']) ? '1' : '0';

				if (!empty($userId) && !empty($formData)) {
					if ($formData['status'] == '1') {
						$formData['activated_at']    = $currentDateTime;
						$formData['deactivated_at']  = NULL;
					} else {
						$formData['activated_at']    = NULL;
						$formData['deactivated_at']  = $currentDateTime;
					}

					//Updating Employee Data
					DB::table('users')->where('id', '=', $userId)->update($formData);

					if (!empty($userId)) {

						//If Is Sales Person checkbox is checked
						$updateSatatus = !empty($formData['is_sales_person']) ? $this->update_department_roles_equipmentType_detail($userId) : true;

						//Save deparment
						$flagDeparment = empty($formData['is_sales_person']) && !empty($requestData['department_id']) ? $this->save_update_department_detail($requestData, $userId) : true;

						//Save roles		
						$flagRole = empty($formData['is_sales_person']) && !empty($requestData['role_id']) ? $this->save_update_roles_detail($requestData, $userId) : true;

						//Save Equipment
						$flagEquipment = empty($formData['is_sales_person']) && !empty($requestData['equipment_type_id']) ? $this->save_update_equipment_type_detail($requestData, $userId) : true;

						//Save Users Custom Permission
						$flagCustomPermission = empty($formData['is_sales_person']) ? $this->save_custom_permissions_detail($permissionData, $userId) : true;

						if ($flagDeparment && $flagRole && $flagEquipment) {

							$error   = '1';
							$data    = $userId;
							$message = config('messages.message.updated');

							//Committing the queries
							DB::commit();
						}
					}
				}
			}
		} else {
			$message = config('messages.message.dataNotFoundToSaved');
		}

		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'userId' => $userId]);
	}

	/**
	 * Save Custom permission for new employee to database
	 * Date : 29-12-17
	 * Author : Praveen Singh
	 * @return \Illuminate\Http\Response
	 */
	public function save_custom_permissions_detail($permissionData, $userId)
	{

		global $models, $user;

		//Deleting all acquired permission of User
		DB::table('user_custom_permissions')->where('user_custom_permissions.ucp_user_id', $userId)->delete();

		$dataSave = array();
		if (!empty($permissionData['custom_permission']) && !empty(array_filter($permissionData['custom_permission']))) {
			$counter = '0';
			foreach ($permissionData['custom_permission'] as $key => $value) {
				$dataSave[$counter]['ucp_user_id']          = $userId;
				$dataSave[$counter]['ucp_permission_key']   = str_replace("'", "", $key);
				$dataSave[$counter]['ucp_permission_value'] = $value;
				$counter++;
			}
			return !empty($dataSave) ? DB::table('user_custom_permissions')->insert($dataSave) : false;
		}
	}

	/**
	 * Add a new employee to database
	 * Date : 29-12-17
	 * Author : Praveen Singh
	 * @return \Illuminate\Http\Response
	 */
	public function save_update_department_detail($requestData, $userId)
	{

		global $models, $user;

		$dataSave = array();
		if (!empty($requestData['department_id'])) {
			DB::table('users_department_detail')->where('users_department_detail.user_id', $userId)->delete();
			foreach ($requestData['department_id'] as $key => $department_id) {
				$dataSave[$key]['user_id']       = $userId;
				$dataSave[$key]['department_id'] = $department_id;
			}
			return !empty($dataSave) ? DB::table('users_department_detail')->insert($dataSave) : false;
		}
	}

	/**
	 * Add a new employee to database
	 * Date : 29-12-17
	 * Author : Praveen Singh
	 * @return \Illuminate\Http\Response
	 */
	public function save_update_roles_detail($requestData, $userId)
	{

		global $models, $user;

		if (!empty($requestData['role_id'])) {
			DB::table('role_user')->where('role_user.user_id', $userId)->delete();
			DB::table('users_equipment_detail')->where('users_equipment_detail.user_id', $userId)->delete();
			DB::table('users')->where('users.id', $userId)->update(['users.role_id' => reset($requestData['role_id'])]);
			$saveUpdated = User::find($userId);
			$saveUpdated->attachRole($requestData['role_id']);
			return true;
		}
	}

	/**
	 * Add a new employee to database
	 * Date : 29-12-17
	 * Author : Praveen Singh
	 * @return \Illuminate\Http\Response
	 */
	public function save_update_equipment_type_detail($requestData, $userId)
	{

		global $models, $user;

		$dataSave = array();

		if (!empty($requestData['equipment_type_id'])) {
			DB::table('users_equipment_detail')->where('users_equipment_detail.user_id', $userId)->delete();
			foreach ($requestData['equipment_type_id'] as $key => $equipment_type_id) {
				$dataSave[$key]['user_id']           = $userId;
				$dataSave[$key]['equipment_type_id'] = $equipment_type_id;
			}
			return !empty($dataSave) ? DB::table('users_equipment_detail')->insert($dataSave) : false;
		}
	}

	/**
	 * Add a new employee to database
	 * Date : 29-12-17
	 * Author : Praveen Singh
	 * @return \Illuminate\Http\Response
	 */
	public function save_update_equipment_type_detail_v1($requestData, $userId)
	{

		global $models, $user;

		$dataSave = array();

		if (!empty($requestData['equipment_type_id'])) {
			DB::table('users_equipment_detail')->where('users_equipment_detail.user_id', $userId)->delete();
			if (!empty($requestData['role_id']) && in_array('7', $requestData['role_id'])) {
				$requestData['equipment_type_id'] = $user->getSectionInchargeEquipmentTypeData($requestData);
			}
			foreach ($requestData['equipment_type_id'] as $key => $equipment_type_id) {
				$dataSave[$key]['user_id']           = $userId;
				$dataSave[$key]['equipment_type_id'] = $equipment_type_id;
			}
			return !empty($dataSave) ? DB::table('users_equipment_detail')->insert($dataSave) : false;
		}
	}

	/**
	 * Add a new employee to database
	 * Date : 29-12-17
	 * Author : Praveen Singh
	 * @return \Illuminate\Http\Response
	 */
	public function update_department_roles_equipmentType_detail($userId)
	{

		global $models, $user;

		if (!empty($userId)) {
			DB::table('users_department_detail')->where('users_department_detail.user_id', $userId)->delete();
			DB::table('role_user')->where('role_user.user_id', $userId)->delete();
			DB::table('users_equipment_detail')->where('users_equipment_detail.user_id', $userId)->delete();
			return true;
		}
		return false;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteEmployeeData(Request $request)
	{
		$returnData = array();
		if ($request->isMethod('post')) {
			try {
				if (!empty($request['data']['user_id'])) {
					try {
						$users = DB::table('users')->where('id', $request['data']['user_id'])->delete();
						if ($users) {
							$returnData = array('success' => config('messages.message.employeeDeleted'));
						} else {
							$returnData = array('error' => config('messages.message.employeeNotDeleted'));
						}
					} catch (\Illuminate\Database\QueryException $ex) {
						$returnData = array('error' => "Cannot delete or update a parent row: a foreign key constraint fails!");
					}
				} else {
					$returnData = array('error' => config('messages.message.employeeNotDeleted'));
				}
			} catch (\Illuminate\Database\QueryException $ex) {
				$returnData = array('error' => config('messages.message.employeeNotDeleted'));
			}
		}
		return response()->json($returnData);
	}

	/**
	 * Get list of employees uploaded by csv
	 * Date : 06-04-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function uploadEmployee(Request $request)
	{
		return view('master.employee.upload_employees');
	}

	/**
	 * Get list of employees uploaded by csv
	 * Date : 06-04-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function uploadEmployeePreview(Request $request)
	{
		global $models;
		$allowedFormat = array('application/vnd.ms-excel', 'application/csv', 'text/csv');
		if (empty($_FILES['employee']['name'])) {
			$returnData = array('error' => config('messages.message.fileNotSelected'));
		} else if (in_array($_FILES['employee']['type'], $allowedFormat) && $_FILES['employee']['size'] > '0') {
			$header = $models->csvToArray($_FILES['employee']['tmp_name']);
			//check all required fields are filled or not			
			if (in_array('name', $header['header']) && in_array('email', $header['header']) && in_array('division_code', $header['header']) && in_array('department_code', $header['header']) && in_array('password', $header['header']) && in_array('is_sales_person', $header['header'])) {
				foreach ($header['data'] as $key => $data) {
					if (count(array_filter($data)) != count($data)) {
						$checkEmpty[$key] = 'Empty record found!';
					}
				}
				if (!empty($checkEmpty) > 0) {
					$returnData = array('error' => config('messages.message.allFieldsRequired'));
				} else {
					$csvEmailArray = array();
					$finalDataForDisplay = array();
					$finalDataForSubmit = array();
					$message = count($header['newheader']);
					$header['newheader'][$message] = 'Message';
					foreach ($header['data'] as $key => $data) {
						$employeeExist = $this->isEmployeeExist($data[1]);
						$recExist['division_id'] = $this->getDivisionId($data[2]);
						$recExist['department_id'] = $this->getDepartmentId($data[3]);
						$dataDisplay = $data;
						$msgSuccess = array();
						$msgError = array();
						if ($employeeExist == 0) {
							if (in_array($data[1], $csvEmailArray)) {
								$dataDisplay[$message] = "Duplicate!";
							} else {
								foreach ($recExist as $recExistkey => $val) {
									if (!empty($val)) {
										$csvEmailArray[] = $data[1];
										$msgSuccess[] = "Success";
									} else {
										$msgError[] = "Invalid " . ucfirst(str_replace('_', ' ', $recExistkey));
									}
								}
								if (!empty($msgError)) {
									$dataDisplay[$message] = implode(',', $msgError);
								} else {
									$data[2] = $recExist['division_id'];
									$data[3] = $recExist['department_id'];
									$dataSubmit = $data;
									$dataDisplay[$message] = "Success";
								}
							}
						} else {
							$dataDisplay[$message] = config('messages.message.exist');
						}
						$headerColumns = $models->shiftArray($header['newheader']);
						$dataColumns = $models->shiftArray($dataDisplay);
						if (!empty($dataColumns)) {
							$finalDataForDisplay[$key] = array_combine($header['newheader'], $dataColumns);
						}
						if (!empty($dataSubmit)) {
							$finalDataForSubmit[$key] = array_combine($header['header'], $dataSubmit);
						}
					}
					if (!empty($finalDataForDisplay)) {
						$returnData = array('success' => "Please check the list below");
						$returnData['newheader'] = $headerColumns;
						$returnData['dataDisplay'] = $finalDataForDisplay;
						$returnData['dataSubmit'] = $finalDataForSubmit;
						$returnData['numberOfSubmitedRecords'] = $csvEmailArray;
					} else {
						$returnData = array('error' => "Some error!");
					}
				}
			} else {
				$returnData = array('error' => config('messages.message.employeeDetails'));
			}
		} else {
			$returnData = array('error' => config('messages.message.invalidFileType'));
		}
		return response()->json(['returnData' => $returnData]);
	}

	/**
	 * Get division id by division code
	 * Date : 5-5-2017
	 * Author : nisha
	 */
	public function getDivisionId($division_code)
	{
		if (!empty($division_code)) {
			$division = DB::table('divisions')->where('divisions.division_code', '=', $division_code)->first();
			if (!empty($division->division_id)) {
				return $division->division_id;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Get department id by department code
	 * Date : 5-5-2017
	 * Author : nisha
	 */
	public function getDepartmentId($department_code)
	{
		if (!empty($department_code)) {
			$department = DB::table('departments')->where('departments.department_code', '=', $department_code)->first();
			if (!empty($department->department_id)) {
				return $department->department_id;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * save list of employees uploaded by csv
	 * Date : 06-04-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function saveUploadedEmployee(Request $request)
	{
		$returnData = array();
		if ($request->isMethod('post')) {
			if (!empty($request['data']['formData'])) {
				//pasrse searlize data 
				$uploaded = array();
				$notUploaded = array();
				$duplicate = array();
				$loggedInUser = \Auth::user()->id;
				$newPostArray = $request['data']['formData'];
				foreach ($newPostArray as $key => $newPostData) {
					if (strtolower($newPostData['is_sales_person']) == 'yes') {
						$detailsData['is_sales_person'] = 1;
					} else {
						$detailsData['is_sales_person'] = 0;
					}
					$detailsData['department_id'] 	= $newPostData['department_code'];
					unset($newPostData['is_sales_person']);
					unset($newPostData['department_id']);
					if ($this->isEmployeeExist($newPostData['email']) == 0) {
						$formData = array_filter($newPostData);
						$formData['created_by'] = $loggedInUser;
						$created = User::create([
							'name' => ucwords($newPostData['name']),
							'email' => $newPostData['email'],
							'division_id' => $newPostData['division_code'],
							'created_by' => \Auth::user()->id,
							'password' => bcrypt($newPostData['password']),
						]);
						//check if users created add data in user detail
						if (!empty($created->id)) {
							$detailsData['user_code'] = strtolower(str_replace(' ', '', $newPostData['name'])) . $created->id;
							$detailsData['user_id'] = $created->id;
							$userDetail = DB::table('users_equipment_detail')->insertGetId($detailsData);
							$uploaded[] = $newPostData['name'];
							$created->attachRole('2');
						} else {
							$notUploaded[] = $newPostData['name'];
						}
					} else {
						$duplicate[] = $newPostData['name'];
					}
				}
				$returnData['success'] = config('messages.message.save');
				if (!empty($uploaded)) {
					$returnData['upload']['uploaded'] = "These employee(s) having name(s) " . implode(', ', $uploaded) . " has been saved successfully.";
				}
				if (!empty($notUploaded)) {
					$returnData['upload']['notUploaded'] = "Error in saving these employee name(s): " . implode(', ', $notUploaded);
				}
				if (!empty($duplicate)) {
					$returnData['upload']['duplicate'] = "These employee(s)  having name(s) " . implode(', ', $duplicate) . " already exist in our record!";
				}
			} else {
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
			return response()->json(['returnData' => $returnData]);
		}
	}
}

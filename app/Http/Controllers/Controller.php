<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Module;
use App\Models;
use Route;
use Auth;
use DB;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function __construct($authData)
	{

		global $module, $models;

		$module = new Module();
		$models = new Models();

		//function for getting constants names (17-08-2017)
		$models->getDefaultSetting();

		if (!empty($authData->id)) {

			//Current Date Settings
			define('CURRENTDATE', date('Y-m-d'));
			define('ORDERCURRENTDATE', date('d-m-Y'));
			define('CURRENTDATETIME', date('Y-m-d H:i:s'));

			//Setting user ID
			if (!defined('USERID')) {
				define('USERID', $authData->id);
			}

			//Setting Role ID
			if (!empty($authData->roles->first()->id)) {
				define('ROLEID', $authData->role_id);
				define('ROLENAME', $this->getUserDeta(USERID)->role_name);
				define('MAILSENDERNAME', $this->getUserDeta(USERID)->user_name);
				define('USERNAME', $this->getUserDeta(USERID)->user_name);
				define('USERSIGNATURE', $this->getUserDeta(USERID)->user_signature);
			}

			//Setting division ID
			if (!defined('DIVISIONID')) {
				define('DIVISIONID', !empty($authData->division_id) ? $authData->division_id : '0');
			}

			//Setting Department ID
			if (!defined('DEPARTMENT_IDS') && !defined('USER_DEPARTMENT_IDS') && !defined('ROLE_IDS') && !defined('EQUIPMENT_TYPE_IDS')) {
				define('DEPARTMENT_IDS', $this->getEmployeeAssignedDetail(USERID)['departments']);
				define('USER_DEPARTMENT_IDS', $this->getEmployeeAssignedDetail(USERID)['user_departments']);
				define('ROLE_IDS', $this->getEmployeeAssignedDetail(USERID)['roles']);
				define('EQUIPMENT_TYPE_IDS', $this->getEmployeeAssignedDetail(USERID)['equipments']);
				$this->hasRoleAssigned(array(ROLEID), USERID);
			}

			//function for getting current module id
			if (!defined('MODULEID')) {
				define('MODULEID', $this->getCurrentModuleId());
			}

			//Setting Multi-Session Permission
			if (defined('ROLEID') && !defined('MULTISESSIONALLOW')) {
				define('MULTISESSIONALLOW', $this->getMultiSessionPermission(ROLEID, 'Multi-Session'));
			}

			//function for permissions according to role and module id
			if (defined('ROLEID') && defined('MODULEID')) {
				define('CUSTOMPERMISSIONS', $this->getCustomRolePermissions(USERID));
				define('PERMISSIONS', $this->getPermissionsData(array(ROLEID), MODULEID));
				if (!empty(PERMISSIONS)) {
					define('ADD', PERMISSIONS['add']);
					define('EDIT', PERMISSIONS['edit']);
					define('VIEW', PERMISSIONS['view']);
					define('DELETE', PERMISSIONS['delete']);
				} else {
					define('ADD', 0);
					define('EDIT', 0);
					define('VIEW', 0);
					define('DELETE', 0);
				}
			}

			//function for getting navigation/sub navigation according to role ID
			if (defined('ROLE_IDS') && !defined('NAVIGATIONS')) {
				list($leftNavigationSetting, $rightNavigationSetting) = $module->getNavigationAccToRole(ROLE_IDS);
				define('LEFTNAVIGATIONS', $leftNavigationSetting);
				define('RIGHTNAVIGATIONS', $rightNavigationSetting);
				define('SUBNAVIGATIONS', $module->getSubNavigationAccToRoleModule(ROLE_IDS, MODULEID));
			}

			//Allowing User to move to the allowed Page Only
			if (defined('ROLE_IDS')) {
				$notallowedtonavigate = $this->getAllowedModuleNavPermissions(ROLE_IDS, MODULEID);
				define('NOTALlOWEDTONAVIGATE', $notallowedtonavigate);
			}
		}
	}

	//getting user information
	public function getCustomRolePermissions($user_id)
	{
		return DB::table('user_custom_permissions')->where('user_custom_permissions.ucp_user_id', $user_id)->pluck('ucp_permission_value', 'ucp_permission_key')->all();
	}

	//getting user information
	public function getUserDeta($user_id)
	{
		return DB::table('users')
			->leftJoin('roles', 'roles.id', '=', 'users.role_id')
			->select('users.*', 'roles.name as role_name', 'users.name as user_name')
			->where('users.id', '=', $user_id)
			->first();
	}

	//getting user information
	public function getEmployeeAssignedDetail($user_id)
	{
		$departmentData = array_filter(DB::table('users_department_detail')->where('users_department_detail.user_id', '=', $user_id)->pluck('department_id')->toArray());
		if (!empty($departmentData)) {
			foreach ($departmentData as $key => $val) {
				$linkedData 	   = DB::table('department_product_categories_link')->where('department_product_categories_link.department_id', '=', $val)->first();
				$departments[$val] = !empty($linkedData->product_category_id) ? $linkedData->product_category_id : '0';
			}
		}
		$data['departments'] 		= !empty($departments) ? array_filter(array_values($departments)) : array();
		$data['user_departments'] 	= !empty($departmentData) ? array_values($departmentData) : array();
		$data['roles']       		= array_filter(DB::table('role_user')->where('role_user.user_id', '=', $user_id)->pluck('role_id')->toArray());
		$data['equipments']  		= array_filter(DB::table('users_equipment_detail')->where('users_equipment_detail.user_id', '=', $user_id)->pluck('equipment_type_id')->toArray());
		return $data;
	}

	//getting the current page moduleID
	public function getCurrentModuleId()
	{
		global $module;
		$currentUrl = Route::getCurrentRoute()->getPath();
		return $module->getThisModuleID($currentUrl, 2);
	}

	//Getting all allowed action to the role user
	public function getPermissionsData($role_ids, $module_menu_id)
	{
		return (array) DB::table('module_permissions')
			->whereIn('module_permissions.role_id', $role_ids)
			->where('module_permissions.module_menu_id', '=', $module_menu_id)
			->first();
	}

	//function for getting navigation according to role ID
	public function getAllowedModuleNavPermissions($role_ids, $module_id)
	{
		global $module;
		return $module->getAllowedModuleNavId($role_ids, $module_id);
	}

	//function for getting Multi Session Permission according to role ID and Module Name
	public function getMultiSessionPermission($role_id, $module_name)
	{
		global $module;
		return $module->getAllowedgMultiSessionModuleId($role_id, $module_name);
	}

	//Updating Sample Status of booked Order in  samples table
	function hasRoleAssigned($role_ids, $user_id)
	{

		/****************************************
	1	Administrator
	2	Employer
	3	Sample Receiver
	4	Order Booker
	5	Job Scheduler
	6	Tester
	7	Reporter/Section Incharge
	8	Reviewer
	9	Finalizer
	10	Approval
	11	Invoice generator
	12 	Dispatcher
	13 	CRM
		 ****************************************/

		//Permission for Administrator ID:1
		if (!empty($role_ids) && in_array(1, $role_ids)) {
			if (!defined('IS_ADMIN')) define('IS_ADMIN', '1');
		}
		//Permission for Employer ID:2
		if (!empty($role_ids) && in_array(2, $role_ids)) {
			if (!defined('IS_EMPLOYEER')) define('IS_EMPLOYEER', '1');
		}
		//Permission for Sample Receiver ID:3
		if (!empty($role_ids) && in_array(3, $role_ids)) {
			if (!defined('IS_SAMPLER')) define('IS_SAMPLER', '1');
		}
		//Permission for Order Booker ID:4
		if (!empty($role_ids) && in_array(4, $role_ids)) {
			if (!defined('IS_ORDER_BOOKER')) define('IS_ORDER_BOOKER', '1');
		}
		//Permission for Job Scheduler ID:5
		if (!empty($role_ids) && in_array(5, $role_ids)) {
			if (!defined('IS_JOB_SCHEDULER')) define('IS_JOB_SCHEDULER', '1');
		}
		//Permission for Tester ID:6
		if (!empty($role_ids) && in_array(6, $role_ids)) {
			if (!defined('IS_TESTER')) define('IS_TESTER', '1');
		}
		if (!empty($role_ids) && in_array(7, $role_ids)) {
			if (!defined('IS_SECTION_INCHARGE')) define('IS_SECTION_INCHARGE', '1');
		}
		//Permission for Reviewer ID:8
		if (!empty($role_ids) && in_array(8, $role_ids)) {
			if (!defined('IS_REVIEWER')) define('IS_REVIEWER', '1');
		}
		//Permission for Finalizer ID:9
		if (!empty($role_ids) && in_array(9, $role_ids)) {
			if (!defined('IS_FINALIZER')) define('IS_FINALIZER', '1');
		}
		//Permission for Approval ID:10
		if (!empty($role_ids) && in_array(10, $role_ids)) {
			if (!defined('IS_APPROVAL')) define('IS_APPROVAL', '1');
		}
		//Permission for Invoice generator ID:11
		if (!empty($role_ids) && in_array(11, $role_ids)) {
			if (!defined('IS_INVOICE_GENERATOR')) define('IS_INVOICE_GENERATOR', '1');
		}
		//Permission for Dispatcher ID:12
		if (!empty($role_ids) && in_array(12, $role_ids)) {
			if (!defined('IS_DISPATCHER')) define('IS_DISPATCHER', '1');
		}
		//Permission for CRM ID:13
		if (!empty($role_ids) && in_array(13, $role_ids)) {
			if (!defined('IS_CRM')) define('IS_CRM', '1');
		}
	}
}

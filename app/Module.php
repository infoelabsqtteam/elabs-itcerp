<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;

class Module extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $table = 'modules';
	protected $fillable = [];
	protected $guarded = ['id'];

	public function parent()
	{
		return $this->belongsTo('App\Module', 'parent_id');
	}

	public function children()
	{
		return $this->hasMany('App\Module', 'parent_id');
	}

	/**
	 * isModuleNameExist Is used to check the item duplicate entry by item_code
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	function isModuleNameExist($module_name, $type = 'add', $module_id = null)
	{
		if ($type == 'add') {
			return DB::table('modules')->where('modules.module_name', '=', $module_name)->count();
		} else if ($type == 'edit') {
			$data = DB::table('modules')->where('modules.id', '=', $module_id)->where('modules.module_name', '=', $module_name)->count();
			if ($data) {
				return false;
			} else {
				return DB::table('modules')->where('modules.module_name', '=', $module_name)->count();
			}
		}
	}

	/**
	 * isModuleLinkExist Is used to check the item duplicate entry by item_code
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	function isModuleLinkExist($module_name, $module_link, $module_level, $type = 'add', $module_id = null)
	{
		if (strtolower($type) == 'add') {
			return DB::table('modules')->where('modules.module_name', '=', $module_name)->where('modules.module_link', '=', $module_link)->where('modules.module_level', '=', $module_level)->count();
		} else if (strtolower($type) == 'edit') {
			if (DB::table('modules')->where('modules.id', '=', $module_id)->where('modules.module_name', '=', $module_name)->where('modules.module_link', '=', $module_link)->where('modules.module_level', '=', $module_level)->count()) {
				return true;
			} else {
				if (DB::table('modules')->where('modules.module_name', '=', $module_name)->where('modules.module_link', '=', $module_link)->where('modules.module_level', '=', $module_level)->count()) {
					return false;
				} else {
					return true;
				}
			}
		}
	}

	/**
	 * isModuleCategoryChanged Is used to check the item duplicate entry by item_code
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	function isModuleCategoryChanged($submiteData)
	{
		$moduleData = DB::table('modules')->where('modules.id', '=', $submiteData['id'])->where('modules.parent_id', '=', $submiteData['parent_id'])->where('modules.module_level', '=', $submiteData['module_level'])->first();
		return empty($moduleData) ? true : false;
	}

	/**
	 * isModuleNameExist Is used to check the item duplicate entry by item_code
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	function getParentChildren($parent_id)
	{
		$tree = array();
		if (!empty($parent_id)) {
			$tree = DB::table('modules')->where('modules.parent_id', '=', $parent_id)->pluck('modules.id')->toArray();
			foreach ($tree as $key => $val) {
				$ids  = $this->getParentChildren($val);
				$tree = array_merge($tree, $ids);
			}
			array_push($tree, $parent_id);
			return array_unique($tree);
		}
	}

	/**
	 * isModuleNameExist Is used to check the item duplicate entry by item_code
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	function deleteModuleNavigationChanged($module_id)
	{
		if (!empty($module_id)) {
			$allParentChildren = array_values($this->getParentChildren($module_id));
			if (!empty($allParentChildren)) {
				DB::table('module_navigations')->whereIn('module_navigations.module_menu_id', $allParentChildren)->delete();
				return true;
			}
		}
	}

	/**
	 * get module id using current url route name
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	function getThisModuleID($currentUrl, $level)
	{
		$res = DB::table('modules')
			->where('modules.module_link', '=', trim($currentUrl))
			->where('modules.module_level', '=', $level)
			->first();
		return !empty($res->id) ? $res->id : '0';
	}

	/**
	 * get module id using current url route name
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	function getAllowedModuleNavId($role_ids, $module_menu_id)
	{
		$moduleData = DB::table('module_navigations')
			->whereIn('module_navigations.role_id', $role_ids)
			->where('module_navigations.module_menu_id', '=', $module_menu_id)
			->first();
		return !empty($moduleData->module_menu_id) ? $moduleData->module_menu_id : '0';
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getNavigationAccToRole($role_ids)
	{

		$leftNavigationSetting = $rightNavigationSetting = array();

		$navigations = DB::table('module_navigations')
			->join('modules as sections', 'module_navigations.module_id', '=', 'sections.id')
			->join('modules as menus', 'module_navigations.module_menu_id', '=', 'menus.id')
			->select('sections.id as section_id', 'sections.module_name as section_module_name', 'sections.module_link as section_module_link', 'sections.module_level as section_module_level', 'sections.module_status as section_module_status', 'sections.module_sort_by as section_module_sort_by', 'menus.id as menu_id', 'menus.module_name as menu_module_name', 'menus.module_link as menu_module_link', 'menus.module_level as menu_module_level', 'menus.module_status as menu_module_status', 'menus.module_sort_by as menu_module_sort_by')
			->whereIn('module_navigations.role_id', $role_ids)
			->whereIn('sections.module_level', array('0', '1'))
			->whereIn('menus.module_level', array('0', '1'))
			->where('sections.module_status', '=', '1')
			->where('menus.module_status', '=', '1')
			->orderBy('section_module_sort_by', 'ASC')
			->orderBy('menu_module_sort_by', 'ASC')
			->get()
			->toArray();

		if (!empty($navigations)) {
			foreach ($navigations as $key => $value) {
				if (strtolower($value->section_module_name) != 'welcome') {
					$leftNavigationSetting[$value->section_id][$value->section_module_name][$value->menu_id] = (array) $value;
				} else {
					$rightNavigationSetting[$value->section_id][$value->section_module_name][$value->menu_id] = (array) $value;
				}
			}
		}

		//echo '<pre>';print_r($rightNavigationSetting);print_r($leftNavigationSetting);die;
		return array(array_values($leftNavigationSetting), array_values($rightNavigationSetting));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getSubNavigationAccToRoleModule($role_ids, $module_menu_id)
	{

		$subNavigations = array();

		$moduleParentData = DB::table('modules')
			->select('modules.id', 'modules.parent_id')
			->where('modules.id', '=', $module_menu_id)
			->first();

		$moduleparentId = !empty($moduleParentData->parent_id) ? $moduleParentData->parent_id : null;

		$moduleDataIds = DB::table('modules')
			->where('modules.parent_id', '=', $moduleparentId)
			->pluck('modules.id')->toArray();

		$subNavigationData = DB::table('module_navigations')
			->join('modules', 'module_navigations.module_menu_id', '=', 'modules.id')
			->select('modules.id', 'modules.parent_id', 'modules.module_name', 'modules.module_link', 'modules.module_level', 'modules.module_sort_by')
			->whereIn('module_navigations.role_id', $role_ids)
			->whereIn('module_navigations.module_menu_id', $moduleDataIds)
			->where('modules.module_level', '2')
			->where('modules.module_status', '1')
			->orderBy('modules.module_sort_by', 'ASC')
			->get()
			->toArray();

		if (!empty($subNavigationData)) {
			foreach ($subNavigationData as $key => $value) {
				$subNavigations[$value->id] = (array) $value;
			}
		}

		//echo '<pre>';print_r($subNavigations);die;
		return $subNavigations;
	}


	/**
	 * get module id using current url route name
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	function getAllowedgMultiSessionModuleId($role_id, $module_name)
	{
		$modules = DB::table('modules')->where('modules.module_name', $module_name)->first();
		$moduleData = DB::table('module_navigations')->where('module_navigations.role_id', $role_id)->where('module_navigations.module_menu_id', '=', !empty($modules->id) ? $modules->id : '0')->first();
		return !empty($moduleData->module_menu_id) ? '1' : '0';
	}
}

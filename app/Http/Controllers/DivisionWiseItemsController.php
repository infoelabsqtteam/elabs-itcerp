<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Item;
use App\Models;
use Validator;
use Route;
use DB;
use App\Helpers\Helper;

class DivisionWiseItemsController extends Controller
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
		global $item,$models;
		$item   = new  Item();
		$models = new  Models();
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
		
        return view('inventory.division_wise_items.index',['title' => 'Branch Wise Items','_branchWiseItems' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);

    }
	
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
    public function getDivisionItems($division_id)
    {
		global $item,$models;
		
		$itemObj = DB::table('division_wise_items')
				   ->join('item_master','item_master.item_id','division_wise_items.item_id')	
		           ->join('item_categories','item_categories.item_cat_id','item_master.item_cat_id')
				   ->join('divisions','divisions.division_id','division_wise_items.division_id')
		           ->select('division_wise_items.*','item_master.item_code','item_master.item_name','item_categories.item_cat_name','divisions.division_name');
				   
		if(!empty($division_id) && is_numeric($division_id)){
			$itemObj->where('division_wise_items.division_id',$division_id);
		}
		
		$items = $itemObj->orderBy('division_wise_items.division_wise_item_id','DESC')->get();	
		
		//to formate created and updated date		   
		$models->formatTimeStampFromArray($items,DATETIMEFORMAT);    
		
		return response()->json(['itemsDivisionList' => $items]);		
    }
	
	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
    public function addDivisionItem(Request $request)
    {
       global $item,$models;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();
        
        //Saving record in orders table
        if(!empty($request->formData['id']) && $request->isMethod('post')){
			//echo '<pre>';print_r($request->formData);die;
			$updateData = [
					'division_wise_items.msl' 		 => $request->formData['msl'],
					'division_wise_items.rol' 		 => $request->formData['rol'],
					'division_wise_items.created_by' => \Auth::user()->id
			];
			DB::table('division_wise_items')->where('division_wise_items.division_wise_item_id',$request->formData['id'])->update($updateData);
			$error   = '1';
			$message = config('messages.message.updated');			
        }
		
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data));	
    }
	
	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
    public function addDivisionAllItem(Request $request)
    {
       global $item,$models;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = $formDataSave = array();
        
        //Saving record in orders table
        if(!empty($request->formData) && $request->isMethod('post')){			
			$user_id = \Auth::user()->id;
            parse_str($request->formData, $formData);			
			//echo '<pre>';print_r($formData);die;                        
            if(!empty($formData) && !empty($formData['division_wise_item_id'])){
				foreach($formData['division_wise_item_id'] as $key => $division_wise_item_id){					
					$formDataSave[$division_wise_item_id]['msl'] = isset($formData['msl'][$key]) ? trim($formData['msl'][$key]) : '';
					$formDataSave[$division_wise_item_id]['rol'] = isset($formData['rol'][$key]) ? trim($formData['rol'][$key]) : '';	
				}
				foreach($formDataSave as $division_wise_item_id => $mslrolValue){
					$updateDataSave = array();
					$updateDataSave = [
						'division_wise_items.msl' 		 => $mslrolValue['msl'],
						'division_wise_items.rol' 		 => $mslrolValue['rol'],
						'division_wise_items.created_by' => $user_id
					];
					if($division_wise_item_id){
						DB::table('division_wise_items')->where('division_wise_items.division_wise_item_id',$division_wise_item_id)->update($updateDataSave);
						$error = '1';
					}
				}
			}
			if($error){
				$message = config('messages.message.updated');	
			}					
        }		
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data));	
    }
	
	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function refreshDivisionItem()
    {
		global $item,$models;
		
		//Cloning all Item of all Division		
		$item->copyItemMaster();
		return redirect('settings');
    }
	
}

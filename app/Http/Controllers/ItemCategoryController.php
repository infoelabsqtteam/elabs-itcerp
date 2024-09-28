<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\ItemCategory;
use Validator;
use Route;
use DB;

class ItemCategoryController  extends Controller
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
        global $models;
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
		
        return view('inventory.item_master.item_categories.index',['title' => 'Ttem Categories','_item_categories' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
	
	//autogenerate code
	public function getAutoGeneratedCode(Request $request)
    {
		global $models;
		$prefix=!empty(config('messages.message.itemCatPrefix'))?config('messages.message.itemCatPrefix'):'ITEMCAT';
		$code=$models->generateCode($prefix,'item_categories','item_cat_code','item_cat_id');						  
		return response()->json(['uniqueCode' =>$code]);		
	}

    /** create new item
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createItemCategory(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);  
				if(empty($newPostData['item_cat_code']))
				{
					$returnData = array('error' => config('messages.message.itemCatCodeRequired'));
				}else if(empty($newPostData['item_cat_name'])){
					$returnData = array('error' => config('messages.message.itemCatNameRequired'));
				}else{  
					// check if item already exist or not 
					if(empty($newPostData['item_parent_cat']) || $newPostData['item_parent_cat']=='?'){ $newPostData['item_parent_cat']=0; }
					if(empty($this->isItemCategoryExist($newPostData['item_cat_code'],$newPostData['item_cat_name']))){ 
						$created = ItemCategory::create([
							'item_cat_code' => $newPostData['item_cat_code'],
							'item_cat_name' => $newPostData['item_cat_name'],
							'item_parent_cat' => $newPostData['item_parent_cat'],
							'created_by' => \Auth::user()->id,
						   ]);
						
						//check if users created add data in user detail
						if($created->id){ 
							$returnData = array('success' => config('messages.message.saved'));
						}else{
							$returnData = array('error' => config('messages.message.error'));
						}
					}else{
						$returnData = array('error' => config('messages.message.itemCatExist'));
					}
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
		} 
		return response()->json($returnData);		
    }

    /**
     * Get list of companies on page load.
     * Date : 01-16-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */  
	public function getItemCategoryList()
    {
		global $models;
		$items = DB::table('item_categories')
			   ->leftjoin('item_categories as category','item_categories.item_parent_cat','=','category.item_cat_id')
			   ->join('users', 'item_categories.created_by', '=', 'users.id')				   
			   ->select('item_categories.*','category.item_cat_name as parent_cat','users.name as createdBy')->get();
		$models->formatTimeStampFromArray($items,DATETIMEFORMAT);
		return response()->json([
		   'itemsList' => $items,
		]);
    }  
	
	/**
     * Get list of item categories using multisearch
     * Date : 02-05-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */  
	public function getItemCategoryListMultisearch(Request $request)
    {
		$searchArry=$request['data']['formData'];
		$itemsListObj = DB::table('item_categories')
			   ->leftjoin('item_categories as category','item_categories.item_parent_cat','=','category.item_cat_id')
			   ->join('users', 'item_categories.created_by', '=', 'users.id')				   
			   ->select('item_categories.*','category.item_cat_name as parent_cat','users.name as createdBy');
				if(!empty($searchArry['search_item_cat_code'])){
					$itemsListObj->where('item_categories.item_cat_code','like','%'.$searchArry['search_item_cat_code'].'%');
				}
				if(!empty($searchArry['search_item_cat_name'])){
					$itemsListObj->where('item_categories.item_cat_name','like','%'.$searchArry['search_item_cat_name'].'%');
				}
				if(!empty($searchArry['search_parent_cat'])){
					$itemsListObj->where('category.item_cat_name','like','%'.$searchArry['search_parent_cat'].'%');
				}
				if(!empty($searchArry['search_created_by'])){
					$itemsListObj->where('users.name','like','%'.$searchArry['search_created_by'].'%');
				}
				if(!empty($searchArry['search_created_at'])){
					$itemsListObj->where('item_categories.created_at','like','%'.$searchArry['search_created_at'].'%');
				}
				if(!empty($searchArry['search_updated_at'])){
					$itemsListObj->where('item_categories.updated_at','like','%'.$searchArry['search_updated_at'].'%');
				}
		$itemsList=$itemsListObj->get();
		return response()->json([
		   'itemsList' => $itemsList,
		]);
    }   

    /**
     * isItemCategoryExist Is used to check the duplicate entry by item_cat_code
     * Date : 01-16-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isItemCategoryExist($item_cat_code,$item_cat_name) 
    {   
		if(!empty($item_cat_code)){
			$itemData = DB::table('item_categories')
						->where('item_categories.item_cat_code', '=', $item_cat_code)
						->orwhere('item_categories.item_cat_name', '=', $item_cat_name)
						->first(); 
			if(!empty($itemData)){
				return count($itemData);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editItemCategoryData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				// get user by email id
				$itemData = DB::table('item_categories')
								->where('item_categories.item_cat_id', '=', $request['data']['id'])
								->first();
				
				if($itemData->item_cat_id){
					$returnData = array('responseData' => $itemData);				
				}else{
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
				$returnData = array('error' => config('messages.message.provideAppData'));			
		}
		return response()->json($returnData);	
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateItemCategoryData(Request $request)
    {
        $returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){   
				//pasrse searlize data 
				$newPostData = array(); 
				parse_str($request['data']['formData'], $newPostData);      
				if(empty($newPostData['item_cat_id1']))
				{
					$returnData = array('error' => config('messages.message.itemCatCodeRequired'));
				}else if(empty($newPostData['item_cat_name1'])){
					$returnData = array('error' => config('messages.message.itemCatNameRequired'));
				}else{
					if(empty($newPostData['item_parent_cat1'] || $newPostData['item_parent_cat1']='?')){ $newPostData['item_parent_cat1']=0; }
					$newPostData['item_cat_id1']=base64_decode($newPostData['item_cat_id1']);
					//echo strtolower(trim($newPostData['item_name_old'])).'=='.strtolower(trim($newPostData['item_cat_name1'])); die;					
				   if(strtolower(trim($newPostData['item_name_old']))==strtolower(trim($newPostData['item_cat_name1']))){
					 $updated = DB::table('item_categories')->where('item_cat_id',$newPostData['item_cat_id1'])->update([
							'item_cat_name' => $newPostData['item_cat_name1'],
							'item_parent_cat' => $newPostData['item_parent_cat1'],
						   ]);
						   //check if data updated in ItemCategory table 
						   $returnData = array('success' => config('messages.message.updated'));
					}else{
						if(empty($this->isCategoryExist($newPostData['item_cat_name1']))){					
						  $updated = DB::table('item_categories')->where('item_cat_id',$newPostData['item_cat_id1'])->update([
							'item_cat_name' => $newPostData['item_cat_name1'],
							'item_parent_cat' => $newPostData['item_parent_cat1'],
						   ]);
						   //check if data updated in ItemCategory table 
						   $returnData = array('success' => config('messages.message.updated'));
						}else{
							$returnData = array('error' => config('messages.message.itemCatExist'));
						}
					}					
				}
			}else{
				$returnData = array('error' =>  config('messages.message.dataNotFound'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFound'));
		} 
		return response()->json($returnData);
    }
	
	public function isCategoryExist($item_cat_name) 
    {   
		if(!empty($item_cat_name)){
			$itemData = DB::table('item_categories')
						->where('item_categories.item_cat_name', '=', $item_cat_name)
						->get(); 
			if(!empty($itemData)){
				return count($itemData);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteItemCategoryData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
 				try{
					$item = DB::table('item_categories')->where('item_cat_id', $request['data']['id'])->delete();
					if($item){
						$returnData = array('success' => config('messages.message.deleted'));
					}else{
						$returnData = array('error' => config('messages.message.deletedError'));					
					}				
				}catch(\Illuminate\Database\QueryException $ex){ 
					   $returnData = array('error' => config('messages.message.foreignKeyDeleteError'));
				}
			}else{
				$returnData = array('error' => config('messages.message.noRecordFound'));
			}
		}
		return response()->json($returnData);
    }
}

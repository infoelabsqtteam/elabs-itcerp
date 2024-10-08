<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Item;
use App\Models;
use Validator;
use DB;
use App\Helpers\Helper;
use App\Helpers\SimpleImage;
use Route;
use File;

class ItemController extends Controller
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
		
        return view('inventory.item_master.items.index',['title' => 'Items','_items' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
	
	//autogenerate code
	public function getAutoGeneratedCode(Request $request)
    {
		global $models;
		$prefix=!empty(config('messages.message.itemPrefix'))?config('messages.message.itemPrefix'):'ITEM';
		$code=$models->generateCode($prefix,'item_master','item_code','item_id');						  
		return response()->json(['uniqueCode' =>$code]);		
	}
	
	/** create new item
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function createItem(Request $request)
	{
		global $item,$models;
		
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $returnData = $newPostData = array();
		
		if ($request->isMethod('post')) { 
			if(!empty($request['formData'])){
				
				//pasrse searlize data 				
				parse_str($request['formData'], $newPostData);
				$newPostData = array_filter($newPostData);
				
				if(empty($newPostData['item_cat_id'])){
					$message = config('messages.message.itemCatCodeRequired');
				}elseif(empty($newPostData['item_code'])){
					$message = config('messages.message.itemCodeRequired');
				}else if($item->checkItemCode($newPostData['item_code'])){
					$message = config('messages.message.itemCodeExist');
				}else if(empty($newPostData['item_barcode'])){
					$message = config('messages.message.itemBarcodeRequired');
				}else if($item->checkItemBarcode($newPostData['item_barcode'])){
					$message = config('messages.message.itemBarcodeExist');
				}else if(empty($newPostData['item_description'])){
					$message = config('messages.message.itemDescRequired');
				}else if(empty($newPostData['item_name'])){
					$message = config('messages.message.itemNameRequired');
				}else{
					unset($newPostData['_token']);
					$newPostData['is_perishable'] = isset($newPostData['is_perishable']) ? '1' : '0';					
					$newPostData['created_by'] = \Auth::user()->id;					
					//echo '<pre>';print_r($newPostData);die;										
					if(!empty($newPostData) && !$this->isItemExist($newPostData['item_code'],$newPostData['item_barcode'])){						
						$created = DB::table('item_master')->insertGetId($newPostData);
						if($created){
							
							//Adding Newly Added Item to the Branch
							$item->copyItemMaster();	
							
							$error    = '1';
							$message = config('messages.message.saved');
						}else{
							$message = config('messages.message.savedError');
						}									
					}else{
						$message = config('messages.message.itemCodeExist');
					}
				}	
			}else{
				$message = config('messages.message.dataNotFoundToSaved');
			}
		}else{
			$message = config('messages.message.dataNotFoundToSaved');
		}
		
		return response()->json(['error'=> $error,'message'=> $message,'data'=> $data]);	
    }

    /**
     * Get list of items on page load.
     * Date : 01-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getItemsList()
    {		
		global $item,$models;
		
		$items = DB::table('item_master')
		           ->join('item_categories','item_categories.item_cat_id','item_master.item_cat_id')	
		           ->join('users','item_master.created_by','users.id')	
		           ->select('item_master.*','item_categories.item_cat_name','users.name as createdBy')	
				   ->get();
		
		//to formate created and updated date		   
		$models->formatTimeStampFromArray($items,DATETIMEFORMAT); 
		return response()->json(['itemsList' => $items]);
    }
	
	/**
     * Get list of items using multisearch
     * Date : 01-05-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getItemsListMultisearch(Request $request)
    {		
		global $item,$models;
		$searchArry=$request['data']['formData'];  //print_r($searchArry); die;
		$itemsObj = DB::table('item_master')
		           ->join('item_categories','item_categories.item_cat_id','item_master.item_cat_id')	
		           ->join('users','item_master.created_by','users.id')	
		           ->select('item_master.*','item_categories.item_cat_name','users.name as createdBy');	
				    if(!empty($searchArry['search_item_code'])){
						$itemsObj->where('item_master.item_code','like','%'.$searchArry['search_item_code'].'%');
					}
					if(!empty($searchArry['search_item_name'])){
						$itemsObj->where('item_master.item_name','like','%'.$searchArry['search_item_name'].'%');
					}
					if(!empty($searchArry['search_item_cat_name'])){
						$itemsObj->where('item_categories.item_cat_name','like','%'.$searchArry['search_item_cat_name'].'%');
					}
					if(!empty($searchArry['search_item_barcode'])){
						$itemsObj->where('item_master.item_barcode','like','%'.$searchArry['search_item_barcode'].'%');
					}
					if(!empty($searchArry['search_created_by'])){
						$itemsObj->where('users.name','like','%'.$searchArry['search_created_by'].'%');
					}
					if(!empty($searchArry['search_created_at'])){
						$itemsObj->where('item_master.created_at','like','%'.$searchArry['search_created_at'].'%');
					}
					if(!empty($searchArry['search_updated_at'])){
						$itemsObj->where('item_master.updated_at','like','%'.$searchArry['search_updated_at'].'%');
					}
		$itemsList=$itemsObj->get();
		
		//to formate created and updated date		   
		$models->formatTimeStampFromArray($itemsList,DATETIMEFORMAT);  
		return response()->json(['itemsList' => $itemsList]);
    }

    /**
     * isItemExist Is used to check the item duplicate entry by item_code
     * Date : 01-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isItemExist($item_code,$item_barcode) 
    { 
		if(!empty($item_code)){
			$itemData = DB::table('item_master')
						->where('item_master.item_code', '=', $item_code)
						->orwhere('item_master.item_barcode', '=', $item_barcode)
						->first(); 
			if(!empty($itemData)){
				return '1';
			}else{
				return '0';
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
    public function viewItem(Request $request,$item_id)
    {
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
				
		if(!empty($item_id)){
			$message  = '';
			$itemData = DB::table('item_master')
						->join('item_categories','item_categories.item_cat_id','item_master.item_cat_id')
						->join('units_db','units_db.unit_id','item_master.item_unit')
						->select('item_master.*','item_categories.item_cat_name','units_db.unit_name')
						->where('item_master.item_id', '=', $item_id)
						->first();			
		}		
        $error   = !empty($itemData) ? 1 : 0;
        return response()->json(array('error'=> $error,'message'=> $message,'itemDetailList'=> $itemData));
    }
	
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateItemData(Request $request)
    {
		global $item,$models;
		
		$error       = '0';
        $message     = config('messages.message.error');
        $data        = '';
		$newPostData = array();
		
		if($request->isMethod('post') && !empty($request['data']['formData'])){			

			parse_str($request['data']['formData'], $newPostData);
			$item_id = !empty($newPostData['item_id']) ? $newPostData['item_id'] : '0';
			
			if(empty($newPostData['item_cat_id'])){
				$message = array('error' => config('messages.message.itemCatCodeRequired'));
			}else if(empty($newPostData['item_name'])){
				$message = array('error' => config('messages.message.itemNameRequired'));
			}else if(empty($newPostData['item_barcode'])){
				$message = array('error' => config('messages.message.itemBarcodeRequired'));
			}else if($item->checkItemBarcode($newPostData['item_barcode'],'edit',$item_id)){
					$message = config('messages.message.itemBarcodeExist');
			}else if(empty($newPostData['item_description'])){
				$message = array('error' => config('messages.message.itemDescRequired'));
			}else{					
				unset($newPostData['_token']);
				unset($newPostData['item_id']);					
				$newPostData['is_perishable'] = isset($newPostData['is_perishable']) ? '1' : '0';				
				//echo '<pre>';print_r($newPostData);die;
				
				if($item_id){
					if(DB::table('item_master')->where('item_master.item_id',$item_id)->update($newPostData)){
						$error   = '1';
						$message = config('messages.message.updated');
					}else{
						$error   = '1';
						$message = config('messages.message.savedNoChange');  
					}
				 }else{  
					$message = config('messages.message.notupdated');
				}  
			}			
		}else{
			$message = config('messages.message.dataNotFound');
		}
		
		return response()->json(['error'=> $error,'message'=> $message,'data'=> $data,'item_id' => $item_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteItemData(Request $request,$item_id)
    {
		global $item;
		
		$error   = '0';
        $message = config('messages.message.error');
        $data    = '';
        
        try{
			$itemData = DB::table('item_master')->where('item_master.item_id',$item_id)->first();
			if(!empty($itemData)){
				$item->removeUploadedItemImage($item_id, $itemData->item_image);
				if(DB::table('item_master')->where('item_master.item_id','=',$item_id)->delete()){
					$error    = '1';
					$message = config('messages.message.deleted');      
				} 
			}			
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.itemForeignKeyConsFail');
        }             
		return response()->json(['error' => $error,'message' => $message]);
    }
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadItemImage(Request $request)
    {
		global $item;
		
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
		
		if($request->isMethod('post') && !empty($request->item_id) && !empty($request->file('item_image'))){
			list($itemImage,$srcPath) = $item->uploadImage($request->item_id, $request);
			if($itemImage){
				DB::table('item_master')->where('item_master.item_id',$request->item_id)->update(['item_master.item_image' => $itemImage]);
				$error    = '1';
				$data     = $srcPath;
                $message = config('messages.message.itemImageUploaded');   
			}
		}        
		return response()->json(['error' => $error,'message' => $message ,'data' => $data]);
    }
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function deleteItemImage(Request $request,$item_id)
    {
		global $item;
		
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
		
		if(!empty($item_id)){
			$itemData = DB::table('item_master')->where('item_master.item_id',$item_id)->first();
			if(!empty($itemData->item_image)){
				$srcPath = $item->removeUploadedItemImage($item_id, $itemData->item_image);
			}				
			DB::table('item_master')->where('item_master.item_id',$item_id)->update(['item_master.item_image' => null]);
			$error    = '1';
			$data     = $srcPath;
			$message = config('messages.message.itemImageRemoved'); 
		}       
		return response()->json(['error' => $error,'message' => $message ,'data' => $data]);
    }
	
}

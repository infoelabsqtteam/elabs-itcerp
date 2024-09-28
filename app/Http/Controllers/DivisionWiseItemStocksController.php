<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Item;
use App\DivisionWiseItemStock;
use App\Models;
use Validator;
use Route;
use DB;
use App\Helpers\Helper;

class DivisionWiseItemStocksController extends Controller
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
		global $models,$stock;
		
		$models = new  Models();
		$stock  = new  DivisionWiseItemStock();
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
		
        return view('inventory.division_wise_item_stock.index',['title' => 'Branch Wise Item Stock','_branchWiseItemStock' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
    
    /**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
    public function getDivisionStockItem($division_id)
    {
		global $models,$stock;
		
		$itemStockDataListObj = DB::table('division_wise_item_stock')
                    ->join('item_master','item_master.item_id','division_wise_item_stock.item_id')
                    ->join('division_wise_stores','division_wise_stores.store_id','division_wise_item_stock.store_id')
				    ->join('divisions','divisions.division_id','division_wise_item_stock.division_id')
		            ->join('users','division_wise_item_stock.created_by','users.id')	
					->select('division_wise_item_stock.*','divisions.division_name','item_master.item_name','division_wise_stores.store_name','users.name as createdBy');
				   
		if(!empty($division_id) && is_numeric($division_id)){
			$itemStockDataListObj->where('division_wise_item_stock.division_id',$division_id);
		}
		
		$itemStockDataList = $itemStockDataListObj->orderBy('division_wise_item_stock.division_wise_item_stock_id','DESC')->get();	
		
		//to formate created and updated date		   
		$models->formatTimeStampFromArray($itemStockDataList,DATETIMEFORMAT);    
		
		return response()->json(['itemStockDataList' => $itemStockDataList]);		
    }
    
    /** create new item
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function createDivisionStockItem(Request $request)
	{
        global $models,$stock;
        
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $returnData = $formData = array();
		
		if ($request->isMethod('post') && !empty($request['formData'])){ 
				
            //pasrse searlize data 				
            parse_str($request['formData'], $formData);
            $formData = array_filter($formData);
			
            if(empty($formData['store_id'])){
                $message = config('messages.message.storeNameRequired');
            }elseif(empty($formData['item_id'])){
                $message = config('messages.message.itemNameRequired');
            }else if(empty($formData['division_id'])){
                $message = config('messages.message.divisionNameRequired');
            }elseif(empty($formData['openning_balance'])){
                $message = config('messages.message.openningBalanceRequired');
            }elseif(empty($formData['openning_balance_date'])){
                $message = config('messages.message.openningBalanceDateRequired');
			}elseif($stock->isStoreDivisionWiseExist($formData['store_id'],$formData['division_id'])){
                $message = config('messages.message.storeDivisionWiseExist');
            }else{
                unset($formData['_token']);
                $formData['created_by'] = \Auth::user()->id;
                //echo '<pre>';print_r($formData);die;
                if(!empty($formData)){
                    $created = DB::table('division_wise_item_stock')->insertGetId($formData);
                    if($created){
                        $error    = '1';
                        $message = config('messages.message.saved');
                    }else{
                        $message = config('messages.message.savedError');
                    }
                }                
            }			
		}
        
		return response()->json(['error'=> $error,'message'=> $message,'data'=> $data]);	
    }
	
	/**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function viewDivisionStockItem(Request $request,$stock_id)
    {
		global $models,$stock;
		
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
				
		if(!empty($stock_id)){			
			$stockDetailList = DB::table('division_wise_item_stock')
				->join('item_master','item_master.item_id','division_wise_item_stock.item_id')
				->join('division_wise_stores','division_wise_stores.store_id','division_wise_item_stock.store_id')
				->join('divisions','divisions.division_id','division_wise_item_stock.division_id')
				->select('division_wise_item_stock.*','divisions.division_name','item_master.item_name','division_wise_stores.store_name')
				->where('division_wise_item_stock.division_wise_item_stock_id', '=', $stock_id)
				->first();
            $message = '';
            $error   = !empty($stockDetailList) ? 1 : 0;
		}		
        
        return response()->json(array('error'=> $error,'message'=> $message,'stockDetailList'=> $stockDetailList));
    }
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDivisionStockItem(Request $request)
    {
        global $models,$stock;
        
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
		$stock_id = '';
		$formData = array();
        
        if ($request->isMethod('post') && !empty($request['formData'])){
            
            //pasrse searlize data 				
            parse_str($request['formData'], $formData);       
            $division_wise_item_stock_id = !empty($formData['division_wise_item_stock_id']) ? $formData['division_wise_item_stock_id'] : '0';
						
			if(empty($formData['store_id'])){
                $message = config('messages.message.storeNameRequired');
            }elseif(empty($formData['item_id'])){
                $message = config('messages.message.itemNameRequired');
            }else if(empty($formData['division_id'])){
                $message = config('messages.message.divisionNameRequired');
            }elseif(empty($formData['openning_balance'])){
                $message = config('messages.message.openningBalanceRequired');
            }elseif(empty($formData['openning_balance_date'])){
                $message = config('messages.message.openningBalanceDateRequired');
			}elseif($stock->isStoreDivisionWiseExist($formData['store_id'],$formData['division_id']) >= 2){
                $message = config('messages.message.storeDivisionWiseExist');
            }else{
                unset($formData['_token']);                
                //echo '<pre>';print_r($formData);die;
                if(!empty($formData) && !empty($division_wise_item_stock_id)){
                    DB::table('division_wise_item_stock')->where('division_wise_item_stock.division_wise_item_stock_id',$division_wise_item_stock_id)->update($formData);
                    $error    = '1';
                    $message = config('messages.message.saved');                    
                }else{
                    $message = config('messages.message.savedError');
                }                
            }	
		}
        
		return response()->json(['error'=> $error,'message'=> $message,'data'=> $data,'division_wise_item_stock_id' => $division_wise_item_stock_id]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteDivisionStockItem(Request $request,$stock_id)
    {
		global $models,$stock;
		
		$error   = '0';
        $message = config('messages.message.error');
        $data    = '';
        
        try{
			$itemStockData = DB::table('division_wise_item_stock')->where('division_wise_item_stock.division_wise_item_stock_id',$stock_id)->first();
			if(!empty($itemStockData)){				
				if(DB::table('division_wise_item_stock')->where('division_wise_item_stock.division_wise_item_stock_id','=',$stock_id)->delete()){
					$error    = '1';
					$message = config('messages.message.deleted');      
				} 
			}else{
                $message = config('messages.message.stockDataNotFound');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.stockForeignKeyConsFail');
        }             
		return response()->json(['error' => $error,'message' => $message]);
    }
}

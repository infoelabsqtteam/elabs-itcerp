<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\ProductCategory;
use App\Product;
use Validator;
use Route;
use DB;

class ProductController extends Controller
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
        global $models,$productCategory;
		$models = new Models();
		$product = new Product();
		$productCategory = new ProductCategory();
		$this->middleware('auth'); 
		$this->middleware(function ($request, $next) {
            $this->session = Auth::user();
			parent::__construct($this->session);
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
		
        return view('master.products.index',['title' => 'Products','_products' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

	//autogenerate code
	public function getAutoGeneratedCode(Request $request)
    {
		global $models;
		$prefix=!empty(config('messages.message.productPrefix'))?config('messages.message.productPrefix'):'PRO';
		$code=$models->generateCode($prefix,'product_master','product_code','product_id');   //prefix,tableName,fieldName,primaryKey						  
		return response()->json(['uniqueCode' =>$code]);		
	}
	
    /** create new product
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createProduct(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);  
				if(empty($newPostData['p_category_id']))
				{
					$returnData = array('error' => config('messages.message.productCatCodeRequired'));
				}elseif(empty($newPostData['product_code']))
				{
					$returnData = array('error' => config('messages.message.productCodeRequired'));
				}else if(empty($newPostData['product_description'])){
					$returnData = array('error' => config('messages.message.productDescRequired'));
				}else if(empty($newPostData['product_name'])){
					$returnData = array('error' => config('messages.message.productNameRequired'));
				}else{ 
					// check if product already exist or not 
					if(empty($this->isProductExist($newPostData['product_code']))){
                      if(empty($this->isProductNameExist($newPostData['product_name'],$newPostData['p_category_id']))){						
							$created = Product::create([
								'p_category_id' => $newPostData['p_category_id'],
								'product_code' => $newPostData['product_code'],
								'product_description' => $newPostData['product_description'],
								'product_name' => $newPostData['product_name'],
								'product_barcode' => $newPostData['product_barcode'],
								'created_by' => \Auth::user()->id,
							   ]);
							
							//check if users created add data in user detail
							if($created->id){ 
								$returnData = array('success' => config('messages.message.productSaved'),'p_category_id' => $newPostData['p_category_id']);
							}else{
								$returnData = array('error' => config('messages.message.productNotSaved'));
							}
					  }else{
						  $returnData = array('error' => config('messages.message.productNameCatExist'));
					  }
					}else{
						$returnData = array('error' => config('messages.message.productExist'));
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
     * isProductExist Is used to check the product duplicate entry by product_code
     * Date : 01-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isProductExist($product_code) 
    { 
		if(!empty($product_code)){
			$productData = DB::table('product_master')
						->where('product_master.product_code', '=', $product_code)
						->first(); 
			if(@$productData->product_id){
				return $productData->product_id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function isProductNameExist($product_name,$p_category_id) 
    { 
		if(!empty($product_name)){
			$productData = DB::table('product_master')
						->where('product_master.product_name', '=', $product_name)
						->where('product_master.p_category_id', '=', $p_category_id)
						->first(); 
			if(@$productData->product_id){
				return $productData->product_id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	/**
     * Get list of products using product category id.
     * Date : 21-2-2017
	 * Author : nisha
     */
    public function getProductListByCatId(Request $request,$p_category_id){ 
		global $models;
		$pro = DB::table('product_master')
	           ->join('product_categories','product_categories.p_category_id','product_master.p_category_id')
			   ->join('product_categories as category','product_categories.parent_id','category.p_category_id')				 
			   ->join('users', 'product_master.created_by', '=', 'users.id')				   
	           ->select('product_master.*','product_categories.p_category_name','users.name as createdBy','category.p_category_name as parent_category_name');
        
		if(!empty($p_category_id)){
			$pro = $pro->where('product_master.p_category_id','=',$p_category_id)
					->Where('product_categories.p_category_id','=',$p_category_id);
		}
		//Filtering records according to search keyword
		if(!empty($request->keyword) && strlen($request->keyword) > 1){
		    $keyword = $request->keyword;
		    $pro->where(function($pro) use ($models,$keyword){
			return $pro->where('product_master.product_name','like','%'.$keyword.'%')
				->Orwhere('product_master.product_code','like','%'.$keyword.'%')
				->Orwhere('product_categories.p_category_name','=','%'.$keyword.'%')
				->Orwhere('product_master.product_description','like','%'.$keyword.'%')
				->Orwhere('users.name','like','%'.$keyword.'%')
				->Orwhere('product_master.created_at','like','%'.date("Y-m-d", strtotime($keyword)).'%')
				->Orwhere('product_master.updated_at','like','%'.$keyword.'%');
		    });
		}

		$products=$pro->get();
		
		$models->formatTimeStampFromArray($products,DATETIMEFORMAT);
		return response()->json([
		   'productsList' => $products,
		]);	
    }   
	
	/**
     * get product using multisearch.
     * Date : 21-04-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProductListMultisearch(Request $request)
    { 
	    $searchArry=$request['data']['formData'];  		
		global $models;
		$pro = DB::table('product_master')
			   ->join('product_categories','product_categories.p_category_id','product_master.p_category_id')
			   ->join('product_categories as category','product_categories.parent_id','category.p_category_id')				 
			   ->join('users', 'product_master.created_by', '=', 'users.id')				   
			   ->select('product_master.*','product_categories.p_category_name','users.name as createdBy','category.p_category_name as parent_category_name');
                  
				if(!empty($searchArry['search_product_code'])){
					$pro->where('product_master.product_code','like','%'.$searchArry['search_product_code'].'%');
				}
				if(!empty($searchArry['search_p_category_id'])){
					$pro->where('product_categories.p_category_id','like','%'.$searchArry['search_p_category_id'].'%');
				}
				if(!empty($searchArry['search_product_barcode'])){
					$pro->where('product_master.product_barcode','like','%'.$searchArry['search_product_barcode'].'%');
				}
				if(!empty($searchArry['search_product_name'])){
					$pro->where('product_master.product_name','like','%'.$searchArry['search_product_name'].'%');
				}
				if(!empty($searchArry['search_product_desc'])){
					$pro->where('product_master.product_description','like','%'.$searchArry['search_product_desc'].'%');
				}
				if(!empty($searchArry['search_created_by'])){
					$pro->where('users.name','like','%'.$searchArry['search_created_by'].'%');
				}
				if(!empty($searchArry['search_created_at'])){
					$pro->where('product_master.created_at','like','%'.$searchArry['search_created_at'].'%');
				}
				if(!empty($searchArry['search_updated_at'])){
					$pro->where('product_master.updated_at','like','%'.$searchArry['search_updated_at'].'%');
				}
		$products=$pro->get();	 
		$models->formatTimeStampFromArray($products,DATETIMEFORMAT);
		return response()->json([
		   'productsList' => $products,
		]);	
    }

	
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProductData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				//get user by email id
				$productData = DB::table('product_master')
								->join('product_categories', 'product_master.p_category_id','product_categories.p_category_id' )
								->where('product_master.product_id', '=', $request['data']['id'])
								->first();
				
				if($productData->product_id){
					$returnData = array('responseData' => $productData);				
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
    public function updateProductData(Request $request)
    {
        $returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['formData'])){   
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);
				unset($newPostData['_token']);				
				unset($newPostData['product_category_name']);				
				if(empty($newPostData['product_id']))
				{
					$returnData = array('error' => config('messages.message.productCodeRequired'));
				}else if(empty($newPostData['p_category_id']))
				{
					$returnData = array('error' => config('messages.message.productCatCodeRequired'));
				}else if(empty($newPostData['product_description'])){
					$returnData = array('error' => config('messages.message.productDescRequired'));
				}else if(empty($newPostData['product_name'])){
					$returnData = array('error' => config('messages.message.productNameRequired'));
				}else{    
					$newPostData['product_id']=base64_decode($newPostData['product_id']);  
					$updated = DB::table('product_master')->where('product_id',$newPostData['product_id'])->update($newPostData);
					if($updated){
						$returnData = array('success' =>  config('messages.message.productUpdated'));
					}else{
						$returnData = array('success' =>  config('messages.message.savedNoChange'));
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProductData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
 				try { 
					$product = DB::table('product_master')->where('product_id', $request['data']['id'])->delete();
					if($product){
						$returnData = array('success' => config('messages.message.productDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.productNotDeleted'));					
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
	
	/**
	* upload products csv data
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
    public function uploadProductCSV(Request $request){
		global $models,$productCategory; 
		$prefix=!empty(config('messages.message.productPrefix'))?config('messages.message.productPrefix'):'PRO';

		$error 		= '0';
        $message 	= config('messages.message.error');
		$proData = array();
		$allowedFormat = array('application/vnd.ms-excel','application/csv','text/csv');
		$allowedFields = array('product_category_name*','product_barcode','product_name*','product_description');
		
		if(empty($_FILES['productMasterFile']['name'])){			 
			 $message = config('messages.message.fileNotSelected');	
		}else if(in_array($_FILES['productMasterFile']['type'],$allowedFormat) && $_FILES['productMasterFile']['size'] > '0'){
			$productTestData = $models->csvToArray($_FILES['productMasterFile']['tmp_name']);
			
			//check csv file valid or not
			foreach($allowedFields as $headerVal){
				if(!in_array($headerVal,$productTestData['header'])){
					$headerMsgArr[] = $headerVal;             //invalid columns array
				}
			}	
			
			if(!empty($headerMsgArr)){			
				$message = config('messages.message.invalidFileCoulmn');
			}else{ 
				if(!empty($productTestData['data'])){
					foreach($productTestData['data'] as $key=>$data){
						$proData[$key]['p_category_id'] = !empty($data[0])? $models->getTableUniqueIdByName('product_categories','p_category_name',trim($data[0]),'p_category_id') :'0';
						$proData[$key]['product_barcode'] = !empty($data[1])? trim($data[1]) :''; 
						$proData[$key]['product_name'] = !empty($data[2])?trim($data[2]): ''; 
						$proData[$key]['product_description'] = !empty($data[3])?trim($data[3]): $proData[$key]['product_name'];
						$proData[$key]['created_by'] = \Auth::user()->id;
						
						//validations
						$rowNum = $key+2;
						if(empty($proData[$key]['p_category_id']) || empty($proData[$key]['product_name']) || empty($proData[$key]['product_description'])){
							$messageArr[] = 'Error in row '.$rowNum;
						}	
						if($productCategory->getCategoryLevel($proData[$key]['p_category_id']) != 2){
							$messageArr[] = 'Error in product category level at row '.$rowNum;
						}						
						if(!empty($this->isProductNameExist($proData[$key]['product_name'],$proData[$key]['p_category_id']))){	
							$messageArr[] = 'Duplicate record at row '.$rowNum;
						}
					}
					if(!empty($messageArr)){
						$message = implode(',',$messageArr); 
					}else{
						if(!empty($proData)){				
							foreach($proData as $key=>$newPostData){
								$newPostData['product_code'] = $models->generateCode($prefix,'product_master','product_code','product_id');     //autogenerate product code
								if(!empty($newPostData)){
									DB::table('product_master')->insert($newPostData);
								}
							}					
							$error 		= '1';
							$message 	= config('messages.message.success');
						}else{			
							$message 	= config('messages.message.noRecordFound');
						}
					}
				}else{					
					$message 	= config('messages.message.provideAppData');
				}
			}
		}else{
			$message = config('messages.message.invalidFileType');
		}
        
		return response()->json(array('error' => $error, 'message' => $message));		
    }
	
}

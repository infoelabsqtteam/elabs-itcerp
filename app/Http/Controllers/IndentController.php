<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\User;
use App\Indent;
use Validator;
use Route;
use DB;

class IndentController extends Controller
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
        global $models,$indent;
		$models = new Models();
		$indent = new Indent();
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
		
        return view('inventory.indent.index',['title' => 'Indents','_indents' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    } 
	
	public function testing()
    {  
	   return view('inventory.indent.testing');
    }
	
	public function getAutoGenCode($sectionName)
    {  
	   global $models;
	   $number=$models->getAutoGenCode($sectionName);
	   return response()->json(['IndentNumber'=>$number]);	
    }
	
	public function get_indent_inputs()
    {  
	   return view('inventory.indent.indent_inputs');
    }

    /** create new Indent
     *  Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function createIndent(Request $request)
    {
		global $indent;
		$returnData = array();
		$user_id=\Auth::user()->id;
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				$data = array();
				parse_str($request['data']['formData'], $newPostData);  
				$itemArray=array_filter($newPostData['item_id']); 
				$requiredQtyArray=array_filter($newPostData['indent_qty']); 
				$requiredDateArray=array_filter($newPostData['required_by_date']);  
				$length=count($itemArray);
				if(count($requiredQtyArray)!=$length || count($requiredDateArray)!=$length){
					$returnData = array('error' => config('messages.message.required'));
				}else{
					unset($newPostData['_token']); 
					unset($newPostData['item_id']); 
					unset($newPostData['indent_qty']);  
					unset($newPostData['required_by_date']);  
					$indentHdrArray=$newPostData; 
					$indentHdrArray['created_by']=$user_id;  
					if(empty($newPostData['indent_date'])){
						$returnData = array('error' => config('messages.message.indentDateRequired'));
					}else if(empty($newPostData['indent_no'])){
						$returnData = array('error' => config('messages.message.indentNumberRequired'));
					}else if(empty($newPostData['indented_by'])){
						$returnData = array('error' => config('messages.message.indentByRequired'));
					}else if(empty($newPostData['division_id'])){
						$returnData = array('error' => config('messages.message.divisionRequired'));
					}else{   			  
							$createdIndent =DB::table('indent_hdr')->insertGetId($indentHdrArray); 
							//if MRS generated save details to details table
							if(!empty($createdIndent)){ 
								foreach($itemArray as $key=>$value){
									if(!empty($value)){
										$indentDetailArray['indent_hdr_id']=$createdIndent;
										$indentDetailArray['item_id']=$indent->getItemId($value);
										$indentDetailArray['indent_qty']=$requiredQtyArray[$key];
										$indentDetailArray['required_by_date']=$requiredDateArray[$key];				
										$indentDetail=DB::table('indent_hdr_detail')->insertGetId($indentDetailArray);
										if(!empty($indentDetail)){
											$data['saved'][]=$value;
										}else{
											$data['notSavedItem'][]=$value;
										}
									}
								}  
								if(!empty($createdIndent)){
								   $returnData = array('success' => config('messages.message.indentSaved'));	
								}else{
								  $returnData = array('success' => config('messages.message.error'));
								}
							}else{
								$returnData = array('error' => config('messages.message.indentNotGenerated'));
							}
					}
				}				
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
			return response()->json(['returnData'=>$returnData,'data'=>$data]);		
		}
	}
	
    /**
     * Get list of indents on page load.
     * Date : 01-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getIndentsList($division_id)
    {
		global $models;
		$ind = DB::table('indent_hdr')
					->join('indent_hdr_detail','indent_hdr.indent_hdr_id','=','indent_hdr_detail.indent_hdr_id')
					->leftjoin('divisions','indent_hdr.division_id','=','divisions.division_id')
					->leftjoin('users','indent_hdr.indented_by','=','users.id')
					->leftjoin('item_master','indent_hdr_detail.item_id','=','item_master.item_id')
					->join('users as u','indent_hdr.created_by','u.id')	
					->select('indent_hdr.*','indent_hdr_detail.*','divisions.division_name','item_master.item_code','item_master.item_name','users.name','u.name as createdBy');
					if($division_id && is_numeric($division_id))
					{					
					   $ind=$ind->where('indent_hdr.division_id','=',$division_id);
					}
		$indents = $ind->groupBy('indent_hdr_detail.indent_hdr_id')->get(); 	
		$models->formatTimeStampFromArray($indents,DATETIMEFORMAT);
		return response()->json([
		   'indentsList' => $indents,
		]);
    } 
	
	
	public function getItemDesc($keyword)
    {
		if(!empty($item_id)){
			$res = DB::table('item_master')
					->select('item_master.item_description')
					->where('item_master.item_code', 'LIKE', "%$keyword%")	
					->first(); 	
			if(!empty($res)){
				$desc=$res->item_description;
			}else{
				$desc="Description not available!";
			}
		}else{
			$desc="";
		}
		return response()->json([
		   'itemDesc' => $desc,
		]);
    }    
	
	//Get list of methods list
	public function getItemMasterList()
    {
		$itemList= DB::table('item_master')
						->select('item_id as id','item_code as name')
						->get(); 	
		return json_encode($itemList);
    }
	   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editIndentData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){ 
				//get user by email id
				$indentHdr = DB::table('indent_hdr')
							->leftjoin('divisions','indent_hdr.division_id','=','divisions.division_id')
							->leftjoin('users','indent_hdr.indented_by','=','users.id')
							->select('indent_hdr.*','divisions.division_name','users.name','users.id as user_id')
							->where('indent_hdr.indent_hdr_id','=',$request['data']['id'])
							->first();
				if(!empty($indentHdr)){
					$indentDtl = DB::table('indent_hdr')
								->join('indent_hdr_detail','indent_hdr.indent_hdr_id','=','indent_hdr_detail.indent_hdr_id')
								->leftjoin('divisions','indent_hdr.division_id','=','divisions.division_id')
								->leftjoin('users','indent_hdr.indented_by','=','users.id')
								->leftjoin('item_master','indent_hdr_detail.item_id','=','item_master.item_id')
								->select('indent_hdr_detail.*','item_master.item_id','item_master.item_name','item_master.item_code','item_master.item_description')
								->where('indent_hdr_detail.indent_hdr_id','=',$request['data']['id'])
								->get(); 
				}			    
				if(!empty($indentHdr) && !empty($indentDtl)){
					$returnData = array('indentHdr' => $indentHdr,'indentDtl' => $indentDtl);				
				}else{
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
				$returnData = array('error' => config('messages.message.provideAppData'));			
		}
		return response()->json(['returnData'=>$returnData]);	
    }
	
    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateIndentData(Request $request)
    {
        global $indent; 
		$returnData = array();
		if ($request->isMethod('post')) { 
			if(!empty($request['data']['formData'])){   
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);		
				unset($newPostData['indent_date']); 		
				$itemArray=array_filter($newPostData['item_id']); 
				$requiredQtyArray=array_filter($newPostData['indent_qty']); 
				$indentDetailIdArray=$newPostData['indent_dtl_id']; 
				$requiredDateArray=array_filter($newPostData['required_by_date']); 
				$length=count($itemArray);
				if(count($requiredQtyArray)!=$length || count($requiredDateArray)!=$length){
					$returnData = array('error' => config('messages.message.required'));
				}else{
					unset($newPostData['_token']); 
					unset($newPostData['item_id']); 
					unset($newPostData['indent_qty']); 
					unset($newPostData['indent_dtl_id']);
					unset($newPostData['required_by_date']);  
					$indentHdrArray=$newPostData;
					if(empty($newPostData['indented_by'])){
						$returnData = array('error' => config('messages.message.indentByRequired'));
					}else if(empty($newPostData['division_id'])){
						$returnData = array('error' => config('messages.message.divisionRequired'));
					}else{ 				  
						$updatedIndentHdr =DB::table('indent_hdr')->where('indent_hdr_id','=',$indentHdrArray['indent_hdr_id'])->update($indentHdrArray); 
						//if MRS generated save details to details table
						foreach($itemArray as $key=>$value){
							if(!empty($value)){
								$indentDetailArray = array();
								$indentDetailArray['indent_hdr_id']		=	$indentHdrArray['indent_hdr_id'];
								$indentDetailArray['indent_dtl_id']		=	$indentDetailIdArray[$key];
								$indentDetailArray['item_id']			=	$indent->getItemId($value);
								$indentDetailArray['indent_qty']		=	$requiredQtyArray[$key];
								$indentDetailArray['required_by_date']	=	$requiredDateArray[$key];
								if(!empty($indentDetailArray['indent_dtl_id'])){
									$indentUpdatedDetails=DB::table('indent_hdr_detail')->where('indent_dtl_id','=',$indentDetailArray['indent_dtl_id'])->update($indentDetailArray);	
								}else{
									$indentUpdatedDetails=DB::table('indent_hdr_detail')->insert($indentDetailArray);			
								}	
							}
						}						
						$returnData = array('success' => config('messages.message.indentUpdated'));							
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
     * Remove indent from indent hdr table.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteIndentData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){ 
				   try { 
						$indent = DB::table('indent_hdr')->where('indent_hdr_id', $request['data']['id'])->delete();
						if($indent){
							$returnData = array('success' => config('messages.message.deleted'));
						}else{
							$returnData = array('error' => config('messages.message.deletedError'));					
						}
					}catch(\Illuminate\Database\QueryException $ex){ 
					   $returnData = array('error' => "Cannot delete or update a parent row: a foreign key constraint fails!");
					}	
			}else{
				$returnData = array('error' => config('messages.message.noRecordFound'));
			}
		}
		return response()->json($returnData);
    } 
	
	/**
     *remove data from indent_hdr_detail table by  indent_dtl_id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteIndentDetailsData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){ 
				   try { 
						$indent = DB::table('indent_hdr_detail')->where('indent_dtl_id', $request['data']['id'])->delete();
						if($indent){
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

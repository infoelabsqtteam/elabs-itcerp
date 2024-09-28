<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Models;
use App\PaymentMadeHdr;
use Session;
use Validator;
use Route;
use DB;

class PaymentMadeHdrsController extends Controller
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
        global $paymentMadeHdr,$models;
        $paymentMadeHdr = new PaymentMadeHdr();
        $models         = new Models();
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
        
		return view('payments.payments_made.index',['title' => 'Payments Made','_payments_made' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
    public function paymentMadeNumber()
    {
        global $models,$paymentMadeHdr;
        
        $error   = '0';
        $message = '';
        $data    = '';
        
        $paymentMadeNumber = $paymentMadeHdr->generatePaymentMadeNumber('PM');        
        return response()->json(array('error'=> $error,'message'=> $message,'paymentMadeNumber'=> $paymentMadeNumber));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPaymentsMade(Request $request)
    {
        global $models,$paymentMadeHdr;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();	
        
        //Saving record in table
        if(!empty($request->formData) && $request->isMethod('post')){                        
                        
            parse_str($request->formData, $formData);
			$formData = array_filter($formData);
                                    
            if(!empty($formData)){                
                
                if(empty($formData['division_id'])){
                    $message = config('messages.message.divisionNameErrorMsg');
                }else if(empty($formData['vendor_id'])){
                    $message = config('messages.message.vendorNameRequired');
                }else if(empty($formData['payment_made_no'])){
                    $message = config('messages.message.paymentMadeNoRequired');
				}else if($paymentMadeHdr->checkPaymentMadeNumber($formData['payment_made_no'])){
					$message = config('messages.message.paymentMadeNoExist');
                }else if(empty($formData['payment_made_date'])){
                    $message = config('messages.message.paymentMadeDateRequired');
				}else if(isset($formData['payment_made_amount']) && $formData['payment_made_amount'] == ''){
                    $message = config('messages.message.amountMadeRequired');
                }else if(empty($formData['payment_source_id'])){
                    $message = config('messages.message.paymentSourceRequired');				
				}else{
					//Unsetting the variable from request data
					$formData = $models->unsetFormDataVariables($formData,array('_token'));                        
                    $formData['created_by'] = USERID;					
					//echo '<pre>';print_r($formData);die;                    
                    if(!empty($formData['payment_made_no'])){
                        $paymentMadeHdrId = DB::table('payment_made_hdr')->insertGetId($formData);
                        if(!empty($paymentMadeHdrId)){
                            $error   = '1';
                            $message = config('messages.message.saved');                                          
                        }
                    }else{
						$message = config('messages.message.savedError');    
					}
                }                
            }
        }        
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data));	
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDivisionWisePaymentsMade($division_id)
    {
		global $models,$paymentMadeHdr;
		
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
		
		$dataObj = DB::table('payment_made_hdr')
                    ->join('divisions','divisions.division_id','payment_made_hdr.division_id')
                    ->join('vendors','vendors.vendor_id','payment_made_hdr.vendor_id')
					->join('payment_sources','payment_sources.payment_source_id','payment_made_hdr.payment_source_id')
					->join('users as createdBy','createdBy.id','payment_made_hdr.created_by')	
		            ->select('payment_made_hdr.*','divisions.division_name','vendors.vendor_name','payment_sources.payment_source_name','createdBy.name as createdByName');
				   
		if(!empty($division_id) && is_numeric($division_id)){
			$dataObj->where('payment_made_hdr.division_id',$division_id);
		}
		
		$paymentsMadeList = $dataObj->orderBy('payment_made_hdr.payment_made_hdr_id','DESC')->get();
		$error            = !empty($paymentsMadeList) ? 1 : '0';
		$message          = $error ? '' : $message;
		
		//to formate created and updated date		   
		$models->formatTimeStampFromArray($paymentsMadeList,DATETIMEFORMAT);		
		return response()->json(array('error'=> $error,'message'=> $message,'paymentsMadeList'=> $paymentsMadeList));
    }

	/**
     * get patment made using multisearch.
     * Date : 30-05-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPaymentMadeMultisearch(Request $request)
    {   
	    $searchArry=$request['data']['formData'];
		global $models;
			    $dataObj = DB::table('payment_made_hdr')
						->join('divisions','divisions.division_id','payment_made_hdr.division_id')
						->join('vendors','vendors.vendor_id','payment_made_hdr.vendor_id')
						->join('payment_sources','payment_sources.payment_source_id','payment_made_hdr.payment_source_id')
						->join('users as createdBy','createdBy.id','payment_made_hdr.created_by')	
						->select('payment_made_hdr.*','divisions.division_name','vendors.vendor_name','payment_sources.payment_source_name','createdBy.name as createdByName');
			   
				if(!empty($searchArry['search_payment_made_no'])){
					$dataObj->where('payment_made_hdr.payment_made_no','like','%'.$searchArry['search_payment_made_no'].'%');
				}
				if(!empty($searchArry['search_division_id'])){
					$dataObj->where('divisions.division_id','=',$searchArry['search_division_id']);
				}			
				if(!empty($searchArry['search_vendor_name'])){
					$dataObj->where('vendors.vendor_name','like','%'.$searchArry['search_vendor_name'].'%');
				}		
				if(!empty($searchArry['search_payment_made_date'])){
					$dataObj->where('payment_made_hdr.payment_made_date','like','%'.date("Y-m-d", strtotime($searchArry['search_payment_made_date'])).'%');
				}		
				if(!empty($searchArry['search_payment_made_amount'])){
					$dataObj->where('payment_made_hdr.payment_made_amount','like','%'.$searchArry['search_payment_made_amount'].'%');
				}		
				if(!empty($searchArry['search_payment_source_name'])){
					$dataObj->where('payment_sources.payment_source_name','like','%'.$searchArry['search_payment_source_name'].'%');
				}			
				if(!empty($searchArry['search_created_by'])){
					$dataObj->where('createdBy.name','like','%'.$searchArry['search_created_by'].'%');
				}
				
		$paymentsMadeList = $dataObj->orderBy('payment_made_hdr.payment_made_hdr_id','DESC')->get();	 
		$models->formatTimeStampFromArray($paymentsMadeList,DATETIMEFORMAT);
		return response()->json([
		   'paymentsMadeList' => $paymentsMadeList,
		]); 
    }

	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewPaymentsMade($payment_made_hdr_id)
    {
		global $models,$paymentMadeHdr;
		
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
		
		$paymentsMadeData = DB::table('payment_made_hdr')
                    ->join('divisions','divisions.division_id','payment_made_hdr.division_id')
                    ->join('vendors','vendors.vendor_id','payment_made_hdr.vendor_id')
					->join('payment_sources','payment_sources.payment_source_id','payment_made_hdr.payment_source_id')
					->join('users as createdBy','createdBy.id','payment_made_hdr.created_by')	
		            ->select('payment_made_hdr.*','divisions.division_name','vendors.vendor_name','payment_sources.payment_source_name','createdBy.name as createdByName')
					->where('payment_made_hdr.payment_made_hdr_id',$payment_made_hdr_id)
				    ->orderBy('payment_made_hdr.payment_made_hdr_id','DESC')
					->first();
					
		$error    = !empty($paymentsMadeData) ? 1 : '0';		
		return response()->json(array('error'=> $error,'message'=> $message,'paymentsMade'=> $paymentsMadeData));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePaymentsMade(Request $request)
    {
        global $models,$paymentMadeHdr;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();	
        
        if ($request->isMethod('post') && !empty($request['formData'])){
            
            //pasrse searlize data 				
            parse_str($request['formData'], $formData);           
            $paymentMadeHdrId = !empty($formData['payment_made_hdr_id']) ? $formData['payment_made_hdr_id'] : '0';
			
            if(empty($formData['division_id'])){
				$message = config('messages.message.divisionNameErrorMsg');
			}else if(empty($formData['vendor_id'])){
				$message = config('messages.message.vendorNameRequired');
			}else if(empty($formData['payment_made_date'])){
				$message = config('messages.message.paymentMadeDateRequired');
			}else if(isset($formData['payment_made_amount']) && $formData['payment_made_amount'] == ''){
				$message = config('messages.message.amountMadeRequired');
			}else if(empty($formData['payment_source_id'])){
				$message = config('messages.message.paymentSourceRequired');				
			}else{
				//Unsetting the variable from request data
				$formData = $models->unsetFormDataVariables($formData,array('_token','payment_made_hdr_id'));
				//$formData['created_by'] = USERID;
                //echo '<pre>';print_r($formData);die;                
                if(!empty($paymentMadeHdrId) && !empty($formData)){
                    if(DB::table('payment_made_hdr')->where('payment_made_hdr.payment_made_hdr_id',$paymentMadeHdrId)->update($formData)){
						$error   = '1';
						$message = config('messages.message.updated');
					}else{
						$error   = '1';
						$message = config('messages.message.savedNoChange');
					}
                }else{
                    $message = config('messages.message.updatedError'); 
                }
            }			
		}
        
		return response()->json(['error'=> $error,'message'=> $message,'data'=> $data,'payment_made_hdr_id' => $paymentMadeHdrId]);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function deletePaymentMade(Request $request, $payment_made_hdr_id)
    {
        $error   = '0';
        $message = '';
        $data    = '';
        
        try{
            if(DB::table('payment_made_hdr')->where('payment_made_hdr.payment_made_hdr_id','=',$payment_made_hdr_id)->delete()){
                $error   = '1';
                $message = config('messages.message.deleted');      
            }else{
                $message = config('messages.message.deletedError');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.deletedErrorFKC');
        }             
		return response()->json(['error' => $error,'message' => $message]);
    }
}

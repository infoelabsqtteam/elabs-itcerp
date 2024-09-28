<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\PaymentReceivedHdr;
use App\Models;
use Session;
use Validator;
use Route;
use DB;

class PaymentReceivedHdrsController extends Controller
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
        global $paymentReceivedHdr,$models;
        $paymentReceivedHdr = new PaymentReceivedHdr();
        $models = new Models();
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
        
		return view('payments.payments_received.index',['title' => 'Payments Received','_payments_received' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
    public function paymentReceivedNumber()
    {
        global $models,$paymentReceivedHdr;
        
        $error   = '0';
        $message = '';
        $data    = '';
        
        $paymentReceivedNumber = $paymentReceivedHdr->generatePaymentReceivedNumber('PR');        
        return response()->json(array('error'=> $error,'message'=> $message,'paymentReceivedNumber'=> $paymentReceivedNumber));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPaymentsReceived(Request $request)
    {
        global $models,$paymentReceivedHdr;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();	
        
        //Saving record in orders table
        if(!empty($request->formData) && $request->isMethod('post')){                        
                        
            parse_str($request->formData, $formData);
			$formData = array_filter($formData);
                                    
            if(!empty($formData)){                
                
                if(empty($formData['division_id'])){
                    $message = config('messages.message.divisionNameErrorMsg');
                }else if(empty($formData['customer_id'])){
                    $message = config('messages.message.customerNameRequired');
                }else if(empty($formData['payment_received_no'])){
                    $message = config('messages.message.paymentReceivedNoRequired');
				}else if($paymentReceivedHdr->checkPaymentReceivedNumber($formData['payment_received_no'])){
					$message = config('messages.message.paymentReceivedNoExist');
                }else if(empty($formData['payment_received_date'])){
                    $message = config('messages.message.paymentReceivedDateRequired');
				}else if(isset($formData['payment_amount_received']) && $formData['payment_amount_received'] == ''){
                    $message = config('messages.message.amountReceivedRequired');
                }else if(empty($formData['payment_source_id'])){
                    $message = config('messages.message.depositedWithRequired');				
				}else{
					//Unsetting the variable from request data
					$formData = $models->unsetFormDataVariables($formData,array('_token'));                        
                    $formData['created_by'] = USERID;					
					//echo '<pre>';print_r($formData);die;                    
                    if(!empty($formData['payment_received_no'])){
                        $paymentReceivedHdrId = DB::table('payment_received_hdr')->insertGetId($formData);
                        if(!empty($paymentReceivedHdrId)){
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
    public function getDivisionWisePaymentsReceived($division_id)
    {
		global $models,$paymentReceivedHdr;
		
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
		
		$dataObj = DB::table('payment_received_hdr')
                    ->join('divisions','divisions.division_id','payment_received_hdr.division_id')
                    ->join('customer_master','customer_master.customer_id','payment_received_hdr.customer_id')
					->join('payment_sources','payment_sources.payment_source_id','payment_received_hdr.payment_source_id')
					->join('users as createdBy','createdBy.id','payment_received_hdr.created_by')	
		            ->select('payment_received_hdr.*','divisions.division_name','customer_master.customer_name','payment_sources.payment_source_name','createdBy.name as createdByName');
				   
		if(!empty($division_id) && is_numeric($division_id)){
			$dataObj->where('payment_received_hdr.division_id',$division_id);
		}
		
		$paymentsReceivedList = $dataObj->orderBy('payment_received_hdr.payment_received_hdr_id','DESC')->get();
		$error                = !empty($paymentsReceivedList) ? 1 : '0';
		$message              = $error ? '' : $message;
		
		//to formate created and updated date		   
		$models->formatTimeStampFromArray($paymentsReceivedList,DATETIMEFORMAT);
		
		return response()->json(array('error'=> $error,'message'=> $message,'paymentsReceivedList'=> $paymentsReceivedList));
    }
	
	/**
     * get patment received using multisearch.
     * Date : 30-05-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPaymentReceivedMultisearch(Request $request)
    {   
	    $searchArry=$request['data']['formData']; 	
		global $models;
			$dataObj = DB::table('payment_received_hdr')
						->join('divisions','divisions.division_id','payment_received_hdr.division_id')
						->join('customer_master','customer_master.customer_id','payment_received_hdr.customer_id')
						->join('payment_sources','payment_sources.payment_source_id','payment_received_hdr.payment_source_id')
						->join('users as createdBy','createdBy.id','payment_received_hdr.created_by')	
						->select('payment_received_hdr.*','divisions.division_name','customer_master.customer_name','payment_sources.payment_source_name','createdBy.name as createdByName');
					   
				if(!empty($searchArry['search_payment_received_no'])){
					$dataObj->where('payment_received_hdr.payment_received_no','like','%'.$searchArry['search_payment_received_no'].'%');
				}
				if(!empty($searchArry['search_division_id'])){
					$dataObj->where('divisions.division_id','=',$searchArry['search_division_id']);
				}			
				if(!empty($searchArry['search_customer_name'])){
					$dataObj->where('customer_master.customer_name','like','%'.$searchArry['search_customer_name'].'%');
				}		
				if(!empty($searchArry['search_payment_received_date'])){
					$dataObj->where('payment_received_hdr.payment_received_date','like','%'.date("Y-m-d", strtotime($searchArry['search_payment_received_date'])).'%');
				}		
				if(!empty($searchArry['search_payment_amount_received'])){
					$dataObj->where('payment_received_hdr.payment_amount_received','like','%'.$searchArry['search_payment_amount_received'].'%');
				}		
				if(!empty($searchArry['search_payment_source_name'])){
					$dataObj->where('payment_sources.payment_source_name','like','%'.$searchArry['search_payment_source_name'].'%');
				}			
				if(!empty($searchArry['search_created_by'])){
					$dataObj->where('createdBy.name','like','%'.$searchArry['search_created_by'].'%');
				}
				
		$paymentsReceivedList = $dataObj->orderBy('payment_received_hdr.payment_received_hdr_id','DESC')->get();	 
		$models->formatTimeStampFromArray($paymentsReceivedList,DATETIMEFORMAT);
		return response()->json([
		   'paymentsReceivedList' => $paymentsReceivedList,
		]); 
    }

	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewPaymentsReceived($payment_received_hdr_id)
    {
		global $models,$paymentReceivedHdr;
		
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
		
		$paymentsReceived = DB::table('payment_received_hdr')
                    ->join('divisions','divisions.division_id','payment_received_hdr.division_id')
                    ->join('customer_master','customer_master.customer_id','payment_received_hdr.customer_id')
					->join('payment_sources','payment_sources.payment_source_id','payment_received_hdr.payment_source_id')
					->join('users as createdBy','createdBy.id','payment_received_hdr.created_by')	
		            ->select('payment_received_hdr.*','divisions.division_name','customer_master.customer_name','payment_sources.payment_source_name','createdBy.name as createdByName')
					->where('payment_received_hdr.payment_received_hdr_id',$payment_received_hdr_id)
				    ->orderBy('payment_received_hdr.payment_received_hdr_id','DESC')
					->first();
					
		$error    = !empty($paymentsReceived) ? 1 : '0';		
		return response()->json(array('error'=> $error,'message'=> $message,'paymentsReceived'=> $paymentsReceived));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePaymentsReceived(Request $request)
    {
        global $models,$paymentReceivedHdr;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();	
        
        if ($request->isMethod('post') && !empty($request['formData'])){
            
            //pasrse searlize data 				
            parse_str($request['formData'], $formData);           
            $paymentReceivedHdrId = !empty($formData['payment_received_hdr_id']) ? $formData['payment_received_hdr_id'] : '0';
			
            if(empty($formData['division_id'])){
				$message = config('messages.message.divisionNameErrorMsg');
			}else if(empty($formData['customer_id'])){
				$message = config('messages.message.customerNameRequired');
			}else if(empty($formData['payment_received_date'])){
				$message = config('messages.message.paymentReceivedDateRequired');
			}else if(isset($formData['payment_amount_received']) && $formData['payment_amount_received'] == ''){
				$message = config('messages.message.amountReceivedRequired');
			}else if(empty($formData['payment_source_id'])){
				$message = config('messages.message.depositedWithRequired');				
			}else{
				//Unsetting the variable from request data
				$formData = $models->unsetFormDataVariables($formData,array('_token','payment_received_hdr_id'));
				//$formData['created_by'] = USERID;
                //echo '<pre>';print_r($formData);die;                
                if(!empty($paymentReceivedHdrId) && !empty($formData)){
                    if(DB::table('payment_received_hdr')->where('payment_received_hdr.payment_received_hdr_id',$paymentReceivedHdrId)->update($formData)){
						$error    = '1';
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
        
		return response()->json(['error'=> $error,'message'=> $message,'data'=> $data,'payment_received_hdr_id' => $paymentReceivedHdrId]);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function deletePaymentReceived(Request $request, $payment_received_hdr_id)
    {
        $error    = '0';
        $message  = '';
        $data     = '';
        
        try{
            if(DB::table('payment_received_hdr')->where('payment_received_hdr.payment_received_hdr_id','=',$payment_received_hdr_id)->delete()){
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

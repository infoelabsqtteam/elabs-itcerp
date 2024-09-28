<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PaymentReceivedHdr extends Model
{
    protected $table = 'payment_received_hdr';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
	
	function generatePaymentReceivedNumber($section){
		
		$currentDay   		  = date('d');
		$currentMonth 		  = date('m');
		$currentYear  		  = date('y');			
							
		//getting Max Serial Number
		$maxPaymentReceivedData = DB::table('payment_received_hdr')->select('payment_received_hdr.payment_received_hdr_id','payment_received_hdr.payment_received_no')->whereMonth('payment_received_hdr.payment_received_date',$currentMonth)->orderBy('payment_received_hdr.payment_received_hdr_id','DESC')->limit(1)->first();			
		$maxSerialNo       = !empty($maxPaymentReceivedData->payment_received_no) ? substr($maxPaymentReceivedData->payment_received_no,7,-2) + 1: '0001';
		$maxSerialNo       = $maxSerialNo != '9999' ? str_pad($maxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';
					
		//Combing all to get unique order number
		$paymentReceivedNumber  = $section.$currentDay.$currentMonth.$maxSerialNo.$currentYear;
		
		//echo '<pre>';print_r($maxPaymentReceivedData);die;            
		return $paymentReceivedNumber;
    }
	
	/**
	* Check Vendor Bill NO
	*
	* @return \Illuminate\Http\Response
	*/
    function checkPaymentReceivedNumber($payment_received_no,$type='add',$payment_received_hdr_id=null){		
		if($type == 'add'){
			return DB::table('payment_received_hdr')->where('payment_received_hdr.payment_received_no','=',$payment_received_no)->count();
		}else if($type == 'edit'){
			$data = DB::table('payment_received_hdr')->where('payment_received_hdr.payment_received_hdr_id','=',$payment_received_hdr_id)->where('payment_received_hdr.payment_received_no','=',$payment_received_no)->count();
			if($data){
				return false;
			}else{
				return DB::table('payment_received_hdr')->where('payment_received_hdr.payment_received_no','=',$payment_received_no)->count();
			}
		}
	}
}

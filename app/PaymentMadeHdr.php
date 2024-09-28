<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PaymentMadeHdr extends Model
{
    protected $table = 'payment_made_hdr';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
	
	function generatePaymentMadeNumber($section){
		
		$currentDay   		  = date('d');
		$currentMonth 		  = date('m');
		$currentYear  		  = date('y');			
							
		//getting Max Serial Number
		$maxPaymentMadeData = DB::table('payment_made_hdr')->select('payment_made_hdr.payment_made_hdr_id','payment_made_hdr.payment_made_no')->whereMonth('payment_made_hdr.payment_made_date',$currentMonth)->orderBy('payment_made_hdr.payment_made_hdr_id','DESC')->limit(1)->first();			
		$maxSerialNo       = !empty($maxPaymentMadeData->payment_made_no) ? substr($maxPaymentMadeData->payment_made_no,7,-2) + 1: '0001';
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
    function checkPaymentMadeNumber($payment_made_no,$type='add',$payment_made_hdr_id=null){		
		if($type == 'add'){
			return DB::table('payment_made_hdr')->where('payment_made_hdr.payment_made_no','=',$payment_made_no)->count();
		}else if($type == 'edit'){
			$data = DB::table('payment_made_hdr')->where('payment_made_hdr.payment_made_hdr_id','=',$payment_made_hdr_id)->where('payment_made_hdr.payment_made_no','=',$payment_made_no)->count();
			if($data){
				return false;
			}else{
				return DB::table('payment_made_hdr')->where('payment_made_hdr.payment_made_no','=',$payment_made_no)->count();
			}
		}
	}
}

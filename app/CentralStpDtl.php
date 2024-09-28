<?php
/*****************************************************
*Order Model File
*Created By:Praveen-Singh
*Created On : 15-Dec-2017
*Modified On : 10-Oct-2018
*Package : ITC-ERP-PKL
******************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CentralStpDtl extends Model
{
    protected $table = 'central_stp_dtls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    /**
     * Get Single Row Data
     */    
    public function getRow($id){
        return DB::table('central_stp_dtls')->join('customer_master','customer_master.customer_id','central_stp_dtls.cstp_customer_id')->select('central_stp_dtls.*','customer_master.customer_code','customer_master.customer_name')->where('central_stp_dtls.cstp_id',$id)->first(); 
    }
    
    /**
     * Get Single Row Data
     */    
    public function validationPostedStpDtl($formData){
        
        $cstp_no             = !empty($formData['cstp_no']) ? $formData['cstp_no'] : '0';
        $cstp_customer_id    = !empty($formData['cstp_customer_id']) ? $formData['cstp_customer_id'] : '0';
        $cstp_customer_city  = !empty($formData['cstp_customer_city']) ? $formData['cstp_customer_city'] : '0';
        $cstp_sample_name    = !empty($formData['cstp_sample_name']) ? trim($formData['cstp_sample_name']) : '0';
        $cstp_date           = !empty($formData['cstp_date']) ? date('Y-m-d',strtotime($formData['cstp_date'])) : '0';
        
        if($cstp_no && $cstp_customer_id && $cstp_customer_city && $cstp_sample_name && $cstp_date){
            return DB::table('central_stp_dtls')
                    ->where('central_stp_dtls.cstp_no',$cstp_no)
                    ->where('central_stp_dtls.cstp_customer_id',$cstp_customer_id)
                    ->where('central_stp_dtls.cstp_customer_city',$cstp_customer_city)
                    ->where('central_stp_dtls.cstp_sample_name','LIKE',$cstp_sample_name)
                    ->where(DB::raw("DATE(central_stp_dtls.cstp_date)"),$cstp_date)
                    ->count(); 
        }else{
           return false;
        }
    }
}

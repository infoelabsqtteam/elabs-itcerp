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

class CentralPoDtl extends Model
{
    protected $table = '';

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
        return DB::table('central_po_dtls')->join('customer_master','customer_master.customer_id','central_po_dtls.cpo_customer_id')->select('central_po_dtls.*','customer_master.customer_code','customer_master.customer_name')->where('central_po_dtls.cpo_id',$id)->first(); 
    }
    
    /**
     * Get Single Row Data
     */    
    public function validationPostedPoDtl($formData){
        
        $cpo_no             = !empty($formData['cpo_no']) ? $formData['cpo_no'] : '0';
        $cpo_customer_id    = !empty($formData['cpo_customer_id']) ? $formData['cpo_customer_id'] : '0';
        $cpo_customer_city  = !empty($formData['cpo_customer_city']) ? $formData['cpo_customer_city'] : '0';
        $cpo_sample_name    = !empty($formData['cpo_sample_name']) ? trim($formData['cpo_sample_name']) : '0';
        $cpo_date           = !empty($formData['cpo_date']) ? date('Y-m-d',strtotime($formData['cpo_date'])) : '0';
        
        if($cpo_no && $cpo_customer_id && $cpo_customer_city && $cpo_sample_name && $cpo_date){
            return DB::table('central_po_dtls')
                    ->where('central_po_dtls.cpo_no',$cpo_no)
                    ->where('central_po_dtls.cpo_customer_id',$cpo_customer_id)
                    ->where('central_po_dtls.cpo_customer_city',$cpo_customer_city)
                    ->where('central_po_dtls.cpo_sample_name','LIKE',$cpo_sample_name)
                    ->where(DB::raw("DATE(central_po_dtls.cpo_date)"),$cpo_date)
                    ->count(); 
        }else{
           return false;
        }
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrderAccreditationCertificate extends Model
{
	    protected $table = 'order_accreditation_certificate_master';

	    /**
	     * The attributes that are mass assignable.
	     *
	     * @var array
	     */
	
	    protected $fillable = [];
    
	    /*********************************************************
	    *validating Record Existance
	    *Created on:29-Aug-2018
	    *Created By:Ruby
	    *********************************************************/	
	    public function checkRecordExistance($postedData){
			$recordExist = DB::table('order_accreditation_certificate_master')->where('order_accreditation_certificate_master.oac_division_id',$postedData['oac_division_id'])->where('order_accreditation_certificate_master.oac_product_category_id',$postedData['oac_product_category_id'])->where('order_accreditation_certificate_master.oac_name',$postedData['oac_name'])->where('order_accreditation_certificate_master.oac_status',$postedData['oac_status'])->first();
			return !empty($recordExist) ? '1' : '0';
	    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CustomerExistAccountHoldUploadDtl extends Model
{
    protected $table = 'customer_exist_account_hold_upload_dtl';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * validate Customer Exist Account
     * Date : 17-June-2019
     * Author : Praveen Singh
     */
    public function validateCustomerExistAccount($value)
    {
        return DB::table('customer_exist_account_hold_upload_dtl')->where('customer_exist_account_hold_upload_dtl.ceahud_customer_code',$value['ceahud_customer_code'])->first();
    }

    
}

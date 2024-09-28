<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class customerComCrmEmailAddress extends Model
{
    protected $table = 'customer_com_crm_email_addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Description : isCustomerExist Is used to check the customer duplicate entry by customer_code
     * Date        : 20-May-2021
     * Author      : Praveen Singh
     */
    public function isCustomerComCrmExist($formData, $type = 'add')
    {

        if (!empty($formData)) {
            if ($type == 'add') {
                return DB::table('customer_com_crm_email_addresses')
                    ->where('customer_com_crm_email_addresses.cccea_division_id', '=',  $formData['cccea_division_id'])
                    ->where('customer_com_crm_email_addresses.cccea_product_category_id', '=',  $formData['cccea_product_category_id'])
                    ->where('customer_com_crm_email_addresses.cccea_name', '=',  $formData['cccea_name'])
                    ->where('customer_com_crm_email_addresses.cccea_email', '=',  $formData['cccea_email'])
                    ->count();
            } elseif ($type == 'edit') {
                $data = DB::table('customer_com_crm_email_addresses')
                    ->where('customer_com_crm_email_addresses.cccea_division_id', '=',  $formData['cccea_division_id'])
                    ->where('customer_com_crm_email_addresses.cccea_product_category_id', '=',  $formData['cccea_product_category_id'])
                    ->where('customer_com_crm_email_addresses.cccea_name', '=',  $formData['cccea_name'])
                    ->where('customer_com_crm_email_addresses.cccea_email', '=',  $formData['cccea_email'])
                    ->where('customer_com_crm_email_addresses.cccea_id', '=',  $formData['cccea_id'])
                    ->count();
                if (!empty($data)) {
                    return false;
                } else {
                    return DB::table('customer_com_crm_email_addresses')
                        ->where('customer_com_crm_email_addresses.cccea_division_id', '=',  $formData['cccea_division_id'])
                        ->where('customer_com_crm_email_addresses.cccea_product_category_id', '=',  $formData['cccea_product_category_id'])
                        ->where('customer_com_crm_email_addresses.cccea_name', '=',  $formData['cccea_name'])
                        ->where('customer_com_crm_email_addresses.cccea_email', '=',  $formData['cccea_email'])
                        ->count();
                }
            }
        } else {
            return false;
        }
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CustomerGstCategory extends Model
{
    protected $table = 'customer_gst_categories';

    /**
     * Get Invoice Detail orders
     * Date : 26-July-2018
     * Author : Praveen Singh
     * @param  $invoiceId
     * @return true/false
     */
    public function getRow($id)
    {
        return DB::table('customer_gst_categories')->where('customer_gst_categories.cgc_id', $id)->first();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrderDynamicField extends Model
{
    protected $table = 'order_dynamic_field_dtl';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    /**
    * get dynamic fields Detail
    * Date : 30-Jan-2020
    * Author :Praveen Singh
    */
    public function getRow($id) {
	return DB::table('order_dynamic_field_dtl')->where('order_dynamic_field_dtl.odf_id',$id)->first();
    }
}

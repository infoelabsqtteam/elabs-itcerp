<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrderReportSignDtl extends Model
{
    protected $table = 'order_report_sign_dtls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
	 * Checking Unqiue Record
	 * Date : 20-Oct-2021
	 * Author : Praveen Singh
	 */
	public function validateUniqueRecord($data)
	{
		return DB::table('order_report_sign_dtls')->where('orsd_employee_id', $data['orsd_employee_id'])->where('orsd_division_id', $data['orsd_division_id'])->where('orsd_product_category_id', $data['orsd_product_category_id'])->count();
	}

}

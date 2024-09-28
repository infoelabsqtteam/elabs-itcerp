<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrderClientApprovalDtl extends Model
{
    protected $table = 'order_client_approval_dtl';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [];

    /****************************************************
	 * Description : Get Order Client Approval Process Detail
	 * created By  : Praveen Singh
	 * created On  : 15-May-2021
	 ***************************************************/
	public function getOrderClientApprovalDetail($orderId)
	{
		global $models;

		$data = DB::table('order_client_approval_dtl')->where('order_client_approval_dtl.ocad_order_id', '=', $orderId)->first();
		$models->formatTimeStamp($data,'d-m-Y'); //Formating Date
		return $data;
	}

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrderReportHeaderType extends Model
{
    protected $table = 'order_report_header_types';

    public function orderReportHdrExist($postedData,$id = NULL){
        
        if ($id) {
			$data = DB::table('order_report_header_types')
            ->where('order_report_header_types.orht_division_id','=',$postedData['orht_division_id'])
            ->where('order_report_header_types.orht_product_category_id','=',$postedData['orht_product_category_id'])
            ->where('order_report_header_types.orht_customer_type','=',$postedData['orht_customer_type'])
            ->where('order_report_header_types.orht_report_hdr_type','=',$postedData['orht_report_hdr_type'])
            ->where('order_report_header_types.orht_id',$id)->first();
			if (!empty($data)) {
				return true;
			} else {

				$data = DB::table('order_report_header_types')
                ->where('order_report_header_types.orht_division_id','=',$postedData['orht_division_id'])
                ->where('order_report_header_types.orht_product_category_id','=',$postedData['orht_product_category_id'])
                ->where('order_report_header_types.orht_customer_type','=',$postedData['orht_customer_type'])
                ->where('order_report_header_types.orht_report_hdr_type','=',$postedData['orht_report_hdr_type'])->count();

				return empty($data) ? true : false;
			}
		} else {
            $data = DB::table('order_report_header_types')
                ->where('order_report_header_types.orht_division_id', '=', $postedData['orht_division_id'])
                ->where('order_report_header_types.orht_product_category_id', '=', $postedData['orht_product_category_id'])
                ->where('order_report_header_types.orht_customer_type', '=', $postedData['orht_customer_type'])
                ->where('order_report_header_types.orht_report_hdr_type', '=', $postedData['orht_report_hdr_type'])->count();
            return empty($data) ? false : true;
        }
    }
}

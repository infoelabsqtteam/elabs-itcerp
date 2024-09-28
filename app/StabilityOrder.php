<?php
/*****************************************************
*StabilityOrders Model File
*Created By:Praveen-Singh
*Created On : 18-Dec-2018
*Modified On : 
*Package : ITC-ERP-PKL
******************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class StabilityOrder extends Model
{
    protected $table = 'stb_order_hdr';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    /*************************
    * Save order parameters details on add order
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    ************************/
    public function get_formated_order_parameter_detail($product_test_dtl_data_raw,$orderId){

	$orderParametersDataSave = array();

        if(!empty($product_test_dtl_data_raw) && !empty($orderId)){

	    //Parsing the form Data
            parse_str($product_test_dtl_data_raw, $product_test_dtl_data);

            if(!empty($product_test_dtl_data['order_parameters_detail'])){
		if(!empty($product_test_dtl_data['order_parameters_detail']['claim_dependent'])){
		    unset($product_test_dtl_data['order_parameters_detail']['claim_dependent']) ;
		}
		if(!empty($product_test_dtl_data['order_parameters_detail']['cwap_invoicing_required'])){
		    unset($product_test_dtl_data['order_parameters_detail']['cwap_invoicing_required']) ;
		}
		if(!empty($product_test_dtl_data['order_parameters_detail']['test_parameter_invoicing_parent_id'])){
		    unset($product_test_dtl_data['order_parameters_detail']['test_parameter_invoicing_parent_id']) ;
		}
                foreach($product_test_dtl_data['order_parameters_detail'] as $keyParameter => $orderParametersData){
                    foreach($orderParametersData as $key => $orderParameters){
			$orderParameters = isset($orderParameters) && strlen($orderParameters) > 0 ? trim($orderParameters) : null;
                        $orderParametersDataSave[$key]['order_id']    = $orderId;
                        $orderParametersDataSave[$key][$keyParameter] = $orderParameters;
                    }
                }
                return $orderParametersDataSave;
            }
        }
    }
    
    /*************************
    * update Stability Prototype Detail Status
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    ************************/
    public function updateStabilityPrototypeDetailStatus($order_id,$stb_order_hdr_id,$stb_order_hdr_dtl_id,$stb_stability_type_id){
        if(!empty($stb_order_hdr_id) && !empty($stb_order_hdr_dtl_id) && !empty($stb_stability_type_id)){
            
            //Updating Status of stb_order_hdr_dtl_detail
            DB::table('stb_order_hdr_dtl_detail')->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$stb_order_hdr_id)->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$stb_order_hdr_dtl_id)->where('stb_order_hdr_dtl_detail.stb_stability_type_id',$stb_stability_type_id)->update(['stb_order_hdr_dtl_detail.stb_order_hdr_detail_status' => '1']);
            
            //Updating Parent Id(stb_order_hdr_detail_id) in order master table
            $stb_order_hdr_dtl_detail = DB::table('stb_order_hdr_dtl_detail')->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$stb_order_hdr_id)->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$stb_order_hdr_dtl_id)->where('stb_order_hdr_dtl_detail.stb_stability_type_id',$stb_stability_type_id)->orderBy('stb_order_hdr_dtl_detail.stb_order_hdr_detail_id','DESC')->first();
            if(!empty($stb_order_hdr_dtl_detail->stb_order_hdr_detail_id)){
                DB::table('order_master')->where('order_master.order_id',$order_id)->update(['order_master.stb_order_hdr_detail_id'=> $stb_order_hdr_dtl_detail->stb_order_hdr_detail_id]);
            }
            
            //Updating Status of stb_order_hdr_dtl.stb_order_book_status
            $stb_order_book_status = DB::table('stb_order_hdr_dtl_detail')->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$stb_order_hdr_id)->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$stb_order_hdr_dtl_id)->where('stb_order_hdr_dtl_detail.stb_order_hdr_detail_status','0')->first();
            if(empty($stb_order_book_status)){
                DB::table('stb_order_hdr_dtl')->where('stb_order_hdr_dtl.stb_order_hdr_id',$stb_order_hdr_id)->where('stb_order_hdr_dtl.stb_order_hdr_dtl_id',$stb_order_hdr_dtl_id)->update(['stb_order_hdr_dtl.stb_order_book_status' => '1']);
            }
            
            //Updating Status of stb_order_hdr.stb_status
            $stb_status = DB::table('stb_order_hdr_dtl')->where('stb_order_hdr_dtl.stb_order_hdr_id',$stb_order_hdr_id)->where('stb_order_hdr_dtl.stb_order_book_status','0')->first();
            if(empty($stb_status)){
                DB::table('stb_order_hdr')->where('stb_order_hdr.stb_order_hdr_id',$stb_order_hdr_id)->update(['stb_order_hdr.stb_status' => '1']);
            }
            
            //Updating Status of stb_order_noti_dtl.stb_order_noti_confirm_date/stb_order_noti_dtl.stb_order_noti_confirm_by
            $stb_order_noti_confirm_date_by = DB::table('stb_order_hdr_dtl')->where('stb_order_hdr_dtl.stb_order_hdr_id',$stb_order_hdr_id)->where('stb_order_hdr_dtl.stb_order_hdr_dtl_id',$stb_order_hdr_dtl_id)->where('stb_order_hdr_dtl.stb_order_book_status','0')->first();
            if(empty($stb_order_noti_confirm_date_by)){
                DB::table('stb_order_noti_dtl')->where('stb_order_noti_dtl.stb_order_hdr_id',$stb_order_hdr_id)->where('stb_order_noti_dtl.stb_order_hdr_dtl_id',$stb_order_hdr_dtl_id)->update(['stb_order_noti_dtl.stb_order_noti_confirm_date' => !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s'),'stb_order_noti_dtl.stb_order_noti_confirm_by' => USERID]);
            }            
        }
    }
    
    
    
}

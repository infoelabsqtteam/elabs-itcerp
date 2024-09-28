<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TrfHdr extends Model
{
    /**
     * Get Single Row Data
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public function getRow($id){
       return DB::table('trf_hdrs')->join('customer_master','customer_master.customer_id','trf_hdrs.trf_customer_id')->select('trf_hdrs.*','customer_master.customer_code','customer_master.customer_name')->where('trf_hdrs.trf_id',$id)->first(); 
    }
    
    public function viewTrfDetail($trfId){
        
        global $models,$trfHdr;

	$error   = '0';
	$message = '';
	$data    = '';
        $response = $trfHdrResponse = $trfHdrDtlResponse = array();
        
        if($trfId){
            
            $trfHdrResponse = DB::table('trf_hdrs')
                ->join('divisions','divisions.division_id','trf_hdrs.trf_division_id')
                ->join('department_product_categories_link','department_product_categories_link.product_category_id','trf_hdrs.trf_product_category_id')
                ->join('departments','departments.department_id','department_product_categories_link.department_id')
                ->join('customer_master','customer_master.customer_id','trf_hdrs.trf_customer_id')
                ->join('city_db','city_db.city_id','customer_master.customer_city')
                ->leftJoin('trf_storge_condition_dtls','trf_storge_condition_dtls.trf_sc_id','trf_hdrs.trf_storage_condition_id')
                ->leftJoin('product_test_hdr','product_test_hdr.test_id','trf_hdrs.trf_product_test_id')
                ->leftJoin('test_standard','test_standard.test_std_id','trf_hdrs.trf_test_standard_id')
                ->leftJoin('product_master','product_master.product_id','trf_hdrs.trf_product_id')
                ->leftJoin('product_categories as trfPcategoryDB','trfPcategoryDB.p_category_id','trf_hdrs.trf_p_category_id')
                ->leftJoin('product_categories as trfSubCategoryDB','trfSubCategoryDB.p_category_id','trf_hdrs.trf_sub_p_category_id')
                ->leftJoin('customer_master as reporting_master','reporting_master.customer_code','trf_hdrs.trf_reporting_to')
                ->leftJoin('customer_master as invoicing_master','invoicing_master.customer_code','trf_hdrs.trf_invoicing_to')
                ->select('trf_hdrs.*','reporting_master.customer_name as reporting_customer_name','reporting_master.customer_id as reporting_customer_id','invoicing_master.customer_id as invoicing_customer_id','invoicing_master.customer_name as invoicing_customer_name','trf_storge_condition_dtls.trf_sc_name as trf_storage_condition_name','divisions.division_name as trf_division_name','departments.department_name as trf_product_category_name','customer_master.customer_name as trf_customer_name','city_db.city_name as trf_city_name','product_test_hdr.test_code as trf_product_test_name','test_standard.test_std_name as trf_j_test_standard_name','product_master.product_name as trf_j_product_name','trfPcategoryDB.p_category_name as trf_p_category_name','trfSubCategoryDB.p_category_name as trf_sub_p_category_name')
                ->where('trf_hdrs.trf_id',$trfId)
                ->first();
                
            if(!empty($trfHdrResponse->trf_id)){
                
                $trfHdrResponse->trf_status = isset($trfHdrResponse->trf_status) && $trfHdrResponse->trf_status == '1' ? 'Booked' : 'Pending';
                $trfHdrResponse->trf_active_deactive_status_name = !empty($trfHdrResponse->trf_active_deactive_status) && $trfHdrResponse->trf_active_deactive_status == '1' ? 'Active' : 'Deactive';
                $trfHdrResponse->trf_product_test_name = !empty($trfHdrResponse->trf_type) && $trfHdrResponse->trf_type == '1' ? $trfHdrResponse->trf_product_test_name : '';
                $trfHdrResponse->trf_test_standard_name = !empty($trfHdrResponse->trf_type) && $trfHdrResponse->trf_type == '1' ? $trfHdrResponse->trf_j_test_standard_name : $trfHdrResponse->trf_test_standard_name;
                $trfHdrResponse->trf_product_name = !empty($trfHdrResponse->trf_type) && $trfHdrResponse->trf_type == '1' ? $trfHdrResponse->trf_j_product_name : $trfHdrResponse->trf_product_name;
                $trfHdrResponse->trf_p_category_name = !empty($trfHdrResponse->trf_type) && $trfHdrResponse->trf_type == '1' ? $trfHdrResponse->trf_p_category_name : '-';
                $trfHdrResponse->trf_sub_p_category_name = !empty($trfHdrResponse->trf_type) && $trfHdrResponse->trf_type == '1' ? $trfHdrResponse->trf_sub_p_category_name : '-';
                
                //Assigning TRF Data to the Array
                $response['trfHdr'] = $models->convertObjectToArray($trfHdrResponse);
                
                $trfHdrDtlResponse = DB::table('trf_hdr_dtls')
                                ->leftJoin('test_parameter','test_parameter.test_parameter_id','trf_hdr_dtls.trf_test_parameter_id')
                                ->select('trf_hdr_dtls.*','test_parameter.test_parameter_name as trf_j_test_parameter_name')
                                ->where('trf_hdr_dtls.trf_hdr_id',$trfHdrResponse->trf_id)
                                ->get()
                                ->toArray();
                if(!empty($trfHdrDtlResponse)){
                    foreach($trfHdrDtlResponse as $key => $values){
                        $values->trf_test_parameter_name = !empty($values->trf_j_test_parameter_name) ? $values->trf_j_test_parameter_name : $values->trf_test_parameter_name;
                    }
                    //Assigning TRF Data to the Array
                    $response['trfHdrDtl'] = $models->convertObjectToArray($trfHdrDtlResponse);
                }                
            }
        }     
        return $response;        
    }
    
    /**
     * Updating TRF Status in ERP and Web Modules
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public function updateTRFStatusInWebAndERP($trf_id){
        
        //Getting TRF HDR Data
        $trfData = $this->getRow($trf_id);
        
        //Updatiing TRF Status in ERP Module
        if(!empty($trfData->trf_id)){
            DB::table('trf_hdrs')->where('trf_hdrs.trf_id',$trfData->trf_id)->update(['trf_hdrs.trf_status' => '1']);
        }        
        //Updatiing TRF Status in WEB Module
        if(!empty($trfData->trf_no)){
            DB::connection('mysql2')->table('trf_hdrs')->where('trf_hdrs.trf_no',$trfData->trf_no)->update(['trf_hdrs.trf_status' => '1']); 
        }        
    }
    
    /**
     * Updating TRF Status in ERP and Web Modules
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public function getTrfProductTestDtlIds($trf_id){
        return DB::table('trf_hdr_dtls')->where('trf_hdr_dtls.trf_hdr_id',$trf_id)->pluck('trf_hdr_dtls.trf_product_test_dtl_id','trf_hdr_dtls.trf_hdr_dtl_id')->all();
    }
    
}

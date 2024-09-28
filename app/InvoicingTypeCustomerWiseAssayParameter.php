<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class InvoicingTypeCustomerWiseAssayParameter extends Model
{
    protected $table = 'customer_invoicing_rates';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
    
    /**
    * get customer products rate list
    *
    * @return \Illuminate\Http\Response
    */
    public function getSelectedCustomerWiseAssayParametersRates($cir_customer_id,$cir_product_category_id,$invocingTypeId){
	
	return DB::table('customer_invoicing_rates')		
	    ->join('product_categories as department', 'department.p_category_id', '=', 'customer_invoicing_rates.cir_product_category_id')
	    ->join('product_categories as productCategory', 'productCategory.p_category_id', '=', 'customer_invoicing_rates.cir_p_category_id')
	    ->join('product_categories as subProductCategory', 'subProductCategory.p_category_id', '=', 'customer_invoicing_rates.cir_sub_p_category_id')
	    ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', '=', 'customer_invoicing_rates.cir_test_parameter_category_id')
	    ->leftJoin('test_parameter','test_parameter.test_parameter_id', '=', 'customer_invoicing_rates.cir_parameter_id')
	    ->join('equipment_type','equipment_type.equipment_id','customer_invoicing_rates.cir_equipment_type_id')
	    ->leftJoin('detector_master','detector_master.detector_id','customer_invoicing_rates.cir_detector_id')
	    ->leftJoin('customer_invoicing_running_time','customer_invoicing_running_time.invoicing_running_time_id','customer_invoicing_rates.cir_running_time_id')
	    ->select('customer_invoicing_rates.*','department.p_category_name as department_name','productCategory.p_category_name as category_name','subProductCategory.p_category_name as sub_category_name','test_parameter_categories.test_para_cat_name','test_parameter.test_parameter_name','equipment_type.equipment_name','detector_master.detector_name','customer_invoicing_running_time.invoicing_running_time_key')
	    ->join('users as createdBy','createdBy.id','customer_invoicing_rates.created_by')	
	    ->whereNotNull('customer_invoicing_rates.cir_is_detector')
	    ->where('customer_invoicing_rates.invoicing_type_id',$invocingTypeId)
	    ->where('customer_invoicing_rates.cir_customer_id',$cir_customer_id)
	    ->where('customer_invoicing_rates.cir_product_category_id',$cir_product_category_id)
	    ->orderBy('customer_invoicing_rates.cir_parameter_id','DESC')
	    ->get();
    }
    
    /**
    * get customer products rate list
    *
    * @return \Illuminate\Http\Response
    */
    public function checkUniqueStructure($postedData,$invocingTypeId){
	
	$customerInvoicingRatesObj = DB::table('customer_invoicing_rates')
				    ->where('customer_invoicing_rates.invoicing_type_id',$invocingTypeId)
				    ->where('customer_invoicing_rates.cir_customer_id',$postedData['cir_customer_id'])
				    ->where('customer_invoicing_rates.cir_product_category_id',$postedData['cir_product_category_id'])
				    ->where('customer_invoicing_rates.cir_p_category_id',$postedData['cir_p_category_id'])
				    ->where('customer_invoicing_rates.cir_sub_p_category_id',$postedData['cir_sub_p_category_id'])	    
				    ->where('customer_invoicing_rates.cir_test_parameter_category_id',$postedData['cir_test_parameter_category_id'])	    
				    ->where('customer_invoicing_rates.cir_equipment_type_id',$postedData['cir_equipment_type_id'])	    
				    ->where('customer_invoicing_rates.cir_equipment_count',$postedData['cir_equipment_count']);
	    
	if(!empty($postedData['cir_parameter_id']) && is_numeric($postedData['cir_parameter_id'])){
	    $customerInvoicingRatesObj->where('customer_invoicing_rates.cir_parameter_id',$postedData['cir_parameter_id']);
	}	
	if(!empty($postedData['cir_is_detector']) && $postedData['cir_is_detector'] == '1'){
	    if(!empty($postedData['cir_detector_id']) && is_numeric($postedData['cir_detector_id'])){
		$customerInvoicingRatesObj->where('customer_invoicing_rates.cir_detector_id',$postedData['cir_detector_id']);
	    }
	    if(!empty($postedData['cir_running_time_id']) && is_numeric($postedData['cir_running_time_id'])){
		$customerInvoicingRatesObj->where('customer_invoicing_rates.cir_running_time_id',$postedData['cir_running_time_id']);
	    }
	    if(!empty($postedData['cir_no_of_injection']) && is_numeric($postedData['cir_no_of_injection'])){
		$customerInvoicingRatesObj->where('customer_invoicing_rates.cir_no_of_injection',$postedData['cir_no_of_injection']);
	    }	    
	} 
	return $customerInvoicingRatesObj->first();
    }
	
	
	
}

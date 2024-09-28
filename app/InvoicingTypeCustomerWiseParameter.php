<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class InvoicingTypeCustomerWiseParameter extends Model
{
    protected $table = 'customer_invoicing_rates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [];

	/**
	* Check Vendor Bill NO
	*
	* @return \Illuminate\Http\Response
	*/
  /**
	* Check Vendor Bill NO
	*
	* @return \Illuminate\Http\Response
	*/
    function checkCustomerWiseParameterRate($cir_customer_id, $cir_parameter_id, $type='add', $cir_id = null){
		if($type == 'add'){
			return DB::table('customer_invoicing_rates')
					->where('customer_invoicing_rates.cir_customer_id','=',$cir_customer_id)
					->where('customer_invoicing_rates.cir_parameter_id','=',$cir_parameter_id)
					->count();
		}else if($type == 'edit'){
			return DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_customer_id','=',$cir_customer_id)->where('customer_invoicing_rates.cir_parameter_id','=',$cir_parameter_id)->where('customer_invoicing_rates.cir_id','<>',$cir_id)->count();
		}
	}

	/**
	* get all products from alias table with
	*
	* @return \Illuminate\Http\Response
	*/
	public function getAllParameters($product_category_id,$product_para_category_id){

	    $allParametersListObj = DB::table('test_parameter')
		->join('test_parameter_categories','test_parameter.test_parameter_category_id','test_parameter_categories.test_para_cat_id')
		->leftJoin('test_parameter_equipment_types','test_parameter.test_parameter_id','test_parameter_equipment_types.test_parameter_id')
		->leftJoin('equipment_type','test_parameter_equipment_types.equipment_type_id','equipment_type.equipment_id')
		->join('product_categories','test_parameter_categories.product_category_id','product_categories.p_category_id')
		->select('product_categories.p_category_id as product_category_id','equipment_type.equipment_id as equipment_type_id','equipment_type.equipment_name','test_parameter.test_parameter_id','test_parameter.test_parameter_name');

	    if(!empty($product_category_id)){
	       $allParametersListObj->where('test_parameter_categories.product_category_id','=',$product_category_id);
	    }
	    if(!empty($product_para_category_id)){
	       $allParametersListObj->where('test_parameter.test_parameter_category_id','=',$product_para_category_id);
	    }

	    $allParametersList = $allParametersListObj->orderBy('test_parameter.test_parameter_name','ASC')->get();

	    //print_r($allParametersList); die;
	    return !empty($allParametersList)? $allParametersList : array();
	}

	/**
	* get customer products rate list
	*
	* @return \Illuminate\Http\Response
	*/
	public function getCustomerParameters($cir_customer_id){
		 $customerParameterRateList = DB::table('customer_invoicing_rates')
							->join('test_parameter','test_parameter.test_parameter_id','customer_invoicing_rates.cir_parameter_id')
							->select('customer_invoicing_rates.cir_parameter_id as id','test_parameter.test_parameter_name as name','customer_invoicing_rates.invoicing_rate as rate','customer_invoicing_rates.cir_id')
							->where('customer_invoicing_rates.invoicing_type_id',4)
							->where('customer_invoicing_rates.cir_customer_id',$cir_customer_id)
							->orderBy('customer_invoicing_rates.cir_parameter_id','DESC')
							->get();
		return !empty($customerParameterRateList)? $customerParameterRateList : array();
	}

	/**
	* get customer products rate list
	*
	* @return \Illuminate\Http\Response
	*/
	public function getSelectedCustomerParametersRateList($cir_customer_id,$invocingTypeId){
	    return DB::table('customer_invoicing_rates')
		->join('test_parameter','test_parameter.test_parameter_id','customer_invoicing_rates.cir_parameter_id')
		->join('test_parameter_categories','test_parameter_categories.test_para_cat_id','test_parameter.test_parameter_category_id')
		->join('equipment_type','equipment_type.equipment_id','customer_invoicing_rates.cir_equipment_type_id')
		->leftJoin('test_standard','test_standard.test_std_id','customer_invoicing_rates.cir_test_standard_id')
		->join('customer_master','customer_master.customer_id','customer_invoicing_rates.cir_customer_id')
		->join('product_categories','customer_invoicing_rates.cir_product_category_id','product_categories.p_category_id')
		->select('customer_invoicing_rates.*','product_categories.p_category_name','test_standard.test_std_name','equipment_type.equipment_name','customer_master.customer_name','test_parameter_categories.test_para_cat_name','test_parameter.test_parameter_name')
		->where('customer_invoicing_rates.invoicing_type_id',$invocingTypeId)
		->where('customer_invoicing_rates.cir_customer_id',$cir_customer_id)
		->orderBy('customer_invoicing_rates.cir_parameter_id','DESC')
		->get();
	}

	/**
	* get customer products rate list
	*
	* @return \Illuminate\Http\Response
	*/
	public function funGetInvoicingRate($cir_customer_id,$product_category_id,$test_parameter_id,$equipment_type_id,$invocingTypeId){

	    $data  = DB::table('customer_invoicing_rates')
		    ->where('customer_invoicing_rates.invoicing_type_id',$invocingTypeId)
		    ->where('customer_invoicing_rates.cir_customer_id',$cir_customer_id)
		    ->where('customer_invoicing_rates.cir_product_category_id',$product_category_id)
		    ->where('customer_invoicing_rates.cir_parameter_id',$test_parameter_id)
		    ->where('customer_invoicing_rates.cir_equipment_type_id',$equipment_type_id)
		    ->first();

	    $cirTestStandardId = !empty($data->cir_test_standard_id) ? $data->cir_test_standard_id : NULL;
	    $invoicingRate     = !empty($data->invoicing_rate) ? $data->invoicing_rate : NULL;

	    return array($cirTestStandardId,$invoicingRate);
	}



}

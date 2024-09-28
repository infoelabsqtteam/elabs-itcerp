<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class InvoicingTypeCustomerWiseProduct extends Model
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
    function checkCustomerWiseProductRate($cir_city_id,$cir_customer_id,$cir_c_product_id,$cir_division_id,$dept_id,$type='add', $cir_id = null){
	if($type == 'add'){
	    return DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_customer_id','=',$cir_customer_id)->where('customer_invoicing_rates.cir_city_id','=',$cir_city_id)->where('customer_invoicing_rates.cir_c_product_id','=',$cir_c_product_id)->where('customer_invoicing_rates.cir_division_id','=',$cir_division_id)->where('customer_invoicing_rates.cir_product_category_id','=',$dept_id)->count();
	}else if($type == 'edit'){
	    return DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_customer_id','=',$cir_customer_id)->where('customer_invoicing_rates.cir_city_id','=',$cir_city_id)->where('customer_invoicing_rates.cir_c_product_id','=',$cir_c_product_id)->where('customer_invoicing_rates.cir_division_id','=',$cir_division_id)->where('customer_invoicing_rates.cir_product_category_id','=',$dept_id)->where('customer_invoicing_rates.cir_id','<>',$cir_id)->count();
	}
    }

    /**
    * Check Vendor Bill NO
    *
    * @return \Illuminate\Http\Response
    */
    function checkCustomerExist($cir_customer_id,$cir_division_id,$dept_id,$type='add'){
	if($type == 'add'){
	    return DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_division_id','=',$cir_division_id)->where('customer_invoicing_rates.cir_product_category_id','=',$dept_id)->where('customer_invoicing_rates.cir_customer_id','=',$cir_customer_id)->count();
	}else if($type == 'edit'){
	    return DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_customer_id','=',$cir_customer_id)->where('customer_invoicing_rates.cir_id','<>',$cir_id)->count();
	}
    }

    /**
    * get all products from alias table with
    *
    * @return \Illuminate\Http\Response
    */
    public function getAllProductMasterAlias($cir_customer_id=NULL,$dept_id=NULL){
	
	$productAliasListObj = DB::table('product_master_alias')->join('product_master','product_master.product_id','product_master_alias.product_id');
	
	if(!empty($dept_id) && !empty($cir_customer_id)){
	    $productAliasListObj->join('customer_invoicing_rates','customer_invoicing_rates.cir_c_product_id','product_master_alias.c_product_id')
				->where('customer_invoicing_rates.cir_product_category_id','=',$dept_id)
				->where('customer_invoicing_rates.cir_customer_id',$cir_customer_id);
	}
	$productAliasListObj->select('product_master_alias.c_product_id as id','product_master_alias.c_product_name as name','product_master.product_name');
	$productAliasList = $productAliasListObj->orderBy('product_master_alias.c_product_id','DESC')->groupBy('product_master_alias.c_product_id')->get();
	
	return !empty($productAliasList)? $productAliasList : array();
    }

    /**
    * get customer products rate list
    *
    * @return \Illuminate\Http\Response
    */
    public function getCustomerProducts($cir_customer_id=NULL,$dept_id=NULL){
	
	$customerProductRateListObj = DB::table('customer_invoicing_rates')
				->join('product_master_alias','product_master_alias.c_product_id','customer_invoicing_rates.cir_c_product_id')
				->join('product_master','product_master.product_id','product_master_alias.product_id')
				->select('customer_invoicing_rates.cir_c_product_id as id','product_master_alias.c_product_name as name','customer_invoicing_rates.invoicing_rate as rate','customer_invoicing_rates.cir_id','product_master.product_name','customer_invoicing_rates.cir_division_id')
				->where('customer_invoicing_rates.invoicing_type_id','3')
				->where('customer_invoicing_rates.cir_customer_id',$cir_customer_id);
	if(!empty($dept_id)){
	    $customerProductRateListObj->where('customer_invoicing_rates.cir_product_category_id','=',$dept_id);
	}
	$customerProductRateList=$customerProductRateListObj->orderBy('product_master_alias.c_product_id','DESC')->get();
	
	return !empty($customerProductRateList)? $customerProductRateList : array();
    }

    /**
    * get customer products rate list
    *12-04-18 new replica of with division check above getCustomerProducts()
    * @return \Illuminate\Http\Response
    */
    public function getCustomerAllProducts($cir_customer_id=NULL,$dept_id=NULL,$div_id=NULL){
	
	$customerProductRateListObj = DB::table('customer_invoicing_rates')
				    ->join('product_master_alias','product_master_alias.c_product_id','customer_invoicing_rates.cir_c_product_id')
				    ->join('product_master','product_master.product_id','product_master_alias.product_id')
				    ->join('product_categories','product_categories.p_category_id','customer_invoicing_rates.cir_product_category_id')
				    ->select('customer_invoicing_rates.cir_c_product_id as id','product_master_alias.c_product_name as name','customer_invoicing_rates.invoicing_rate as rate','customer_invoicing_rates.cir_id','product_master.product_name','customer_invoicing_rates.cir_division_id','product_categories.p_category_name')
				    ->where('customer_invoicing_rates.invoicing_type_id','3')
				    ->where('customer_invoicing_rates.cir_customer_id',$cir_customer_id);
				    
	if(!empty($dept_id)){
	    $customerProductRateListObj->where('customer_invoicing_rates.cir_product_category_id','=',$dept_id)->where('customer_invoicing_rates.cir_division_id','=',$div_id);
	}
	$customerProductRateList=$customerProductRateListObj->orderBy('product_master_alias.c_product_id','DESC')->get();
	
	return !empty($customerProductRateList)? $customerProductRateList : array();
    }

    /**
    * get customer products rate list
    *
    * @return \Illuminate\Http\Response
    */
    public function departAccToCustomerWiseProductInvoicing($cir_customer_id,$cir_id){
	
	$deptID = DB::table('customer_invoicing_rates')
		->where('customer_invoicing_rates.cir_customer_id',$cir_customer_id)
		->where('customer_invoicing_rates.cir_product_category_id',$cir_id)
		->select('customer_invoicing_rates.cir_product_category_id')
		->first();
		
	return !empty($deptID) ? $deptID->cir_product_category_id : '0';
    }

}

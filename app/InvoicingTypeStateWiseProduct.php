<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class InvoicingTypeStateWiseProduct extends Model
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
    function checkStateWiseProductRate($division_id,$cir_state_id,$cir_c_product_id,$dept_id=null,$type='add',$cir_id=null){
		//print_r($cir_id);die;
		if($type == 'add'){
			return DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_division_id','=',$division_id)->where('customer_invoicing_rates.cir_state_id','=',$cir_state_id)->where('customer_invoicing_rates.cir_c_product_id','=',$cir_c_product_id)->where('customer_invoicing_rates.cir_product_category_id','=',$dept_id)->count();
		}else if($type == 'edit'){
			return DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_division_id','=',$division_id)->where('customer_invoicing_rates.cir_state_id','=',$cir_state_id)->where('customer_invoicing_rates.cir_c_product_id','=',$cir_c_product_id)->where('customer_invoicing_rates.cir_id','<>',$cir_id)->where('customer_invoicing_rates.cir_product_category_id','=',$dept_id)->count();
		}
	}
	public function getAllProductMasterAlias($cir_state_id=NULL,$dept_id=NULL,$division_id=NULL){
		 $productAliasListObj = DB::table('product_master_alias')
							->leftJoin('product_master','product_master.product_id','product_master_alias.product_id');
							if(!empty($dept_id) && !empty($cir_state_id) && !empty($division_id)){
								$productAliasListObj->leftJoin('customer_invoicing_rates','customer_invoicing_rates.cir_c_product_id','product_master_alias.c_product_id')
                ->where('customer_invoicing_rates.cir_division_id','=',$division_id)
								->where('customer_invoicing_rates.cir_product_category_id','=',$dept_id)
								->where('customer_invoicing_rates.cir_state_id',$cir_state_id);
							}
						$productAliasListObj->select('product_master_alias.c_product_id as id','product_master_alias.c_product_name as name','product_master.product_name')
							->where('product_master_alias.view_type','=','1');

							$productAliasListObj->orderBy('product_master_alias.c_product_id','DESC');
							$productAliasListObj->groupBy('product_master_alias.c_product_id');
						$productAliasList=$productAliasListObj->get();
		return !empty($productAliasList)? $productAliasList : array();
	}
	public function getStateProducts($cir_state_id=NULL,$dept_id=NULL,$division_id=NULL){
			$stateProductRateListObj = DB::table('customer_invoicing_rates')
            ->leftJoin('product_master_alias','product_master_alias.c_product_id','customer_invoicing_rates.cir_c_product_id')
			->leftJoin('product_master','product_master.product_id','product_master_alias.product_id')
			->leftJoin('state_db','state_db.state_id','customer_invoicing_rates.cir_state_id')
			->leftJoin('product_categories','product_categories.p_category_id','customer_invoicing_rates.cir_product_category_id')
			->select('customer_invoicing_rates.cir_c_product_id as id','product_master_alias.c_product_name as name','customer_invoicing_rates.invoicing_rate as rate','customer_invoicing_rates.cir_id','customer_invoicing_rates.cir_product_category_id as dept_id','product_master.product_name','customer_invoicing_rates.cir_division_id','state_db.state_name','product_categories.p_category_name')
			->where('customer_invoicing_rates.invoicing_type_id','2')
			->where('customer_invoicing_rates.cir_state_id',$cir_state_id);
			if(!empty($dept_id) && !empty($division_id)){
			$stateProductRateListObj->where('customer_invoicing_rates.cir_division_id','=',$division_id)
			->where('customer_invoicing_rates.cir_product_category_id','=',$dept_id);
						}
			$stateProductRateList= $stateProductRateListObj->orderBy('product_master_alias.c_product_id','DESC')->get();
		return !empty($stateProductRateList)? $stateProductRateList : array();
	}

	public function departAccToStateWiseInvoicing($cir_state_id,$cir_id){
		$deptID = DB::table('customer_invoicing_rates')
			->where('customer_invoicing_rates.cir_state_id',$cir_state_id)
			->where('customer_invoicing_rates.cir_id',$cir_id)
			->select('customer_invoicing_rates.cir_product_category_id')
			->first();

		$cir_product_category_id = !empty($deptID) ? $deptID->cir_product_category_id : '0';
		return $cir_product_category_id;
	}
}

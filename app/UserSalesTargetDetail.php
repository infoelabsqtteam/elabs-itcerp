<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserSalesTargetDetail extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $table = 'user_sales_target_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    /**
     * Get Single Row Data
     */    
    public function getRow($id){
        return DB::table('user_sales_target_details')->where('user_sales_target_details.ust_id',$id)->first();
    }
    
    /**
    * Checking user Record
    *
    * @return \Illuminate\Http\Response
    */
    function validationUserSalesTarget($submitedData,$type='add'){		
        if($type == 'add'){
            return DB::table('user_sales_target_details')
                ->where('user_sales_target_details.ust_user_id','=',$submitedData['ust_user_id'])
                ->where('user_sales_target_details.ust_division_id','=',$submitedData['ust_division_id'])
                ->where('user_sales_target_details.ust_product_category_id','=',$submitedData['ust_product_category_id'])
                ->where('user_sales_target_details.ust_customer_id','=',$submitedData['ust_customer_id'])
                ->where('user_sales_target_details.ust_type_id','=',$submitedData['ust_type_id'])
                ->where('user_sales_target_details.ust_month',date('m',strtotime($submitedData['ust_date'])))
                ->where('user_sales_target_details.ust_year',date('Y',strtotime($submitedData['ust_date'])))
                ->whereDate('user_sales_target_details.ust_date',date('Y-m-d',strtotime($submitedData['ust_date'])))
                ->count();
        }else if($type == 'edit'){
            $data = DB::table('user_sales_target_details')
                    ->where('user_sales_target_details.ust_user_id','=',$submitedData['ust_user_id'])
                    ->where('user_sales_target_details.ust_division_id','=',$submitedData['ust_division_id'])
                    ->where('user_sales_target_details.ust_product_category_id','=',$submitedData['ust_product_category_id'])
                    ->where('user_sales_target_details.ust_customer_id','=',$submitedData['ust_customer_id'])
                    ->where('user_sales_target_details.ust_type_id','=',$submitedData['ust_type_id'])
                    ->where('user_sales_target_details.ust_month',date('m',strtotime($submitedData['ust_date'])))
                    ->where('user_sales_target_details.ust_year',date('Y',strtotime($submitedData['ust_date'])))
                    ->whereDate('user_sales_target_details.ust_date',date('Y-m-d',strtotime($submitedData['ust_date'])))
                    ->where('user_sales_target_details.ust_id','=',$submitedData['ust_id'])
                    ->count();
            if($data){
                return false;
            }else{
                return DB::table('user_sales_target_details')
                ->where('user_sales_target_details.ust_user_id','=',$submitedData['ust_user_id'])
                ->where('user_sales_target_details.ust_division_id','=',$submitedData['ust_division_id'])
                ->where('user_sales_target_details.ust_product_category_id','=',$submitedData['ust_product_category_id'])
                ->where('user_sales_target_details.ust_customer_id','=',$submitedData['ust_customer_id'])
                ->where('user_sales_target_details.ust_type_id','=',$submitedData['ust_type_id'])
                ->where('user_sales_target_details.ust_month',date('m',strtotime($submitedData['ust_date'])))
                ->where('user_sales_target_details.ust_year',date('Y',strtotime($submitedData['ust_date'])))
                ->whereDate('user_sales_target_details.ust_date',date('Y-m-d',strtotime($submitedData['ust_date'])))
                ->count();
            }
        }
    }
    
    /**
    * Checking user Record
    *
    * @return \Illuminate\Http\Response
    */
    function validationUserSalesTargetCsvData($submitedData){
       
        return DB::table('user_sales_target_details')
                ->where('user_sales_target_details.ust_user_id','=',$submitedData['ust_user_id'])
                ->where('user_sales_target_details.ust_division_id','=',$submitedData['ust_division_id'])
                ->where('user_sales_target_details.ust_product_category_id','=',$submitedData['ust_product_category_id'])
                ->where('user_sales_target_details.ust_customer_id','=',$submitedData['ust_customer_id'])
                ->where('user_sales_target_details.ust_type_id','=',$submitedData['ust_type_id'])
                ->where('user_sales_target_details.ust_month',$submitedData['ust_month'])
                ->where('user_sales_target_details.ust_year',$submitedData['ust_year'])
                ->whereDate('user_sales_target_details.ust_date',date('Y-m-d',strtotime($submitedData['ust_date'])))
                ->first();
        
    }
}

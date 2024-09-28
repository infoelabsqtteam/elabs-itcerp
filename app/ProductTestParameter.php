<?php

namespace App;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class ProductTestParameter extends Authenticatable implements HasRoleContract
{
    use Notifiable;
    
    /**
    * Third Party Service for user role ...
    * URI https://github.com/httpoz/roles
    */	
    use Notifiable, HasRole;
	
    protected $table = 'product_test_dtl';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'test_id', 'department_id', 'test_parameter_id', 'standard_value_type', 'standard_value_from', 'standard_value_to', 'equipment_type_id','detector_id','method_id', 'claim_dependent', 'time_taken_mins', 'time_taken_days','parameter_decimal_place','cost_price', 'selling_price','parameter_nabl_scope', 'description', 'parameter_sort_by', 'created_by'
    ];	
    
    /************************************
    * Description : isExist Is used to check the test_parameter duplicate entry by code
    * Date        : 01-09-17
    * Author      : nisha
    * Parameter   : \Illuminate\Http\Request  $request
    * @return     : \Illuminate\Http\Response
    ************************************/
    public function getEquipmentId($parameter_id){
	return DB::table('test_parameter')->select('equipment_type_id')->where('test_parameter_id','=',$parameter_id)->first()->equipment_type_id; 
    }
    
    /************************************
    * Description : isExist Is used to check the test_parameter duplicate entry by code
    * Date        : 01-09-17
    * Author      : nisha
    * Parameter   : \Illuminate\Http\Request  $request
    * @return     : \Illuminate\Http\Response
    ************************************/
    public function getParameterSortNumber($test_id){ 
	$parameter_sort_by = DB::table('product_test_dtl')->select('parameter_sort_by')->where('test_id',$test_id)->orderBy('parameter_sort_by','DESC')->first(); 
	return  !empty($parameter_sort_by)? $parameter_sort_by->parameter_sort_by+1 : 1;
    }
    
    /************************************
    * Description : isExist Is used to check the test_parameter duplicate entry by code
    * Date        : 01-09-17
    * Author      : nisha
    * Parameter   : \Illuminate\Http\Request  $request
    * @return     : \Illuminate\Http\Response
    ************************************/
    public function validateParameterExistenceInTestMaster($test_id,$test_parameter_id){
	if(!empty($test_id)){
	    $data = DB::table('product_test_dtl')->where('product_test_dtl.test_id', '=', $test_id)->where('product_test_dtl.test_parameter_id', '=', $test_parameter_id)->first(); 
	    if(!empty($data->test_id)){
		return $data->test_id;
	    }else{
		return false;
	    }
	}else{
	    return false;
	}
    }
}

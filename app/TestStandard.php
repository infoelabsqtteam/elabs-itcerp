<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;
use DB;

class TestStandard extends Authenticatable implements HasRoleContract
{
    use Notifiable;
	
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
    
    protected $table = 'test_standard';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'test_std_code', 'test_std_name','test_std_desc','product_category_id','created_by'
    ];
	
	/**
	* Check Vendor Bill NO
	*
	* @return \Illuminate\Http\Response
	*/
    function checkTestStandardName($test_std_name,$type='add',$test_std_id=null){		
		if($type == 'add'){
			return DB::table('test_standard')->where('test_standard.test_std_name','=',$test_std_name)->count();
		}else if($type == 'edit'){
			$data = DB::table('test_standard')->where('test_standard.test_std_id','=',$test_std_id)->where('test_standard.test_std_name','=',$test_std_name)->count();
			if($data){
				return false;
			}else{
				return DB::table('test_standard')->where('test_standard.test_std_name','=',$test_std_name)->count();
			}
		}
	}
}

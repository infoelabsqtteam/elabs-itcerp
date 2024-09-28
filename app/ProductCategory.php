<?php

namespace App;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Authenticatable implements HasRoleContract
{
    use Notifiable;
	
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'product_categories';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'p_category_code','p_category_name','parent_id','created_by','level'
    ];	
	
    public function childs() {
        return $this->hasMany('App\ProductCategory','parent_id','p_category_id');
    }
	
    public function parent(){
        return $this->belongsTo('App\ProductCategory','parent_id','p_category_id')->where('parent_id',0)->with('parent')->select('p_category_id','p_category_name','parent_id','level');
    }

    public function children(){
        return $this->hasMany('App\ProductCategory','parent_id','p_category_id')->with('children')->select('p_category_id','p_category_name','parent_id','level');
    }
	
    public function getCategoryLevel($id){   
        $level= DB::table('product_categories')->select('level')->where('p_category_id','=',$id)->first(); 
	if(!empty($level)){
	    return $level->level;
	}else{
	    return false;	
	}
    }
	
    //delete the test parameters from test_parameter table entry when delete test parameters category
    public function categoryDetails($p_category_id){ 
        return  DB::table('product_categories')->where('p_category_id',$p_category_id)->first();
    }
	
    /**
    * Generating Category Tree
    *
    * return json of tree
    */
    public function categoryTree(){
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$productCategoryObj = $this->with('children')->select('p_category_id','p_category_name','parent_id','level')->where('parent_id',0);
	!empty($department_ids) ? $productCategoryObj->whereIn('p_category_id',$department_ids) : '';
	return $productCategoryObj->get()->toArray();
    }	
}

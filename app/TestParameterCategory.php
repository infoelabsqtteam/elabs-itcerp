<?php

namespace App;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
/**
 * Third Party Service for user role ...
 * URI https://github.com/httpoz/roles
 */
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class TestParameterCategory extends Authenticatable implements HasRoleContract
{
    use Notifiable;
    
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'test_parameter_categories';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'test_para_cat_code', 'test_para_cat_name', 'test_para_cat_print_desc', 'parent_id', 'product_category_id','category_sort_by','level','created_by'
    ];	
	
    public function parent(){
        return $this->belongsTo('App\TestParameterCategory','parent_id','test_para_cat_id')->where('parent_id',0)->with('parent');
    }
	
    public function childs(){
        return $this->hasMany('App\TestParameterCategory','parent_id','test_para_cat_id','product_category_id') ;
    }
	
    public function children(){
        return $this->hasMany('App\TestParameterCategory','parent_id','test_para_cat_id')->with('children');
    }
	
    /**
    * Generating Category Tree
    *
    * return json of tree
    */
    public function categoryTree(){    
	$department_ids = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$parameterCategoryObj = $this->with('children')->where('status',1)->where('parent_id',0)->orderBy('product_category_id','ASC');
	!empty($department_ids) ? $parameterCategoryObj->whereIn('product_category_id',$department_ids) : '';
	return $parameterCategoryObj->get()->toArray();	
    }
    
    /**
    * Generating Category Tree
    *
    * return json of tree
    */
    public function categoryTreeByCategoty($product_category_id){
	$parameterCategory = $this->with('children')->select()->where('status',1)->where('parent_id',0)->where('product_category_id',$product_category_id)->get()->toArray();
	return $parameterCategory;
    }

    /**
    * get Category level
    *
    * return level
    */
    public function getCategoryLevel($id){   
	$level= DB::table('test_parameter_categories')->select('level')->where('test_para_cat_id','=',$id)->first(); 
	if(!empty($level)){
	    return $level->level;
	}else{
	    return false;	
	}
    }
	
    /**
    * return current category sort number 
    *
    * return level
    */
    public function getCategorySortNumber($product_category_id){ 
	$category_sort_by = DB::table('test_parameter_categories')->select('category_sort_by')->where('product_category_id',$product_category_id)->orderBy('category_sort_by','DESC')->first(); 
	return  !empty($category_sort_by)? $category_sort_by->category_sort_by+1 : 1;
    }
}

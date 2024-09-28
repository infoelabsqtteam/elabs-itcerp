<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

use DB;
use App\Helpers\SimpleImage;

class User extends Authenticatable implements HasRoleContract
{
    /**
    * Third Party Service for user role ...
    * URI https://github.com/httpoz/roles
    */	
    use Notifiable, HasRole;
	
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id','division_id','user_code','is_sales_person','created_by','is_assign_job',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','division_id','created_by',
    ];
	
    public function users_equipment_detail(){
        return $this->has_many('users_equipment_detail');
    }

    public function role_user(){
        return $this->has_many('role_user');
    }
	
    /**
    * isEmployeeExist Is used to check the user duplicate entry by email
    * Date : 22-May-18
    * Author : Praveen Singh
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    function isEmployeeExist($email,$type='add',$user_id=null){		
	if($type == 'add'){
	    return DB::table('users')->where('users.email','=',$email)->count();
	}else if($type == 'edit'){
	    $data = DB::table('users')->where('users.id','=',$user_id)->where('users.email','=',$email)->count();
	    if($data){
		return false;
	    }else{
		return DB::table('users')->where('users.email','=',$email)->count();
	    }
	}
    }
    
    /**
    * checkMultipleMicrobiologist :To chek multiple Microbiologist
    * Date : 22-May-18
    * Author : Praveen Singh
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    * Modified On : 27-June-2018
    */
    function checkMultipleMicrobiologist($formData){	
	if(!empty($formData['user_id'])){
	    $data = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('users.id',$formData['user_id'])->where('role_user.role_id','15')->where('users.division_id',$formData['division_id'])->count();
	    if(!empty($data)){
		return false;
	    }else{
		return DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id','15')->where('users.division_id',$formData['division_id'])->count();
	    }
	}else{
	    return DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id','15')->where('users.division_id',$formData['division_id'])->count();
	}	
    }

    /**
    * upload the specified image in storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function uploadSignature($user_id, $requestData) {
	
	$deptNames = array();	
	$division_name = $user_name = '';
	
	if(!empty($requestData->file('user_signature'))){
	    $file     = $requestData->file('user_signature');	
	    $userData = DB::table('users')
		    ->join('divisions','divisions.division_id','=','users.division_id')
		    ->join('users_department_detail','users_department_detail.user_id','=','users.id')
		    ->join('department_product_categories_link','department_product_categories_link.department_id','=','users_department_detail.department_id')
		    ->join('departments','departments.department_id','=','department_product_categories_link.department_id')
		    ->select('users.name','divisions.division_name','departments.department_name as dept_name')
		    ->where('users.id','=',$user_id)->get();
	    if(!empty($userData)){
		foreach($userData as $key=>$value){
		    $deptNames[$key] = substr($value->dept_name, 0, 1);
		    $division_name   = $value->division_name;
		    $user_name       = $value->name;
		}	    
		$division_name 	= strtolower(substr($value->division_name, 0, 1));
		$dept_name 	= strtolower(str_replace(',','',implode(',',$deptNames)));
	    }
	    $file_name  	= strtolower(preg_replace('/[_]+/','_',preg_replace("/[^a-zA-Z]/", "_",$user_name))).'_'.$division_name.$dept_name.'.'.$file->getClientOriginalExtension();
	    $extract_ext 	= pathinfo($file_name);
	    $dirname 		= $extract_ext['dirname'];
	    $basename 		= $extract_ext['basename'];
	    $imagename		= $extract_ext['filename'];
	    $image_extension 	= $extract_ext['extension'];
	    $image_path 	= public_path() . '/images/signatures/';
	    
	    if (!file_exists($image_path)){			
		mkdir($image_path, 0777, true);
	    }
	    if ($file->move($image_path, $file_name)){
		$image = new SimpleImage();
		$image->load($image_path . $file_name);
		//Item Image size
		$image->resizeToHeight(80);
		$image_thumb = $imagename.'.'.$image_extension;
		$image->save($image_path . $image_thumb);
		$src_Path = url(''). '/public/images/signatures/'.$image_thumb;
		return array($image_thumb,$src_Path);
	    }
	}
    }
	
    /**
    * upload the specified image in storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function removeUploadedSignature($item_id, $item_image) {
	$image_path = public_path() . '/images/signatures/'.$item_image;
	$src_Path   = url(''). '/public/images/default-item.jpeg';
	if(array_map('unlink', glob($image_path."*"))){
	    return $src_Path;
	}
	return false;
    }
	
    /**
    * upload the specified image in storage.
    *get Section Incharge Equipment Type Data
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function getSectionInchargeEquipmentTypeData($requestData) {
	$returnData = array();
	$addEquipmentDetail =  array_unique(DB::table('users_equipment_detail')
		->join('users','users.id','users_equipment_detail.user_id')
		->join('users_department_detail','users_department_detail.user_id','users.id')
		->join('role_user','role_user.user_id','=','users.id')
		->where('role_user.role_id','=','7')
		->where('users.division_id','=',$requestData['division_id'])
		->whereIn('users_department_detail.department_id',$requestData['department_id'])
		->pluck('users_equipment_detail.equipment_type_id')
		->all());
	if(!empty($requestData['equipment_type_id'])){
		      foreach($requestData['equipment_type_id'] as $key => $value){
			       if(!in_array($value,$addEquipmentDetail)){
					$returnData[$key] = $value; 
			       }
		      }
	}
	return $returnData;
    }
    
    /***
    **Check order master,for sales person exist
    **if yes, not allow to change his/her role
    **Created on:04-Sept-2018
    **Created By:Ruby
    ***/
    public function validateSalesExecutiveOnRemove($user_id){
	$saleExecutiveInOrder 	= !empty($user_id) ? DB::table('order_master')->where('sale_executive',$user_id)->count() : '0'; 
	return !empty($saleExecutiveInOrder) ? '0' : '1';
    }
    
    /***
    **Check order_process_log and samples for user id
    **if exist, not allow to change his/her role
    **Created on:04-Sept-2018
    **Created By:Ruby
    ***/
    public function validateSalesExecutiveOnAdd($user_id){
	$flag = '1';
	if(!empty($user_id)){
	    if(DB::table('order_process_log')->where('order_process_log.opl_user_id',$user_id)->count()){
		$flag = '0';
	    }else if(DB::table('samples')->where('samples.created_by',$user_id)->count()){
		$flag = '0';
	    }
	}	
	return $flag;
    }
}

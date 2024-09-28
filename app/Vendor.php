<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Vendor extends Model
{
    protected $table = 'vendors';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
	
	//check vendor code existence
    function getVendorCodeExistence($vendorCode,$type='add',$vendor_id=null){
		if($type == 'add'){
			return DB::table('vendors')->where('vendors.vendor_code',trim($vendorCode))->count();
		}else if($type == 'edit'){
			$data = DB::table('vendors')->where('vendors.vendor_id','=',$vendor_id)->where('vendors.vendor_code','=',trim($vendorCode))->count();
			if($data){
				return false;
			}else{
				return DB::table('vendors')->where('vendors.vendor_code',trim($vendorCode))->count();
			}
		}
    }
	
	//check vendor email existence
    function getVendorEmailExistence($vendorEmail,$type='add',$vendor_id=null){
		if($type == 'add'){
			return DB::table('vendors')->where('vendors.vendor_email',trim($vendorEmail))->count();
		}else if($type == 'edit'){
			$data = DB::table('vendors')->where('vendors.vendor_id','=',$vendor_id)->where('vendors.vendor_email','=',trim($vendorEmail))->count();
			if($data){
				return false;
			}else{
				return DB::table('vendors')->where('vendors.vendor_email',trim($vendorEmail))->count();
			}
		}
    }
	
	//check vendor mobile existence
    function getVendorMobileExistence($vendorMobile,$type='add',$vendor_id=null){
		if($type == 'add'){
			return DB::table('vendors')->where('vendors.vendor_mobile',trim($vendorMobile))->count();
		}else if($type == 'edit'){
			$data = DB::table('vendors')->where('vendors.vendor_id','=',$vendor_id)->where('vendors.vendor_mobile','=',trim($vendorMobile))->count();
			if($data){
				return false;
			}else{
				return DB::table('vendors')->where('vendors.vendor_mobile',trim($vendorMobile))->count();
			}
		}
    }
}

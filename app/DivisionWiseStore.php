<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class DivisionWiseStore extends Model
{
	protected $table = 'division_wise_stores';
	
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = ['store_code','store_name','division_id'];
	
	/**
	* isStoreCodeExist.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function isStoreCodeExist($columnName,$columnValue){			
		return DB::table('division_wise_stores')->where($columnName,$columnValue)->count();
	}
	
}

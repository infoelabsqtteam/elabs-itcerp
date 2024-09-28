<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class DivisionWiseItemStock extends Model
{
    protected $table = 'division_wise_item_stock';
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['store_id','item_id','openning_balance','openning_balance_date','division_id'];
    
    /**
    * isStoreCodeExist.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function isStoreDivisionWiseExist($store_id,$division_id){			
	return DB::table('division_wise_item_stock')->where(['division_wise_item_stock.store_id'=> $store_id, 'division_wise_item_stock.division_id' => $division_id])->count();
    }
}

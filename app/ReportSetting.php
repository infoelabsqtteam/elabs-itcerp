<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;

class ReportSetting extends Model
{
    protected $table = 'order_report_settings';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
    
    /**
    * get Order Details
    * Date :
    * Author :
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getRow($id) {
	return DB::table('order_report_settings')->where('order_report_settings','=',$id)->first();
    }
    
}

<?php
/*****************************
*Created By  : Praveen Singh
*Created On  : 09-Nov-2019
*Modified On : 09-Nov-2019
******************************/

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;

class OrderReportDiscipline extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $table = 'order_report_disciplines';
    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */    
    protected $fillable = [];
    
    /**********************************************
    *Function    : Get Row Detail
    *Created By  : Praveen Singh
    *Created On  : 09-Nov-2019
    *Modified On : 09-Nov-2019
    **********************************************/
    function getRow($id){
        return DB::table('order_report_disciplines')->where('order_report_disciplines.or_discipline_id',$id)->first();
    }
    
    /**
    * Validate Code
    *
    * @return \Illuminate\Http\Response
    */
    function validateCode($code,$type='add',$d=null){		
        if($type == 'add'){
            return DB::table('order_report_disciplines')->where('order_report_disciplines.or_discipline_code','=',$code)->count();
        }else if($type == 'edit'){
            $data = DB::table('order_report_disciplines')->where('order_report_disciplines.or_discipline_id','=',$d)->where('order_report_disciplines.or_discipline_code','=',$code)->count();
            if($data){
                return false;
            }else{
                return DB::table('order_report_disciplines')->where('order_report_disciplines.or_discipline_code','=',$code)->count();
            }
        }
    }
    
    /**
    * Validate Name
    *
    * @return \Illuminate\Http\Response
    */
    function validateName($name,$type='add',$d=null){		
        if($type == 'add'){
            return DB::table('order_report_disciplines')->where('order_report_disciplines.or_discipline_name','=',$name)->count();
        }else if($type == 'edit'){
            $data = DB::table('order_report_disciplines')->where('order_report_disciplines.or_discipline_id','=',$d)->where('order_report_disciplines.or_discipline_name','=',$name)->count();
            if($data){
                return false;
            }else{
                return DB::table('order_report_disciplines')->where('order_report_disciplines.or_discipline_name','=',$name)->count();
            }
        }
    }
}

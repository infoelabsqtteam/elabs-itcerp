<?php
/*****************************
*Created By  : Praveen Singh
*Created On  : 09-Nov-2019
*Modified On : 09-Nov-2019
******************************/

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;

class OrderReportDisciplineParameterDtl extends Model
{    
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $table = 'order_report_discipline_parameter_dtls';
    
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
        return DB::table('order_report_discipline_parameter_dtls')->where('order_report_discipline_parameter_dtls.ordp_id',$id)->first();
    }
    
}

<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'template_dtl';
    
    public function templateExist($postedData){
        if(!empty($postedData)){
            return DB::table('template_dtl')
                    ->where('template_dtl.template_type_id','=',$postedData['template_type_id'])
                    ->where('template_dtl.division_id','=',$postedData['division_id'])
                    ->where('template_dtl.product_category_id','=',$postedData['product_category_id'])
                    ->first();    
        }else{
            return false;
        }       
    }
}

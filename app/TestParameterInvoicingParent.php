<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TestParameterInvoicingParent extends Model
{
    protected $table = 'test_parameter_invoicing_parents';

    /************************************
     * Description : save_test_parameter_invoicing_parents
     * Date        : 29-04-2022
     * Author      : Praveen Singh
     ************************************/
    public function save_test_parameter_invoicing_parents($tpip_status_id, $test_parameter_id)
    {
        $tpip_id = DB::table('test_parameter_invoicing_parents')->where('test_parameter_invoicing_parents.test_parameter_id', '=', $test_parameter_id)->pluck('test_parameter_invoicing_parents.tpip_id')->first();
        if (empty($tpip_id)) {
            if (!empty($tpip_status_id)) {
                $dataSave = array();
                $dataSave['test_parameter_id']     = $test_parameter_id;
                $dataSave['test_parameter_status'] = '1';
                return DB::table('test_parameter_invoicing_parents')->insertGetId($dataSave);
            }
        } else {
            $test_parameter_id = DB::table('test_parameter')->where('test_parameter.test_parameter_invoicing_parent_id', '=', $tpip_id)->pluck('test_parameter.test_parameter_id')->first();
            if (empty($test_parameter_id)) {
                if (empty($tpip_status_id)) {
                    return DB::table('test_parameter_invoicing_parents')->where('test_parameter_invoicing_parents.tpip_id', '=', $tpip_id)->delete();
                }
            }
        }
    }
}

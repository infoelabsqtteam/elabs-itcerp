<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Instance extends Model
{
    protected $table = 'instance_master';

    /*********************************
     * Get Row
     * Date : 29-Nov-2021
     * Author : Praveen Singh
     *********************************/
    public function getRow($id)
    {
        return DB::table('instance_master')->where('instance_master.instance_id', $id)->first();
    }
}

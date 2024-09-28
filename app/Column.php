<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Column extends Model
{
    protected $table = 'column_master';

    /*********************************
     * Get Row
     * Date : 29-Nov-2021
     * Author : Praveen Singh
     *********************************/
    public function getRow($id)
    {
        return DB::table('column_master')->where('column_master.column_id', $id)->first();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrderAnalystWindowSetting extends Model
{
    protected $table = 'order_analyst_window_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Checking Unqiue Record
     * Date : 20-Oct-2021
     * Author : Praveen Singh
     */
    public function validateUniqueRecord($data)
    {
        return DB::table('order_analyst_window_settings')->where('oaws_division_id', $data['oaws_division_id'])->where('oaws_product_category_id', $data['oaws_product_category_id'])->where('oaws_equipment_type_id', $data['oaws_equipment_type_id'])->count();
    }

    /**
     * Generating Unqiue Record
     * Date : 20-Oct-2021
     * Author : Praveen Singh
     */
    function generateUniqueNumber()
    {
        $number = mt_rand(10000, 99999);
        $isExist = DB::table('order_analyst_window_settings')->where('oaws_unique_id', $number)->count();
        if ($isExist) {
            return $this->generateBarcodeNumber();
        }
        return $number;
    }
}

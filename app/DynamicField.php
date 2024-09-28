<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DynamicField extends Model
{
    protected $table = 'order_dynamic_fields';
    protected $fillable = ['dynamic_field_name','dynamic_field_code','dynamic_field_status','odfs_created_by'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Holiday extends Model
{
    protected $table = 'holiday_master';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
}

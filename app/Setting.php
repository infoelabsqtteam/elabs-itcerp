<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Setting extends Model
{
    protected $table = 'settings';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
}

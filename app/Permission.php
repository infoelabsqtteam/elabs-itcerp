<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Permission extends Model
{
    protected $table = 'permissions';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
}

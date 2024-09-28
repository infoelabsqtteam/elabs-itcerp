<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class ProductMasterAlias extends Model
{
    protected $table = 'product_master_alias';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
}

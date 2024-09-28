<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Helpers\SimpleImage;
use File;
use DB;

class DivisionWiseItem extends Model
{
   use Notifiable;
	
   /**
   * Third Party Service for user role ...
   * URI https://github.com/httpoz/roles
   */	
   use Notifiable, HasRole;
	
    protected $table = 'division_wise_items';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
}

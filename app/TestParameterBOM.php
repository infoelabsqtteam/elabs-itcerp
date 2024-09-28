<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
/**
 * Third Party Service for user role ...
 * URI https://github.com/httpoz/roles
 */
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class TestParameterBOM extends Authenticatable implements HasRoleContract
{
    use Notifiable;
    
    /**
    * Third Party Service for user role ...
    * URI https://github.com/httpoz/roles
    */	
    use Notifiable, HasRole;
	
    protected $table = 'product_test_parameter_bom';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'product_test_dtl_id', 'test_id', 'item_id', 'consumed_qty'
    ];	
}

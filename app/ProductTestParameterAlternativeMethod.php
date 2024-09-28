<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class ProductTestParameterAlternativeMethod extends Authenticatable implements HasRoleContract
{
    use Notifiable;
	
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'product_test_parameter_altern_method';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'product_test_param_altern_method_id',
		'product_test_dtl_id',
		'test_id', 
		'test_parameter_id',
		'standard_value_type',
		'standard_value_from', 
		'standard_value_to', 
		'equipment_type_id',
		'detector_id',
		'method_id',
		'claim_dependent',
		'time_taken_days',
		'time_taken_mins',
		'cost_price',
		'selling_price',
		'created_by',
    ];	
}

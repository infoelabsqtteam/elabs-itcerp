<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class DivisionParameters extends Authenticatable implements HasRoleContract
{
    use Notifiable;
    
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'division_parameters';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'division_parameter_id','division_id','division_address','division_country','division_state','division_city','division_PAN','division_VAT_no'
    ];	
}

<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Authenticatable implements HasRoleContract
{
    use Notifiable;
	
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'equipment_type';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'equipment_id', 'equipment_code', 'equipment_description', 'equipment_name','created_by'
    ];	

}

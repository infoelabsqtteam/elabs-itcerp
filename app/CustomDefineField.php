<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

use DB;

class CustomDefineField extends Authenticatable implements HasRoleContract
{
    use Notifiable;
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'custom_defined_fields';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'label_id','label_name','label_value','label_status','created_by'
    ];
}

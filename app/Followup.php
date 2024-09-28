<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class Followup extends Authenticatable implements HasRoleContract
{
    /**
    * Third Party Service for user role ...
    * URI https://github.com/httpoz/roles
    */	
    use Notifiable, HasRole;
	
    protected $table = 'inquiry_followups';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inquiry_id', 'followup_by', 'mode','followup_detail','next_followup_date','status','created_by'
    ];
    
}
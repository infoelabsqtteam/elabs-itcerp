<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Authenticatable implements HasRoleContract
{
	/**
	 * Third Party Service for user role ...
	 * URI https://github.com/httpoz/roles
	 */	
	use Notifiable, HasRole;
	
    protected $table = 'inquiry';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'inquiry_no', 'inquiry_detail','inquiry_date','next_followup_date','inquiry_status','created_by'
    ];	
	
}

<?php
/*****************************************************
*Order Model File
*Created By:Praveen Kumar Singh
*Created On : 15-Dec-2017
*Modified On : 29-Feb-2020
*Package : ITC-ERP-PKL
******************************************************/

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class City extends Authenticatable implements HasRoleContract
{
    use Notifiable;
    
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
    
    protected $table = 'city_db';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_id','state_id', 'city_code', 'city_name','created_by'
    ];	

}

<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class Department extends Authenticatable implements HasRoleContract
{
    use Notifiable;
    
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'departments';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'department_code', 'department_name', 'department_type', 'created_by',
    ];	
	
	// delete the divisions parameters details table entry when delete company
	public function division_parameters()
    {
        return $this->has_many('division_parameters');
    }
	
	//this is a recommended way to declare event handlers
    protected static function boot() {
        parent::boot();
        static::deleting(function($record) { // before delete() method call this
             $record->division_parameters()->delete();
             // do the rest of the cleanup...
        });
    }
}

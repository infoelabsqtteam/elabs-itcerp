<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class Company extends Authenticatable implements HasRoleContract
{
    use Notifiable;
    
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'company_master';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_code','company_name', 'company_address', 'company_city',
    ];	
	
	// delete the deparment details table entry when delete company
	public function departments()
    {
        return $this->has_many('departments');
    }
	
	//delete the divisions details table entry when delete company
	public function divisions()
    {
        return $this->has_many('divisions');
    }
	//this is a recommended way to declare event handlers
    protected static function boot() {
        parent::boot();
        static::deleting(function($record) { // before delete() method call this
             $record->departments()->delete();
             $record->divisions()->delete();
             // do the rest of the cleanup...
        });
    }
}

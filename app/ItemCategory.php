<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Authenticatable implements HasRoleContract
{
    use Notifiable;
	
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'item_categories'; 
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'item_cat_code','item_cat_name','item_parent_cat','created_by',
    ];	
	
	//this is a recommended way to declare event handlers
	public function item_master()
    {
        return $this->has_many('item_master');
    }
	
	//this is a recommended way to declare event handlers
    protected static function boot() {
        parent::boot();
        static::deleting(function($user){ 
             $user->item_master()->delete();
        });
    }
}

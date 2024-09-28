<?php

namespace App;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class Indent extends Authenticatable implements HasRoleContract
{
    use Notifiable;
    
    /**
    * Third Party Service for user role ...
    * URI https://github.com/httpoz/roles
    */	
    use Notifiable, HasRole;
	
    protected $table = 'indent_hdr';
	
    public function getItemId($itemCode){  
	$itemId= DB::table('item_master')->select('item_master.item_id')->where('item_master.item_code',trim($itemCode))->first();  
        return trim($itemId->item_id);
    }
	
}

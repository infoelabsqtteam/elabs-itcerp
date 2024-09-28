<?php

namespace App;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;
class Requisition extends Authenticatable implements HasRoleContract

{
    use Notifiable;
	
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'req_slip_hdr';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'req_slip_date','req_slip_no', 'req_department_id', 'req_by'
    ];
	
	public function getItemId($itemCode){  
		$itemId= DB::table('item_master')->select('item_master.item_id')->where('item_master.item_code',trim($itemCode))->first();  
        return trim($itemId->item_id);
	}
}

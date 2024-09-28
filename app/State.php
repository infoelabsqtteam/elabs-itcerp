<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

use DB;

class State extends Authenticatable implements HasRoleContract
{
    use Notifiable;
    
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'state_db';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'state_id','country_id', 'state_code', 'state_name','created_by'
    ];
	
    /***
    * country,state,city tree view
    * created_by:RUBY
    * created_on:23-JAN-2019
    * */
    public function countryTreeView($county_id=NULL){
	$countryStructureObj = DB::table('countries_db')->select('countries_db.country_id','countries_db.country_name','countries_db.country_level as level')->where('countries_db.country_status','1');
	if(!empty($county_id)){$countryStructureObj->where('countries_db.country_id',$county_id);}
	$countryStructure    = $countryStructureObj->get()->toArray();
	if(!empty($countryStructure)){
	    foreach($countryStructure as $key => $country){
		$country->children = DB::table('state_db')->select('state_db.state_id','state_db.country_id as state_country_id', 'state_db.state_code', 'state_db.state_name as country_name','state_db.state_level as level')->where('state_db.country_id',$country->country_id)->where('state_db.state_status','1')->get()->toArray();
	    }
	}
	return json_decode(json_encode($countryStructure), True);
    }
    
    /**
    * Generating State City Tree
    *
    * return array of tree
    */
    public function statesTree(){		
	$stateTreeStructure = DB::table('state_db')->select('state_id','country_id as level', 'state_code', 'state_name')->where('country_id','101')->get()->toArray();
	if(!empty($stateTreeStructure)){
	    foreach($stateTreeStructure as $key => $values){
		$values->cities = DB::table('city_db')->select('city_id','state_id', 'city_code', 'city_name')->where('state_id',$values->state_id)->get()->toArray();
	    }
	}
	return json_decode(json_encode($stateTreeStructure), True);
    }
}

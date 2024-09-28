<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Route;
use DB;

class UserLogActivity extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $table = 'user_log_activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'action','subject', 'url', 'method', 'ip', 'agent', 'user_id'
    ];
    
    /**
    * Checking user Record
    *
    * @return \Illuminate\Http\Response
    */
    public static function addToUserLogActivity($subject){
    	$log 		= array();
        $log['action']  = basename(Request::fullUrl());
    	$log['subject'] = $subject;
    	$log['url'] 	= Request::fullUrl();
    	$log['method'] 	= Request::method();
    	$log['ip'] 	= Request::ip();
    	$log['agent'] 	= Request::header('user-agent');
    	$log['user_id'] = auth()->check() ? auth()->user()->id : NULL;
    	UserLogActivity::create($log);
    }
}

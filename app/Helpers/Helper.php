<?php

namespace App\Helpers;
use DB;

class Helper
{
    public static function get_divisions(){
       return DB::table('divisions')->get();
    }
    
    public static function formatDate($datetime){
       return date('d-m-Y',strtotime($datetime));
    }

    public static function get_roles(){
       return DB::table('roles')->select('roles.id','roles.name')->where('roles.status','1')->get();   
    } 

    public static function getComapnyDetails(){
       return DB::table('company_master')->select('*')->where('company_master.company_id','1')->first();   
    }

    /**********************************
    *Function:To get user assigned Roles
    *Created By : Praveen Singh
    *Created On:01-March-2018
    ****************************/
    public static function getAllUserRolesList(){
	return DB::table('roles')->select('id','name')->whereIn('roles.id',defined('ROLE_IDS') ? ROLE_IDS : array(0))->get();
    }
    
    /**********************************
    *Function:To get user assigned Roles
    *Created By : Praveen Singh
    *Created On:01-March-2018
    ****************************/
    public static function getCustomerDefinedFieldSymbol($columnName,$customerDefinedFieldArray){
	return !empty($columnName) && !empty($customerDefinedFieldArray) && in_array($columnName,array_values($customerDefinedFieldArray)) ? '<sup style="font-size:9px;">&nbsp;&#x23;</sup>' : '';
    }

}
?>
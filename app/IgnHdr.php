<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class IgnHdr extends Model
{
    protected $table = 'ign_hdr';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
	
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	function generateIgnNo($sectionName){            
        return $sectionName.date('d').date('m').date('y').time();
    }
	
	/**
	* get IGN Header Data
	*
	* @return \Illuminate\Http\Response
	*/
    function getIGNHeader($id){		
		return DB::table('ign_hdr')
                        ->join('divisions','divisions.division_id','ign_hdr.division_id')
                        ->join('vendors','vendors.vendor_id','ign_hdr.vendor_id')
						->join('users as employees','employees.id','ign_hdr.employee_id') 
						->select('ign_hdr.*','divisions.division_name','vendors.vendor_name','employees.name as employee_name')
						->orderBy('ign_hdr.ign_hdr_id','DESC')    
						->where('ign_hdr.ign_hdr_id','=',$id)
						->first();
	}
	
	/**
	* get IGN Header Detail Data
	*
	* @return \Illuminate\Http\Response
	*/
    function getIGNHeaderDetail($id){
		return DB::table('ign_hdr_dtl')
                    ->join('item_master','item_master.item_id','ign_hdr_dtl.item_id')
					->leftJoin('po_hdr','po_hdr.po_hdr_id','ign_hdr_dtl.po_hdr_id')
					->select('ign_hdr_dtl.*','item_master.item_code','item_master.item_name','po_hdr.po_no')
                    ->where('ign_hdr_dtl.ign_hdr_id','=',$id)
                    ->get();
	}
	
	/**
	* Check Vendor Bill NO
	*
	* @return \Illuminate\Http\Response
	*/
    function checkVendorBillNumber($vendor_bill_no,$type='add',$ign_hdr_id=null){		
		if($type == 'add'){
			return DB::table('ign_hdr')->where('ign_hdr.vendor_bill_no','=',$vendor_bill_no)->count();
		}else if($type == 'edit'){
			$data = DB::table('ign_hdr')->where('ign_hdr.ign_hdr_id','=',$ign_hdr_id)->where('ign_hdr.vendor_bill_no','=',$vendor_bill_no)->count();
			if($data){
				return false;
			}else{
				return DB::table('ign_hdr')->where('ign_hdr.vendor_bill_no','=',$vendor_bill_no)->count();
			}
		}
	}
	
	/**
	* Check Gate Pass NO
	*
	* @return \Illuminate\Http\Response
	*/
    function checkGatePassNumber($gate_pass_no,$type='add',$ign_hdr_id=null){
		if($type == 'add'){
			return DB::table('ign_hdr')->where('ign_hdr.gate_pass_no','=',$gate_pass_no)->count();
		}else if($type == 'edit'){
			$data = DB::table('ign_hdr')->where('ign_hdr.ign_hdr_id','=',$ign_hdr_id)->where('ign_hdr.gate_pass_no','=',$gate_pass_no)->count();
			if($data){
				return false;
			}else{
				return DB::table('ign_hdr')->where('ign_hdr.gate_pass_no','=',$gate_pass_no)->count();
			}
		}
	}
}

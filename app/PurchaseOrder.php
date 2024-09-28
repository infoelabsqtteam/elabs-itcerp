<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PurchaseOrder extends Model
{
    protected $table = 'po_hdr';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
	
	function generateDPOPONumber($sectionName){            
        return $sectionName.date('d').date('m').date('y').time();
    }
    
    //Date that needs to be tested goes here    
    function getFormatedDate($date,$format='Y-m-d'){
        return date($format,strtotime($date));
    }
	
	public function getItemId($itemCode){  
		$itemId= DB::table('item_master')->select('item_master.item_id')->where('item_master.item_code',trim($itemCode))->first();  
        return trim($itemId->item_id);
	}
	
	//get Alternative Order Parameters
    function getPurchaseOrderHeader($id){
		
		return DB::table('po_hdr')
                        ->join('divisions','divisions.division_id','po_hdr.division_id')
                        ->join('vendors','vendors.vendor_id','po_hdr.vendor_id')             
						->select('po_hdr.*','divisions.division_name','vendors.vendor_name')
						->orderBy('po_hdr.po_hdr_id','DESC')    
						->where('po_hdr.po_hdr_id','=',$id)
						->first();
	}
	
	//get Alternative Order Parameters
    function getPurchaseOrderHeaderDetail($id){
		return DB::table('po_hdr_detail')
                    ->join('item_master','item_master.item_id','po_hdr_detail.item_id')
					->select('po_hdr_detail.*','item_master.item_code','item_master.item_name','item_master.item_description')
                    ->where('po_hdr_detail.po_hdr_id','=',$id)
                    ->get();
	}
	
	//get Alternative Order Parameters
    function updateDPOPOStatus($po_hdr_id){
		return DB::table('po_hdr')->where('po_hdr.po_hdr_id','=',$po_hdr_id)->update(['status'=> '2','short_close_date' => CURRENTDATE]);
	}
}

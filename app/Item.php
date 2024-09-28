<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

use App\Helpers\SimpleImage;
use File;
use DB;

class Item extends Authenticatable implements HasRoleContract
{
    use Notifiable;
    
    /**
     * Third Party Service for user role ...
     * URI https://github.com/httpoz/roles
     */	
    use Notifiable, HasRole;
	
    protected $table = 'item_master';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
        'created_by', 'item_cat_id', 'item_code', 'item_name', 'item_description', 'item_barcode', 'item_image', 'item_long_description','item_technical_description','item_specification','item_unit','is_perishable','shelf_life_days',
    ];
	
    /**
    * upload the specified image in storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function uploadImage($item_id, $requestData) {
    
        $file 		= $requestData->file('item_image');
        $file_name      = time() . '-item_image.' . $file->getClientOriginalExtension();
        $extract_ext 	= pathinfo($file_name);
        $dirname 	= $extract_ext['dirname'];
        $basename 	= $extract_ext['basename'];
        $imagename 	= $extract_ext['filename'];
        $image_extension= $extract_ext['extension'];
        $image_path 	= public_path() . '/images/items/' . $item_id . '/';
		
	if(file_exists($image_path)){
	    array_map('unlink', glob($image_path."*"));
	}
		
        if(!file_exists($image_path)){			
            mkdir($image_path, 0777, true);
        }
		
        if($file->move($image_path, $file_name)){
			
            $image = new SimpleImage();
            $image->load($image_path . $file_name);            
            
	    //Item Image size
            $image->resize(150,190);
            $image_thumb = $imagename . '_thumb'.'.'.$image_extension;
            $image->save($image_path . $image_thumb);
	    $src_Path = url(''). '/public/images/items/' . $item_id . '/'.$image_thumb;
			
            return array($image_thumb,$src_Path);
        }       
    }
	
    /**
    * upload the specified image in storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function removeUploadedItemImage($item_id, $item_image) {
	    
	    $image_path 	 = public_path() . '/images/items/' . $item_id . '/';
	    $src_Path        = url(''). '/public/images/default-item.jpeg';
	    
	    if(array_map('unlink', glob($image_path."*"))){
		    return $src_Path;
	    }
	    return false;
    }
    
    /**
    * upload the specified image in storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function copyItemMaster(){		
	    $divisions = DB::table('divisions')->get();
	    if(!empty($divisions)){
		    foreach($divisions as $division){				
			    $itemData = DB::table('item_master')->get();
			    if(!empty($itemData)){
				    foreach($itemData as $key => $item){
					    $itemExistCheck = DB::table('division_wise_items')
									    ->where('division_wise_items.item_id',$item->item_id)
									    ->where('division_wise_items.division_id',$division->division_id)
									    ->first();
					    if(empty($itemExistCheck)){
						    $dataSave = array();
						    $dataSave['item_id']     = $item->item_id;
						    $dataSave['division_id'] = $division->division_id;
						    $dataSave['created_by']  = USERID;
						    $created = DB::table('division_wise_items')->insertGetId($dataSave);					
					    }
				    }
			    }
		    }
	    }
	    return true;
    }
    
    /**
    * upload the specified image in storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function getItemId($itemCode){  
	$itemId= DB::table('item_master')->select('item_master.item_id')->where('item_master.item_code',trim($itemCode))->first();  
	return trim($itemId->item_id);
    }
    
    /**
    * Check Item Code
    *
    * @return \Illuminate\Http\Response
    */
    function checkItemCode($item_code,$type='add',$item_id=null){		
	if($type == 'add'){
	    return DB::table('item_master')->where('item_master.item_code','=',$item_code)->count();
	}else if($type == 'edit'){
	    $data = DB::table('item_master')->where('item_master.item_id','=',$item_id)->where('item_master.item_code','=',$item_code)->count();
	    if($data){
		return false;
	    }else{
		return DB::table('item_master')->where('item_master.item_code','=',$item_code)->count();
	    }
	}
    }
	
    /**
    * Check Item barcode
    *
    * @return \Illuminate\Http\Response
    */
    function checkItemBarcode($item_barcode,$type='add',$item_id=null){		
	if($type == 'add'){
	    return DB::table('item_master')->where('item_master.item_barcode','=',$item_barcode)->count();
	}else if($type == 'edit'){
	    $data = DB::table('item_master')->where('item_master.item_id','=',$item_id)->where('item_master.item_barcode','=',$item_barcode)->count();
	    if($data){
		return false;
	    }else{
		return DB::table('item_master')->where('item_master.item_barcode','=',$item_barcode)->count();
	    }
	}
    }

}

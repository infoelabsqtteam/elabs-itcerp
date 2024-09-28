<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;

class CreditNote extends Model
{
	    /**
	    * The attributes that are mass assignable.
	    *
	    * @var array
	    */
	    protected $table = 'credit_notes';
	    
	    /**
	    * The attributes that are mass assignable.
	    *
	    * @var array
	    */    
	    protected $fillable = [];
	    
	    /**********************************************
	    *Function    : Get Credit Note Detail
	    *Created By  : Praveen Singh
	    *Created On  : 11-Dec-2018
	    *Modified On : 11-Dec-2018
	    **********************************************/
	    function getRow($id){
			return DB::table('credit_notes')->where('credit_notes.credit_note_id',$id)->first();
	    }
	    
	    /**********************************************
	    *Function    : Auto Generating the Credit Note Number
	    *Created By  : Praveen Singh
	    *Created On  : 11-Dec-2018
	    *Modified On : 11-Dec-2018
	    **********************************************/
	    function generateCreditNoteNumber($sectionName){
			
			$currentDay   = date('d');
			$currentMonth = date('m');
			$currentYear  = date('Y');
			$currentYr  = date('y');			
								
			//getting Max Serial Number
			$maxCreditNoteData = DB::table('credit_notes')->select('credit_notes.credit_note_id','credit_notes.credit_note_no')->whereMonth('credit_notes.credit_note_date',$currentMonth)->whereYear('credit_notes.credit_note_date',$currentYear)->orderBy('credit_notes.credit_note_id','DESC')->limit(1)->first();			
			$maxSerialNo       = !empty($maxCreditNoteData->credit_note_no) ? substr($maxCreditNoteData->credit_note_no,8) + 1: '0001';
			$maxSerialNo       = $maxSerialNo != '9999' ? str_pad($maxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';
		
			//Combing all to get unique order number		
			$creditNoteNumber  = $sectionName.$currentYr.$currentMonth.$currentDay.$maxSerialNo;
			
			//echo '<pre>';print_r($maxPaymentReceivedData);die;            
			return $creditNoteNumber;
	    }
		
	    /**
	    * Check Vendor Bill NO
	    *
	    * @return \Illuminate\Http\Response
	    */
	    function checkCreditNoteNumber($credit_note_no,$type='add',$credit_note_id=null){		
			if($type == 'add'){
				    return DB::table('credit_notes')->where('credit_notes.credit_note_no','=',$credit_note_no)->count();
			    }else if($type == 'edit'){
				    $data = DB::table('credit_notes')->where('credit_notes.credit_note_id','=',$credit_note_id)->where('credit_notes.credit_note_no','=',$credit_note_no)->count();
				    if($data){
					    return false;
				    }else{
					    return DB::table('credit_notes')->where('credit_notes.credit_note_no','=',$credit_note_no)->count();
				    }
			    }
	    }
	    
	    /**********************************************
	    *Function    : Calculating TAX Slab for credit note
	    *Created By  : Praveen Singh
	    *Created On  : 11-Dec-2018
	    *Modified On : 11-Dec-2018
	    **********************************************/
	    public function calculateCreditNoteTaxSlab($formData){
			
			global $models,$invoice;
			
			$defaultTaxDetail = array();
			
			$taxSlabType = '0';
			
			$SGST = defined('SGST') ? SGST : '0';
			$CGST = defined('CGST') ? CGST : '0';
			$IGST = defined('IGST') ? IGST : '0';
			
			if(!empty($formData['credit_note_type_id'])){
				    if($formData['credit_note_type_id'] == '1'){	//Against Invoice						
						//getting Invoice Detail
						$defaultTaxDetail = $invoice->getInvoiceHdr($formData['invoice_id']);						
						if(!empty($defaultTaxDetail->sgst_rate) && !empty($defaultTaxDetail->cgst_rate)){
							    $taxSlabType = '1';
						}else if(!empty($defaultTaxDetail->igst_rate)){
							    $taxSlabType = '2';
						}						
				    }else if($formData['credit_note_type_id'] == '2'){		//Against Fresh Ref. No.
						$division_id = !empty($formData['division_id']) ? trim($formData['division_id']) : '0';
						$customer_id = !empty($formData['customer_id']) ? trim($formData['customer_id']) : '0';
						if($division_id && $customer_id){
							    $divisionTaxDetail = DB::table('division_parameters')->select('division_parameters.division_id','division_parameters.division_state')->where('division_parameters.division_id',$division_id)->first();
							    $customerTaxDetail = DB::table('customer_master')->select('customer_master.customer_id','customer_master.customer_state')->where('customer_master.customer_id',$customer_id)->first();
							    if($divisionTaxDetail->division_state == $customerTaxDetail->customer_state){
									$taxSlabType = '1';
							    }else{
									$taxSlabType = '2';	
							    }
						}						
				    }
				    if($taxSlabType == '1'){
						$formData['credit_note_sgst_rate']    = $SGST;
						$formData['credit_note_sgst_amount']  = ($formData['credit_note_amount'] * $SGST) / 100;
						$formData['credit_note_cgst_rate']    = $CGST;
						$formData['credit_note_cgst_amount']  = ($formData['credit_note_amount'] * $CGST) / 100;
						$formData['credit_note_net_amount']   = $formData['credit_note_amount'] + $formData['credit_note_sgst_amount'] + $formData['credit_note_cgst_amount'];
				    }else if($taxSlabType == '2'){
						$formData['credit_note_igst_rate']   = $IGST;
						$formData['credit_note_igst_amount'] = ($formData['credit_note_amount'] * $IGST) / 100;
						$formData['credit_note_net_amount']  = $formData['credit_note_amount'] + $formData['credit_note_igst_amount'];
				    }
			}
			return $formData;
	    }
	    
	    /**********************************************
	    *Function    : Getting Auto Credit Note Detail
	    *Created By  : Praveen Singh
	    *Created On  : 11-Dec-2018
	    *Modified On : 11-Dec-2018
	    **********************************************/
	    public function getCreditNoteDetail($credit_note_id){
	
			global $models,$invoice,$numbersToWord;
			
			$creditDetailList = array();
			
			if(!empty($credit_note_id)){	    
				    $creditNoteDetail = $this->getRow($credit_note_id);
				    if(!empty($creditNoteDetail->credit_note_type_id)){
						if($creditNoteDetail->credit_note_type_id == '1'){
						    $creditDetailList = $this->getAutoCreditNoteDetail($credit_note_id);
						}else{
						    $creditDetailList = $this->getManualCreditNoteDetail($credit_note_id);
						}
				    }
			}
			return $creditDetailList;
	    }
	    
	    /**********************************************
	    *Function    : Getting Auto Credit Note Detail
	    *Created By  : Praveen Singh
	    *Created On  : 11-Dec-2018
	    *Modified On : 11-Dec-2018
	    **********************************************/	    
	    function getAutoCreditNoteDetail($id){
			
			global $models,$invoice,$numbersToWord;
			
			$returnData = array();
			
			$creditDetail = DB::table('credit_notes')
				    ->join('invoice_hdr', 'invoice_hdr.invoice_id', 'credit_notes.invoice_id')
				    ->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
				    ->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
				    ->join('product_master_alias','product_master_alias.c_product_id','order_master.sample_description_id')
				    ->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
				    ->join('customer_master', 'customer_master.customer_id', 'invoice_hdr.customer_id')
				    ->join('customer_contact_persons','customer_contact_persons.customer_id','customer_master.customer_id')
				    ->join('state_db','state_db.state_id','customer_master.customer_state')
				    ->join('city_db','customer_master.customer_city','city_db.city_id')
				    ->join('users as invoiceByTb', 'invoiceByTb.id', 'invoice_hdr.created_by')
				    ->leftjoin('customer_master as invoicing_master','invoicing_master.customer_id','invoice_hdr_detail.order_invoicing_to')
				    ->leftjoin('city_db as invoicingToCity','invoicingToCity.city_id','invoicing_master.customer_city')
				    ->leftjoin('state_db as invoicingToState','invoicingToState.state_id','invoicing_master.customer_state')
				    ->leftJoin('template_dtl', function($join){
						$join->on('template_dtl.division_id', '=', 'invoice_hdr.division_id');
						$join->where('template_dtl.template_type_id','=','2');
						$join->where('template_dtl.template_status_id','=','1');
				    })
				    ->select('credit_notes.*','customer_master.customer_name','customer_master.customer_email','customer_master.customer_address','customer_master.customer_state','customer_master.customer_city','customer_master.customer_gst_no','city_db.city_name as customer_city_name','state_db.state_name as customer_state_name','order_master.order_date', 'order_master.sample_description_id', 'order_master.batch_no', 'order_master.order_no', 'order_master.order_id', 'order_master.discount_type_id', 'order_master.discount_value','order_master.product_category_id','order_master.billing_type_id','order_master.po_no','order_master.po_date','order_master.po_date','invoice_hdr.*', 'order_report_details.report_file_name','order_report_details.report_no','product_master_alias.c_product_name as sample_description','invoice_hdr_detail.order_amount','invoice_hdr_detail.order_discount','invoice_hdr_detail.order_total_amount','invoice_hdr_detail.order_sgst_amount','invoice_hdr_detail.order_cgst_amount','invoice_hdr_detail.order_igst_amount','invoice_hdr_detail.order_net_amount','invoicing_master.customer_address as altInvoicingAddress','invoicingToState.state_name as invoicing_state','invoicingToCity.city_name as invoicing_city','invoicing_master.customer_name as invoicingCustomerName','invoicing_master.customer_gst_no as invoicingCustomerGSTo','invoiceByTb.name as invoice_by','invoiceByTb.user_signature','template_dtl.header_content','template_dtl.footer_content')
				    ->where('credit_notes.credit_note_id',!empty($id) ? $id : '0')
				    ->orderBy('order_master.po_no', 'ASC')
				    ->orderBy('order_master.order_no', 'ASC')
				    ->orderBy('credit_notes.credit_note_id', 'ASC')
				    ->get()
				    ->toArray();
			
			if(!empty($creditDetail)){
				    foreach($creditDetail as $key => $values){					
						$values->net_total_wsw = round(DB::table('invoice_hdr_detail')->where('invoice_hdr_detail.invoice_hdr_id',$values->invoice_id)->sum('invoice_hdr_detail.order_net_amount'));
						$returnData['creditNoteHeader'] = array(
							    'invoice_id'            	=> $values->invoice_id,
							    'invoice_no'            	=> $values->invoice_no,
							    'credit_note_no'            => $values->credit_note_no,
							    'credit_note_type_id'       => $values->credit_note_type_id,
							    'credit_reference_no'       => $values->credit_reference_no,							    
							    'credit_note_date'          => date(DATEFORMAT,strtotime($values->credit_note_date)),
							    'credit_note_remark'        => $values->credit_note_remark,
							    'customer_name'         	=> !empty($values->invoicingCustomerName) ? ucfirst($values->invoicingCustomerName) : ucfirst($values->customer_name),
							    'customer_city_name'    	=> !empty($values->invoicing_city) ? strtoupper($values->invoicing_city) : strtoupper($values->customer_city_name),
							    'customer_state_name'   	=> !empty($values->invoicing_state) ? strtoupper($values->invoicing_state) : strtoupper($values->customer_state_name),
							    'customer_address'      	=> !empty($values->altInvoicingAddress) ? $values->altInvoicingAddress : $values->customer_address,
							    'customer_gst_no'       	=> !empty($values->invoicingCustomerGSTo) ? strtoupper($values->invoicingCustomerGSTo) : strtoupper($values->customer_gst_no),
							    'invoice_date'          	=> date(DATEFORMAT,strtotime($values->invoice_date)),
							    'order_no'          	=> $values->order_no,
							    'billing_type'          	=> $values->billing_type_id,
							    'invoice_by'		=> $values->invoice_by,
							    'user_sign_path'		=> SITE_URL.SIGN_PATH,
							    'user_signature'		=> $values->user_signature,
							    'division_id'   		=> $values->division_id,
							    'product_category_id'   	=> $values->product_category_id,
							    'invoice_file_name'     	=> $values->invoice_file_name,
							    'invoice_file_name_without_hf' => $values->invoice_file_name_without_hf,
							    'header_content' 		=> $values->header_content,
							    'footer_content' 		=> $values->footer_content,			
						);		    
						if(!empty($values->billing_type_id) && !empty($values->po_no) && $values->billing_type_id == '5'){
							    $poDatekey = !empty($values->po_no) && !empty($values->po_date) ? $values->po_no.'|'.date('d-m-Y',strtotime($values->po_date)) : $values->po_no;
							    $returnData['creditNoteBody'][$poDatekey][$key] = array(
									'order_id'          	=> $values->order_id,
									'po_no'          	=> $values->po_no,
									'name_of_product'   	=> $values->sample_description,
									'batch_no'          	=> $values->batch_no,
									'order_no'          	=> $values->order_no,
									'report_no'         	=> $values->report_no,
									'report_file_name'  	=> $values->report_file_name,
									'amount'            	=> $values->order_amount,
									'basic_rate'            => $values->order_total_amount,
									'service_tax'          	=> $models->roundValue($values->order_sgst_amount + $values->order_cgst_amount + $values->order_igst_amount),
									'l1_final_amount'       => $models->roundValue($values->order_net_amount),
							    );
						}else{
							    $returnData['creditNoteBody'][$key] = array(
									'order_id'          	=> $values->order_id,
									'po_no'          	=> $values->po_no,
									'name_of_product'   	=> $values->sample_description,
									'batch_no'          	=> $values->batch_no,
									'order_no'          	=> $values->order_no,
									'report_no'         	=> $values->report_no,
									'report_file_name'  	=> $values->report_file_name,
									'amount'            	=> $values->order_amount,
									'basic_rate'            => $values->order_total_amount,
									'service_tax'          	=> $models->roundValue($values->order_sgst_amount + $values->order_cgst_amount + $values->order_igst_amount),
									'l1_final_amount'       => $models->roundValue($values->order_net_amount),
							    );
						}		    
						$returnData['creditNoteFooter'] = array(
							    'total'              		=> $values->total_amount,
							    'discount'           		=> $values->total_discount,
							    'discount_text'      		=> !empty($values->discount_type_id) && $values->discount_type_id == '2' ? '('.$values->discount_value.'%)' : '0',
							    'net_amount'         		=> !empty($values->total_discount) && round($values->total_discount) > '0' ? number_format((float) $values->total_amount - $values->total_discount, 2, '.', '') : '',
							    'surcharge_amount'   		=> $values->surcharge_amount,
							    'extra_amount'       		=> $values->extra_amount,
							    'sgst_rate'          		=> $values->sgst_rate,
							    'sgst_amount'        		=> $values->sgst_amount,
							    'cgst_rate'          		=> $values->cgst_rate,
							    'cgst_amount'        		=> $values->cgst_amount,
							    'igst_rate'          		=> $values->igst_rate,
							    'igst_amount'        		=> $values->igst_amount,
							    'net_total_wsw'          		=> number_format($values->net_total_wsw, 2),
							    'net_total_in_words_wsw' 		=> strtoupper($numbersToWord->number_to_word($values->net_total_wsw)),
							    'net_total'          		=> number_format(round($values->net_total_amount), 2),
							    'net_total_in_words' 		=> strtoupper($numbersToWord->number_to_word(round($values->net_total_amount)))
						);
				    }
			}
			return $returnData;
	    }
	    
	    /**********************************************
	    *Function    : Getting Manual Credit Note Detail
	    *Created By  : Praveen Singh
	    *Created On  : 11-Dec-2018
	    *Modified On : 11-Dec-2018
	    **********************************************/	    
	    function getManualCreditNoteDetail($id){
			
			global $models,$invoice,$numbersToWord;
			
			$returnData = array();
			
			$creditDetail = DB::table('credit_notes')
				    ->leftJoin('invoice_hdr', 'invoice_hdr.invoice_id', 'credit_notes.invoice_id')
				    ->leftJoin('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
				    ->join('customer_master', 'customer_master.customer_id', 'credit_notes.customer_id')
				    ->join('customer_contact_persons','customer_contact_persons.customer_id','customer_master.customer_id')
				    ->join('state_db','state_db.state_id','customer_master.customer_state')
				    ->join('city_db','customer_master.customer_city','city_db.city_id')
				    ->leftJoin('users as invoiceByTb', 'invoiceByTb.id', 'credit_notes.created_by')
				    ->leftjoin('customer_master as invoicing_master','invoicing_master.customer_id','invoice_hdr_detail.order_invoicing_to')
				    ->leftjoin('city_db as invoicingToCity','invoicingToCity.city_id','invoicing_master.customer_city')
				    ->leftjoin('state_db as invoicingToState','invoicingToState.state_id','invoicing_master.customer_state')
				    ->leftJoin('template_dtl', function($join){
						$join->on('template_dtl.division_id', '=', 'credit_notes.division_id');
						$join->where('template_dtl.template_type_id','=','2');
						$join->where('template_dtl.template_status_id','=','1');
				    })
				    ->select('credit_notes.*','customer_master.customer_name','customer_master.customer_email','customer_master.customer_address','customer_master.customer_state','customer_master.customer_city','customer_master.customer_gst_no','city_db.city_name as customer_city_name','state_db.state_name as customer_state_name','invoice_hdr.invoice_id','invoice_hdr.invoice_no','invoice_hdr.product_category_id','invoice_hdr.invoice_date','invoiceByTb.name as invoice_by','invoiceByTb.user_signature','template_dtl.header_content','template_dtl.footer_content')
				    ->where('credit_notes.credit_note_id',!empty($id) ? $id : '0')
				    ->groupBy('credit_notes.credit_note_id')
				    ->orderBy('credit_notes.credit_note_id', 'ASC')
				    ->get()
				    ->toArray();
			
			if(!empty($creditDetail)){
				    foreach($creditDetail as $key => $values){
						$returnData['creditNoteHeader'] = array(
							    'invoice_id'            	=> $values->invoice_id,
							    'invoice_no'            	=> !empty($values->invoice_no) ? $values->invoice_no : $values->credit_reference_no,
							    'credit_note_no'            => $values->credit_note_no,
							    'credit_note_type_id'       => $values->credit_note_type_id,
							    'credit_reference_no'       => $values->credit_reference_no,
							    'credit_note_date'          => !empty($values->credit_note_date) ? date(DATEFORMAT,strtotime($values->credit_note_date)) : '',
							    'credit_note_remark'        => $values->credit_note_remark,
							    'customer_name'         	=> !empty($values->invoicingCustomerName) ? ucfirst($values->invoicingCustomerName) : ucfirst($values->customer_name),
							    'customer_city_name'    	=> !empty($values->invoicing_city) ? strtoupper($values->invoicing_city) : strtoupper($values->customer_city_name),
							    'customer_state_name'   	=> !empty($values->invoicing_state) ? strtoupper($values->invoicing_state) : strtoupper($values->customer_state_name),
							    'customer_address'      	=> !empty($values->altInvoicingAddress) ? $values->altInvoicingAddress : $values->customer_address,
							    'customer_gst_no'       	=> !empty($values->invoicingCustomerGSTo) ? strtoupper($values->invoicingCustomerGSTo) : strtoupper($values->customer_gst_no),
							    'invoice_date'          	=> !empty($values->invoice_date) ? date(DATEFORMAT,strtotime($values->invoice_date)) : '',
							    'invoice_by'		=> $values->invoice_by,
							    'user_sign_path'		=> SITE_URL.SIGN_PATH,
							    'user_signature'		=> $values->user_signature,
							    'division_id'   		=> $values->division_id,
							    'product_category_id'   	=> $values->product_category_id,
							    'header_content' 		=> $values->header_content,
							    'footer_content' 		=> $values->footer_content,			
						);		    
						
						$returnData['creditNoteBody'][$key] = array(
							    'name_of_product'   	=> 'Testing Charge',
							    'batch_no'          	=> '-',
							    'order_no'          	=> '-',
							    'report_no'         	=> '-',
							    'amount'            	=> $values->credit_note_amount,
						);
								    
						$returnData['creditNoteFooter'] = array(
							    'total'              	=> $values->credit_note_amount,
							    'sgst_rate'          	=> $values->credit_note_sgst_rate,
							    'sgst_amount'        	=> $values->credit_note_sgst_amount,
							    'cgst_rate'          	=> $values->credit_note_cgst_rate,
							    'cgst_amount'        	=> $values->credit_note_cgst_amount,
							    'igst_rate'          	=> $values->credit_note_igst_rate,
							    'igst_amount'        	=> $values->credit_note_igst_amount,
							    'net_total'          	=> number_format(round($values->credit_note_net_amount), 2),
							    'net_total_in_words' 	=> strtoupper($numbersToWord->number_to_word(round($values->credit_note_net_amount)))
						);
				    }
			}
			return $returnData;
	    }

}

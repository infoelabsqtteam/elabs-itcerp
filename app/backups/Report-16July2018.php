<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
	    /*************************
	    * get report details from order_report_details table
	    *************************/
	    public function getOrderReportDetails($report_id) {
			return DB::table('order_report_details')->where('report_id','=',$report_id)->first();
	    }

	    /*************************
	    *Generating Report Number
	    *Format : Prefix-DepartmentName-YYMMDDSERIALNo
	    *************************/
	    function generateReportNumber($currentDate,$productCategoryId,$divisionId){

			if(!empty($currentDate) && !empty($productCategoryId) && !empty($divisionId)){

				    $reportDay   = date('d',strtotime($currentDate));
				    $reportMonth = date('m',strtotime($currentDate));
				    $reportYear  = date('Y',strtotime($currentDate));
				    $reportDYear = date('y',strtotime($currentDate));

				    //Getting Section Name
				    $productCategoryData = DB::table('product_categories')->where('product_categories.p_category_id',$productCategoryId)->first();
				    $sectionName         = !empty($productCategoryData->p_category_name) ? substr($productCategoryData->p_category_name,0,1) : 'F';

				    //In case of Pharma Deparment,order number will be generated according to current month and current day
				    if($productCategoryId == '2'){
						$maxReportData = DB::table('order_report_details')
							    ->join('order_master','order_report_details.report_id','order_master.order_id')
							    ->select('order_report_details.report_id','order_report_details.report_no')
							    ->where('order_master.product_category_id',$productCategoryId)
							    ->whereDay('order_report_details.report_date',$reportDay)
							    ->whereMonth('order_report_details.report_date',$reportMonth)
							    ->whereYear('order_report_details.report_date',$reportYear)
							    ->where('order_master.division_id',$divisionId)
							    ->orderBy('order_report_details.report_no','DESC')
							    ->limit(1)
							    ->first();

				    }else{
						$maxReportData = DB::table('order_report_details')
							    ->join('order_master','order_report_details.report_id','order_master.order_id')
							    ->select('order_report_details.order_report_id','order_report_details.report_id','order_report_details.report_no')
							    ->where('order_master.product_category_id',$productCategoryId)
							    ->whereMonth('order_report_details.report_date',$reportMonth)
							    ->whereYear('order_report_details.report_date',$reportYear)
							    ->where('order_master.division_id',$divisionId)
							    ->orderBy('order_report_details.report_no','DESC')
							    ->limit(1)
							    ->first();
				    }

				    //getting Max Serial Number
				    $maxSerialNo = !empty($maxReportData->report_no) ? substr($maxReportData->report_no,10) + 1: '0001';
				    $maxSerialNo = $maxSerialNo != '9999' ? str_pad($maxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';

				    //Combing all to get unique order number
				    $reportNumber = REPORT_PREFIX.$sectionName.'-'.$reportDYear.$reportMonth.$reportDay.$maxSerialNo;

				    //echo '<pre>';print_r($reportNumber);die;
				    return $reportNumber;
			}
	    }

	    /*************************
	    *Generating Report Number
	    *Save report number
	    *************************/
	    function updateGenerateReportNumberDate($orderId,$reportDate,$backReportDate=NULL){
			if(!empty($orderId)){
				    $dataSave   	= $reportData = array();
				    $orderData 		= DB::table('order_master')->where('order_master.order_id','=',$orderId)->first();
				    $reportDetailData 	= DB::table('order_report_details')->where('order_report_details.report_id','=',$orderId)->first();
				    if(!empty($orderData->order_id)){
						if(empty($reportDetailData->report_no) && empty($reportDetailData->report_date)){
							    $reportData['report_no']   = $this->generateReportNumber($reportDate,$orderData->product_category_id,$orderData->division_id);
							    $reportData['report_date'] = $reportDate;
							    return DB::table('order_report_details')->where('report_id',$orderId)->update($reportData);
						}else if(!empty($backReportDate) && !empty($reportDetailData->report_no) && !empty($reportDetailData->report_date)){
							    $reportData['report_no']   = $this->generateReportNumber($reportDate,$orderData->product_category_id,$orderData->division_id);
							    $reportData['report_date'] = $reportDate;
							    return DB::table('order_report_details')->where('report_id',$orderId)->update($reportData);
						}
				    }
			}
			return false;
	    }

	    /*************************
	    *Update order status
	    *while saving a report
	    *************************/
	    public function updateReportOrderStatusLog($orderId){
		    global $order,$report,$models,$invoice,$mail,$numbersToWord;
		    $reportData = $order->getOrder($orderId);
		    if($reportData){
			    !empty($reportData->status) && $reportData->status == '7' ? $order->updateOrderStausLog($orderId,'8') : $order->updateOrderLog($orderId,'8');
			    return true;
		    }
		    return false;
	    }

	    /*************************
	    * Update analyst/tester job/order assigned status
	    *
	    *************************/
	    public function updateAnalystJobAssignedStatus($order_id,$order_parameter_id,$employee_id){
			if(defined('IS_TESTER') && IS_TESTER){
				$whereCondition = array('schedulings.order_id' => $order_id,'schedulings.order_parameter_id' => $order_parameter_id,'schedulings.employee_id' => $employee_id);
			}else{
				$whereCondition = array('schedulings.order_id' => $order_id,'schedulings.order_parameter_id' => $order_parameter_id);
			}
			if(DB::table('schedulings')->where($whereCondition)->update(['status' => '3' ,'notes' => 'Completed', 'completed_at' => defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s')])){
				    return true;
			}else{
				    return false;
			}
	    }

	    /*************************
	    * Get user whose task status is uncompleted
	    *
	    *************************/
	    public function getUserRoleIdTaskUncompleted($role_ids){
		    if(!empty($role_ids)){
			    return DB::table('order_status')
			    ->whereIn('order_status.role_id',$role_ids)
			    ->pluck('order_status.order_status_id')
			    ->toArray();
		    }
	    }

	    /*************************
	    * Get user whose task status is completed
	    *
	    *************************/
	    public function getUserOrderIdTaskCompleted($role_ids,$user_id){
			if(!empty($role_ids) && !empty($user_id)){
				    return DB::table('order_status')
						->join('order_process_log','order_process_log.opl_order_status_id','order_status.order_status_id')
						->whereIn('order_status.role_id',$role_ids)
						->where('order_process_log.opl_user_id',$user_id)
						->groupBy('order_process_log.opl_order_id')
						->pluck('order_process_log.opl_order_id')
						->toArray();
			}
	    }

	    /*************************
	    * Get user whose task status is completed
	    * updated on 19-JAN-2018
	    *************************/
	    public function getOrderStageWithOrWithoutAmendment($order_id){

			global $order,$report,$models,$invoice,$mail,$numbersToWord;
	    
			$reportData = $order->getOrder($order_id);
	    
			if(!empty($reportData->status) && $reportData->status == '7'){
	    
				    $orderSampleType = !empty($reportData->order_sample_type) && in_array($reportData->order_sample_type,array('1','2')) ? true : false;
	    
				    $data = DB::table('order_process_log')
						->where('order_process_log.opl_amend_status','1')
						->where('order_process_log.opl_order_id',$order_id)
						->orderBy('order_process_log.opl_id','DESC')
						->limit(1)
						->first();
				    $checkInvoice =  DB::table('order_process_log')
						->join('invoice_hdr_detail','invoice_hdr_detail.order_id','=','order_process_log.opl_order_id')
						->where('order_process_log.opl_order_id', '=', $order_id)
						->where('order_process_log.opl_order_status_id','8')
						->where('order_process_log.opl_amend_status','1')
						->whereNotNull('order_process_log.opl_user_id')
						->get()->toArray();
				    if($data){
						if(empty($checkInvoice) && empty($orderSampleType)){
						    return array('7','8');	
						}
						else{
						    return array('7','9');		
						}
				    }else{
						return $orderSampleType ? array('7','9') : array('7','8');
				
				    }
				    
			}
			return array(0,0);
	    }
	    
	    /*************************
	    *Update Report Type On Report Generation
	    *Praveen Singh
	    *Date : 10-01-2018
	    *************************/
	    function updateReportTypeOnReportGeneration($orderId,$reportType){
			$reportDetailData = DB::table('order_report_details')->where('order_report_details.report_id','=',$orderId)->first();
			return !empty($reportDetailData) && !empty($reportType) ? DB::table('order_report_details')->where('order_report_details.report_id',$orderId)->update(['order_report_details.report_type' => $reportType]) : false;
	    }

	    /*************************
	    *Get all parent product categories
	    *Date : 06-02-2018
	    *************************/
	    public function getParentProductCategories(){
			$colArr =   array();
			$catData = DB::table('product_categories')->where('product_categories.parent_id','0');
			if(empty($colArr)){
				$data =  $catData->pluck('p_category_id')->toArray();
			}else{
				$data = $catData->select('p_category_id')->get();
			}
			return !empty($data) ? $data : array();
	    }

	    /*************************
	    *Checking Back Date Booking Allowed in Pharma Department
	    *Date : 06-02-2018
	    *************************/
	    public function checkBackDateBookingAllowed($orderList){
			$orderDate = strtotime(date(DATEFORMAT,strtotime($orderList->order_date)));
			$bookingDate = strtotime(date(DATEFORMAT,strtotime($orderList->booking_date)));
			return $orderList->product_category_id == '2' && $orderDate != $bookingDate ? 1 : 0;
	    }

	    /*************************
	    *Checking Order Date And Report Data Validation
	    *Date : 06-02-2018
	    *************************/
	    public function checkOrderDateAndReportDataValidation($orderId,$reportDate){
			$orderData = DB::table('order_master')->where('order_master.order_id','=',$orderId)->first();
			if(!empty($orderData->order_date)){
				    $orderDate  = strtotime(date(DATEFORMAT,strtotime($orderData->order_date)));
				    $reportDate = strtotime(date(DATEFORMAT,strtotime($reportDate)));
				    return $reportDate >= $orderDate ? '1' : '0';
			}else{
				    return false;
			}
	    }

	    /****
	    ***** Check quality standard of report
	    *****/
	    public function getStandardQualityStampOrNot($orderDetail){
			$greenStandardQualityCustomers =  array('740','750');
			$order_id = !empty($orderDetail->order_id) ? $orderDetail->order_id :'0';
			if(!empty($orderDetail->order_report_id) && $orderDetail->customer_type =='1' && $orderDetail->product_category_id == '2'){
				    $reportDetail =  DB::table('order_report_details')
						->join('order_report_note_remark_default','order_report_note_remark_default.remark_name','=','order_report_details.remark_value')
						->whereIn('order_report_note_remark_default.is_display_stamp', [1, 2])
						->whereColumn('order_report_note_remark_default.remark_name','order_report_details.remark_value')
						->where('order_report_details.report_id','=',$order_id)
						->where('order_report_note_remark_default.product_category_id','=',$orderDetail->product_category_id)
						->select('order_report_note_remark_default.is_display_stamp')
						->first();
				    if(!empty($reportDetail)){
					 $reportDetail->stampType =  in_array($orderDetail->customer_id,$greenStandardQualityCustomers) ?  true :false;	
				    }
				    return $reportDetail;
			}else{
				    return false;
			}
	    }
	    
	    /*************************
	    *Saving Microbilogical Name other than Pharma Department
	    *Date : 02-05-2018
	    *Created By:Praveen Singh
	    *************************/
	    public function updateMicroBiologicalName($postedData,$productCategoryId){		
			if(!empty($productCategoryId) && $productCategoryId != '2'){
				    $orderData 			 = DB::table('order_master')->select('order_master.order_id','order_master.division_id')->where('order_master.order_id','=',$postedData['report_id'])->first();
				    $orderReportDetail           = DB::table('order_report_details')->where('order_report_details.report_id','=',$postedData['report_id'])->whereNull('order_report_details.report_microbiological_name')->first();
				    $hasMicrobiologicalEquipment = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id','22')->where('order_parameters_detail.order_id','=',$postedData['report_id'])->first();
				    if(!empty($orderReportDetail) && !empty($hasMicrobiologicalEquipment)){
						$microbiologistData = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id','15')->where('users.division_id',$orderData->division_id)->first();
						if(!empty($microbiologistData->name)){
							    $reportMicrobiologicalSign = strtolower(preg_replace('/[_]+/','_',preg_replace("/[^a-zA-Z]/", "_", $microbiologistData->name)).'.png');
							    return DB::table('order_report_details')->where('order_report_details.report_id',$postedData['report_id'])->update(['order_report_details.report_microbiological_name' => $microbiologistData->name, 'order_report_details.report_microbiological_sign' => $reportMicrobiologicalSign]);    
						}else{
							return false;    
						}
				    }    
			}			
			return true;
	    }
	    
	    /*************************
	    *Saving Microbilogical Name other than Pharma Department
	    *Date : 02-05-2018
	    *Created By:Praveen Singh
	    *************************/
	    public function updateSaveTestReportHeaderFooterContent($reportId){
			
			global $order,$models;
			
			if(!empty($reportId)){
				    $orderData         = DB::table('order_master')->select('order_master.order_id','order_master.division_id','order_master.product_category_id')->where('order_master.order_id','=',$reportId)->first();
				    $orderReportHeader = DB::table('order_report_details')->where('order_report_details.report_id','=',$reportId)->whereNull('order_report_details.header_content')->first();
				    $orderReportFooter = DB::table('order_report_details')->where('order_report_details.report_id','=',$reportId)->whereNull('order_report_details.footer_content')->first();
				    if(!empty($orderReportHeader) && !empty($orderReportFooter)){
						list($header_content,$footer_content) = $order->getDynamicHeaderFooterTemplate('1',$orderData->division_id,$orderData->product_category_id);
						if($header_content && $footer_content){
							    return DB::table('order_report_details')->where('order_report_details.report_id',$reportId)->update(['order_report_details.header_content' => $header_content, 'order_report_details.footer_content' => $footer_content]);    
						}else{
							    return false;	    
						}						
				    }else{
						return true;	
				    }				    
			}			
			return false;
	    }
	    
	    /*************************
	    *Updating Report Reviewing Date
	    *Created on:12-June-2018
	    *Created By:Praveen Singh
	    *************************/
	    function updateReportReviewingDate($formData,$reviewingDate){
			
			global $order,$report,$models;
			
			if(!empty($formData['report_id']) && !empty($reviewingDate)){
				    $orderData 	= DB::table('order_master')->where('order_master.order_id','=',$formData['report_id'])->first();
				    $reportData = DB::table('order_report_details')->where('order_report_details.report_id','=',$formData['report_id'])->first();
				    if(!empty($orderData->order_no) && !empty($reportData->report_no)){
						//CASE 1:Review Date Updated if Order is not amended
						//CASE 2:Review Date Updated if Order is amended and checkbox is checked
						if(!empty($order->isBookingOrderAmendedOrNot($formData['report_id']))){
							    $flag = !empty($formData['is_amended_no']) ? '1' : '0'; 
						}else{
							    $flag = '1';
						}						
						if($flag){
							    return DB::table('order_report_details')->where('order_report_details.report_id',$formData['report_id'])->update(['order_report_details.reviewing_date' => $reviewingDate]);
						}					
				    }
			}
			return false;
	    }
	    
	    /*************************
	    *Updating Report Finalizing Date Date
	    *Created on:12-June-2018
	    *Created By:Praveen Singh
	    *************************/
	    function updateReportFinalizingDate($formData,$finalizingDate){
			
			global $order,$report,$models;
			
			if(!empty($formData['order_id']) && !empty($finalizingDate)){
				    $orderData 	= DB::table('order_master')->where('order_master.order_id','=',$formData['order_id'])->first();
				    $reportData = DB::table('order_report_details')->where('order_report_details.report_id','=',$formData['order_id'])->first();
				    if(!empty($orderData->order_no) && !empty($reportData->report_no)){
						//CASE 1:Finalizing Date Updated if Order is not amended
						//CASE 2:Finalizing Date Updated if Order is amended with prefix 'A'
						if(!empty($order->isBookingOrderAmendedOrNot($formData['order_id']))){
							    $flag = !empty($reportData->is_amended_no) ? '1' : '0'; 
						}else{
							    $flag = '1';
						}
						if($flag){
							    return DB::table('order_report_details')->where('order_report_details.report_id',$formData['order_id'])->update(['order_report_details.finalizing_date' => $finalizingDate]);
						}					
				    }
			}
			return false;
	    }
	    
	    /*************************
	    *Updating Report Approving Date Date
	    *Created on:12-June-2018
	    *Created By:Praveen Singh
	    *************************/
	    function updateReportApprovingDate($reportId,$approvingDate){
			
			global $order,$report,$models;
			
			if(!empty($reportId) && !empty($approvingDate)){
				    $orderData 	= DB::table('order_master')->where('order_master.order_id','=',$reportId)->first();
				    $reportData = DB::table('order_report_details')->where('order_report_details.report_id','=',$reportId)->first();
				    if(!empty($orderData->order_no) && !empty($reportData->report_no)){
						//CASE 1:Approving Date Updated if Order is not amended
						//CASE 2:Approving Date Updated if Order is amended with prefix 'A'
						if(!empty($order->isBookingOrderAmendedOrNot($reportId))){
							    $flag = !empty($reportData->is_amended_no) ? '1' : '0'; 
						}else{
							    $flag = '1';
						}						
						if($flag){
							    return DB::table('order_report_details')->where('order_report_details.report_id',$reportId)->update(['order_report_details.approving_date' => $approvingDate]);
						}					
				    }
			}
			return false;
	    }

	    /*************************
	    *quality Stamp On Web View
	    *Created on:25-June-2018
	    *Created By:
	    *************************/
	    function qualityStampOnWebView($orderList){
			$checkReportQuality = $this->getStandardQualityStampOrNot($orderList);
			if(!empty($checkReportQuality)){
			    foreach($checkReportQuality as $key=>$value){
				 $orderList->$key = $value;
			    }	
			}
			return $orderList;
	    }
	    
	     /*************************
	    *get Orde Equipment Incharge Detail
	    *Created on:06-July-2018
	    *Created By:
	    *************************/	    
	    public function getOrderEquipmentInchargeDetail($orderId){
			
			$userData = $formData = array();
			
			$equipmentsArr =  DB::table('order_parameters_detail')
				    ->where('order_id',$orderId)
				    ->groupBy('order_parameters_detail.equipment_type_id')
				    ->pluck('order_parameters_detail.equipment_type_id');
				    
			if(!empty($equipmentsArr)){
				    foreach($equipmentsArr as $equipKey =>$equipValue){
						$userEqipDetail = DB::table('users_equipment_detail')
								->join('users','users.id','=','users_equipment_detail.user_id')
								->join('roles','roles.id','=','users.role_id')
								->where('users_equipment_detail.equipment_type_id','=',$equipValue)
								->where('roles.id','=',7)
								->select('users.name','users.id','users_equipment_detail.equipment_type_id')
								->get();
						if(!empty($userEqipDetail)){
							    foreach($userEqipDetail as $userKey =>$value){
									$userData[$value->id][] = $value->equipment_type_id;
							    }    
						}
				    }	   
			}
			return !empty($userData) ? $userData : false;
	    }
	    
	    /*--
	     *--Reset order incharge detail
	     *--
	     *--
	     */
	    public function resetOrderInchargeDetail($order_id){
			
			global $order,$models;
			
			$dataSave = $inchargeInsertedAllArr = $oidArrayKeyIds = array();
			
			$orderData = DB::table('order_process_log')->where('order_process_log.opl_order_id',$order_id)->where('order_process_log.opl_current_stage','1')->where('order_process_log.opl_amend_status','=','0')->first();
			$errorParameterIdsArr = !empty($orderData->error_parameter_ids)? array_values(explode(',',$orderData->error_parameter_ids)): array();
			
			$backedEquipmentsArr =  DB::table('order_parameters_detail')
				    ->where('order_id',$order_id)
				    ->whereIn('analysis_id',$errorParameterIdsArr)
				    ->whereNotNull('order_parameters_detail.equipment_type_id')
				    ->groupBy('order_parameters_detail.equipment_type_id')
				    ->pluck('order_parameters_detail.equipment_type_id')
				    ->all();
				    
			$inchargeInsertedRawArr = DB::table('order_incharge_dtl')->where('order_id','=',$order_id)->get();
			if(!empty($inchargeInsertedRawArr)){
				    foreach($inchargeInsertedRawArr as $values){
						$inchargeInsertedAllArr[$values->oid_id] = array_values(explode(',',$values->oid_equipment_ids));
				    }				    
			}			
			if(!empty($backedEquipmentsArr) && !empty($inchargeInsertedAllArr)){
				    foreach($backedEquipmentsArr as $backEquipmentId){
						foreach($inchargeInsertedAllArr as $oidKey => $inchargeInsertedArr){
							    if(!empty($inchargeInsertedArr) && in_array($backEquipmentId,$inchargeInsertedArr)){
									$oidArrayKeyIds[$oidKey] = $oidKey;
							    }
						}						
				    }
			}
			
			//echo '<pre>';print_r($backedEquipmentsArr);
			//echo '<pre>';print_r($inchargeInsertedAllArr);
			//echo '<pre>';print_r($oidArrayKeyIds);
			
			if(!empty($oidArrayKeyIds) && !empty($order_id)){
				    $dataSave = array('order_incharge_dtl.oid_status' => '0','order_incharge_dtl.oid_confirm_date' => NULL,'order_incharge_dtl.oid_confirm_by' => NULL);
				    return DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id','=',$order_id)->whereIn('order_incharge_dtl.oid_id',$oidArrayKeyIds)->update($dataSave);	    
			}
	    }
	    
	    /***
	    ***
	    ***05-07-2018
	    ***
	    ******/
	    public function updateOrderSectionInchargeDetail($orderId){
			
			global $order,$models,$report;
			
			$error    = '0';
			$message  = config('messages.message.error');
			$flag     = '0';
			$formData = $equipmentIds = array();
			$inserted = '';
			$getEquipmentAndOrderIds = $this->getOrderEquipmentInchargeDetail($orderId);
			
			if(!empty($getEquipmentAndOrderIds)){				
				    $alreadyExist = DB::table('order_incharge_dtl')->where('order_id',$orderId)->pluck('oid_employee_id')->all();				    
				    if(!empty($alreadyExist)){
						
						$combinedArr 	= array_unique(array_merge(array_keys($getEquipmentAndOrderIds),$alreadyExist)); // Combine employees
						$commonEmpArr 	= array_intersect($combinedArr,$alreadyExist); // comman employees						
						$newEmpArr 	= array_diff($combinedArr,$alreadyExist);// new employees 						
						$removedEmpArr 	= array_diff($combinedArr,array_keys($getEquipmentAndOrderIds));// removed employees
						
						//echo '$combinedArr<pre>';print_r($combinedArr);
						//echo '$commanEmpArr<pre>';print_r($commonEmpArr);
						//echo '$newEmpArr<pre>';print_r($newEmpArr);
						//echo '$removedEmpArr<pre>';print_r($removedEmpArr);
						
						if($removedEmpArr){
							    DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id',$orderId)->whereIn('order_incharge_dtl.oid_employee_id',$removedEmpValue)->update(['order_incharge_dtl.oid_status' => '2']);  
						}
						if($commonEmpArr){
							    DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id',$orderId)->whereIn('order_incharge_dtl.oid_employee_id',$commonEmpArr)->update(['order_incharge_dtl.created_at' => CURRENTDATETIME]);
						}
						if($newEmpArr){							
							    foreach($newEmpArr as $newEmpValue){
									$formData			= array();
									$formData['order_id']		= $orderId;
									$formData['oid_employee_id'] 	= $newEmpValue;
									$formData['oid_equipment_ids']	= !empty($getEquipmentAndOrderIds[$newEmpValue]) ? implode(',',$getEquipmentAndOrderIds[$newEmpValue]) : NULL;
									$formData['oid_status']		= '0';
									$inserted = DB::table('order_incharge_dtl')->insert($formData);								    
							    }
							
						}
				    }else{
						foreach($getEquipmentAndOrderIds as $oidEmployeeIdKey => $oidEquipmentIdArr){
							    $formData			   = array();
							    $formData['order_id']	   = $orderId;
							    $formData['oid_employee_id']   = $oidEmployeeIdKey;
							    $formData['oid_equipment_ids'] = !empty($oidEquipmentIdArr) ? implode(',',$oidEquipmentIdArr) : NULL;
							    $formData['oid_status']	   = '0';
							    $inserted = DB::table('order_incharge_dtl')->insert($formData);
						}
				    }
				    if($inserted){
						return true;	
				    }
			}
	    }
    
	     /*************************
	    *get Order Equipment Incharge Detail
	    *Created on:06-July-2018
	    *Created By:
	    *************************/
	    public function updateSectionInchargeStatus($order_id,$user_id){
			$dataSave = array();
			if(!empty($order_id) && !empty($user_id)){
				    $orderStage = DB::table('order_master')->where('status','=','4')->where('order_id','=',$order_id)->first();
				    if(!empty($orderStage)){
						$dataSave = array('order_incharge_dtl.oid_status' => '1','order_incharge_dtl.oid_confirm_by' => $user_id,'order_incharge_dtl.oid_confirm_date' => CURRENTDATETIME);
						$updatedObj = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id','=',$order_id)->where('order_incharge_dtl.oid_status','=','0');
						if(defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE){
							    $updatedObj->where('order_incharge_dtl.oid_employee_id','=',$user_id);  
						}
						$updatedObj->update($dataSave);						
						$orderConfirmStatus = defined('IS_ADMIN') && IS_ADMIN ? 0 : DB::table('order_incharge_dtl')->where('order_id','=',$order_id)->where('oid_status','=',0)->count();
						
						//1 if atleast any one Section Incharge doesnot confirm the report
						//2 if all Section Incharge confirm the report						
						return $orderConfirmStatus ? '1' : '2';
				    }else{
						return false;	
				    }
			}
	    }
	   
	    /**
	     *Update last confirm date of the order by section incharge
	     * in order reports table
	     ***/
	    public function lastUpdateDateBySectionIncharge($order_id){
			return DB::table('order_incharge_dtl')->where('order_id','=',$order_id)->select('oid_confirm_date')->orderBy('oid_confirm_date','DESC')->first();
	    }
	    
	    /**
	     *Update last confirm date of the order by section incharge
	     * in order reports table
	     ***/
	    public function updateReportInchargeReviewingDate($order_id){
			$lastUpdatedDate = $this->lastUpdateDateBySectionIncharge($order_id);
			if(!empty($lastUpdatedDate)){
				    return DB::table('order_master')->where('order_id','=',$order_id)->update(['incharge_reviewing_date'=>$lastUpdatedDate->oid_confirm_date]);
			}else{
				    return false;
			}
	    }
	    
	    
	    /***
	    *** check current status of incharge order
	    *** if 1 do not show coonfirm button again
	    *** if 0 show confirm button 
	    ***/
	    public function currentInchargeOrderStatus($order_id,$user_id){
			$confirmStatus = '';
			$inchargeStatusObj = DB::table('order_incharge_dtl')
								->where('order_incharge_dtl.order_id','=',$order_id);
								if(defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE){
									$inchargeStatusObj->where('order_incharge_dtl.oid_employee_id','=',$user_id);
								}
							$inchargeStatus = $inchargeStatusObj->pluck('order_incharge_dtl.oid_status');
							if(!empty($inchargeStatus)){
								foreach($inchargeStatus as $status){
									if($status =='1'){
										$confirmStatus=$status;
									}else{
										$confirmStatus=$status;
									}
								}
							}
			return $confirmStatus;
		}
}

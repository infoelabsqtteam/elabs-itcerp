<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ScheduledMisReportDtl extends Model
{
    protected $table = 'scheduled_mis_report_dtls';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
    
    /****************************************
    * Getting Single Row MIS Scheduled Report
    * Created By : Praveen Singh
    * created On : 21-June-2019
    *****************************************/
    public function getRow($id){
        
        $rowData = DB::table('scheduled_mis_report_dtls')
                    ->join('divisions','divisions.division_id','scheduled_mis_report_dtls.smrd_division_id')
                    ->join('product_categories','product_categories.p_category_id','scheduled_mis_report_dtls.smrd_product_category_id')
                    ->join('mis_report_default_types','mis_report_default_types.mis_report_id','scheduled_mis_report_dtls.smrd_mis_report_id')
                    ->select('scheduled_mis_report_dtls.*','divisions.division_name as smrd_division_name','product_categories.p_category_name as smrd_product_category_name','mis_report_default_types.mis_report_name as smrd_mis_report_name')
                    ->where('scheduled_mis_report_dtls.smrd_id',$id)
                    ->first();
                    
        if(!empty($rowData)){
            $rowData->smrd_scheduled_edit_title = $rowData->smrd_division_name.'|'.$rowData->smrd_product_category_name.'|'.$rowData->smrd_mis_report_name;
            $rowData->smrd_to_email_address = DB::table('scheduled_mis_report_dtls')
                ->join('divisions','divisions.division_id','scheduled_mis_report_dtls.smrd_division_id')
                ->join('product_categories','product_categories.p_category_id','scheduled_mis_report_dtls.smrd_product_category_id')
                ->join('mis_report_default_types','mis_report_default_types.mis_report_id','scheduled_mis_report_dtls.smrd_mis_report_id')
                ->where('scheduled_mis_report_dtls.smrd_division_id',$rowData->smrd_division_id)
                ->where('scheduled_mis_report_dtls.smrd_product_category_id',$rowData->smrd_product_category_id)
                ->where('scheduled_mis_report_dtls.smrd_mis_report_id',$rowData->smrd_mis_report_id)
                ->whereNotNull('scheduled_mis_report_dtls.smrd_to_email_address')
                ->pluck('scheduled_mis_report_dtls.smrd_to_email_address')
                ->all();
            $rowData->smrd_from_email_address = DB::table('scheduled_mis_report_dtls')
                ->join('divisions','divisions.division_id','scheduled_mis_report_dtls.smrd_division_id')
                ->join('product_categories','product_categories.p_category_id','scheduled_mis_report_dtls.smrd_product_category_id')
                ->join('mis_report_default_types','mis_report_default_types.mis_report_id','scheduled_mis_report_dtls.smrd_mis_report_id')
                ->where('scheduled_mis_report_dtls.smrd_division_id',$rowData->smrd_division_id)
                ->where('scheduled_mis_report_dtls.smrd_product_category_id',$rowData->smrd_product_category_id)
                ->where('scheduled_mis_report_dtls.smrd_mis_report_id',$rowData->smrd_mis_report_id)
                ->whereNotNull('scheduled_mis_report_dtls.smrd_from_email_address')
                ->pluck('scheduled_mis_report_dtls.smrd_from_email_address')
                ->all();
        }
        
        return $rowData;
    }
    
    /****************************************
    * Getting Single Row MIS Scheduled Report
    * Created By : Praveen Singh
    * created On : 21-June-2019
    *****************************************/
    public function getSchMisPriSecEmailAddresses($values){
        
        $returnData = array();
        
        $returnData['smrd_to_email_address'] = DB::table('scheduled_mis_report_dtls')
            ->join('divisions','divisions.division_id','scheduled_mis_report_dtls.smrd_division_id')
            ->join('product_categories','product_categories.p_category_id','scheduled_mis_report_dtls.smrd_product_category_id')
            ->join('mis_report_default_types','mis_report_default_types.mis_report_id','scheduled_mis_report_dtls.smrd_mis_report_id')
            ->where('scheduled_mis_report_dtls.smrd_division_id',$values->smrd_division_id)
            ->where('scheduled_mis_report_dtls.smrd_product_category_id',$values->smrd_product_category_id)
            ->where('scheduled_mis_report_dtls.smrd_mis_report_id',$values->smrd_mis_report_id)
            ->whereNotNull('scheduled_mis_report_dtls.smrd_to_email_address')
            ->pluck('scheduled_mis_report_dtls.smrd_to_email_address')
            ->all();
        $returnData['smrd_from_email_address'] = DB::table('scheduled_mis_report_dtls')
            ->join('divisions','divisions.division_id','scheduled_mis_report_dtls.smrd_division_id')
            ->join('product_categories','product_categories.p_category_id','scheduled_mis_report_dtls.smrd_product_category_id')
            ->join('mis_report_default_types','mis_report_default_types.mis_report_id','scheduled_mis_report_dtls.smrd_mis_report_id')
            ->where('scheduled_mis_report_dtls.smrd_division_id',$values->smrd_division_id)
            ->where('scheduled_mis_report_dtls.smrd_product_category_id',$values->smrd_product_category_id)
            ->where('scheduled_mis_report_dtls.smrd_mis_report_id',$values->smrd_mis_report_id)
            ->whereNotNull('scheduled_mis_report_dtls.smrd_from_email_address')
            ->pluck('scheduled_mis_report_dtls.smrd_from_email_address')
            ->all();
        
        return $returnData;
    }
    
    /****************************************
    * Formating MIS Scheduled Report
    * Created By : Praveen Singh
    * created On : 20-June-2019
    *****************************************/
    public function formatPriSecScheduledEmailData($formData){
        
        global $models;
        
        $returnPrimaryData = $returnSecondayData = array();
        
        if(!empty($formData['smrd_to_email_address'])){
            $smrdToEmailAddressOut = $models->validateMailEmailIds(array_unique($formData['smrd_to_email_address']));
            foreach($smrdToEmailAddressOut as $key => $value){
                $returnPrimaryData[$key]['smrd_division_id']          = $formData['smrd_division_id'];
                $returnPrimaryData[$key]['smrd_product_category_id']  = $formData['smrd_product_category_id'];
                $returnPrimaryData[$key]['smrd_mis_report_id']        = $formData['smrd_mis_report_id'];
                $returnPrimaryData[$key]['smrd_division_id']          = $formData['smrd_division_id'];
                $returnPrimaryData[$key]['smrd_to_email_address']     = $value;
                $returnPrimaryData[$key]['smrd_created_by']           = USERID;
            }
        }
        if(!empty($formData['smrd_from_email_address'])){
            $smrdFromEmailAddressOut = $models->validateMailEmailIds(array_unique($formData['smrd_from_email_address']));
            foreach($smrdFromEmailAddressOut as $key => $value){
                $returnSecondayData[$key]['smrd_division_id']          = $formData['smrd_division_id'];
                $returnSecondayData[$key]['smrd_product_category_id']  = $formData['smrd_product_category_id'];
                $returnSecondayData[$key]['smrd_mis_report_id']        = $formData['smrd_mis_report_id'];
                $returnSecondayData[$key]['smrd_division_id']          = $formData['smrd_division_id'];
                $returnSecondayData[$key]['smrd_from_email_address']   = $value;
                $returnSecondayData[$key]['smrd_created_by']           = USERID;
            }
        }
        return array(array_values($returnPrimaryData),array_values($returnSecondayData));
    }
    
     /**
    * Formating MIS Scheduled Report
    * Created By : Praveen Singh
    * created On : 20-June-2019
    */
    public function validateMisReportExistence($formData){
        
        global $models;
        
        $smrd_division_id         = !empty($formData['smrd_division_id']) ? $formData['smrd_division_id'] : '0';
        $smrd_product_category_id = !empty($formData['smrd_product_category_id']) ? $formData['smrd_product_category_id'] : '0';
        $smrd_mis_report_id       = !empty($formData['smrd_mis_report_id']) ? $formData['smrd_mis_report_id'] : '0';
        
        return DB::table('scheduled_mis_report_dtls')
            ->where('scheduled_mis_report_dtls.smrd_division_id',$smrd_division_id)
            ->where('scheduled_mis_report_dtls.smrd_product_category_id',$smrd_product_category_id)
            ->where('scheduled_mis_report_dtls.smrd_mis_report_id',$smrd_mis_report_id)
            ->count();
    }
    
    /**
    * generate MIS Report::TAT Report Detail
    *
    * @return \Illuminate\Http\Response
    */
    public function generate_scheduled_tat_report($values,$currentDate,$type){
    
        global $models,$order,$report,$invoice,$schMisRepDtl,$misReport;
    
        $response = array();

        $responseDataObj = DB::table('order_master')
                    ->join('divisions','divisions.division_id','order_master.division_id')
                    ->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
                    ->join('departments','departments.department_id','department_product_categories_link.department_id')
                    ->join('customer_master','customer_master.customer_id','order_master.customer_id')
                    ->join('customer_billing_types','customer_billing_types.billing_type_id','order_master.billing_type_id')
                    ->join('users as sales','sales.id','order_master.sale_executive')
                    ->join('city_db','city_db.city_id','customer_master.customer_city')
                    ->join('state_db','state_db.state_id','customer_master.customer_state')
                    ->join('product_master_alias','product_master_alias.c_product_id','order_master.sample_description_id')
                    ->join('order_sample_priority','order_sample_priority.sample_priority_id','order_master.sample_priority_id')
                    ->join('samples','samples.sample_id','order_master.sample_id');

        if(!empty($values->is_display_pcd)){
            $responseDataObj->select('divisions.division_name as branch','departments.department_name as department','order_master.order_id','customer_master.customer_name as party_name','city_db.city_name as place','order_master.brand_type as brand','customer_billing_types.billing_type','product_master_alias.c_product_name as sample_name','order_master.order_no as sample_reg_code','order_master.batch_no','order_master.order_date as sample_reg_date','order_master.order_date as sample_reg_time','order_master.booking_date as sample_current_reg_date','order_master.booking_date as sample_current_reg_time','order_sample_priority.sample_priority_name as sample_priority','order_master.expected_due_date','order_master.status');
        }else{
            $responseDataObj->select('divisions.division_name as branch','departments.department_name as department','order_master.order_id','customer_master.customer_name as party_name','city_db.city_name as place','order_master.brand_type as brand','customer_billing_types.billing_type','product_master_alias.c_product_name as sample_name','order_master.order_no as sample_reg_code','order_master.batch_no','order_master.order_date as sample_reg_date','order_master.order_date as sample_reg_time','order_sample_priority.sample_priority_name as sample_priority','order_master.expected_due_date','order_master.status');
        }
        if(!empty($values->smrd_division_id)){
            $responseDataObj->where('order_master.division_id',$values->smrd_division_id);
        }
        if(!empty($values->smrd_product_category_id)){
            $responseDataObj->where('order_master.product_category_id',$values->smrd_product_category_id);
        }
        
        //Last Four Month TAT Department and Branch Wise
        if($type == '1'){
            $fileName = 'tat_reports('.date('d-m-Y').')';
            $responseDataObj->where(DB::raw("DATE(order_master.booking_date)"), '>=', $currentDate);
        }        
        //Due Today-reports having expected due date of today but not approved. = 25-06-2019
        if($type == '2'){
            $fileName = 'due_today_reports('.date('d-m-Y',strtotime($currentDate)).')';
            $responseDataObj->where(DB::raw("DATE(order_master.expected_due_date)"),'=',$currentDate);
            $responseDataObj->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id');
            $responseDataObj->leftJoin('order_mail_dtl', function($join){
                $join->on('order_mail_dtl.order_id','=','order_master.order_id');
                $join->where('order_mail_dtl.mail_content_type','3');
                $join->whereNotNull('order_mail_dtl.order_id');
            });
            $responseDataObj->whereNull('order_report_details.approving_date');
            $responseDataObj->whereNull('order_mail_dtl.mail_date');
        }        
        //Over Due - reports having expected due date till yesterday but not approved. <= 24-06-2019
        if($type == '3'){
            $fileName = 'over_due_reports('.date('d-m-Y',strtotime($currentDate)).')';
            $responseDataObj->where(DB::raw("DATE(order_master.expected_due_date)"),'<=',$currentDate);
            $responseDataObj->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id');
            $responseDataObj->leftJoin('order_mail_dtl', function($join){
                $join->on('order_mail_dtl.order_id','=','order_master.order_id');
                $join->where('order_mail_dtl.mail_content_type','3');
                $join->whereNotNull('order_mail_dtl.order_id');
            });
            $responseDataObj->whereNull('order_report_details.approving_date');
            $responseDataObj->whereNull('order_mail_dtl.mail_date');
        }        
        //Advance - reports having expected due date of tomorrow but not approved. = 26-06-2019
        if($type == '4'){
            $fileName = 'advance_reports('.date('d-m-Y',strtotime($currentDate)).')';
            $responseDataObj->where(DB::raw("DATE(order_master.expected_due_date)"),'=',$currentDate);
            $responseDataObj->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id');
            $responseDataObj->leftJoin('order_mail_dtl', function($join){
                $join->on('order_mail_dtl.order_id','=','order_master.order_id');
                $join->where('order_mail_dtl.mail_content_type','3');
                $join->whereNotNull('order_mail_dtl.order_id');
            });
            $responseDataObj->whereNull('order_report_details.approving_date');
            $responseDataObj->whereNull('order_mail_dtl.mail_date');
        }        
        //Approved today - reports having approved date of today. = 24-06-2019
        if($type == '5'){
            $fileName = 'approved_reports('.date('d-m-Y',strtotime($currentDate)).')';
            $responseDataObj->join('order_report_details','order_report_details.report_id','order_master.order_id');
            $responseDataObj->join('order_mail_dtl', function($join){
                $join->on('order_mail_dtl.order_id','=','order_master.order_id');
                $join->where('order_mail_dtl.mail_content_type','3');
                $join->where('order_mail_dtl.mail_active_type','1');
                $join->whereNotNull('order_mail_dtl.order_id');
            });
            $responseDataObj->where(DB::raw("DATE(order_mail_dtl.mail_date)"),'=',$currentDate);
            $responseDataObj->whereNotNull('order_report_details.approving_date');
        }        
        $responseDataObj->whereNotIn('order_master.status',array('10','12'));
        $responseDataObj->orderBy('order_master.order_date','DESC');
        $responseData = $responseDataObj->get();

        if(!empty($responseData)){
            foreach($responseData as $values){            
                $isCancelledStatus 	    = !empty($order->isOrderBookingCancelled($values->order_id)) ? true : false;
                $isOrderSampleType 	    = !empty($order->hasOrderInterLabOrCompensatory($values->order_id)) ? true : false;
                $values->sample_reg_date    = date(DATEFORMATEXCEL,strtotime($values->sample_reg_date));
                $values->sample_reg_time    = date('h:i A',strtotime($values->sample_reg_time));
                if(!empty($values->is_display_pcd)){
                    $values->sample_current_reg_date = date(DATEFORMATEXCEL,strtotime($values->sample_current_reg_date));
                    $values->sample_current_reg_time = date('h:i A',strtotime($values->sample_current_reg_time));
                }
                $values->expected_due_date  = date(DATEFORMATEXCEL,strtotime($values->expected_due_date));
                $values->scheduled_status   = $isCancelledStatus ? '' : $misReport->checkScheduledStatusOfOrder($values->order_id);
                
                //Getting Order process Detail
                $misReport->getOrderProcessStageDetail($values,$isCancelledStatus,$isOrderSampleType);
            }
        }
        
        //removing unrequired coloums
        $responseData 			= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();
        $responseData 			= $models->unsetFormDataVariablesArray($responseData,array('order_id','status'));
        $response['file_name']  	= !empty($fileName) ? trim($fileName) : 'reports';
        $response['heading'] 		= !empty($responseData) && !empty($fileName) ? strtoupper(str_replace('_',' ',$fileName)).'('.count($responseData).')' : '';
        $response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
        $response['tableBody'] 		= !empty($responseData) ? $responseData : array();       
    
        //echo'<pre>'; print_r($response);die;
        return $response;
    }
    
    /**
    * generate MIS Report::TAT Report Detail
    *
    * @return \Illuminate\Http\Response
    */
    public function get_main_content_data($values,$currentDate,$todayDueDate,$advanceDate,$dueTodayReportFileData,$overDueReportFileData,$advanceReportFileData){
    
        global $models,$order,$report,$invoice,$schMisRepDtl,$misReport;
    
        $response = $reportPendencyArray = $reportPendencyDetail = array();
        
        //Column One Data
        $response[date('d-m-Y',strtotime($currentDate))]['Samples Booked'] = DB::table('order_master')
                                                    ->where('order_master.division_id',$values->smrd_division_id)
                                                    ->where('order_master.product_category_id',$values->smrd_product_category_id)
                                                    ->where(DB::raw("DATE(order_master.booking_date)"),'=',$currentDate)
                                                    ->whereNotIn('order_master.status',array('10','12'))
                                                    ->count();
	if(!empty($values->smrd_division_id) && $values->smrd_division_id == '2'){
	    $response[date('d-m-Y',strtotime($currentDate))]['Reports Approved'] = DB::table('order_master')
                                                    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
                                                    ->where('order_master.division_id',$values->smrd_division_id)
                                                    ->where('order_master.product_category_id',$values->smrd_product_category_id)
						    ->whereDate('order_report_details.approving_date','=',$currentDate)
                                                    ->whereNotIn('order_master.status',array('10','12'))
                                                    ->whereNotNull('order_report_details.approving_date')
                                                    ->count();
	}else{
	    $response[date('d-m-Y',strtotime($currentDate))]['Reports Approved'] = DB::table('order_master')
                                                    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
                                                    ->join('order_mail_dtl', function($join){
                                                        $join->on('order_mail_dtl.order_id','=','order_master.order_id');
                                                        $join->where('order_mail_dtl.mail_content_type','3');
                                                        $join->where('order_mail_dtl.mail_active_type','1');
                                                        $join->whereNotNull('order_mail_dtl.order_id');
                                                    })
                                                    ->where('order_master.division_id',$values->smrd_division_id)
                                                    ->where('order_master.product_category_id',$values->smrd_product_category_id)
						    ->whereDate('order_mail_dtl.mail_date','=',$currentDate)
                                                    ->whereNotIn('order_master.status',array('10','12'))
                                                    ->whereNotNull('order_report_details.approving_date')
                                                    ->count();
	}
        $response[date('d-m-Y',strtotime($currentDate))]['Sample Hold'] = DB::table('order_master')
                                                    ->where('order_master.division_id',$values->smrd_division_id)
                                                    ->where('order_master.product_category_id',$values->smrd_product_category_id)
                                                    ->where(DB::raw("DATE(order_master.booking_date)"),'<=',$currentDate)
                                                    ->where('order_master.status','=','12')
                                                    ->count();
        //Coloumn Two Data
        $response[date('d-m-Y',strtotime($todayDueDate))]['Today Due('.date('d-m-Y',strtotime($todayDueDate)).')'] = !empty($dueTodayReportFileData['tableBody']) ? count($dueTodayReportFileData['tableBody']) : '0';
        $response[date('d-m-Y',strtotime($todayDueDate))]['Overdue('.date('d-m-Y',strtotime($currentDate)).')']    = !empty($overDueReportFileData['tableBody']) ? count($overDueReportFileData['tableBody']) : '0';
        $response[date('d-m-Y',strtotime($todayDueDate))]['Due for Tomorrow']                                      = !empty($advanceReportFileData['tableBody']) ? count($advanceReportFileData['tableBody']) : '0';
        
        //Coloumn Three Data
        $intrumentWiseReport = array_merge($dueTodayReportFileData['tableBody'],$overDueReportFileData['tableBody']);
        if(!empty($intrumentWiseReport)){
            foreach($intrumentWiseReport as $values){
                $orderNoArray[] = $values['sample_reg_code'];
            }
            $orderIds = DB::table('order_master')->whereIn('order_master.order_no',!empty($orderNoArray) ? array_values($orderNoArray) : array())->pluck('order_master.order_id')->all();
            $pendingEquiplementData = DB::table('order_parameters_detail')
                                    ->join('equipment_type','equipment_type.equipment_id','order_parameters_detail.equipment_type_id')
                                    ->whereIn('order_parameters_detail.order_id',$orderIds)
                                    ->whereNull('order_parameters_detail.test_result')
                                    ->select('order_parameters_detail.analysis_id','order_parameters_detail.order_id','equipment_type.equipment_name')
                                    ->get()
                                    ->toArray();
            if(!empty($pendingEquiplementData)){
                foreach($pendingEquiplementData as $values){
                    $reportPendencyArray[$values->equipment_name][$values->order_id] = $values->order_id;
                }
                foreach($reportPendencyArray as $key => $values){
                    $reportPendencyDetail[$key] = count($values);
                }
            }
        }
        $response['Out of '.count($intrumentWiseReport).'(Today Due + Overdue) Reports, Pendency in Instruments are :'] = !empty($reportPendencyDetail) ? $reportPendencyDetail : array();
        
        //echo'<pre>'; print_r($orderNoArray);die;
        return $response;
    }
    
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Dashboard extends Model
{

    protected $table = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    /**
    * Check Item Code
    *
    * @return \Illuminate\Http\Response
    */
    function getSampleReceiverContent($key,$division_id,$department_ids,$roleDetail){
	
	$returnData = array();
	
	$tatVisibilty = true; //date('G') >= '17' && date('G') <= '20' ? true : false;
	
	//Coloum Data
	$coloumArrayData = array(
	    '0' => 'No of Samples Received',
	    '1' => 'No of Samples booked'
	);
	$departmentData = DB::table('product_categories')->select('product_categories.p_category_id','product_categories.p_category_name')->whereIn('product_categories.p_category_id',$department_ids)->where('product_categories.parent_id','0')->get();
	if(!empty($departmentData)){
	    foreach($coloumArrayData as $coloumKey => $coloumName){
		$totalData = array();
		$returnData['tableBody'][$counter]['department'] = $coloumName;
		foreach($departmentData as $department){
		    if($coloumKey == '0'){
			$countValueObj = DB::table('samples')->where(DB::raw("DATE(samples.sample_current_date)"),'<=',CURRENTDATE);
			if(!empty($division_id))$countValueObj->whereIn('samples.division_id',$division_id);
			if(!empty($department->p_category_id))$countValueObj->where('samples.product_category_id',$department->p_category_id);
			$countValue = $countValueObj->count();
		    }else{
			$countValueObj = DB::table('samples')->where(DB::raw("DATE(samples.sample_booked_date)"),'<=',CURRENTDATE);
			if(!empty($division_id))$countValueObj->whereIn('samples.division_id',$division_id);
			if(!empty($department->p_category_id))$countValueObj->where('samples.product_category_id',$department->p_category_id);
			$countValue = $countValueObj->count();
		    }			    
		    $returnData['tableBody'][$counter][$department->p_category_name] = $countValue;
		    $totalData['tableBody'][$counter][$department->p_category_name]  = $countValue;
		}
		$returnData['tableBody'][$counter]['total'] = !empty($totalData['tableBody'][$counter]) ? array_sum($totalData['tableBody'][$counter]) : '0';
	    }
	}	
	return $returnData;
    }
    
    /**
    * Check Item Code
    *
    * @return \Illuminate\Http\Response
    */
    function getOrderBookerContent($division_id,$department_ids,$roleDetail){
	
	$returnData = array();
	
	$tatVisibilty = true; //date('G') >= '17' && date('G') <= '20' ? true : false;
	
	//Count of Packets pending till date
	//Count of Packets booked today
	//TAT % -( Count of Packets booked today/ Count of Packets pending till date * 100)
	$coloumArrayData = array(
	    '0' => 'Packets pending',
	    '1' => 'Packets booked',
	    '2' => 'TAT %'
	);	
	if(!empty($coloumArrayData)){
	    $counter = '0';
	    foreach($coloumArrayData as $coloumKey => $coloumName){
		if($coloumKey == '0'){		//Count of Packets pending till date		
		    $countValueObj = DB::table('samples')->where(DB::raw("DATE(samples.sample_current_date)"),'<=',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('samples.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('samples.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNotNull('samples.sample_current_date')->whereNull('samples.sample_booked_date')->count();
		}else if($coloumKey == '1'){ 	//Count of Packets booked today
		    $countValueObj = DB::table('samples')->where(DB::raw("DATE(samples.sample_booked_date)"),'=',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('samples.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('samples.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNotNull('samples.sample_booked_date')->count();
		}else if($coloumKey == '2'){	//TAT % -( Count of Packets booked today / Count of Packets pending till date * 100)
		    $countTodayBooked = !empty($returnData['tableBody'][0]['Packets booked']) ? $returnData['tableBody'][0]['Packets booked'] : '0';		//Packed Booked Today
		    $countPendingTillDate = !empty($returnData['tableBody'][0]['Packets pending']) ? $returnData['tableBody'][0]['Packets pending'] : '0';	//Packed Booked Today
		    //TAT %
		    $returnData['tableBody'][$counter][$coloumName] = $tatVisibilty && !empty($countTodayBooked) && !empty($countPendingTillDate) ? round(($countTodayBooked / $countPendingTillDate) * 100 ,2) : '-';
		}
	    }
	}		    
	return $returnData;
    }
    
    /**
    * Check Item Code
    *
    * @return \Illuminate\Http\Response
    */
    function getSchedulerContent($division_id,$department_ids,$roleDetail){
	
	$returnData = array();
	
	$tatVisibilty = true; //date('G') >= '17' && date('G') <= '20' ? true : false;
	
	//Pending for scheduling:  Parameter wise till date
	//Count of parameters scheduled-today
	//TAT % - formula same as above
	$coloumArrayData = array(
	    '0' => 'Pending for scheduling',
	    '1' => 'Parameters scheduled',
	    '2' => 'TAT %'
	);
	if(!empty($coloumArrayData)){
	    $counter = '0';
	    foreach($coloumArrayData as $coloumKey => $coloumName){
		if($coloumKey == '0'){		//Pending for scheduling: Parameter wise till date
		    $countValueObj = DB::table('schedulings')->join('order_master','order_master.order_id','schedulings.order_id');
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $countValueObj->whereNull('schedulings.scheduled_at');
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNotIn('order_master.status',array('10','12'))->count();
		}else if($coloumKey == '1'){ 	//Count of parameters scheduled-today
		    $countValueObj = DB::table('schedulings')->join('order_master','order_master.order_id','schedulings.order_id');
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $countValueObj->whereNotNull('schedulings.scheduled_at');
		    $countValueObj->where(DB::raw("DATE(schedulings.scheduled_at)"),'=',CURRENTDATE);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNotIn('order_master.status',array('10','12'))->count();
		}else if($coloumKey == '2'){	//TAT % - formula same as above
		    $countScheduledToday  = !empty($returnData['tableBody'][0]['Parameters scheduled']) ? $returnData['tableBody'][0]['Parameters scheduled'] : '0'; 		//Parameters scheduled Today
		    $countPendingTillDate = !empty($returnData['tableBody'][0]['Pending for scheduling']) ? $returnData['tableBody'][0]['Pending for scheduling'] : '0';	//Parameters scheduled till Date
		    
		    //TAT %
		    $returnData['tableBody'][$counter][$coloumName] = $tatVisibilty && !empty($countScheduledToday) && !empty($countPendingTillDate) ? round(($countScheduledToday / $countPendingTillDate) * 100 ,2) : '-';
		}
	    }
	}		    
	return $returnData;
    }
    
    /**
    * Check Item Code
    *
    * @return \Illuminate\Http\Response
    */
    function getTesterContent($user_id,$division_id,$department_ids,$roleDetail){
	
	$returnData = array();
	
	$tatVisibilty = true; //date('G') >= '17' && date('G') <= '20' ? true : false;
	
	//Total pendency till date - Today Due and Over due(parameter wise)
	//TAT %(No. of parameters completed within TAT/ No. of parameters allocated) * 100
	$coloumArrayData = array(
	    '0' => 'Total Due',
	    '1' => 'Total Overdue',
	    '2' => 'TAT %',
	);
	
	if(!empty($coloumArrayData)){
	    $counter = '0';
	    foreach($coloumArrayData as $coloumKey => $coloumName){
		if($coloumKey == '0'){		//Total pendency - Today Due(parameter wise) 
		    
		    $countValueObj = DB::table('schedulings')
				    ->join('order_master','schedulings.order_id','order_master.order_id')
				    ->join('order_parameters_detail','order_parameters_detail.order_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->whereNull('schedulings.completed_at')
				    ->where(DB::raw("DATE(order_parameters_detail.dept_due_date)"),'=',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    if(!empty($user_id))$countValueObj->where('schedulings.employee_id',$user_id);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->count();
		    
		}else if($coloumKey == '1'){		//Total pendency - Over due(parameter wise) 
		    
		    $countValueObj = DB::table('schedulings')
				    ->join('order_master','schedulings.order_id','order_master.order_id')
				    ->join('order_parameters_detail','order_parameters_detail.order_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->whereNull('schedulings.completed_at')
				    ->where(DB::raw("DATE(order_parameters_detail.dept_due_date)"),'<',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    if(!empty($user_id))$countValueObj->where('schedulings.employee_id',$user_id);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->count();
		    
		}else if($coloumKey == '2'){ 	//TAT %(No. of parameters completed within TAT/ No. of parameters allocated) * 100
		    
		    //No. of tests Conducted Within TAT
		    $noOfTestsConductedWithinTATObj = DB::table('schedulings')
				    ->join('order_master','schedulings.order_id','order_master.order_id')
				    ->join('order_parameters_detail','order_parameters_detail.order_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->whereNotNull('schedulings.completed_at')
				    ->where(DB::raw("DATE(schedulings.completed_at)"),'<=',DB::raw("DATE(order_parameters_detail.dept_due_date)"))
				    ->where(DB::raw("DATE(schedulings.completed_at)"),'<=',CURRENTDATE);
		    if(!empty($division_id))$noOfTestsConductedWithinTATObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$noOfTestsConductedWithinTATObj->whereIn('order_master.product_category_id',$department_ids);
		    if(!empty($user_id))$noOfTestsConductedWithinTATObj->where('schedulings.employee_id',$user_id);
		    $noOfTestsConductedWithinTAT = $noOfTestsConductedWithinTATObj->count();
		    
		    //No. of parameters allocated till date
		    $noOfTestsAllocatedObj = DB::table('schedulings')
				    ->join('order_master','schedulings.order_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->whereNotNull('schedulings.scheduled_at')
				    ->where(DB::raw("DATE(schedulings.scheduled_at)"),'<=',CURRENTDATE);
		    if(!empty($division_id))$noOfTestsAllocatedObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$noOfTestsAllocatedObj->whereIn('order_master.product_category_id',$department_ids);
		    if(!empty($user_id))$noOfTestsAllocatedObj->where('schedulings.employee_id',$user_id);
		    $noOfTestsAllocated = $noOfTestsAllocatedObj->count();
		    
		    //TAT %
		    $returnData['tableBody'][$counter][$coloumName] = $tatVisibilty && !empty($noOfTestsConductedWithinTAT) && !empty($noOfTestsAllocated) ? round(($noOfTestsConductedWithinTAT / $noOfTestsAllocated) * 100,2) : '-';
		}
	    }
	}
	
	return $returnData;
    }
    
    /**
    * Check Item Code
    *
    * @return \Illuminate\Http\Response
    */
    function getReviewerContent($division_id,$department_ids,$roleDetail){
	
	$returnData = array();
	
	$tatVisibilty = true; //date('G') >= '17' && date('G') <= '20' ? true : false;
	
	//Total pendency for reviewing till date-Today Due(count) & Overdue(count)(sample wise)
	//TAT % - total no. of reports reviewed in current/ no. of reports pending for reviewing in till date

	$coloumArrayData = array(
	    '0' => 'Total Due',
	    '1' => 'Total Overdue',
	    '2' => 'TAT %',
	);
	
	if(!empty($coloumArrayData)){
	    $counter = '0';
	    foreach($coloumArrayData as $coloumKey => $coloumName){
		if($coloumKey == '0'){		//Total Due
		    $countValueObj = DB::table('order_master')
				    ->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_master.test_completion_date)"),'=',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNull('order_report_details.reviewing_date')->count();
		}else if($coloumKey == '1'){ 	//Total Overdue
		    $countValueObj = DB::table('order_master')
				    ->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_master.test_completion_date)"),'<',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNull('order_report_details.reviewing_date')->count();
		}else if($coloumKey == '2'){ 	//TAT % - total no. of reports reviewed in current/ no. of reports pending for reviewing in till date
		    
		    //total no. of reports reviewed in current
		    $reportsReviewedCurrentObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_report_details.reviewing_date)"),'=',CURRENTDATE);
		    if(!empty($division_id))$reportsReviewedCurrentObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$reportsReviewedCurrentObj->whereIn('order_master.product_category_id',$department_ids);
		    $reportsReviewedCurrent = $reportsReviewedCurrentObj->whereNotNull('order_report_details.reviewing_date')->count();
		    
		    //No. of reports pending for reviewing in till date
		    $reportsReviewedTillDateObj = DB::table('order_master')
				    ->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_master.test_completion_date)"),'<=',CURRENTDATE);
		    if(!empty($division_id))$reportsReviewedTillDateObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$reportsReviewedTillDateObj->whereIn('order_master.product_category_id',$department_ids);
		    $reportsReviewedTillDate = $reportsReviewedTillDateObj->whereNull('order_report_details.reviewing_date')->count();
		    
		    //TAT %
		    $returnData['tableBody'][$counter][$coloumName] = $tatVisibilty && !empty($reportsReviewedCurrent) && !empty($reportsReviewedTillDate) ? round(($reportsReviewedCurrent / $reportsReviewedTillDate) * 100 ,2) : '-';
		}
	    }
	}
	
	return $returnData;
    }
    
    /**
    * Check Item Code
    *
    * @return \Illuminate\Http\Response
    */
    function getFinalizerContent($division_id,$department_ids,$roleDetail){
	
	$returnData = array();
	
	$tatVisibilty = true; //date('G') >= '17' && date('G') <= '20' ? true : false;
	
	//Total pendency for finalizing till date- Today Due(count)  & Overdue(count) (sample wise)
	//TAT % -total no. of reports finalized in current date / no. of reports pending for finalizing till date
	$coloumArrayData = array(
	    '0' => 'Total Due',
	    '1' => 'Total Overdue',
	    '2' => 'TAT %',
	);
	
	if(!empty($coloumArrayData)){
	    $counter = '0';
	    foreach($coloumArrayData as $coloumKey => $coloumName){
		if($coloumKey == '0'){		//Total Due
		    $countValueObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_report_details.reviewing_date)"),'=',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNull('order_report_details.finalizing_date')->count();
		}else if($coloumKey == '1'){ 	//Total Overdue
		    $countValueObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_report_details.reviewing_date)"),'<',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNull('order_report_details.finalizing_date')->count();
		}else if($coloumKey == '2'){ 	//TAT % - total no. of reports finalizing in current/ no. of reports pending for finalizing in till date
		    
		    //total no. of reports finalize in current
		    $reportsFinalizeCurrentObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_report_details.finalizing_date)"),'=',CURRENTDATE);
		    if(!empty($division_id))$reportsFinalizeCurrentObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$reportsFinalizeCurrentObj->whereIn('order_master.product_category_id',$department_ids);
		    $reportsFinalizeCurrent = $reportsFinalizeCurrentObj->whereNotNull('order_report_details.finalizing_date')->count();
		    
		    //No. of reports pending for finalize in till date
		    $reportsFinalizeTillDateObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_report_details.reviewing_date)"),'<=',CURRENTDATE);
		    if(!empty($division_id))$reportsFinalizeTillDateObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$reportsFinalizeTillDateObj->whereIn('order_master.product_category_id',$department_ids);
		    $reportsFinalizeTillDate = $reportsFinalizeTillDateObj->whereNull('order_report_details.finalizing_date')->count();
		    
		    //TAT %
		    $returnData['tableBody'][$counter][$coloumName] = $tatVisibilty && !empty($reportsFinalizeCurrent) && !empty($reportsFinalizeTillDate) ? round(($reportsFinalizeCurrent / $reportsFinalizeTillDate) * 100 ,2) : '-';
		}
	    }
	}
	
	return $returnData;
    }
    
    /**
    * Check Item Code
    *
    * @return \Illuminate\Http\Response
    */
    function getApprovalContent($division_id,$department_ids,$roleDetail){
	
	$returnData = array();
	
	$tatVisibilty = true; //date('G') >= '17' && date('G') <= '20' ? true : false;

	//Total pendency for approving till date- Today Due & Overdue 
	//TAT % -total no. of reports approved in current date / no. of reports pending for approving in till date
	$coloumArrayData = array(
	    '0' => 'Total Due',
	    '1' => 'Total Overdue',
	    '2' => 'TAT %',
	);
	
	if(!empty($coloumArrayData)){
	    $counter = '0';
	    foreach($coloumArrayData as $coloumKey => $coloumName){
		if($coloumKey == '0'){		//Total Due
		    $countValueObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_report_details.finalizing_date)"),'=',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNull('order_report_details.approving_date')->count();
		}else if($coloumKey == '1'){ 	//Total Overdue
		    $countValueObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_report_details.finalizing_date)"),'<',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNull('order_report_details.approving_date')->count();
		}else if($coloumKey == '2'){ 	//TAT % - total no. of reports finalizing in current/ no. of reports pending for finalizing in till date
		    
		    //total no. of reports approving in current
		    $reportsApprovedCurrentObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_report_details.approving_date)"),'=',CURRENTDATE);
		    if(!empty($division_id))$reportsApprovedCurrentObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$reportsApprovedCurrentObj->whereIn('order_master.product_category_id',$department_ids);
		    $reportsApprovedCurrent = $reportsApprovedCurrentObj->whereNotNull('order_report_details.approving_date')->count();
		    
		    //No. of reports pending for approving in till date
		    $reportsApprovedTillDateObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_report_details.finalizing_date)"),'<=',CURRENTDATE);
		    if(!empty($division_id))$reportsApprovedTillDateObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$reportsApprovedTillDateObj->whereIn('order_master.product_category_id',$department_ids);
		    $reportsApprovedTillDate = $reportsApprovedTillDateObj->whereNull('order_report_details.approving_date')->count();
		    
		    //TAT %		    
		    $returnData['tableBody'][$counter][$coloumName] = $tatVisibilty && !empty($reportsApprovedCurrent) && !empty($reportsApprovedTillDate) ? round(($reportsApprovedCurrent / $reportsApprovedTillDate) * 100,2) : '-';
		}
	    }
	}
	
	return $returnData;
    }
    
    /**
    * Check Item Code
    *
    * @return \Illuminate\Http\Response
    */
    function getInvoicerContent($division_id,$department_ids,$roleDetail){
	
	$returnData = array();
	
	$tatVisibilty = true; //date('G') >= '17' && date('G') <= '20' ? true : false;
	
	//Total Pendency: - Count of Daily and Monthly
	//TAT % - Daily & Monthly Wise(No. of invoices generated/ no. of invoices pending)
	$coloumArrayData = array(
	    '0' => 'Total Pendency Daily',
	    '1' => 'Total Pendency Monthly',
	    '2' => 'TAT %',
	);
	
	if(!empty($coloumArrayData)){
	    $counter = '0';
	    foreach($coloumArrayData as $coloumKey => $coloumName){
		if($coloumKey == '0'){		//Total Pendency Daily
		    $countValueObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->leftJoin('invoice_hdr_detail', function($join){
					$join->on('order_report_details.report_id','=','invoice_hdr_detail.order_id');
					$join->where('invoice_hdr_detail.invoice_hdr_status','1');
				    })
				    ->leftJoin('invoice_hdr', function($join){
					$join->on('invoice_hdr.invoice_id','=','invoice_hdr_detail.invoice_hdr_id');
					$join->where('invoice_hdr.invoice_status','1');
				    })
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->whereNull('order_master.order_sample_type')
				    ->where('order_master.billing_type_id','1')
				    ->where(DB::raw("DATE(order_report_details.approving_date)"),'<=',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNull('invoice_hdr.invoice_date')->count();
		}else if($coloumKey == '1'){ 	//Total Pendency Monthly
		    $countValueObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->leftJoin('invoice_hdr_detail', function($join){
					$join->on('order_report_details.report_id','=','invoice_hdr_detail.order_id');
					$join->where('invoice_hdr_detail.invoice_hdr_status','1');
				    })
				    ->leftJoin('invoice_hdr', function($join){
					$join->on('invoice_hdr.invoice_id','=','invoice_hdr_detail.invoice_hdr_id');
					$join->where('invoice_hdr.invoice_status','1');
				    })
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->whereNull('order_master.order_sample_type')
				    ->where('order_master.billing_type_id','4')
				    ->where(DB::raw("DATE(order_report_details.approving_date)"),'<=',CURRENTDATE);
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNull('invoice_hdr.invoice_date')->count();
		}else if($coloumKey == '2'){ 	//TAT % - Daily & Monthly Wise(No. of invoices generated/ no. of invoices pending)
		    
		    //No. of invoices generated
		    $invoicesGeneratedObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->join('invoice_hdr_detail','order_report_details.report_id','invoice_hdr_detail.order_id')
				    ->join('invoice_hdr','invoice_hdr.invoice_id','invoice_hdr_detail.invoice_hdr_id')
				    ->whereNull('order_master.order_sample_type')
				    ->where(DB::raw("DATE(order_report_details.approving_date)"),'<=',CURRENTDATE)
				    ->where(DB::raw("DATE(invoice_hdr.invoice_date)"),'=',CURRENTDATE)
				    ->whereNotIn('order_master.status',array('10','12'));
		    if(!empty($division_id))$invoicesGeneratedObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$invoicesGeneratedObj->whereIn('order_master.product_category_id',$department_ids);
		    $invoicesGenerated = $invoicesGeneratedObj->whereNotNull('invoice_hdr.invoice_date')->count();
		    
		    //no. of invoices pending
		    $invoicesPendingObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->leftJoin('invoice_hdr_detail', function($join){
					$join->on('order_report_details.report_id','=','invoice_hdr_detail.order_id');
					$join->where('invoice_hdr_detail.invoice_hdr_status','1');
				    })
				    ->leftJoin('invoice_hdr', function($join){
					$join->on('invoice_hdr.invoice_id','=','invoice_hdr_detail.invoice_hdr_id');
					$join->where('invoice_hdr.invoice_status','1');
				    })
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->whereNull('order_master.order_sample_type')
				    ->whereIn('order_master.billing_type_id',array('1','4'))
				    ->where(DB::raw("DATE(order_report_details.approving_date)"),'<=',CURRENTDATE);
		    if(!empty($division_id))$invoicesPendingObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$invoicesPendingObj->whereIn('order_master.product_category_id',$department_ids);
		    $invoicesPending = $invoicesPendingObj->whereNull('invoice_hdr.invoice_date')->count();
		    
		    //TAT %		    
		    $returnData['tableBody'][$counter][$coloumName] = $tatVisibilty && !empty($invoicesGenerated) && !empty($invoicesPending) ? round(($invoicesGenerated / $invoicesPending) * 100,2) : '-';
		}
	    }
	}
	
	return $returnData;
    }
    
    /**
    * Check Item Code
    *
    * @return \Illuminate\Http\Response
    */
    function getDispatcherContent($division_id,$department_ids,$roleDetail){
	
	$returnData = array();
	
	$tatVisibilty = true; //date('G') >= '17' && date('G') <= '20' ? true : false;
	
	//Total Pendency: - Count of Daily & Monthly
	//TAT % - Daily & Monthly Wise(no. of reports dispatched today/No. of reports pending for dispatching)
	$coloumArrayData = array(
	    '0' => 'Total Pendency Daily',
	    '1' => 'Total Pendency Monthly',
	    '2' => 'TAT %',
	);
	
	if(!empty($coloumArrayData)){
	    $counter = '0';
	    foreach($coloumArrayData as $coloumKey => $coloumName){
		if($coloumKey == '0'){		//Total Pendency Daily
		    
		    $countValueObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->join('invoice_hdr_detail', function($join){
					$join->on('order_report_details.report_id','=','invoice_hdr_detail.order_id');
					$join->where('invoice_hdr_detail.invoice_hdr_status','1');
				    })
				    ->join('invoice_hdr', function($join){
					$join->on('invoice_hdr.invoice_id','=','invoice_hdr_detail.invoice_hdr_id');
					$join->where('invoice_hdr.invoice_status','1');
				    })
				    ->leftJoin('order_dispatch_dtl', function($join){
					$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
					$join->where('order_dispatch_dtl.amend_status','0');
				    })
				    ->where('order_master.billing_type_id','1')
				    ->where(DB::raw("DATE(invoice_hdr.invoice_date)"),'<=',CURRENTDATE)
				    ->whereNotIn('order_master.status',array('10','12'));
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNull('order_dispatch_dtl.dispatch_date')->count();
		    
		}else if($coloumKey == '1'){ 	//Total Pendency Monthly
		    
		    $countValueObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->leftJoin('invoice_hdr_detail', function($join){
					$join->on('order_report_details.report_id','=','invoice_hdr_detail.order_id');
					$join->where('invoice_hdr_detail.invoice_hdr_status','1');
				    })
				    ->leftJoin('invoice_hdr', function($join){
					$join->on('invoice_hdr.invoice_id','=','invoice_hdr_detail.invoice_hdr_id');
					$join->where('invoice_hdr.invoice_status','1');
				    })
				    ->leftJoin('order_dispatch_dtl', function($join){
					$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
					$join->where('order_dispatch_dtl.amend_status','0');
				    })
				    ->where('order_master.billing_type_id','4')
				    ->where(DB::raw("DATE(order_report_details.approving_date)"),'<=',CURRENTDATE)
				    ->whereNotIn('order_master.status',array('10','12'));
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->whereNull('order_dispatch_dtl.dispatch_date')->count();
		
		}else if($coloumKey == '2'){	//TAT % - Daily & Monthly Wise(no. of reports dispatched today/No. of reports pending for dispatching)
		    
		    //Total Dispatched today
		    $totalDispatchedCurrentObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->join('order_dispatch_dtl', function($join){
					$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
					$join->where('order_dispatch_dtl.amend_status','0');
				    })
				    ->where(DB::raw("DATE(order_dispatch_dtl.dispatch_date)"),'=',CURRENTDATE)
				    ->whereNotIn('order_master.status',array('10','12'));
		    if(!empty($division_id))$totalDispatchedCurrentObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$totalDispatchedCurrentObj->whereIn('order_master.product_category_id',$department_ids);
		    $totalDispatchedCurrent = $totalDispatchedCurrentObj->whereNotNull('order_dispatch_dtl.dispatch_date')->count();
		    		    
		    //No. of reports pending for dispatching
		    $reportsPendingForDispatchingObj = DB::table('order_master')
				    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->leftJoin('order_dispatch_dtl', function($join){
					$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
					$join->where('order_dispatch_dtl.amend_status','0');
				    })
				    ->whereNotIn('order_master.status',array('10','12'));
		    if(!empty($division_id))$reportsPendingForDispatchingObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$reportsPendingForDispatchingObj->whereIn('order_master.product_category_id',$department_ids);
		    $reportsPendingForDispatching = $reportsPendingForDispatchingObj->whereNull('order_dispatch_dtl.dispatch_date')->count();
		    
		    //TAT %		    
		    $returnData['tableBody'][$counter][$coloumName] = $tatVisibilty && !empty($totalDispatchedCurrent) && !empty($reportsPendingForDispatching) ? round(($totalDispatchedCurrent / $reportsPendingForDispatching) * 100,2) : '-';
		}
	    }
	}
	
	return $returnData;
    }
    
    /**
    * Check Item Code
    *
    * @return \Illuminate\Http\Response
    */
    function getCRMContent($division_id,$department_ids,$roleDetail){
	
	global $models,$order,$dashboard;
	
	$returnData = array();
	
	$tatVisibilty = true; //date('G') >= '17' && date('G') <= '20' ? true : false;
	
	//Count of Total Pending Reports till date 
	//No. of reports Due- Today due & Overdue 
	//No. of samples hold

	$coloumArrayData = array(
	    '0' => 'Pending Reports',	
	    '1' => 'Reports Due',
	    '2' => 'Reports Overdue',
	    '3' => 'Samples hold',
	    '4' => 'Customer Hold',
	);
	
	if(!empty($coloumArrayData)){
	    $counter = '0';
	    foreach($coloumArrayData as $coloumKey => $coloumName){
		if($coloumKey == '0'){		//Total Pending Reports till date
		    $countValueObj = DB::table('order_master')
				    ->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_master.expected_due_date)"),'<=',CURRENTDATE)
				    ->where(function($query){
					$query->whereNull('order_report_details.approving_date');
					$query->orWhere(DB::raw("DATE(order_report_details.approving_date)"),'>',CURRENTDATE);			    
				    });
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->count();
		}else if($coloumKey == '1'){ 	//No. of reports Due
		    $countValueObj = DB::table('order_master')
				    ->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_master.expected_due_date)"),'=',CURRENTDATE)
				    ->where(function($query){
					$query->whereNull('order_report_details.approving_date');
					$query->orWhere(DB::raw("DATE(order_report_details.approving_date)"),'>',CURRENTDATE);			    
				    });
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->count();
		}else if($coloumKey == '2'){ 	//No. of reports Overdue
		    $countValueObj = DB::table('order_master')
				    ->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id')
				    ->whereNotIn('order_master.status',array('10','12'))
				    ->where(DB::raw("DATE(order_master.expected_due_date)"),'<',CURRENTDATE)
				    ->where(function($query){
					$query->whereNull('order_report_details.approving_date');
					$query->orWhere(DB::raw("DATE(order_report_details.approving_date)"),'>',CURRENTDATE);			    
				    });
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->count();
		}else if($coloumKey == '3'){ 	//No. of samples hold		    
		    $countValueObj = DB::table('order_master')->where('order_master.status','12');
		    if(!empty($division_id))$countValueObj->whereIn('order_master.division_id',$division_id);
		    if(!empty($department_ids))$countValueObj->whereIn('order_master.product_category_id',$department_ids);
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->count();
		}else if($coloumKey == '4'){ 	//No. of Customer hold		    
		    $countValueObj = DB::table('customer_master')->where('customer_master.customer_status','3');
		    $returnData['tableBody'][$counter][$coloumName] = $countValueObj->count();		    
		}
	    }
	}
	
	return $returnData;
    }

}

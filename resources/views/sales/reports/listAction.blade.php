<!------view report------>
<div class="report_btn_div">                               
    <span ng-if="{{defined('VIEW') && VIEW}}">                              
	<button type="button" ng-click="funViewTestReport(getBranchWiseReportObj.order_id)" class="btn btn-primary btn-sm report_btn_span" title="View Test Report"><i class="fa fa-eye" aria-hidden="true"></i></button>
    </span>                               
</div>
<!-----/view report------>

<!--If Search All is Off then it will display all action-->
<div ng-if="searchAllOnOff == 0">
    
    <!--Action for Adding Report By Admin/Tester-->
    <div ng-if="{{defined('ADD') && ADD}}" class="report_btn_div">
	<span ng-if="{{defined('IS_TESTER') && IS_TESTER}}">
	    <span ng-if="getBranchWiseReportObj.order_status == 3"><button type="button" ng-click="funAddTestParametersReportByTester(getBranchWiseReportObj.order_id)"  title="Add Test Result" class="btn btn-success btn-sm report_btn_span"><i class="fa fa-plus" aria-hidden="true"></i></button></span>
	</span>
	<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
	    <span ng-if="getBranchWiseReportObj.order_status == 3"><button type="button" ng-click="funAddTestParametersReportByTester(getBranchWiseReportObj.order_id)"  title="Add Test Result" class="btn btn-success btn-sm report_btn_span"><i class="fa fa-plus" aria-hidden="true"></i></button></span>
	</span>
    </div>
    <!--/Action for Adding Report By Admin/Tester-->
    
    <!--Action for Verifying Report By Admin/Section Incharge-->
    <div ng-if="{{defined('ADD') && ADD}}" class="report_btn_div">
	<span ng-if="{{defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE}}">
	    <span ng-if="!getBranchWiseReportObj.oid_confirm_by && (getBranchWiseReportObj.order_status == '3' || getBranchWiseReportObj.order_status == '4')">
		<a ng-click="funAddTestParametersReportBySectionIncharge(getBranchWiseReportObj.order_id)" class="btn btn-success btn-sm report_btn_span" title="Section Incharge"><i class="fa fa-user-circle" aria-hidden="true"></i></i></a>
	    </span>
	</span>
	<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
	    <span ng-if="!getBranchWiseReportObj.oid_confirm_by && getBranchWiseReportObj.order_status == '4'">
		<a ng-click="funAddTestParametersReportBySectionIncharge(getBranchWiseReportObj.order_id)" class="btn btn-success btn-sm report_btn_span" title="Section Incharge"><i class="fa fa-user-circle" aria-hidden="true"></i></i></a>
	    </span>                               
	</span>
    </div>
    <!--/Action for Verifying Report By Admin/Section Incharge-->
				
    <!--Action for Review Report By REVIEWER Part(A&B&D)-->
    <div ng-if="{{defined('ADD') && ADD}}" class="report_btn_div">
	<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
	    <span ng-if="getBranchWiseReportObj.order_status == 5">
		<button type="button" ng-click="funAddTestParametersReportByReporter(getBranchWiseReportObj.order_id)" class="btn btn-success btn-sm report_btn_span" title="Review Report"><i class="fa fa-desktop" aria-hidden="true"></i></button>
	    </span>                               
	</span>
	<span ng-if="{{defined('IS_REVIEWER') && IS_REVIEWER}}">
	    <span ng-if="getBranchWiseReportObj.order_status == 5">
		<button type="button" ng-click="funAddTestParametersReportByReporter(getBranchWiseReportObj.order_id)" class="btn btn-success btn-sm report_btn_span" title="Review Report"><i class="fa fa-desktop" aria-hidden="true"></i></button>
	    </span>
	</span>
    </div>
    <!--/Action for Review Report By REVIEWER Part(A&B&D)--> 						
				
    <!--Action for Review Report By FINALIZER-->
    <div ng-if="{{defined('VIEW') && VIEW}}" class="report_btn_div">                               
	<span ng-if="{{defined('IS_FINALIZER') && IS_FINALIZER}}">
	    <span ng-if="getBranchWiseReportObj.order_status == 6">
		<button type="button" ng-click="funAddTestReportByFinalizerAndQA(getBranchWiseReportObj.order_id)" class="btn btn-success btn-sm report_btn_span" title="Finalize Report"><i class="fa fa-folder" aria-hidden="true"></i></button>
	    </span>                               
	</span>                                                       
	<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
	    <span ng-if="getBranchWiseReportObj.order_status == 6">
		<button type="button" ng-click="funAddTestReportByFinalizerAndQA(getBranchWiseReportObj.order_id)" class="btn btn-success btn-sm report_btn_span" title="Finalize Report"><i class="fa fa-folder" aria-hidden="true"></i></button>
	    </span>                               
	</span>                                
    </div>
    <!--/Action for Review Report by FINALIZER-->
				
    <!--Action for Review Report By APPROVAL-->
    <div ng-if="{{defined('VIEW') && VIEW}}" class="report_btn_div">                               
	<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
	    <span ng-if="getBranchWiseReportObj.order_status == 7">
		<button type="button" ng-click="funAddTestReportByFinalizerAndQA(getBranchWiseReportObj.order_id)" class="btn btn-success btn-sm report_btn_span" title="Approve Report"><i class="fa fa-user" aria-hidden="true"></i></button>
	    </span>                               
	</span>
	<span ng-if="{{defined('IS_APPROVAL') && IS_APPROVAL}}">
	    <span ng-if="getBranchWiseReportObj.order_status == 7">
		<button type="button" ng-click="funAddTestReportByFinalizerAndQA(getBranchWiseReportObj.order_id)" class="btn btn-success btn-sm report_btn_span" title="Approve Report"><i class="fa fa-user" aria-hidden="true"></i></button>
	    </span>                               
	</span>
    </div>
    <!--Amendment of Report-->
    <div class="report_btn_div" ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
	<span ng-if="getBranchWiseReportObj.order_status >= 8">
	    <button type="button" ng-click="funConfirmMessage(getBranchWiseReportObj.order_id,divisionID,'Do you really want to amend this  report?','amendReport')" title="Amend Report" class="btn btn-danger btn-sm report_btn_span"> <i class="fa fa-adn" aria-hidden="true"></i></button>  
	</span>
    </div>
    <!--<div class="report_btn_div" ng-if="{{defined('IS_APPROVAL') && IS_APPROVAL}}">
	<span ng-if="getBranchWiseReportObj.order_status >= 8">
	    <button type="button" ng-click="funConfirmMessage(getBranchWiseReportObj.order_id,divisionID,'Do you really want to amend this  report?','amendReport')" title="Amend Report" class="btn btn-danger btn-sm report_btn_span"> <i class="fa fa-adn" aria-hidden="true"></i></button>  
	</span>
    </div>-->
    <!--Amendment of Report-->
    <!--/Action for Review Report by APPROVAL-->
    
    <!--Display dispatch button-->
    <div class="report_btn_div" ng-if="{{defined('IS_ADMIN') && IS_ADMIN }}">
	<span ng-if="getBranchWiseReportObj.canDispatchOrder == 2">
	    <span ng-if="getBranchWiseReportObj.order_status > 7 && getBranchWiseReportObj.order_status <= 11">
		<button type="button" ng-click="funOpenOrderDispatchPopup('dispatchOrderWithListPopupWindow','show',getBranchWiseReportObj.order_id,getBranchWiseReportObj.order_no)" title="Dispatch Report" class="btn btn-primary btn-sm report_btn_span"> <i class="fa fa-buysellads" aria-hidden="true"></i></button> 
	    </span>
	</span>
	<span ng-if="getBranchWiseReportObj.canDispatchOrder == 1" class="ng-scope">
	    <span ng-if="getBranchWiseReportObj.order_status > 7 && getBranchWiseReportObj.order_status <= 11">
		<button type="button" ng-click="funOpenOrderDispatchPopup('dispatchOrderWithListPopupWindow','show',getBranchWiseReportObj.order_id,getBranchWiseReportObj.order_no)"" class="btn btn-success btn-sm report_btn_span" title="Order Dispatched"><i class="fa fa-send" aria-hidden="true"></i></button>
	    </span>
	</span>	
    </div>
    <div class="report_btn_div" ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}">
	<span ng-if="getBranchWiseReportObj.canDispatchOrder == 2 && getBranchWiseReportObj.order_status > 7 && getBranchWiseReportObj.order_status < 11">
	    <button type="button" ng-click="funOpenOrderDispatchPopup('dispatchOrderWithListPopupWindow','show',getBranchWiseReportObj.order_id,getBranchWiseReportObj.order_no)" title="Dispatch Report" class="btn btn-primary btn-sm report_btn_span"> <i class="fa fa-buysellads" aria-hidden="true"></i></button> 
	</span>		
	<span ng-if="getBranchWiseReportObj.canDispatchOrder == 1" class="ng-scope">
	    <span ng-if="getBranchWiseReportObj.order_status > 7 && getBranchWiseReportObj.order_status <= 11">
		<button type="button" ng-click="funOpenOrderDispatchPopup('dispatchOrderWithListPopupWindow','show',getBranchWiseReportObj.order_id,getBranchWiseReportObj.order_no)"" class="btn btn-success btn-sm report_btn_span" title="Order Dispatched"><i class="fa fa-send" aria-hidden="true"></i></button>
	    </span>
	</span>
    </div>
    <!--/Display dispatch button-->
    
    <!--Deleting of Report-->
    <div ng-if="{{defined('DELETE') && DELETE}}" class="report_btn_div">
	<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
	    <button type="button" ng-click="funConfirmMessage(getBranchWiseReportObj.order_id,divisionID,'Are you sure you want to delete this record?','listReport')" title="Delete Report" class="btn btn-danger btn-sm report_btn_span"> <i class="fa fa-trash-o" aria-hidden="true"></i></button>
	</span>
    </div>
    <!--Deleting of Report-->
    
</div>
<!--/If Search All is Off then it will display all action-->
<form name="orderReportForm" novalidate>
    <!-- container start here -->
    <div class="container pdng-20 botm-table" style="padding:10px;border: 1px solid;">
        <!-- non editable part A container start here -->
        <div ng-if="{{defined('IS_ADMIN') && IS_ADMIN || defined('IS_REPORTER') && IS_REPORTER || defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_FINALIZER') && IS_FINALIZER || defined('IS_APPROVAL') && IS_APPROVAL || defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR || defined('IS_DISPATCHER') && IS_DISPATCHER || defined('IS_CRM') && IS_CRM}}" class="col-md-12">
	    	<table class="table table-bordered" style="margin:0;">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <span class="bold editableSampleName">Sample Name</span>
                            <span class="bold">:</span>
                            <span class="bold text-value" ng-if="viewOrderReport.sample_description">[[viewOrderReport.sample_description]]</span>
                            <span class="text-value" ng-if="!viewOrderReport.sample_description">-</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="width50">
                            <span class="bold font_lable ">Supplied By</span><span class="bold">:</span>
                            <span class="text-value" ng-if="viewOrderReport.supplied_by">[[viewOrderReport.supplied_by]]</span>
                            <span class="text-value" ng-if="!viewOrderReport.supplied_by"></span>
                        </td>
                        <td class="width50">
                            <span class="bold font_lable">Report Date</span><span class="bold">:</span>
                            <span ng-if="!viewOrderReport.report_date"></span>
                            <span ng-if="viewOrderReport.report_date">[[viewOrderReport.report_date]]</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="width50">
                            <span class="bold font_lable">Manufactured By</span><span class="bold">:</span>
                            <span class="text-value" ng-if="viewOrderReport.manufactured_by">[[viewOrderReport.manufactured_by]]</span>
                            <span class="text-value" ng-if="!viewOrderReport.manufactured_by"></span>
                        </td>
                        <td class="width50">
                            <span class="bold font_lable">Booking Code</span><span class="bold">:</span>
                            <span class="text-value" ng-if="viewOrderReport.order_no">[[viewOrderReport.order_no]]</span>
                            <span class="text-value" ng-if="!viewOrderReport.order_no"></span>
                            <span ng-if="viewOrderReport.is_amended_no">-[[viewOrderReport.is_amended_no]]</span>
                        </td>
                    </tr>
						  
		    <tr>
			<td colspan="2" style="padding:0px !important;">
			    <table class="table table-bordered bodr_top bodr_bottom" style="background:transparent;margin:0" ng-if="viewOrderReport.nabl_no">
				<tr>
				    <td class=" bodr_top bodr_bottom width50 border_left">
					<span class="bold font_lable">Submitted By</span>
					<span class="bold">:</span>
					<span class="text-value" ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]]</span>
					<span class="text-value" ng-if="!viewOrderReport.customer_name"></span>
				    </td>
				    <td class="width50 border_right">
					<span ng-if="viewOrderReport.nabl_no">
					    <span class="bold font_lable">NABL ULR No.</span><span class="bold">:</span>
					    <span ng-if="viewOrderReport.nabl_no">[[viewOrderReport.nabl_no]]</span>
					</span>
				    </td>
				</tr>
				<tr>
				    <td class="bodr_top width50 border_left">
					<span class="bold font_lable">Mfg. Lic. No.</span>
					<span class="bold">:</span>
					<span class="text-value" ng-if="viewOrderReport.mfg_lic_no">[[viewOrderReport.mfg_lic_no]]</span>
					<span class="text-value" ng-if="!viewOrderReport.mfg_lic_no"></span>
				    </td>
				    <td class="bodr_top bodr_bottom border_right">
					<span class="bold font_lable">Booking Date</span>
					<span class="bold">:</span>
					<span class="text-value" ng-if="viewOrderReport.order_date">[[viewOrderReport.order_date]]</span>
					<span class="text-value" ng-if="!viewOrderReport.order_date"></span>
				    </td>
				</tr>
				<tr>
				    <td  colspan="2"  class="bodr_bottom border_left">
					<span class="bold editableSampleName">Address</span>
					<span class="bold">:</span>
					<span class="text-value" ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]] - [[viewOrderReport.city_name]] ( [[viewOrderReport.state_name]] )</span>
					<span class="text-value" ng-if="!viewOrderReport.customer_name"></span>
				    </td>
				</tr>
				<tr>
				     <td class="width50 border_left border_bottom">
					<span class="bold font_lable">Party Ref. No</span><span class="bold">:</span>
					<span ng-if="viewOrderReport.reference_no">[[viewOrderReport.reference_no]]</span>
					<span ng-if="!viewOrderReport.reference_no"></span>
				     </td>
				     <td  class="width50 border_bottom"></td>
				</tr>
			    </table>
			    
			    <table class="table table-bordered bodr_top bodr_bottom" style="background:transparent;margin:0" ng-if="!viewOrderReport.nabl_no">
				<tr>
				    <td class=" bodr_top bodr_bottom width50 border_left">
					<span class="bold font_lable">Submitted By</span>
					<span class="bold">:</span>
					<span class="text-value" ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]]</span>
					<span class="text-value" ng-if="!viewOrderReport.customer_name"></span>
				    </td>
				    <td class="bodr_top bodr_bottom border_right">
					<span class="bold font_lable">Booking Date</span>
					<span class="bold">:</span>
					<span class="text-value" ng-if="viewOrderReport.order_date">[[viewOrderReport.order_date]]</span>
					<span class="text-value" ng-if="!viewOrderReport.order_date"></span>
				    </td>	
				</tr>						
				<tr>
				    <td  colspan="2" class="bodr_bottom border_left">
					<span class="bold editableSampleName">Address</span>
					<span class="bold">:</span>
					<span class="text-value" ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]] - [[viewOrderReport.city_name]] ( [[viewOrderReport.state_name]] )</span>
					<span class="text-value" ng-if="!viewOrderReport.customer_name"></span>
				    </td>
				</tr>
				<tr>
				    <td class="bodr_top width50 border_bottom  border_left">
					<span class="bold font_lable">Mfg. Lic. No.</span>
					<span class="bold">:</span>
					<span class="text-value" ng-if="viewOrderReport.mfg_lic_no">[[viewOrderReport.mfg_lic_no]]</span>
					<span class="text-value" ng-if="!viewOrderReport.mfg_lic_no"></span>
				    </td>
				    <td class="border_right border_bottom "><span class="bold font_lable">Party Ref. No</span>
					<span class="bold">:</span>
					<span ng-if="viewOrderReport.reference_no">[[viewOrderReport.reference_no]]</span>
					<span ng-if="!viewOrderReport.reference_no"></span>
				    </td>
				</tr>
			    </table>
			</td>
		    </tr>
		    
                    <tr>
                        <td colspan="2" style="padding:0!important">
                            <table class="table table-bordered bodr_top bodr_bottom" style="background:transparent;margin:0">
                                <tr>
                                    <td class=" bodr_top width25">
                                        <span class="bold font_lable">Batch No</span>
                                        <span class="bold">:</span>
                                        <span class="text-value" ng-if="viewOrderReport.batch_no">[[viewOrderReport.batch_no]]</span>
                                        <span class="text-value" ng-if="!viewOrderReport.batch_no"></span>
                                    </td>
                                    <td class="bodr_top width25">
                                        <span class="bold font_lable">Batch Size</span>
                                        <span class="bold">:</span>
                                        <span class="text-value" ng-if="viewOrderReport.batch_size">[[viewOrderReport.batch_size]]</span>
                                        <span class="text-value" ng-if="!viewOrderReport.batch_size"></span>
                                    </td>
                                    <td class="bodr_top width25">
                                        <span class="bold font_lable">Party Ref. Date</span>
                                        <span class="bold">:</span>
                                        <span ng-if="viewOrderReport.letter_no">[[viewOrderReport.letter_no]]</span>
                                        <span ng-if="!viewOrderReport.letter_no"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bodr_bottom">
                                        <span class="bold font_lable">D/M</span>
                                        <span class="bold">:</span>
                                        <span class="text-value" ng-if="!viewOrderReport.mfg_date"></span>
                                        <span class="text-value" ng-if="viewOrderReport.mfg_date">[[viewOrderReport.mfg_date]]</span>
                                    </td>
                                    <td class="bodr_bottom">
                                        <span class="bold font_lable">D/E</span>
                                        <span class="bold">:</span>
                                        <span class="text-value" ng-if="!viewOrderReport.expiry_date"></span>
                                        <span class="text-value" ng-if="viewOrderReport.expiry_date">[[viewOrderReport.expiry_date]]</span>
                                    </td>
                                    <td class="bodr_bottom" style="border-bottom:0px!important;">
                                        <span class="bold font_lable">Sample Qty</span>
                                        <span class="bold">:</span>
                                        <span class="text-value" ng-if="viewOrderReport.sample_qty">[[viewOrderReport.sample_qty]]</span>
                                        <span class="text-value" ng-if="!viewOrderReport.sample_qty"></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--- /non editable part A container start here -->

		<div class="col-md-12 mT5 mB5 font16">
			<span class="pull-left bold">
				<span class="bodr_bottom">Date of start of analysis</span>
				<span> :</span>
				<span ng-if="viewOrderReport.analysis_start_date">[[orderTrackRecord.analysis_start_date]]</span>
				<span ng-if="!viewOrderReport.analysis_start_date"></span>
			</span>
			<span class="pull-right bold">
				<span class="bodr_bottom">Date of completion of analysis</span>
				<span> :</span>
				<span ng-if="viewOrderReport.orderAmendStatus && viewOrderReport.is_amended_no">[[orderTrackRecord.reviewing.report_view_date ? orderTrackRecord.reviewing.report_view_date : orderTrackRecord.finalizing.report_view_date]]</span>
				<span ng-if="viewOrderReport.analysis_completion_date && !viewOrderReport.is_amended_no">[[viewOrderReport.analysis_completion_date]]</span>
			</span>
		</div>

        <!-- part C div start here -->
        <div class="container-fluid pdng-20 botm-table" id="foorPartC">
            <div ng-if="viewOrderReport.header_note" class="col-md-12 col-xs-12 botm-row text-center dis_flex " style="padding:0;">
                <div class="col-sm-11 col-xs-11">
                    <h4 class="text-center f_weight">[[viewOrderReport.header_note]]</h4>
                </div>
            </div>
            <div class="container-fluid pdng-20 botm-table mT30" id="foorPartC">
                <div class="row" style="padding:0">
                    <div class="col-md-12 col-xs-12 bord">
                        <div class="col-sm-12 col-xs-12 report">
                            <div class="col-sm-12  col-xs-12 upr-case"><span></span> TEST RESULTS </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding:0">
                    <div class="col-md-1 col-xs-1 bord">S.No</div>
                    <div class="col-md-3 col-xs-3 bord">Test Parameter</div>
                    <div class="col-md-2 col-xs-2 bord">Inst. Used</div>
                    <div class="col-md-2 col-xs-2 bord">Method</div>
                    <div class="col-md-2 col-xs-2 bord">Requirement</div>
                    <div class="col-md-2 col-xs-2 bord">Result</div>
                </div>
                <div class="row" style="padding:0">
                    <div class="col-md-12 col-xs-1 mB5"> <b>Test Details</b> :&nbsp;&nbsp;
                        <span ng-if="viewOrderReport.header_note">[[viewOrderReport.header_note | capitalize]]</span>
                        <span ng-if="!viewOrderReport.header_note"></span>
                    </div>
                </div>

                <div ng-repeat="categoryParameters in orderParametersList">
                    <div class="row text-center" style="padding:0">
                        <div class="col-md-1 col-xs-1 bord"><b>[[$index+1]].</b></div>
                        <div class="col-md-11 col-xs-11 bord">
                            <div class="col-sm-12 col-xs-12 text-left">
				<span class="text-left"><strong>[[categoryParameters.categoryName]]</strong></span>
				<span class="text-left" ng-if="categoryParameters.categoryNameSymbol"><strong ng-bind-html="categoryParameters.categoryNameSymbol"></strong></span>
			    </div>
                        </div>
                    </div>
                    <div ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">
                        <div ng-if="subCategoryParameters.description">
                            <div class="row text-center" style="padding:0">
                                <div class="col-md-1 col-xs-1 bord">[[subCategoryParameters.charNumber]])</div>
                                <div class="col-md-3 col-xs-3 bord text-left">
                                    <span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
				</div>
				<div class="col-md-8 col-xs-8 bord text-justify">
				    <span ng-if="subCategoryParameters.description">[[subCategoryParameters.description]]</span>
				    <span ng-if="!subCategoryParameters.description"><span>
				</div>
			    </div>
			</div>	
			<div ng-if="!subCategoryParameters.description" class="row">
			    <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
				<div class="col-md-1 col-xs-1 bord">[[subCategoryParameters.charNumber]])</div>
				<div class="col-md-3 col-xs-3 bord text-left">
				    <span ng-if="subCategoryParameters.test_parameter_name" class="txt-left" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
				    <span ng-if="subCategoryParameters.non_nabl_parameter_symbol" ng-bind-html="subCategoryParameters.non_nabl_parameter_symbol"></span>
				</div>
				<div class="col-md-2 col-xs-2 bord">
				    <span ng-if="subCategoryParameters.equipment_name">[[subCategoryParameters.equipment_name]]</span>
				    <span ng-if="!subCategoryParameters.equipment_name"><span>
				</div>
				<div class="col-md-2 col-xs-2 bord">
				    <span ng-if="subCategoryParameters.method_name">[[subCategoryParameters.method_name]]</span>
				    <span ng-if="!subCategoryParameters.method_name"><span>
				</div>
				<div class="col-md-2 col-xs-2 bord">[[subCategoryParameters.requirement_from_to]]</div>
				<div class="col-md-2 col-xs-2 bord">
				    <span ng-if="subCategoryParameters.test_result">[[subCategoryParameters.test_result]]</span>
				    <span ng-if="!subCategoryParameters.test_result"><span>
				</div>
			    </div>
			</div>
		    </div>
		</div>
		<div class="col-md-12 col-xs-12 bord-remark" ng-if="orderParametersList.length && viewOrderReport.order_nabl_os_remark_scope">Remarks&nbsp;:&nbsp;'&#x2A;' represents categories/test parameters not covered under NABL&nbsp;&#124;&nbsp;'&#x2A;&#x2A;' represents outsource sample</div>
	    </div>
	</div>
	<!-- part C div start here -->

	<!-- part D div start here -->
	<div ng-if="{{defined('IS_ADMIN') && IS_ADMIN || defined('IS_REPORTER') && IS_REPORTER || defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_FINALIZER') && IS_FINALIZER || defined('IS_APPROVAL') && IS_APPROVAL || defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR || defined('IS_DISPATCHER') && IS_DISPATCHER || defined('IS_CRM') && IS_CRM}}">

	    <!-------------------------REPORT NOTE------------------------------->
	    <div ng-if="viewOrderReport.note_value" class="col-md-12 col-xs-12 mrgn_20 mT20 mB20">
		<div class="col-sm-1 col-xs-1 upr-case">NOTE :</div>
		<div class="col-sm-10 col-xs-10">
		    <span ng-if="viewOrderReport.note_value">[[viewOrderReport.note_value]]</span>
		    <span ng-if="!viewOrderReport.note_value"></span>
		</div>
	    </div>
	    <!-------------------------/REPORT NOTE------------------------------->
	    
	    <div class="col-md-12 col-xs-12 botm-row dis_flex" ng-if="viewOrderReport.remark_value">
		<span class="pharamNote">
		    <span ng-if="viewOrderReport.remark_value">[[viewOrderReport.remark_value]] <span ng-if="viewOrderReport.test_standard_value"> [[viewOrderReport.test_standard_value]]</span></span>
		</span>
	    </div>
	    
	    <div class="col-md-12 col-xs-12 botm-row dis_flex" ng-if="!viewOrderReport.remark_value">
		<span class="pharamNote">
		    <span ng-if="!viewOrderReport.remark_value"> No Review Available. </span>
		</span>
	    </div>
	    
	    <!--Name and Sinature-->
	    <div class="col-md-12 col-xs-12 mT30">
		<div ng-if="viewOrderReport.status >= 6 && !viewOrderReport.report_microbiological_name" class="col-sm-4 col-xs-4 text-left mrgn_20 mT30">
		    <h5><b>Report Date</b> :<span ng-if="viewOrderReport.report_date">[[viewOrderReport.report_date]]</span></h5>
		</div>
		<div ng-if="viewOrderReport.status >= 4 && viewOrderReport.report_microbiological_name" class="col-sm-4 col-xs-4 text-center mrgn_20 mT30">
		    <p ng-if="viewOrderReport.report_microbiological_sign_path" style="width:100%;"><img height="40px" width="100px" style="margin:-40px 0 0;" ng-alt="[[viewOrderReport.report_microbiological_name]]" ng-src="[[viewOrderReport.report_microbiological_sign_path]]" /></p>
		    <h5 ng-if="viewOrderReport.incharge_reviewing_date">[[viewOrderReport.incharge_reviewing_date]]</h5>
		    <p ng-if="viewOrderReport.report_microbiological_name" style="font-size:10px;width:100%;">[[viewOrderReport.report_microbiological_name]]</p>
		    <h4 ng-if="viewOrderReport.report_microbiological_name">[Tech Manager (Micro.)]</h4>
		</div>
		<div class="col-sm-4 col-xs-4 text-center mrgn_20 mT30">
		    <p ng-if="viewOrderReport.status >= 6" style="width:100%;"><img height="40px" width="100px" style="margin:-40px 0 0;" ng-alt="[[orderTrackRecord.reviewing.user_signature]]" ng-src="{{SITE_URL.SIGN_PATH}}[[orderTrackRecord.reviewing.user_signature]]" /></p>
		    <p ng-if="viewOrderReport.status >= 6" style="font-size:10px;width:100%;">[[orderTrackRecord.reviewing.username]]</p>
		    <h5 ng-if="viewOrderReport.status >= 6">[[viewOrderReport.reviewing_date]]</h5>
		    <h4 ng-if="viewOrderReport.status >= 6">Reviewer</h4>
		</div>
		<div class="col-sm-4 col-xs-4 text-right mrgn_20 mT30">
		    <p ng-if="viewOrderReport.status >= 7" style="width:100%;"><img height="40px" width="100px" style="margin:-40px 0px 0px; padding-left: 15px;" ng-alt="[[orderTrackRecord.finalizing.user_signature]]" ng-src="{{SITE_URL.SIGN_PATH}}[[orderTrackRecord.finalizing.user_signature]]" /></p>
		    <h5 ng-if="viewOrderReport.status >= 7">[[viewOrderReport.finalizing_date]]</h5>
		    <h4 ng-if="viewOrderReport.status >= 7">[[orderTrackRecord.finalizing.username]]</h4>
		    <h4 ng-if="viewOrderReport.status >= 7">[Person Incharge]</h4>
		</div>
	    </div>
	    <!--Name and Sinature-->
	    
	    <!--Save Button-->
	    <div class="col-md-12 col-xs-12 dis_flex test_foot mT20" style="padding:0">
		<div class="col-md-5 col-sm-5 col-xs-5 text-center">&nbsp;</div>
		<div class="col-md-4 col-sm-4 col-xs-4 text-center">
		    <div class="col-md-6 col-xs-6">
			<span ng-if="viewOrderReport.status > 7 && viewOrderReport.approved_by"><input type="submit" ng-click="funOpenNeedModificationModal('generateReportCriteriaId')" value="Download Report" class="btn btn-primary"></span>
			<button type="button" class="btn btn-primary hideContentOnPdf" ng-click="backButton()">Back</button>
		    </div>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-3 text-center">&nbsp;</div>
	    </div>
	    <!--Save Button-->
	    
	</div>
    </div>
</form>

<!-------generateReportCriteriaPopup popup---------->
@include('sales.reports.generateReportCriteriaPopup')
<!-------/generateReportCriteriaPopup popup---------->

<style>
.font_lable {width: 30%;float: left;margin-left: 4px;margin-left: 4px;}
.table{font-size: 15px!important;}
.bold {font-weight: 600;}
.table td {padding: 3px!important;border-color: #000;}
.bodr_top {border-top: 0px!important;}
.left_txt {border: 1px solid;padding: 3px;margin-top: 5px;font-size: 15px;width: auto;float: left;}
</style>
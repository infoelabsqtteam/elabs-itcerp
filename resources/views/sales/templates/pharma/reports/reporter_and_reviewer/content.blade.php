<form name="orderReportFormByReporter" id="orderReportFormByReporter" novalidate>
    <!-- container start here -->
    <div class="container pdng-20 botm-table" style="padding:10px;border: 1px solid;">
        <!-- non editable part A container start here -->
        <div ng-if="viewNonEditablePartA" ng-if="{{defined('IS_ADMIN') && IS_ADMIN || defined('IS_REPORTER') && IS_REPORTER || defined('IS_REVIEWER') && IS_REVIEWER }}" class="col-md-12">
            <span ng-if="viewOrderReport.status == 5">
                <span ng-if="{{defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_ADMIN') && IS_ADMIN}}">
                    <a ng-click="toggelEditReportPartAForm(viewOrderReport.product_category_id,viewOrderReport.test_standard,viewOrderReport.order_id)" class="cursor-pointer editLinkCss">Edit</a>
                </span>
            </span>
            <table class="table table-bordered" style="margin:0;">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <span class="bold editableSampleName">Sample Name</span>
                            <span class="bold">:</span>
                            <span ng-if="viewOrderReport.sample_description" class="">[[viewOrderReport.sample_description]]</span>
                            <span ng-if="!viewOrderReport.sample_description"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="width50">
                            <span class="bold font_lable">Supplied By</span><span class="bold">:</span>
                            <span ng-if="viewOrderReport.supplied_by">[[viewOrderReport.supplied_by]]</span>
                            <span ng-if="!viewOrderReport.supplied_by"></span>
                        </td>
                        <td>
                            <span class="bold font_lable">Booking Code</span><span class="bold">:</span>
                            <span class="text-value" ng-if="viewOrderReport.order_no">[[viewOrderReport.order_no]]</span>
                            <span class="text-value" ng-if="!viewOrderReport.order_no"></span>
                            <span ng-if="viewOrderReport.is_amended_no">-[[viewOrderReport.is_amended_no]]</span>
                            <span ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'P'">{{'&nbsp;(1)&nbsp;(2)'}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:0!important">
                            <table class="table table-bordered bodr_top bodr_bottom" style="background:transparent;margin:0" ng-if="viewOrderReport.nabl_no">
                                <tbody>
                                    <tr>
                                        <td class="width50">
                                            <span class="bold font_lable">Manufactured By</span><span class="bold">:</span>
                                            <span ng-if="viewOrderReport.manufactured_by">[[viewOrderReport.manufactured_by]]</span>
                                            <span ng-if="!viewOrderReport.manufactured_by"></span>
                                        </td>
                                        <td class="width50">
                                            <span ng-if="viewOrderReport.nabl_no">
                                                <span class="bold font_lable">NABL ULR No.</span><span class="bold">:</span>
                                                <span ng-if="viewOrderReport.nabl_no">[[viewOrderReport.nabl_no]]</span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="width50">
                                            <span class="bold font_lable">Party Ref. Date</span><span class="bold">:</span>
                                            <span ng-if="viewOrderReport.letter_no">[[viewOrderReport.letter_no]]</span>
                                            <span ng-if="!viewOrderReport.letter_no"></span>
                                        </td>
                                        <td class=" bodr_top bodr_bottom"><span class="bold font_lable">Booking Date</span><span class="bold">:</span>
                                            <span ng-if="viewOrderReport.order_date">[[viewOrderReport.order_date]]</span>
                                            <span ng-if="!viewOrderReport.order_date"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding:0!important">
                                            <table class="table table-bordered bodr_top bodr_bottom" style="background:transparent;margin:0">
                                                <tr>
                                                    <td colspan="2" class="bodr_top bodr_bottom width50"><span class="bold editableSampleName">Submitted By</span><span class="bold">:</span>
                                                        <span ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]] - [[viewOrderReport.city_name]] ( [[viewOrderReport.state_name]] ) </span>
                                                        <span ng-if="!viewOrderReport.customer_name"></span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered bodr_top bodr_bottom" style="background:transparent;margin:0" ng-if="!viewOrderReport.nabl_no">
                                <tbody>
                                    <tr>
                                        <td class="width50">
                                            <span class="bold font_lable">Manufactured By</span><span class="bold">:</span>
                                            <span ng-if="viewOrderReport.manufactured_by">[[viewOrderReport.manufactured_by]]</span>
                                            <span ng-if="!viewOrderReport.manufactured_by"></span>
                                        </td>
                                        <td class="width50">
                                            <span class="bold font_lable">Party Ref. Date</span><span class="bold">:</span>
                                            <span ng-if="viewOrderReport.letter_no">[[viewOrderReport.letter_no]]</span>
                                            <span ng-if="!viewOrderReport.letter_no"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bodr_top bodr_bottom width50"><span class="bold font_lable">Submitted By</span><span class="bold">:</span>
                                            <span ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]] - [[viewOrderReport.city_name]] ( [[viewOrderReport.state_name]] ) </span>
                                            <span ng-if="!viewOrderReport.customer_name"></span>
                                        </td>
                                        <td class=" bodr_top bodr_bottom"><span class="bold font_lable">Booking Date</span><span class="bold">:</span>
                                            <span ng-if="viewOrderReport.order_date">[[viewOrderReport.order_date]]</span>
                                            <span ng-if="!viewOrderReport.order_date"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:0!important">
                            <table class="table table-bordered bodr_top bodr_bottom" style="background:transparent;margin:0">
                                <tr>
                                    <td class="bodr_top width25">
                                        <span class="bold font_lable">Mfg. Lic. No.</span><span class="bold">:</span>
                                        <span ng-if="viewOrderReport.mfg_lic_no">[[viewOrderReport.mfg_lic_no]]</span>
                                        <span ng-if="!viewOrderReport.mfg_lic_no"></span>
                                    </td>
                                    <td class=" bodr_top width25">
                                        <span class="bold font_lable">Batch No</span><span class="bold">:</span>
                                        <span ng-if="viewOrderReport.batch_no">[[viewOrderReport.batch_no]]</span>
                                        <span ng-if="!viewOrderReport.batch_no"></span>
                                    </td>
                                    <td class=" bodr_top width25">
                                        <span class="bold font_lable">Batch Size</span><span class="bold">:</span>
                                        <span ng-if="viewOrderReport.batch_size">[[viewOrderReport.batch_size]]</span>
                                        <span ng-if="!viewOrderReport.batch_size"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bodr_bottom">
                                        <span class="bold font_lable">D/M</span><span class="bold">:</span>
                                        <span ng-if="!viewOrderReport.mfg_date"></span>
                                        <span ng-if="viewOrderReport.mfg_date">[[viewOrderReport.mfg_date]]</span>
                                    </td>
                                    <td class="bodr_bottom">
                                        <span class="bold font_lable">D/E</span><span class="bold">:</span>
                                        <span ng-if="!viewOrderReport.expiry_date"></span>
                                        <span ng-if="viewOrderReport.expiry_date">[[viewOrderReport.expiry_date]]</span>
                                    </td>
                                    <td class="bodr_bottom">
                                        <span class="bold font_lable">Sample Qty</span><span class="bold">:</span>
                                        <span ng-if="viewOrderReport.sample_qty">[[viewOrderReport.sample_qty]]</span>
                                        <span ng-if="!viewOrderReport.sample_qty"></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

            </table>
        </div>
        <!--- /non editable part A container end here -->

        <!---editablePartA------>
        <span ng-if="viewOrderReport.status == 5">
            <span ng-if="{{defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_ADMIN') && IS_ADMIN}}">
                @include('sales.templates.pharma.reports.reporter_and_reviewer.editablePartA')
            </span>
        </span>
        <!---editablePartA------>

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
            <div ng-if="viewOrderReport.stability_note " class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0;">
                <div class="col-sm-11 col-xs-11">
                    <h4 class="text-center f_weight">[[viewOrderReport.stability_note]]</h4>
                </div>
            </div>

            <div ng-repeat="categoryParameters in orderParametersList">
                <div ng-if="viewOrderReport.header_note && viewOrderReport.product_category_id == '2' && categoryParameters.productCategoryName == viewOrderReport.assayParametersWithoutSpace" class="col-md-12 col-xs-12 botm-row text-center dis_flex " style="padding:0;">
                    <div class="col-sm-11 col-xs-11">
                        <h4 class="text-center f_weight">[[viewOrderReport.header_note]]</h4>
                    </div>
                </div>
                <div ng-if="!viewOrderReport.header_note && viewOrderReport.product_category_id == '2' && categoryParameters.productCategoryName == viewOrderReport.assayParametersWithoutSpace" class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding: 14px;">
                    <div class="col-sm-11 col-xs-11">
                        <h4 class="text-center"></h4>
                    </div>
                </div>
                <div ng-if="categoryParameters.categoryName == viewOrderReport.testParametersWithSpace" class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                    <div class="col-md-4 col-xs-4 bord">
                        <div class="col-sm-12 col-xs-12 text-left">
                            <span class="text-left"><strong>[[categoryParameters.categoryName]]</strong></span>
                            <span class="text-left" ng-if="categoryParameters.categoryNameSymbol"><strong ng-bind-html="categoryParameters.categoryNameSymbol"></strong></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-4 bord bold">RESULT</div>
                    <div class="col-md-4 col-xs-4 bord bold">LIMIT</div>
                </div>
                <div ng-if="categoryParameters.categoryName != viewOrderReport.testParametersWithSpace" class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                    <div class="col-md-4 col-xs-4 bord">
                        <div class="col-sm-12 col-xs-12 text-left">
                            <span class="text-left"><strong>[[categoryParameters.categoryName]]</strong></span>
                            <span class="text-left" ng-if="categoryParameters.categoryNameSymbol"><strong ng-bind-html="categoryParameters.categoryNameSymbol"></strong></span>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-2 bord bold">RESULT</div>
                    <div class="col-md-2 col-xs-2 bord bold">CLAIM</div>
                    <div class="col-md-2 col-xs-2 bord bold">LIMIT</div>
                    <div class="col-md-2 col-xs-2 bord bold">METHOD</div>
                </div>
                <div ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">
                    <div ng-if="categoryParameters.categoryName == viewOrderReport.testParametersWithSpace && subCategoryParameters.description.length">
                        <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                            <div class="col-md-4 col-xs-4 bord text-left">
                                <span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
                                <span ng-if="!subCategoryParameters.test_parameter_name"><span>
                            </div>
                            <div class="col-md-8 col-xs-8 bord text-justify" ng-if="!viewEditPartAForm">
                                <span ng-if="subCategoryParameters.description">[[subCategoryParameters.description]]</span>
                                <span ng-if="!subCategoryParameters.description"></span>
                            </div>
                            <div class="col-md-8 col-xs-8 bord text-justify" ng-if="viewEditPartAForm">
                                <span>
                                    <input class="form-control" ng-modal="viewEditOrderReport.description" id="analysis_id_[[subCategoryParameters.analysis_id]]" type="text" name="orderParameterDetail['[[subCategoryParameters.analysis_id]]']" placeholder="Description" ng-value="subCategoryParameters.description" value="[[subCategoryParameters.description]]">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div ng-if="categoryParameters.categoryName == viewOrderReport.testParametersWithSpace && !subCategoryParameters.description.length">
                        <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                            <div class="col-md-4 col-xs-4 bord text-left">
                                <span ng-if="subCategoryParameters.test_parameter_name" class="txt-left" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
                                <span ng-if="subCategoryParameters.non_nabl_parameter_symbol" ng-bind-html="subCategoryParameters.non_nabl_parameter_symbol"></span>
                            </div>
                            <div class="col-md-4 col-xs-4 bord">
                                <span class="resultValue" ng-if="subCategoryParameters.test_result">[[subCategoryParameters.test_result]]</span>
                                <span class="resultCheckbox mL20" ng-if="subCategoryParameters.test_result">
                                    <input type="checkbox" ng-modal="analysis_id_[[$index]]" id="analysis_id_[[subCategoryParameters.analysis_id]]" ng-click="funSelectedAnalysisIdArr(subCategoryParameters.analysis_id)" name="analysis_id[]" ng-value="subCategoryParameters.analysis_id">
                                </span>
                                <span ng-if="!subCategoryParameters.test_result"><span>
                            </div>
                            <div class="col-md-4 col-xs-4 bord">
                                <span ng-if="subCategoryParameters.requirement_from_to">[[subCategoryParameters.requirement_from_to]]
                                    &nbsp;<span ng-if="subCategoryParameters.claim_value_unit "> [[subCategoryParameters.claim_value_unit | capitalizeAll]]</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div ng-if="categoryParameters.categoryName != viewOrderReport.testParametersWithSpace">
                        <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                            <div class="col-md-4 col-xs-4 bord text-left">
                                <span ng-if="subCategoryParameters.test_parameter_name" class="txt-left" style="margin-right:0px!important;" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
                                <span ng-if="subCategoryParameters.non_nabl_parameter_symbol" ng-bind-html="subCategoryParameters.non_nabl_parameter_symbol"></span>
                            </div>
                            <div class="col-md-2 col-xs-2 bord">
                                <span class="resultValue" ng-if="subCategoryParameters.test_result">[[subCategoryParameters.test_result]]</span>
                                <span class="resultCheckbox mL20" ng-if="subCategoryParameters.test_result">
                                    <input type="checkbox" ng-modal="analysis_id_[[$index]]" id="analysis_id_[[subCategoryParameters.analysis_id]]" ng-click="funSelectedAnalysisIdArr(subCategoryParameters.analysis_id)" name="analysis_id[]" ng-value="subCategoryParameters.analysis_id">
                                </span>
                                <span ng-if="!subCategoryParameters.test_result"><span>
                            </div>
                            <div class="col-md-2 col-xs-2 bord">
                                <span ng-if="subCategoryParameters.claim_value ">
                                    <span ng-if="subCategoryParameters.claim_value">[[subCategoryParameters.claim_value]]</span>
                                    <span ng-if="subCategoryParameters.claim_value_unit "> [[subCategoryParameters.claim_value_unit | capitalizeAll]]</span>
                                </span>

                            </div>
                            <div class="col-md-2 col-xs-2 bord">
                                <span ng-if="subCategoryParameters.requirement_from_to">[[subCategoryParameters.requirement_from_to]]
                                    &nbsp;<span ng-if="subCategoryParameters.claim_value_unit "> [[subCategoryParameters.claim_value_unit | capitalizeAll]]</span>
                                </span>
                            </div>
                            <div class="col-md-2 col-xs-2 bord">
                                <span ng-if="subCategoryParameters.method_name">[[subCategoryParameters.method_name]]</span>
                                <span ng-if="!subCategoryParameters.method_name"><span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid font11" ng-if="orderParametersList.length && viewOrderReport.order_nabl_os_remark_scope">Remarks&nbsp;:&nbsp;'&#x2A;' represents categories/test parameters not covered under NABL&nbsp;&#124;&nbsp;'&#x2A;&#x2A;' represents outsource sample</div>

        <!--view error notes-->
        <div class="report-note alert-danger col-md-12 col-xs-12 mrgn_20 mT20 mB20" ng-if="viewOrderReport.note.length">
            <div class="col-sm-1 col-xs-4 uprcase">NOTE:</div>
            <div class="col-sm-10 col-xs-8">
                <span ng-if="viewOrderReport.note" ng-bind-html="viewOrderReport.note"></span>
            </div>
        </div>
        <!--/view error notes-->

        <!--Add Notes and Remarks-->
        <div class="col-md-12 col-xs-12">
            <div ng-if="noteRemarkReportOptions" ng-repeat="(key, values) in noteRemarkReportOptions track by $index" class="col-md-12 col-xs-12 mrgn_20 mT20 mB20">
                <div ng-if="key == 'Notes'" class="col-sm-12 col-xs-12">
                    <div class="col-sm-3 col-xs-3 upr-case">[[key]] :</div>
                    <div class="col-sm-9 col-xs-9">
                        <select class="form-control" name="note_value">
                            <option value="">---Select Note----</option>
                            <option ng-selected="viewOrderReport.note_value == optionValue" ng-value="optionValue" ng-repeat="optionValue in values">[[optionValue]]</option>
                        </select>
                    </div>
                </div>
                <div ng-if="key == 'Remarks'" class="col-sm-12 col-xs-12">
                    <div class="col-sm-3 col-xs-3 upr-case">[[key]] :<em class="asteriskRed">*</em></div>
                    <div class="col-sm-7 col-xs-7">
                        <select class="form-control" name="remark_value">
                            <option value="">---Select Remarks----</option>
                            <option ng-selected="viewOrderReport.remark_value == optionValue" ng-value="optionValue" ng-repeat="optionValue in values">[[optionValue]]</option>
                        </select>
                    </div>
                    <div class="col-sm-2 col-xs-2">
                        <select class="form-control" name="test_standard_value" ng-model="viewOrderReport.test_standard_value.selectedOption" ng-options="item.name for item in testStandardsList track by item.name">
                            <option value="">Select Standard</option>
                        </select>
                    </div>
                </div>
                <div ng-if="key == 'NoteWithInput'" class="col-sm-12 col-xs-12">
                    <div class="col-sm-3 col-xs-3 upr-case">Notes :</div>
                    <div class="col-sm-9 col-xs-9"><input type="text" name="note_value" ng-modal="viewOrderReport.note_value" ng-value="viewOrderReport.note_value" class="form-control" placeholder="Enter Note"></div>
                </div>
                <div ng-if="key == 'stabilityRemarkWithInput' && viewOrderReport.stb_prototype_no" class="col-sm-12 col-xs-12">
                    <div class="col-sm-3 col-xs-3 upr-case">Stability Remark :<em class="asteriskRed">*</em></div>
                    <div class="col-sm-9 col-xs-9"><input type="text" name="stability_remark_value" ng-modal="viewOrderReport.stability_remark_value" ng-value="viewOrderReport.stability_remark_value" class="form-control" placeholder="Enter stability remark value"></div>
                </div>
            </div>
        </div>
        <!--/Add Notes and Remarks-->

        <!--ReportDate Date Picker In case of Pharma-->
        <div class="col-md-12 col-xs-12 mT20" ng-if="viewOrderReport.remark_value && viewOrderReport.isBackDateBookingAllowed == 1">
            <div class="col-sm-12 col-xs-12" ng-init="funGetReviewReportDateInit()">
                <div class="col-sm-3 col-xs-3 upr-case">Report Date<em class="asteriskRed">*</em></div>
                <div class="col-sm-9 col-xs-9">
                    <input type="text" id="report_date" ng-model="viewOrderReport.report_date" ng-value="viewOrderReport.report_date" name="report_date" class="form-control bgWhite" placeholder="Report Date">
                    <input type="hidden" id="back_report_date" ng-model="viewOrderReport.isBackDateBookingAllowed" ng-value="viewOrderReport.isBackDateBookingAllowed" name="back_report_date">
                </div>
            </div>
        </div>
        <!--/ReportDate DatePicket Incase of Pharma-->

        <!--Name and Sinature-->
        <div class="col-md-12 col-xs-12 mT30 mB30" ng-if="viewOrderReport.status >= 4 && viewOrderReport.report_microbiological_name">
            <div class="col-sm-4 col-xs-4 text-center mrgn_20">
                <p ng-if="viewOrderReport.report_microbiological_sign" style="width:100%;"><img ng-if="viewOrderReport.report_microbiological_sign_path" ng-if="viewOrderReport.report_microbiological_sign_path" height="40px" width="100px" style="margin:-40px 0 0;" ng-alt="[[viewOrderReport.report_microbiological_name]]" ng-src="[[viewOrderReport.report_microbiological_sign_path]]" /></p>
                <!--<h5 ng-if="viewOrderReport.incharge_reviewing_date">[[viewOrderReport.incharge_reviewing_date]]</h5>-->
                <p ng-if="viewOrderReport.report_microbiological_name" style="font-size:10px;width:100%;">[[viewOrderReport.report_microbiological_name]]</p>
                <h4 ng-if="viewOrderReport.report_microbiological_name">[Tech Manager (Micro.)]</h4>
            </div>
            <div class="col-sm-4 col-xs-4 text-center mrgn_20">&nbsp;</div>
            <div class="col-sm-4 col-xs-4 text-right mrgn_20">&nbsp;</div>
        </div>
        <!--Name and Sinature-->

        <div class="col-md-12 col-xs-12 dis_flex test_foot mT30" style="padding:0">
            <div class="col-md-3 col-sm-3 col-xs-3">
                <h4 class="font15"><b>Report Date</b> :<span ng-if="viewOrderReport.report_date">[[viewOrderReport.report_date]]</span></h4>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 text-center">
                <!--Save Button-->
                <div class="col-md-12 col-xs-12 hideContentOnPdf">
                    <div class="col-sm-12 col-xs-12 text-center mrgn_20">
                        <input type="hidden" name="product_category_id" ng-model="viewOrderReport.product_category_id" ng-value="viewOrderReport.product_category_id">
                        <input type="hidden" name="report_id" ng-model="viewOrderReport.order_id" ng-value="viewOrderReport.order_id">
                        <input type="hidden" name="report_file_name" id="report_file_name" ng-model="viewOrderReport.report_file_name" ng-value="viewOrderReport.report_file_name">
                        <!----action report by reporter----->
                        <span ng-if="viewOrderReport.status==4">
                            <span ng-if="{{defined('IS_REPORTER') && IS_REPORTER || defined('IS_ADMIN') && IS_ADMIN}}">
                                <!----save test report----->
                                <input type="submit" ng-click="saveFinalReportByReporter(viewOrderReport.order_id,'save')" value="Save" class="btn btn-primary">
                                <!----save test report----->

                                <!----confirm test report----->
                                <input type="submit" ng-if="viewOrderReport.remark_value" name="save_report" ng-click="funConfirmReportStatus(viewOrderReport.order_id,'ByReporter','Are you sure you want to save this record?')" value="Confirm" class="btn btn-primary">
                                <!----confirm test report----->

                                <!----need modification test report----->
                                <input type="submit" ng-if="selectedAnalysisIdArr.length" data-toggle="modal" ng-click="funOpenNeedModificationModal('NeedModificationByReporter')" class="btn btn-primary" value="Need Modification">
                                <!----need modification test report----->
                            </span>
                        </span>

                        <!----action report by reviewer----->
                        <span ng-if="viewOrderReport.status == 5">
                            <span ng-if="{{defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_ADMIN') && IS_ADMIN}}">
                                <!----save test report by reviewer----->
                                <input type="submit" ng-if="!selectedAnalysisIdArr.length" ng-click="saveFinalReportByReviewer(viewOrderReport.order_id,'save')" value="Save" class="btn btn-primary">
                                <!----/save test report by reviewer----->

                                <!----confirm test report by reviewer----->
                                <input type="submit" ng-if="viewOrderReport.remark_value && !selectedAnalysisIdArr.length" name="save_report" ng-click="funConfirmReportStatus(viewOrderReport.order_id,'ByReviewer','Are you sure you want to save this record?')" value="Confirm" class="btn btn-primary">
                                <!----/confirm test report by reviewer----->

                                <!----need modification test report by reviewer----->
                                <input type="submit" ng-if="selectedAnalysisIdArr.length" data-toggle="modal" ng-click="funOpenNeedModificationModal('NeedModificationByReviewer')" class="btn btn-primary" value="Need Modification">
                                <!----/need modification test report by reviewer----->
                            </span>
                        </span>
                        <button type="button" class="btn btn-primary hideContentOnPdf" ng-click="backButton()">Back</button>
                    </div>
                </div>
                <!--Save Button-->
            </div>

            <div class="col-md-3 col-sm-3 col-xs-3 text-right"></div>
        </div>
        <!-- /part C div ends here -->
    </div>

    <!--Popup Div-->
    <span ng-if="viewOrderReport.status == 4">
        <span ng-if="{{defined('IS_REPORTER') && IS_REPORTER || defined('IS_ADMIN') && IS_ADMIN}}">
            @include('sales.templates.pharma.reports.reporter_and_reviewer.needModificationByReporter')
        </span>
    </span>
    <span ng-if="viewOrderReport.status == 5">
        <span ng-if="{{defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_ADMIN') && IS_ADMIN}}">
            @include('sales.templates.pharma.reports.reporter_and_reviewer.needModificationByReviewer')
        </span>
    </span>
    <!--/Popup Div-->
</form>

<style>
    .font_lable {
        width: 30%;
        float: left;
        margin-left: 4px;
    }

    .table {
        font-size: 14px !important;
    }

    .bold {
        font-weight: 600;
    }

    .table td {
        padding: 3px !important;
        border-color: #000;
    }

    .bodr_top {
        border-top: 0px !important;
    }

    .bodr_bottom {
        border-bottom: 0px !important;
    }

    .left_txt {
        border: 1px solid;
        padding: 3px;
        margin-top: 5px;
        font-size: 14px;
        width: auto;
        float: left;
    }
</style>
<form name="orderReportFormByReporter" novalidate>
    <!-- container start here -->
    <div class="container pdng-20 botm-table" style="padding:10px;border: 1px solid;">
        <!-- non editable part A container start here -->
        <div ng-if="viewNonEditablePartA" ng-if="{{defined('IS_ADMIN') && IS_ADMIN || defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE  }}" class="col-md-12">
            <table class="table table-bordered" style="margin:0;">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <span class="bold samplename">Sample Name</span>
                            <span class="bold">:</span>
                            <span class="bold text-value" ng-if="viewOrderReport.sample_description">[[viewOrderReport.sample_description]]</span>
                            <span class="text-value" ng-if="!viewOrderReport.sample_description">-</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="bold font_lable">Supplied By</span><span class="bold">:</span>
                            <span class="text-value" ng-if="viewOrderReport.supplied_by">[[viewOrderReport.supplied_by]]</span>
                            <span class="text-value" ng-if="!viewOrderReport.supplied_by"></span>
                        </td>
                        <td>
                            <span class="bold font_lable">Report Date</span><span class="bold">:</span>
                            <span ng-if="!viewOrderReport.report_date"> </span>
                            <span ng-if="viewOrderReport.report_date"> [[viewOrderReport.report_date]]</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="width65">
                            <span class="bold font_lable">Manufactured By</span><span class="bold">:</span>
                            <span class="text-value" ng-if="viewOrderReport.manufactured_by">[[viewOrderReport.manufactured_by]]</span>
                            <span class="text-value" ng-if="!viewOrderReport.manufactured_by"></span>
                        </td>
                        <td>
                            <span class="bold font_lable">Booking Code</span><span class="bold">:</span>
                            <span class="text-value" ng-if="viewOrderReport.order_no">[[viewOrderReport.order_no]]</span>
                            <span class="text-value" ng-if="!viewOrderReport.order_no"></span>
                            <span ng-if="viewOrderReport.is_amended_no">-[[viewOrderReport.is_amended_no]]</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:0px!important;">
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
                                    <td colspan="2" class="bodr_bottom border_left">
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
                                    <td class="width50 border_bottom"></td>
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
                                    <td colspan="2" class=" border_left">
                                        <span class="bold editableSampleName">Address</span>
                                        <span class="bold">:</span>
                                        <span class="text-value" ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]] - [[viewOrderReport.city_name]] ( [[viewOrderReport.state_name]] )</span>
                                        <span class="text-value" ng-if="!viewOrderReport.customer_name"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bodr_top width50 border_bottom border_left">
                                        <span class="bold font_lable">Mfg. Lic. No.</span>
                                        <span class="bold">:</span>
                                        <span class="text-value" ng-if="viewOrderReport.mfg_lic_no">[[viewOrderReport.mfg_lic_no]]</span>
                                        <span class="text-value" ng-if="!viewOrderReport.mfg_lic_no"></span>
                                    </td>
                                    <td class="width50 border_bottom  border_right"><span class="bold font_lable">Party Ref. No</span>
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
                                    <td class=" bodr_top">
                                        <span class="bold font_lable">Batch No</span>
                                        <span class="bold">:</span>
                                        <span class="text-value" ng-if="viewOrderReport.batch_no">[[viewOrderReport.batch_no]]</span>
                                        <span class="text-value" ng-if="!viewOrderReport.batch_no"></span>
                                    </td>
                                    <td class=" bodr_top">
                                        <span class="bold font_lable">Batch Size</span>
                                        <span class="bold">:</span>
                                        <span class="text-value" ng-if="viewOrderReport.batch_size">[[viewOrderReport.batch_size]]</span>
                                        <span class="text-value" ng-if="!viewOrderReport.batch_size"></span>
                                    </td>
                                    <td>
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
            </table>
        </div>
        <!--- /non editable part A container end here -->

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
                    <h4 class="text-center f_weight">[[viewOrderReport.stability_note]]</h4>
                </div>
            </div>
            <div class="container-fluid pdng-20 botm-table mT30" id="foorPartC">
                <div class="row" style="padding:0">
                    <div class="col-md-12 col-xs-12 bord">
                        <div class="col-sm-12 col-xs-12 report">
                            <div class="col-sm-1 col-xs-2 upr-case"></div>
                            <div class="col-sm-11  col-xs-10 upr-case"><span></span>TEST RESULTS</div>
                        </div>
                    </div>
                </div>
                <div class="row text-center" style="padding:0">
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

                <div class="row" ng-repeat="categoryParameters in orderParametersList">
                    <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                        <div class="col-md-1 col-xs-1 bord"><b>[[$index+1]].</b></div>
						<div class="col-md-11 col-xs-11 bord">
							<div class="col-sm-12 col-xs-12 text-left" ng-hide="(categoryParameters.categoryName | capitalize)=='Description' && defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE">
								<span class="text-left"><strong>[[categoryParameters.categoryName]]</strong></span>
								<span class="text-left" ng-if="categoryParameters.categoryNameSymbol"><strong ng-bind-html="categoryParameters.categoryNameSymbol"></strong></span>
							</div>
						</div>
                    </div>
                    <div ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">

                        <div ng-hide="(subCategoryParameters.test_parameter_name | capitalize)=='Description' && defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE">
                            <div ng-if="subCategoryParameters.description.length">
                                <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                                    <div class="col-md-1 col-xs-1 bord">[[subCategoryParameters.charNumber]])</div>
                                    <div class="col-md-3 col-xs-3 bord text-left">
                                        <span ng-if="subCategoryParameters.test_parameter_name" class="txt-left" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
                            			<span ng-if="subCategoryParameters.non_nabl_parameter_symbol" ng-bind-html="subCategoryParameters.non_nabl_parameter_symbol"></span>
                                    </div>
                                    <div class="col-md-6 col-xs-6 bord text-justify">
                                        <span ng-if="subCategoryParameters.description">[[subCategoryParameters.description]]</span>
                                        <span ng-if="!subCategoryParameters.description"><span>
                                    </div>
                                    <div class="col-md-2 col-xs-2 bord">
                                        <span class="resultCheckbox mL20" ng-if="subCategoryParameters.test_result">
                                            <input type="hidden" ng-if="subCategoryParameters.equipment_type_id" ng-model="equipment_type_id_[[$index]]" id="equipment_type_id_[[subCategoryParameters.analysis_id]]" name="equipment_type_id[]" ng-value="subCategoryParameters.equipment_type_id">
                                            <input type="checkbox" ng-modal="analysis_id_[[$index]]" id="analysis_id_[[subCategoryParameters.analysis_id]]" ng-click="funSelectedAnalysisIdArr(subCategoryParameters.analysis_id)" name="analysis_id[]" ng-value="subCategoryParameters.analysis_id">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div ng-if="!subCategoryParameters.description.length">
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
                                <div class="col-md-2 col-xs-2 bord">
                                    [[subCategoryParameters.requirement_from_to]]
                                </div>
                                <div class="col-md-2 col-xs-2 bord">
                                    <span class="resultValue" ng-if="subCategoryParameters.test_result">[[subCategoryParameters.test_result]]</span>
                                    <span class="resultCheckbox mL20" ng-if="subCategoryParameters.test_result">
                                        <input type="hidden" ng-if="subCategoryParameters.equipment_type_id" ng-model="equipment_type_id_[[$index]]" id="equipment_type_id_[[subCategoryParameters.analysis_id]]" name="equipment_type_id[]" ng-value="subCategoryParameters.equipment_type_id">
                                        <input type="checkbox" ng-modal="analysis_id_[[$index]]" id="analysis_id_[[subCategoryParameters.analysis_id]]" ng-click="funSelectedAnalysisIdArr(subCategoryParameters.analysis_id)" name="analysis_id[]" ng-value="subCategoryParameters.analysis_id">
                                    </span>
                                    <span ng-if="!subCategoryParameters.test_result"><span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- part C div start here -->

        <!--Name and Sinature-->
        <div class="col-md-12 col-xs-12 mT30" ng-if="viewOrderReport.status >= 4 && viewOrderReport.report_microbiological_name">
            <div class="col-md-12 col-xs-12 mT30">
                <div class="col-sm-4 col-xs-4 text-center mrgn_20">
                    <p ng-if="viewOrderReport.report_microbiological_sign" style="width:100%;"><img ng-if="viewOrderReport.report_microbiological_sign_path" height="40px" width="100px" style="margin:-40px 0 0;" ng-alt="[[viewOrderReport.report_microbiological_name]]" ng-src="[[viewOrderReport.report_microbiological_sign_path]]" /></p>
                    <h5 ng-if="viewOrderReport.incharge_reviewing_date">[[viewOrderReport.incharge_reviewing_date]]</h5>
                    <p ng-if="viewOrderReport.report_microbiological_name" style="font-size:10px;width:100%;">[[viewOrderReport.report_microbiological_name]]</p>
                    <h4 ng-if="viewOrderReport.report_microbiological_name">[Tech Manager (Micro.)]</h4>
                </div>
                <div class="col-sm-4 col-xs-4 text-center mrgn_20">&nbsp;</div>
                <div class="col-sm-4 col-xs-4 text-right mrgn_20">&nbsp;</div>
            </div>
        </div>
        <!--Name and Sinature-->

        <div class="col-md-12 col-xs-12 dis_flex test_foot mT20" style="padding:0">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <!--Save Button-->
                <div class="col-md-12 col-xs-12 hideContentOnPdf">
                    <div class="col-sm-12 col-xs-12 text-center mrgn_20">
                        <input type="hidden" name="product_category_id" ng-model="viewOrderReport.product_category_id" ng-value="viewOrderReport.product_category_id">
                        <input type="hidden" name="report_id" ng-model="viewOrderReport.order_id" ng-value="viewOrderReport.order_id">
                        <input type="hidden" name="report_file_name" id="report_file_name" ng-model="viewOrderReport.report_file_name" ng-value="viewOrderReport.report_file_name">
                        <input type="hidden" name="oipd_incharge_id" id="order_incharge_id" ng-model="viewOrderReport.oid_id" ng-value="viewOrderReport.oid_id">
                        <span ng-if="{{defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE || defined('IS_ADMIN') && IS_ADMIN}}">
                            <span ng-if="{{defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE}}">
                                <!--confirm test report-->
                                <input type="submit" ng-if="!selectedAnalysisIdArr.length && viewOrderReport.incharge_confirm_status == '1'" name="save_report" ng-click="funConfirmReportStatus(viewOrderReport.order_id,'BySectionIncharge','Are you sure you want to save this record?')" value="Confirm" class="btn btn-primary">
                                <!--confirm test report-->
                            </span>
                            <span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
                                <!--confirm test report-->
                                <input type="submit" ng-if="!selectedAnalysisIdArr.length && viewOrderReport.status == '4'" name="save_report" ng-click="funConfirmReportStatus(viewOrderReport.order_id,'BySectionIncharge','Are you sure you want to save this record?')" value="Confirm" class="btn btn-primary">
                                <!--confirm test report-->
                            </span>
                            <span>
                                <!--need modification test report-->
                                <input type="submit" ng-if="selectedAnalysisIdArr.length" data-toggle="modal" ng-click="funOpenNeedModificationModal('NeedModificationBySectionIncharge')" class="btn btn-primary" value="Need Modification">
                                <!--need modification test report-->
                            </span>
                        </span>
                        <button type="button" class="btn btn-primary hideContentOnPdf" ng-click="backButton()">Back</button>
                    </div>
                </div>
                <!--Save Button-->
            </div>
        </div>
        <!-- /part C div start here -->
    </div>

    <span ng-if="viewOrderReport.status">
        <span ng-if="{{defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE || defined('IS_ADMIN') && IS_ADMIN}}">
            @include('sales.templates.ayurvedic.reports.section_incharge.needModificationBySectionIncharge')
        </span>
    </span>
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

    .left_txt {
        border: 1px solid;
        padding: 3px;
        margin-top: 5px;
        font-size: 14px;
        width: auto;
        float: left;
    }

</style>

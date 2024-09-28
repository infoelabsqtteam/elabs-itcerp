<form name="orderReportFormByReporter" id="orderReportFormByReporter" novalidate>
    <!-- part A div start here -->
    <div class="container-fluid pdng-20 botm-table" id="foorPartA">
        <div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
            <div class="col-md-6 col-xs-6 bord">
                <div class="col-sm-12 col-xs-12 report">
                    <div class="col-sm-6 col-xs-5 upr-case" ng-if="viewNonEditTestStandard">Test Report as per IS : </div>
                    <div class="col-sm-6 col-xs-7 upr-case" ng-if="viewNonEditTestStandard">[[viewOrderReport.test_std_name]]</div>
                    <div class="col-sm-6 col-xs-5 upr-case" ng-if="viewEditTestStandard">Test Report as per IS : <em class="asteriskRed">*</em></div>
                    <div class="col-sm-6 col-xs-7" ng-if="viewEditTestStandard">
                        <select class="hideContentOnPdf form-control" name="test_standard" ng-model="viewOrderReport.test_standard_id.selectedOption" id="test_standard" ng-required='true' ng-options="item.test_std_name for item in testStandardList track by item.test_std_id">
                            <option value="">Select Test Standard</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-6 bord">
                <div class="col-sm-12 col-xs-12 report">
                    <div class="col-sm-6  col-xs-5 upr-case">With Amendment No.(s) :</div>
                    <div class="col-sm-6  col-xs-7 upr-case">
                        <span ng-if="viewNonEditAmendmentWith">
                            <span ng-if="viewOrderReport.with_amendment_no">[[viewOrderReport.with_amendment_no]]</span>
                            <span ng-if="!viewOrderReport.with_amendment_no">-</span>
                            <input class="form-control" ng-model="viewOrderReport.with_amendment_no" type="hidden" name="with_amendment_no" ng-value="viewOrderReport.with_amendment_no">
                        </span>
                        <span ng-if="viewEditAmendmentWith">
                            <input class="form-control" ng-model="viewOrderReport.with_amendment_no" type="text" name="with_amendment_no">
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-6 bord" ng-if="viewEditAmendmentNo && viewOrderReport.orderAmendStatus =='1'">
                <div class="col-sm-12 col-xs-12 report">
                    <div class="col-sm-6 col-xs-6 upr-case">Amendment No:</div>
                    <div class="col-sm-6 col-xs-6 upr-case">
                        <input class="" ng-model="viewOrderReport.amended_no" type="checkbox" name="is_amended_no" value="A" ng-checked="checkedAmendmentNo" ng-disabled="disabledAmendmentNo">
                        <input class="" type="hidden" name="amended_status" value="[[viewOrderReport.orderAmendStatus]]">
                    </div>
                </div>
            </div>
        </div>
        <!---nonEditablePartA------>
        <div id="nonEditablePartA" ng-if="viewNonEditablePartA">
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-12 col-xs-12 bord">
                    <div class="col-sm-12 col-xs-12 report">
                        <div class="col-sm-1 col-xs-4 upr-case">PART A</div>
                        <div class="col-sm-11 col-xs-8 upr-case"><span>:</span> PARTICULARS OF SAMPLE SUBMITTED
                            <span ng-if="viewOrderReport.status == 5">
                                <span ng-if="{{defined('IS_REPORTER') && IS_REPORTER || defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_ADMIN') && IS_ADMIN}}">
                                    <a ng-click="toggelEditReportPartAForm([[viewOrderReport.product_category_id]],viewOrderReport.test_standard,viewOrderReport.order_id);" class="cursor-pointer editLinkCss">Edit</a>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">a)</div>
                <div class="col-md-5 col-xs-5 bord">Nature of Sample</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.sample_description">[[viewOrderReport.sample_description]]</span>
                    <span ng-if="!viewOrderReport.sample_description"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">b)</div>
                <div class="col-md-5 col-xs-5 bord">Grade / Variety / Type / Class / Size etc.</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="!showGradeType" ng-model="grade_type" ng-click="show_grade_type()">
                        <span ng-if="viewOrderReport.grade_type">[[viewOrderReport.grade_type]]</span>
                        <span ng-if="!viewOrderReport.grade_type"></span>
                        <input ng-model="viewOrderReport.grade_type" type="hidden" ng-bind="viewOrderReport.grade_type" ng-value="viewOrderReport.grade_type" value="[[viewOrderReport.grade_type]]" name="grade_type">
                    </span>
                    <span ng-if="showGradeType">
                        <input class="form-control width35" ng-model="viewOrderReport.grade_type" type="text" name="grade_type" ng-bind="viewOrderReport.grade_type" ng-value="viewOrderReport.grade_type" value="[[viewOrderReport.grade_type]]">
                    </span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">c)</div>
                <div class="col-md-5 col-xs-5 bord">Brand Name</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.brand_type">[[viewOrderReport.brand_type]]</span>
                    <span ng-if="!viewOrderReport.brand_type"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">d)</div>
                <div class="col-md-5 col-xs-5 bord">Declared Values,if any</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.declared_values">[[viewOrderReport.declared_values]]</span>
                    <span ng-if="!viewOrderReport.declared_values"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">e)</div>
                <div class="col-md-5 col-xs-5 bord">Code No.</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.barcode"><img ng-src="[[viewOrderReport.barcode]]" /></span>
                    <span ng-if="!viewOrderReport.barcode"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">f)</div>
                <div class="col-md-5 col-xs-5 bord">Batch Number</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.batch_no">[[viewOrderReport.batch_no]]</span>
                    <span ng-if="!viewOrderReport.batch_no"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">g)</div>
                <div class="col-md-5 col-xs-5 bord">D.O.M</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.mfg_date">[[viewOrderReport.mfg_date]]</span>
                    <span ng-if="!viewOrderReport.mfg_date"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">h)</div>
                <div class="col-md-5 col-xs-5 bord">Date of Expiry</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.expiry_date">[[viewOrderReport.expiry_date]]</span>
                    <span ng-if="!viewOrderReport.expiry_date"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">i)</div>
                <div class="col-md-5 col-xs-5 bord">Sample Quantity</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.sample_qty">[[viewOrderReport.sample_qty]]</span>
                    <span ng-if="!viewOrderReport.sample_qty"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">j)</div>
                <div class="col-md-5 col-xs-5 bord">Batch Size</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.batch_size">[[viewOrderReport.batch_size]]</span>
                    <span ng-if="!viewOrderReport.batch_size"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">k)</div>
                <div class="col-md-5 col-xs-5 bord">Mode of Packing</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.packing_mode">[[viewOrderReport.packing_mode]]</span>
                    <span ng-if="!viewOrderReport.packing_mode"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">l)</div>
                <div class="col-md-5 col-xs-5 bord">Date of Receipt</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.order_date">[[viewOrderReport.order_date]]</span>
                    <span ng-if="!viewOrderReport.order_date"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">m)</div>
                <div class="col-md-5 col-xs-5 bord">Date of Start</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.analysis_start_date">[[viewOrderReport.analysis_start_date]]</span>
                    <span ng-if="!viewOrderReport.analysis_start_date"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">n)</div>
                <div class="col-md-5 col-xs-5 bord">Date of Completion</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.orderAmendStatus && viewOrderReport.is_amended_no">[[viewOrderReport.reviewing_date ? viewOrderReport.reviewing_date : viewOrderReport.finalizing_date]]</span>
                    <span ng-if="viewOrderReport.analysis_completion_date && !viewOrderReport.is_amended_no" ng-bind="viewOrderReport.analysis_completion_date"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">o)</div>
                <div class="col-md-5 col-xs-5 bord">BIS Seal (Intact/Not Intact/Unsealed)</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.is_sealed==0">Unsealed</span>
                    <span ng-if="viewOrderReport.is_sealed==1">Sealed</span>
                    <span ng-if="viewOrderReport.is_sealed==2">Intact</span>
                    <span ng-if="!viewOrderReport.is_sealed==3"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">p)</div>
                <div class="col-md-5 col-xs-5 bord">IO'S Signature (Signed/Unsigned)</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.is_signed==0">Unsigned</span>
                    <span ng-if="viewOrderReport.is_signed==1">Signed</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">q)</div>
                <div class="col-md-5 col-xs-5 bord">Any Other Information</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.remarks">[[viewOrderReport.remarks]]</span>
                    <span ng-if="!viewOrderReport.remarks"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">r)</div>
                <div class="col-md-5 col-xs-5 bord">Submitted By</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]] - [[viewOrderReport.city_name]] ( [[viewOrderReport.state_name]] )</span>
                    <span ng-if="!viewOrderReport.customer_name"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">s)</div>
                <div class="col-md-5 col-xs-5 bord">Manufactured By</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.manufactured_by">[[viewOrderReport.manufactured_by]]</span>
                    <span ng-if="!viewOrderReport.manufactured_by"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">t)</div>
                <div class="col-md-5 col-xs-5 bord">Supplied By</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.supplied_by">[[viewOrderReport.supplied_by]]</span>
                    <span ng-if="!viewOrderReport.supplied_by"></span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">u)</div>
                <div class="col-md-5 col-xs-5 bord">Sample Condition</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.sample_condition">[[viewOrderReport.sample_condition]]</span>
                    <span ng-if="!viewOrderReport.sample_condition"></span>
                </div>
            </div>
        </div>
        <!---nonEditablePartA------>

        <!---editablePartA------>
        <span ng-if="viewOrderReport.status == 5">
            <span ng-if="{{defined('IS_REPORTER') && IS_REPORTER || defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_ADMIN') && IS_ADMIN}}">
                @include('sales.templates.building.reports.reporter_and_reviewer.editablePartA')
            </span>
        </span>
        <!---editablePartA------>
    </div>
    <!-- /part A div end here -->

    <!-- part B div start here -->
    <div class="container-fluid pdng-20 botm-table" id="helmetReportPartB">
        <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
            <div class="col-md-12 col-xs-12 bord">
                <div class="col-sm-12 col-xs-12 report">
                    <div class="col-sm-1 col-xs-4 upr-case">Part B</div>
                    <div class="col-sm-11  col-xs-8 upr-case"><span>:</span>SUPPLIMENTARY INFORMATIONS <em class="red">*</em></div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
            <div class="col-md-12 col-xs-12 bord">
                <div class="col-sm-12 col-xs-12 report border-bottom-dotted" style="font-weight:normal!important">
                    <div class="col-sm-1 col-xs-1">a</div>
                    <div class="col-sm-5 col-xs-8">Reference to sampling procedure, whenever applicable.</div>
                    <div class="col-sm-6 col-xs-3">
                        <span class="float-left"> : </span>
                        <select class="hideContentOnPdf form-control width35" name="ref_sample_value" ng-model="viewOrderReport.ref_sample_value.selectedoption" id="ref_sample_value" ng-required='true' ng-options="item.name for item in reportOptionsList track by item.id ">
                            <option value="">Select Option</option>
                        </select>
                        <span ng-messages="orderReportFormByReporter.ref_sample_value.$error" ng-if='orderReportFormByReporter.ref_sample_value.$dirty || orderReportFormByReporter.$submitted' role="alert">
                            <span ng-message="required" class="error">This field is required</span>
                        </span>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12 report border-bottom-dotted" style="font-weight:normal!important">
                    <div class="col-sm-1 col-xs-1">b</div>
                    <div class="col-sm-5 col-xs-8">
                        Supporting documents for the measurement taken and results derived like graphs, tables, sketches and/or photographs as appropriate to test reports, if any
                    </div>
                    <div class="col-sm-6 col-xs-3"><span class="float-left"> : </span>
                        <select class="hideContentOnPdf form-control width35" name="result_drived_value" ng-model="viewOrderReport.result_drived_value.selectedoption" id="result_drived_value" ng-required='true' ng-options="item.name for item in reportOptionsList track by item.id">
                            <option value="">Select Option</option>
                        </select>
                        <span ng-messages="orderReportFormByReporter.result_drived_value.$error" ng-if='orderReportFormByReporter.result_drived_value.$dirty || orderReportFormByReporter.$submitted' role="alert">
                            <span ng-message="required" class="error">This field is required</span>
                        </span>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12 report" style="font-weight:normal!important">
                    <div class="col-sm-1 col-xs-1">c</div>
                    <div class="col-sm-5 col-xs-8">Deviation from the test methods as prescribed in relevent ISS/WORK instruments, if any</div>
                    <div class="col-sm-6 col-xs-3">
                        <span class="float-left"> : </span>
                        <span>
                            <select class="hideContentOnPdf form-control width35" name="deviation_value" ng-model="viewOrderReport.deviation_value.selectedoption" id="deviation_value" ng-required='true' ng-options="item.name for item in reportOptionsList track by item.id ">
                                <option value="">Select Option</option>
                            </select>
                            <span ng-messages="orderReportFormByReporter.deviation_value.$error" ng-if='orderReportFormByReporter.deviation_value.$dirty || orderReportFormByReporter.$submitted' role="alert">
                                <span ng-message="required" class="error">This field is required</span>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /part B div start here -->

    <!-- part C div start here -->
    <div class="container-fluid pdng-20 botm-table mT30" id="foorPartC">
        <div class="col-md-12 col-xs-12 botm-row mrgn_20 dis_flex" style="padding:0">
            <div class="col-md-12 col-xs-12 bord">
                <div class="col-sm-12 col-xs-12 report">
                    <div class="col-sm-1 col-xs-2 upr-case">Part C</div>
                    <div class="col-sm-11  col-xs-10 upr-case"><span>:</span>TEST RESULTS</div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
            <div class="col-md-1 col-xs-1 bord">S.No</div>
            <div class="col-md-3 col-xs-3 bord">Test Parameter</div>
            <div class="col-md-2 col-xs-2 bord">Inst. Used</div>
            <div class="col-md-2 col-xs-2 bord">Method</div>
            <div class="col-md-2 col-xs-2 bord">Requirement</div>
            <div class="col-md-2 col-xs-2 bord">Result</div>
        </div>
        <div class="col-md-12 col-xs-12 botm-row dis_flex " style="padding:0">
            <div class="col-md-12 col-xs-1 mB5"> <b>Test Details</b> :&nbsp;&nbsp;
                <span ng-if="viewOrderReport.header_note">[[viewOrderReport.header_note | capitalize]]</span>
                <span ng-if="!viewOrderReport.header_note"></span>
            </div>
        </div>

        <!--ORDER PARAMETERS LIST-->
        <div ng-if="orderParametersList">

            <!--DESCRIPTION WISE PARAMETER LIST-->
            <div ng-if="orderParametersList.descriptionWiseParameterList.length" ng-repeat="descriptionWiseParameters in orderParametersList.descriptionWiseParameterList">

                <!--CATEGORY NAME-->
                <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                    <div class="col-md-1 col-xs-1 bord"></div>
                    <div class="col-md-11 col-xs-11 bord">
                        <div class="col-sm-12 col-xs-12 text-left">
                            <span class="text-left"><strong>[[descriptionWiseParameters.categoryName]]</strong></span>
                            <span class="text-left" ng-if="descriptionWiseParameters.categoryNameSymbol"><strong ng-bind-html="descriptionWiseParameters.categoryNameSymbol"></strong></span>
                        </div>
                    </div>
                </div>
                <!--/CATEGORY NAME-->

                <div ng-repeat="descriptionWiseParameterObj in descriptionWiseParameters.categoryParams track by $index">
                    <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                        <div class="col-md-1 col-xs-1 bord"></div>
                        <div class="col-md-3 col-xs-3 bord text-left">
                            <span ng-if="descriptionWiseParameterObj.test_parameter_name" ng-bind-html="descriptionWiseParameterObj.test_parameter_name"></span>
                            <span ng-if="!descriptionWiseParameterObj.test_parameter_name"><span>
                        </div>
                        <div class="col-md-8 col-xs-8 bord text-justify" ng-if="!viewEditPartAForm">
                            <span ng-if="descriptionWiseParameterObj.description">[[descriptionWiseParameterObj.description]]</span>
                            <span ng-if="!descriptionWiseParameterObj.description"><span>
                        </div>
                        <div class="col-md-8 col-xs-8 bord text-justify" ng-if="viewEditPartAForm">
                            <input class="form-control" ng-modal="viewEditOrderReport.description" id="analysis_id_[[descriptionWiseParameterObj.analysis_id]]" type="text" name="orderParameterDetail['[[descriptionWiseParameterObj.analysis_id]]']" placeholder="Description" ng-value="descriptionWiseParameterObj.description" value="[[descriptionWiseParameterObj.description]]">
                        </div>
                    </div>
                </div>
            </div>
            <!--/DESCRIPTION WISE PARAMETER LIST-->

            <!--CATEGORY WISE PARAMETER LIST-->
            <div ng-if="orderParametersList.categoryWiseParameterList.length" ng-repeat="categoryWiseParameters in orderParametersList.categoryWiseParameterList">

                <!--CATEGORY NAME-->
                <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                    <div class="col-md-1 col-xs-1 bord"><b>[[$index+1]].</b></div>
                    <div class="col-md-11 col-xs-11 bord">
                        <div class="col-sm-12 col-xs-12 text-left">
                            <span class="text-left"><strong>[[categoryWiseParameters.categoryName]]</strong></span>
                            <span class="text-left" ng-if="categoryWiseParameters.categoryNameSymbol"><strong ng-bind-html="categoryWiseParameters.categoryNameSymbol"></strong></span>
                        </div>
                    </div>
                </div>
                <!--/CATEGORY NAME-->

                <div ng-repeat="subCategoryParameters in categoryWiseParameters.categoryParams track by $index">
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
                            <span class="resultValue" ng-if="subCategoryParameters.test_result">[[subCategoryParameters.test_result]]</span>
                            <span class="resultCheckbox mL20" ng-if="subCategoryParameters.test_result">
                                <input type="checkbox" ng-modal="analysis_id_[[$index]][[subCategoryParameters.analysis_id]]" id="analysis_id_[[subCategoryParameters.analysis_id]]" ng-click="funSelectedAnalysisIdArr(subCategoryParameters.analysis_id)" name="analysis_id[]" ng-value="subCategoryParameters.analysis_id">
                            </span>
                            <span ng-if="!subCategoryParameters.test_result"><span>
                        </div>
                    </div>
                </div>
            </div>
            <!--/CATEGORY WISE PARAMETER LIST-->

            <!--DISCIPLINE WISE PARAMETER LIST-->
            <div ng-if="orderParametersList.disciplineWiseParametersList.length" ng-repeat="disciplineWiseParameters in orderParametersList.disciplineWiseParametersList">

                <!--DISCIPLINE NAME-->
                <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0" ng-if="disciplineWiseParameters.disciplineHdr.discipline_name">
                    <div class="col-md-1 col-xs-1 bord"></div>
                    <div class="col-md-11 col-xs-11 bord">
                        <div class="col-sm-12 col-xs-12 text-left p0"><strong>Discipline&nbsp;:&nbsp;[[disciplineWiseParameters.disciplineHdr.discipline_name]]</strong></div>
                        <input type="hidden" name="discipline_id[]" id="discipline_id_[[disciplineWiseParameters.disciplineHdr.discipline_id]]" ng-model="viewOrderReport.discipline_id_[[disciplineWiseParameters.disciplineHdr.discipline_id]]" ng-value="disciplineWiseParameters.disciplineHdr.discipline_id">
                    </div>
                </div>
                <!--/DISCIPLINE NAME-->

                <!--GROUP NAME-->
                <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0" ng-if="viewOrderReport.group_dropdown_list.length">
                    <div class="col-md-1 col-xs-1 bord"></div>
                    <div class="col-md-11 col-xs-11 bord">
                        <div class="col-md-2 col-xs-2 mt5"><strong>Select Group</strong><em class="asteriskRed">*</em></div>
                        <div class="col-sm-3 col-xs-3 text-left p0">
                            <select class="form-control" name="group_id['[[disciplineWiseParameters.disciplineHdr.discipline_id]]']" id="group_id_[[disciplineWiseParameters.disciplineHdr.discipline_id]]" ng-model="viewOrderReport.group_id[[disciplineWiseParameters.disciplineHdr.discipline_id]]" ng-options="item.name for item in viewOrderReport.group_dropdown_list track by item.id" ng-init="viewOrderReport.group_id[[disciplineWiseParameters.disciplineHdr.discipline_id]]={id:disciplineWiseParameters.disciplineHdr.group_id}" ng-required='true'>
                                <option value="">Select Group Name</option>
                            </select>
                            <span ng-messages="orderReportFormByReporter.group_id_[[disciplineWiseParameters.disciplineHdr.discipline_id]].$error" ng-if="orderReportFormByReporter.group_id_[[disciplineWiseParameters.disciplineHdr.discipline_id]].$dirty || orderReportFormByReporter.$submitted" role="alert">
                                <span ng-message="required" class="error">Group Name is required</span>
                            </span>
                        </div>
                    </div>
                </div>
                <!--/GROUP NAME-->

                <!--PARAMETER LIST-->
                <div ng-repeat="categoryWiseParameters in disciplineWiseParameters.disciplineDtl">

                    <!--CATEGORY NAME-->
                    <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                        <div class="col-md-1 col-xs-1 bord"><b>[[$index+1]].</b></div>
                        <div class="col-md-11 col-xs-11 bord">
                            <div class="col-sm-12 col-xs-12 text-left p0">
                                <span class="text-left"><strong>[[categoryWiseParameters.categoryName]]</strong></span>
                                <span class="text-left" ng-if="categoryWiseParameters.categoryNameSymbol"><strong ng-bind-html="categoryWiseParameters.categoryNameSymbol"></strong></span>
                            </div>
                        </div>
                    </div>
                    <!--/CATEGORY NAME-->

                    <div ng-repeat="subCategoryParameters in categoryWiseParameters.categoryParams track by $index">
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
                                <span class="resultValue" ng-if="subCategoryParameters.test_result">[[subCategoryParameters.test_result]]</span>
                                <span class="resultCheckbox mL20" ng-if="subCategoryParameters.test_result">
                                    <input type="checkbox" ng-modal="analysis_id_[[$index]][[subCategoryParameters.analysis_id]]" id="analysis_id_[[subCategoryParameters.analysis_id]]" ng-click="funSelectedAnalysisIdArr(subCategoryParameters.analysis_id)" name="analysis_id[]" ng-value="subCategoryParameters.analysis_id">
                                </span>
                                <span ng-if="!subCategoryParameters.test_result"><span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/PARAMETER LIST-->
            </div>
            <!--DISCIPLINE WISE PARAMETER LIST-->
        </div>
        <!--/ORDER PARAMETERS LIST-->

        <div class="col-md-12 col-xs-12 bord-remark" ng-if="orderParametersList.length && viewOrderReport.order_nabl_os_remark_scope">Remarks&nbsp;:&nbsp;'&#x2A;' represents categories/test parameters not covered under NABL&nbsp;&#124;&nbsp;'&#x2A;&#x2A;' represents outsource sample</div>
    </div>
    <!-- /part C div end here -->

    <!-- part D div start here -->
    <div class="container-fluid pdng-20 botm-table" id="foorPartD">
        <div class="col-md-12 col-xs-12 botm-row  bord" style="padding:0">

            <div class="report-note alert-danger col-md-12 col-xs-12 mrgn_20 mT20 mB20" ng-if="viewOrderReport.note.length">
                <div class="col-sm-1 col-xs-4 uprcase">NOTE:</div>
                <div class="col-sm-10 col-xs-8">
                    <span ng-if="viewOrderReport.note" ng-bind-html="viewOrderReport.note"></span>
                </div>
            </div>

            <!--Add Notes and Remarks-->
            <div class="col-md-12 col-xs-12 mB30">
                <div ng-if="noteRemarkReportOptions" ng-repeat="(key, values) in noteRemarkReportOptions track by $index" class="col-md-12 col-xs-12 mrgn_20 mT20 mB20">
                    <div ng-if="key == 'Notes'" class="col-sm-12 col-xs-12">
                        <div class="col-sm-2 col-xs-2 upr-case">[[key]] :</div>
                        <div class="col-sm-10 col-xs-10">
                            <select class="form-control" name="note_value">
                                <option value="">---Select Note----</option>
                                <option ng-selected="viewOrderReport.note_value == optionValue.replace('sample-name',SampleNameScope).replace('test-standard',TestStandardNameScope).replace('amendment-text',AmendmentTextScope)" ng-value="optionValue.replace('sample-name',SampleNameScope).replace('test-standard',TestStandardNameScope).replace('amendment-text',AmendmentTextScope)" ng-repeat="optionValue in values">[[optionValue.replace('sample-name',SampleNameScope).replace('test-standard',TestStandardNameScope).replace('amendment-text',AmendmentTextScope)]]</option>
                            </select>
                        </div>
                    </div>
                    <div ng-if="key == 'Remarks'" class="col-sm-12 col-xs-12">
                        <div class="col-sm-2 col-xs-2 upr-case">[[key]] :<em class="red">*</em></div>
                        <div class="col-sm-10 col-xs-10">
                            <select class="form-control" name="remark_value">
                                <option value="">---Select Remarks----</option>
                                <option ng-selected="viewOrderReport.remark_value == optionValue.replace('sample-name',SampleNameScope).replace('test-standard',TestStandardNameScope).replace('amendment-text',AmendmentTextScope)" ng-value="optionValue.replace('sample-name',SampleNameScope).replace('test-standard',TestStandardNameScope).replace('amendment-text',AmendmentTextScope)" ng-repeat="optionValue in values">[[optionValue.replace('sample-name',SampleNameScope).replace('test-standard',TestStandardNameScope).replace('amendment-text',AmendmentTextScope)]]</option>
                            </select>
                        </div>
                    </div>
                    <div ng-if="key == 'NoteWithInput'" class="col-sm-12 col-xs-12">
                        <div class="col-sm-2 col-xs-2 upr-case">NOTES :</div>
                        <div class="col-sm-10 col-xs-10">
                            <input type="text" ng-value="viewOrderReport.note_value" name="note_value" class="form-control" placeholder="Enter Note">
                        </div>
                    </div>
                    <div ng-if="key == 'stabilityRemarkWithInput' && viewOrderReport.stb_prototype_no" class="col-sm-12 col-xs-12">
                        <div class="col-sm-2 col-xs-2 upr-case">Stability Remark :<em class="asteriskRed">*</em></div>
                        <div class="col-sm-10 col-xs-10"><input type="text" name="stability_remark_value" ng-modal="viewOrderReport.stability_remark_value" ng-value="viewOrderReport.stability_remark_value" class="form-control" placeholder="Enter stability remark value"></div>
                    </div>
                </div>
                <!--ReportDate Date Picker In case of Building-->
                <div class="col-md-12 col-xs-12 mT20" ng-if="viewOrderReport.remark_value && viewOrderReport.isBackDateBookingAllowed == 1">
                    <div class="col-sm-12 col-xs-12" ng-init="funGetReviewReportDateInit()">
                        <div class="col-sm-2 col-xs-2 upr-case">Report Date<em class="asteriskRed">*</em></div>
                        <div class="col-sm-10 col-xs-10">
                            <input type="text" id="report_date" ng-model="viewOrderReport.report_date" ng-value="viewOrderReport.report_date" name="report_date" class="form-control bgWhite" placeholder="Report Date" autocomplete="off">
                            <input type="hidden" id="back_report_date" ng-model="viewOrderReport.isBackDateBookingAllowed" ng-value="viewOrderReport.isBackDateBookingAllowed" name="back_report_date">
                        </div>
                    </div>
                </div>
                <!--/ReportDate DatePicket Incase of Building-->
                <div class="col-sm-4 col-xs-4 text-left mrgn_20 mT30" ng-if="viewOrderReport.status >=4 && !viewOrderReport.report_microbiological_name"">
					<h5>
						<b>Report Date</b> :
						<span ng-if=" !viewOrderReport.report_date"> </span>
                        <span ng-if="viewOrderReport.report_date"> [[viewOrderReport.report_date]]</span>
                    </h5>
                </div>
            </div>
            <!--/Add Notes and Remarks-->

            <div class="col-md-12 col-xs-12 mT30 mB30">
                <div class="col-sm-4 col-xs-4 text-center mrgn_20" ng-if="viewOrderReport.status >= 4 && viewOrderReport.report_microbiological_name">
                    <p ng-if="viewOrderReport.report_microbiological_sign" style="width:100%;"><img ng-if="viewOrderReport.report_microbiological_sign_path" height="40px" width="100px" style="margin:-40px 0 0;" ng-alt="[[viewOrderReport.report_microbiological_name]]" ng-src="[[viewOrderReport.report_microbiological_sign_path]]" /></p>
                    <h5 ng-if="viewOrderReport.incharge_reviewing_date">[[viewOrderReport.incharge_reviewing_date]]</h5>
                    <p ng-if="viewOrderReport.report_microbiological_name" style="font-size:10px;width:100%;">[[viewOrderReport.report_microbiological_name]]</p>
                    <h4 ng-if="viewOrderReport.report_microbiological_name">[Tech Manager (Micro.)]</h4>
                </div>
                <div class="col-sm-4 col-xs-4 text-center mrgn_20">
                    <p ng-if="viewOrderReport.status >= 6" style="width:100%;"><img height="40px" width="100px" style="margin:-40px 0 0;" ng-alt="[[orderTrackRecord.reviewing.user_signature]]" ng-src="{{SITE_URL.SIGN_PATH}}[[orderTrackRecord.reviewing.user_signature]]" /></p>
                    <p ng-if="viewOrderReport.status >= 6" style="font-size:10px;width:100%;">[[orderTrackRecord.reviewing.username]]</p>
                    <h5 ng-if="viewOrderReport.status >= 6">[[viewOrderReport.reviewing_date]]</h5>
                    <h4 ng-if="viewOrderReport.status >= 6">Reviewer</h4>
                </div>
                <div class="col-sm-4 col-xs-4 text-right mrgn_20">
                    <p ng-if="viewOrderReport.status >= 7" style="width:100%;"><img height="40px" width="100px" style="margin:-40px 0px 0px; padding-left: 15px;" ng-alt="[[orderTrackRecord.finalizing.user_signature]]" ng-src="{{SITE_URL.SIGN_PATH}}[[orderTrackRecord.finalizing.user_signature]]" /></p>
                    <h5 ng-if="viewOrderReport.status >= 7">[[viewOrderReport.finalizing_date]]</h5>
                    <h4 ng-if="viewOrderReport.status >= 7">[[orderTrackRecord.finalizing.username]]</h4>
                    <h4 ng-if="viewOrderReport.status >= 7">[Tech Manager]</h4>
                </div>
            </div>

            <div class="col-md-12 col-xs-12 hideContentOnPdf mT20 mB20">
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
                            <input type="submit" ng-if="viewOrderReport.remark_value && viewOrderReport.ref_sample_value && viewOrderReport.result_drived_value && viewOrderReport.deviation_value" name="save_report" ng-click="funConfirmReportStatus(viewOrderReport.order_id,'ByReporter','Are you sure you want to save this record?')" value="Confirm" class="btn btn-primary">
                            <!----confirm test report----->

                            <!----need modification test report----->
                            <input type="submit" ng-if="selectedAnalysisIdArr.length" data-toggle="modal" ng-click="funOpenNeedModificationModal('NeedModificationByReporter')" class="btn btn-primary" value="Need Modification">
                            <!----need modification test report----->
                        </span>
                    </span>

                    <!----action report by reviewer----->
                    <span ng-if="viewOrderReport.status == 5">
                        <span ng-if="{{defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_ADMIN') && IS_ADMIN}}">
                            <span ng-hide="selectedAnalysisIdArr.length">
                                <!----save test report by reviewer----->
                                <input type="submit" ng-click="saveFinalReportByReviewer(viewOrderReport.order_id,'save')" value="Save" class="btn btn-primary">
                                <!----/save test report by reviewer----->

                                <!----confirm test report by reviewer----->
                                <input type="submit" ng-if="viewOrderReport.remark_value && viewOrderReport.ref_sample_value && viewOrderReport.result_drived_value && viewOrderReport.deviation_value" name="save_report" ng-click="funConfirmReportStatus(viewOrderReport.order_id,'ByReviewer','Are you sure you want to save this record?')" value="Confirm" class="btn btn-primary">
                                <!----/confirm test report by reviewer----->
                            </span>

                            <!----need modification test report by reviewer----->
                            <input type="submit" ng-if="selectedAnalysisIdArr.length" data-toggle="modal" ng-click="funOpenNeedModificationModal('NeedModificationByReviewer')" class="btn btn-primary" value="Need Modification">
                            <!----/need modification test report by reviewer----->
                        </span>
                    </span>
                    <button type="button" class="btn btn-primary hideContentOnPdf" ng-click="backButton()">Back</button>
                </div>
            </div>
        </div>
    </div>

    <span ng-if="viewOrderReport.status == 4">
        <span ng-if="{{defined('IS_REPORTER') && IS_REPORTER || defined('IS_ADMIN') && IS_ADMIN}}">
            @include('sales.templates.building.reports.reporter_and_reviewer.needModificationByReporter')
        </span>
    </span>
    <span ng-if="viewOrderReport.status == 5">
        <span ng-if="{{defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_ADMIN') && IS_ADMIN}}">
            @include('sales.templates.building.reports.reporter_and_reviewer.needModificationByReviewer')
        </span>
    </span>

</form>
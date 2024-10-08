<form name="orderReportFormByReporter" id="orderReportFormByReporter" novalidate>
    <!-- part A div start here -->
    <div class="container-fluid pdng-20 botm-table" id="foorPartA">
        <div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
            <div class="col-md-6 col-xs-6 bord">
                <div class="col-sm-12 col-xs-12 report" ng-if="viewNonEditTestStandard">
                    <div class="col-sm-6 col-xs-5 upr-case">Test Report as per IS : </div>
                    <div class="col-sm-6  col-xs-7 upr-case"> [[viewOrderReport.test_std_name]]</div>
                </div>
            </div>
            <div class="col-md-6 col-xs-6 bord">
                <div class="col-sm-12 col-xs-12 report">
                    <div class="col-sm-6  col-xs-6 upr-case">With Amendment No.(s) :</div>
                    <div class="col-sm-6  col-xs-6 upr-case">
                        <span ng-if="viewNonEditAmendmentWith">
                            <span ng-if="viewOrderReport.with_amendment_no">[[viewOrderReport.with_amendment_no]]</span>
                            <span ng-if="!viewOrderReport.with_amendment_no">-</span>
                            <span><input class="form-control" ng-model="viewOrderReport.with_amendment_no" type="hidden" name="with_amendment_no" ng-value="viewOrderReport.with_amendment_no"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!---nonEditablePartA------>
        <div id="nonEditablePartA" ng-if="viewNonEditablePartA">
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-12 col-xs-12 bord">
                    <div class="col-sm-12 col-xs-12 report">
                        <!--<div class="col-sm-1 col-xs-4 upr-case">PART A</div>-->
                        <div class="col-sm-12 col-xs-12 upr-case">PARTICULARS OF SAMPLE SUBMITTED </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">a)</div>
                <div class="col-md-5 col-xs-5 bord">Nature of Sample</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.sample_description">[[viewOrderReport.sample_description]]</span>
                    <span ng-if="!viewOrderReport.sample_description">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">b)</div>
                <div class="col-md-5 col-xs-5 bord">
                    Grade / Variety / Type / Class / Size etc.
                </div>

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
                    <span ng-if="!viewOrderReport.brand_type">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">d)</div>
                <div class="col-md-5 col-xs-5 bord">Declared Values,if any</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.declared_values">[[viewOrderReport.declared_values]]</span>
                    <span ng-if="!viewOrderReport.declared_values">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">e)</div>
                <div class="col-md-5 col-xs-5 bord">Code No.</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.barcode"><img ng-src="[[viewOrderReport.barcode]]" /></span>
                    <span ng-if="!viewOrderReport.barcode">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">f)</div>
                <div class="col-md-5 col-xs-5 bord">Batch Number</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.batch_no">[[viewOrderReport.batch_no]]</span>
                    <span ng-if="!viewOrderReport.batch_no">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">g)</div>
                <div class="col-md-5 col-xs-5 bord">D.O.M</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.mfg_date">[[viewOrderReport.mfg_date]]</span>
                    <span ng-if="!viewOrderReport.mfg_date">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">h)</div>
                <div class="col-md-5 col-xs-5 bord">Date of Expiry</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.expiry_date">[[viewOrderReport.expiry_date]]</span>
                    <span ng-if="!viewOrderReport.expiry_date">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">i)</div>
                <div class="col-md-5 col-xs-5 bord">Sample Quantity</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.sample_qty">[[viewOrderReport.sample_qty]]</span>
                    <span ng-if="!viewOrderReport.sample_qty">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">j)</div>
                <div class="col-md-5 col-xs-5 bord">Batch Size</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.batch_size">[[viewOrderReport.batch_size]]</span>
                    <span ng-if="!viewOrderReport.batch_size">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">k)</div>
                <div class="col-md-5 col-xs-5 bord">Mode of Packing</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.packing_mode">[[viewOrderReport.packing_mode]]</span>
                    <span ng-if="!viewOrderReport.packing_mode">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">l)</div>
                <div class="col-md-5 col-xs-5 bord">Date of Receipt</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.order_date">[[viewOrderReport.order_date]]</span>
                    <span ng-if="!viewOrderReport.order_date">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">m)</div>
                <div class="col-md-5 col-xs-5 bord">Date of Start</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.analysis_start_date">[[viewOrderReport.analysis_start_date]]</span>
                    <span ng-if="!viewOrderReport.analysis_start_date">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">n)</div>
                <div class="col-md-5 col-xs-5 bord">Date of Completion</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="!viewOrderReport.is_amended_no && viewOrderReport.analysis_completion_date" ng-bind="viewOrderReport.analysis_completion_date"></span>
                    <span ng-if="viewOrderReport.orderAmendStatus && viewOrderReport.is_amended_no">[[orderTrackRecord.reviewing.report_view_date ? orderTrackRecord.reviewing.report_view_date : orderTrackRecord.finalizing.report_view_date]]</span>

                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">o)</div>
                <div class="col-md-5 col-xs-5 bord">BIS Seal (Intact/Not Intact/Unsealed)</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.is_sealed==0">Unsealed</span>
                    <span ng-if="viewOrderReport.is_sealed==1">Sealed</span>
                    <span ng-if="viewOrderReport.is_sealed==2">Intact</span>
                    <span ng-if="!viewOrderReport.is_sealed==3">N/A</span></div>
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
                    <span ng-if="!viewOrderReport.remarks"></span>N/A</div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">r)</div>
                <div class="col-md-5 col-xs-5 bord">Submitted By</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]] - [[viewOrderReport.city_name]] ( [[viewOrderReport.state_name]] )</span>
                    <span ng-if="!viewOrderReport.customer_name">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">s)</div>
                <div class="col-md-5 col-xs-5 bord">Manufactured By</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.manufactured_by">[[viewOrderReport.manufactured_by]]</span>
                    <span ng-if="!viewOrderReport.manufactured_by">N/A</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
                <div class="col-md-1 col-xs-2 bord">t)</div>
                <div class="col-md-5 col-xs-5 bord">Supplied By</div>
                <div class="col-md-6 col-xs-5 bord">
                    <span ng-if="viewOrderReport.supplied_by">[[viewOrderReport.supplied_by]]</span>
                    <span ng-if="!viewOrderReport.supplied_by">N/A</span>
                </div>
            </div>
        </div>
        <!---nonEditablePartA------>
    </div>
    <!-- /part A div end here -->

    <!-- part C div start here -->
    <div class="container-fluid pdng-20 botm-table mT30" id="foorPartC">

        <div class="col-md-12 col-xs-12 botm-row mrgn_20 dis_flex" style="padding:0">
            <div class="col-md-12 col-xs-12 bord">
                <div class="col-sm-12 col-xs-12 report">
                    <div class="col-sm-12  col-xs-12 upr-case">TEST RESULTS</div>
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
                    <div class="col-md-1 col-xs-1 bord"><b>[[$index+1]].</b></div>
                    <div class="col-md-11 col-xs-11 bord">
                        <div class="col-sm-12 col-xs-12 text-left" ng-hide="(descriptionWiseParameters.categoryName | capitalize)=='Description' && defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE">
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
                        <div class="col-md-6 col-xs-6 bord text-justify">
                            <span ng-if="descriptionWiseParameterObj.description">[[descriptionWiseParameterObj.description]]</span>
                            <span ng-if="!descriptionWiseParameterObj.description"><span>
                        </div>
                        <div class="col-md-2 col-xs-2 bord">
                            <span class="resultCheckbox mL20" ng-if="descriptionWiseParameterObj.test_result">
                                <input type="checkbox" ng-modal="analysis_id_[[$index]][[descriptionWiseParameterObj.analysis_id]]" id="analysis_id_[[descriptionWiseParameterObj.analysis_id]]" ng-click="funSelectedAnalysisIdArr(descriptionWiseParameterObj.analysis_id)" name="analysis_id[]" ng-value="descriptionWiseParameterObj.analysis_id">
                            </span>
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
                    <div class="col-md-11 col-xs-11 bord ">
                        <div class="col-sm-12 col-xs-12 text-left" ng-hide="(categoryWiseParameters.categoryName | capitalize)=='Description' && defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE">
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
                                <input type="hidden" ng-if="subCategoryParameters.equipment_type_id" ng-model="equipment_type_id_[[$indexin]]" id="equipment_type_id_[[subCategoryParameters.analysis_id]]" name="equipment_type_id[]" ng-value="subCategoryParameters.equipment_type_id">
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
                    </div>
                </div>
                <!--/DISCIPLINE NAME-->

                <!--GROUP NAME-->
                <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0" ng-if="disciplineWiseParameters.disciplineHdr.group_name">
                    <div class="col-md-1 col-xs-1 bord"></div>
                    <div class="col-md-11 col-xs-11 bord">
                        <div class="col-sm-12 col-xs-12 text-left p0"><strong>Group&nbsp;:&nbsp;[[disciplineWiseParameters.disciplineHdr.group_name]]</strong></div>
                    </div>
                </div>
                <!--/GROUP NAME-->

                <!--PARAMETER LIST-->
                <div ng-repeat="categoryWiseParameters in disciplineWiseParameters.disciplineDtl">

                    <!--CATEGORY NAME-->
                    <div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
                        <div class="col-md-1 col-xs-1 bord"><b>[[$index+1]].</b></div>
                        <div class="col-md-11 col-xs-11 bord ">
                            <div class="col-sm-12 col-xs-12 text-left" ng-hide="(categoryWiseParameters.categoryName | capitalize)=='Description' && defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE">
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
                                    <input type="hidden" ng-if="subCategoryParameters.equipment_type_id" ng-model="equipment_type_id_[[$index]]" id="equipment_type_id_[[subCategoryParameters.analysis_id]]" name="equipment_type_id[]" ng-value="subCategoryParameters.equipment_type_id">
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
        <div class="col-md-12 col-xs-12 botm-row bord" style="padding:0">

            <!--Name and Sinature-->
            <div class="col-md-12 col-xs-12 mT30" ng-if="viewOrderReport.status >= 4 && viewOrderReport.report_microbiological_name">
                <div class="col-md-12 col-xs-12 mT30">
                    <div class="col-sm-4 col-xs-4 text-center">
                        <p ng-if="viewOrderReport.report_microbiological_sign" style="width:100%;"><img ng-if="viewOrderReport.report_microbiological_sign_path" height="40px" width="100px" style="margin:-40px 0 0;" ng-alt="[[viewOrderReport.report_microbiological_name]]" ng-src="[[viewOrderReport.report_microbiological_sign_path]]" /></p>
                        <h5 ng-if="viewOrderReport.incharge_reviewing_date">[[viewOrderReport.incharge_reviewing_date]]</h5>
                        <p ng-if="viewOrderReport.report_microbiological_name" style="font-size:10px;width:100%;">[[viewOrderReport.report_microbiological_name]]</p>
                        <h4 ng-if="viewOrderReport.report_microbiological_name">[Tech Manager (Micro.)]</h4>
                    </div>
                    <div class="col-sm-4 col-xs-4 text-center mrgn_20">&nbsp;</div>
                    <div class="col-sm-4 col-xs-4 text-right mrgn_20">&nbsp;</div>
                </div>
            </div>
            <!--/Name and Sinature-->

            <div class="col-md-12 col-xs-12 hideContentOnPdf mT20 mB20">
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
                            <!----need modification test report----->
                            <input type="submit" ng-if="selectedAnalysisIdArr.length" data-toggle="modal" ng-click="funOpenNeedModificationModal('NeedModificationBySectionIncharge')" class="btn btn-primary" value="Need Modification">
                            <!----need modification test report----->
                        </span>
                    </span>
                    <button type="button" class="btn btn-primary hideContentOnPdf" ng-click="backButton()">Back</button>
                </div>
            </div>
        </div>
    </div>
    <!-- part D div end here -->

    <!--Popup Div-->
    <span ng-if="viewOrderReport.status">
        <span ng-if="{{defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE || defined('IS_ADMIN') && IS_ADMIN}}">
            @include('sales.templates.food.reports.section_incharge.needModificationBySectionInchargeModal')
        </span>
    </span>
    <!--/Popup Div-->
</form>

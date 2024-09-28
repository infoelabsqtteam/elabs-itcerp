<div class="row edit order-section">Products & Tests Detail</div>
<div class="row mT10">

    <!--Test Product-->
    <div class="col-xs-4 form-group">
        <label for="product_id">Testing Product<em class="asteriskRed">*</em></label>
        <select class="form-control" name="product_id" ng-model="updateOrder.product_id.selectedOption" id="product_id" ng-required="true" ng-options="testSampleRecevied.product_name for testSampleRecevied in testProductListing track by testSampleRecevied.product_id">
            <!--<option value="">Select Testing Product</option>-->
        </select>
        <span ng-messages="erpUpdateOrderForm.product_id.$error" ng-if='erpUpdateOrderForm.product_id.$dirty  || erpUpdateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Testing Product is required</span>
        </span>
    </div>
    <!--/Test Product-->

    <!-- defined test standard -->
    <div class="col-xs-4 form-group">
        <label for="defined_test_standard">Analysis Type<em class="asteriskRed">*</em></label>
        <select 
            class="form-control" 
            name="defined_test_standard"
            ng-required="true" 
            ng-model="updateOrder.defined_test_standard.selectedOption"
            id="defined_test_standard_edit" 
            ng-options="testStd.name for testStd in definedTestStandardList track by testStd.id">
            <option value="">Select Analysis Type</option>
        </select>
        <span ng-messages="erpUpdateOrderForm.defined_test_standard.$error" ng-if='erpUpdateOrderForm.defined_test_standard.$dirty || erpUpdateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Analysis Type is required</span>
        </span>
    </div>
    <!-- /defined test standard-->

    <!--Product Tests-->
    <div class="col-xs-4 form-group">
        <label for="product_test_id" class="width100pc">Product Tests<em class="asteriskRed">*</em>
            <a href="javascript:;" ng-if="!updateOrder.invoice_generated_id" ng-click="funAddMoreParameters(updateOrder.selectedTestId,order_id);" class="generate btn btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i></a>
        </label>
        <select class="form-control" name="product_test_id" ng-model="updateOrder.test_id.selectedOption" id="product_test_id" ng-required="true" ng-options="testRecevied.test_code for testRecevied in productTestListing track by testRecevied.test_id">
        </select>
        <input type="hidden" name="test_standard" value="[[orderProductTest.test_standard]]" id="test_standard">
        <span ng-messages="erpUpdateOrderForm.product_test_id.$error" ng-if='erpUpdateOrderForm.product_test_id.$dirty  || erpUpdateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Product Tests is required</span>
        </span>
    </div>
    <!--/Product Tests-->

    <!--Header Note-->
    <div class="col-xs-4 form-group" ng-if="headerNoteStatus">
        <label for="header_note">Header note<em class="asteriskRed">*</em></label>
        <input class="form-control" name="header_note" ng-model="updateOrder.header_note" id="header_note" ng-change="getAutoSearchHeaderNoteMatches(updateOrder.header_note);" placeholder="Header note" ng-required='true' autocomplete="off">
        <span ng-messages="erpUpdateOrderForm.header_note.$error" ng-if='erpUpdateOrderForm.header_note.$dirty || erpUpdateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Header note is required</span>
        </span>
        <ul ng-if="showHeaderNoteAutoSearchList" class="autoSearch">
            <li ng-if="headerNotesList.length" ng-repeat="headerNoteObj in headerNotesList" ng-click="funSetSelectedHeaderNote(headerNoteObj.name,headerNoteObj.header_limit,'edit');" ng-bind="headerNoteObj.name"></li>
        </ul>
    </div>
    <!--/Header Note-->

    <!--Product As Per Customer-->
    <div class="col-xs-4 form-group">&nbsp;</div>
    <!--/Product As Per Customer-->

    <!--Stability Note-->
    <div class="col-xs-4 form-group" ng-if="realTimeStabilityStatus">
        <label for="stability_note">Real Time Stability<em class="asteriskRed">*</em></label>
        <input class="form-control" name="stability_note" ng-model="updateOrder.stability_note" id="stability_note" ng-change="getAutoSearchStabilityNoteMatches(updateOrder.stability_note);" placeholder="Stability Note" ng-required='true' autocomplete="off">
        <span ng-messages="erpUpdateOrderForm.stability_note.$error" ng-if='erpUpdateOrderForm.stability_note.$dirty || erpUpdateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Real Time Stability is required</span>
        </span>
        <ul ng-if="showStabilityNoteAutoSearchList" class="autoSearch">
            <li ng-if="stabilityNotesList.length" ng-repeat="stabilityNoteObj in stabilityNotesList" ng-click="funSetSelectedStabilityNote(stabilityNoteObj.name,'edit');" ng-bind="stabilityNoteObj.name"></li>
        </ul>
    </div>
    <!--/Stability Note-->

    <!--Test to perform-->
    <div ng-show="updateOrderParameters.length">
        <div class="col-xs-12 form-group">
            <div class="row">
                <div id="no-more-tables" class="fixed_table">
                    <table border="1" class="col-sm-12 table-striped table-condensed cf">
                        <thead class="cf">
                            <tr>
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('test_parameter_name')">Test Parameter Name </label>
                                    <span class="sortorder" ng-show="predicate === 'test_parameter_name'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('equipment_name')">Equipment Name </label>
                                    <span class="sortorder" ng-show="predicate === 'equipment_name'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('method_name')">Method Name </label>
                                    <span class="sortorder" ng-show="predicate === 'method_name'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('detector_name')">Detector</label>
                                    <span class="sortorder" ng-show="predicate === 'detector_name'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('claim_dependent')">Claim(%)</label>
                                    <span class="sortorder" ng-show="predicate === 'claim_dependent'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('claim_value_unit')">Claim Unit</label>
                                    <span class="sortorder" ng-show="predicate === 'claim_value_unit'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('standard_value_from')">Standard Value From</label>
                                    <span class="sortorder" ng-show="predicate === 'standard_value_from'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('standard_value_to')">Standard Value To</label>
                                    <span class="sortorder" ng-show="predicate === 'standard_value_to'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('parameter_decimal_place')">Decimal Place</label>
                                    <span class="sortorder" ng-show="predicate === 'parameter_decimal_place'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('parameter_nabl_scope')">NABL Scope</label>
                                    <span class="sortorder" ng-show="predicate === 'parameter_nabl_scope'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('time_taken')">Time Taken</label>
                                    <span class="sortorder" ng-show="predicate === 'time_taken'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th ng-if="istableTrTdVisibleID"><label>Running Time</label></th>
                                <th ng-if="istableTrTdVisibleID"><label>No. of Injections</label></th>
                                <th><label>Action</label></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="categoryParameters in updateOrderParameters">
                                <td ng-show="categoryParameters.categoryParams.length" colspan="[[tableTrTdColspanLevelI]]" data-title="Test Parameter Category Name">
                                    <table border="1" class="col-sm-12 table-striped table-condensed" id="editOrderParameters">
                                        <thead>
                                            <th align="left" colspan="[[tableTrTdColspanLevelI]]">[[categoryParameters.categoryName]]</th>
                                        </thead>
                                        <tbody ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">
                                            <tr ng-if="subCategoryParameters.description.length">
                                                <td id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name" ng-bind-html="subCategoryParameters.test_parameter_name"></td>
                                                <td colspan="[[tableTrTdColspanLevelII]]" id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name" ng-bind-html="subCategoryParameters.description">[[subCategoryParameters.description]]</td>
                                            </tr>
                                            <tr ng-if="categoryParameters.categoryParams && !subCategoryParameters.description.length">
                                                <td id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name" ng-bind-html="subCategoryParameters.test_parameter_name"></td>
                                                <td id="equipment_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Equipment Name">[[subCategoryParameters.equipment_name]]</td>
                                                <td id="method_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Method Name">[[subCategoryParameters.method_name]]</td>
                                                <td id="detector_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Detector Name">[[subCategoryParameters.detector_name]]</td>
                                                <td align="center" id="claim_value_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Claim Value">
                                                    <input type="text" ng-if="subCategoryParameters.claim_dependent" class="form-control widthPx80" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][claim_value]" ng-model="orderEditProductTest.claim_value[[subCategoryParameters.product_test_dtl_id]]" ng-value="subCategoryParameters.claim_value" id="claim_value[[subCategoryParameters.product_test_dtl_id]]" ng-change="funChangeTestParameterValueAccToClaim('[[subCategoryParameters.product_test_dtl_id]]','edit')" placeholder="Claim Value">
                                                    <input type="hidden" value="[[subCategoryParameters.productTestDtlStdValFrom]]" id="edit_org_standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.productTestDtlStdValTo]]" id="edit_org_standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
                                                </td>
                                                <td align="center" id="claim_value_unit_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Claim Value Unit">
                                                    <input type="text" ng-if="subCategoryParameters.claim_dependent" class="form-control widthPx80" id="claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][claim_value_unit]" ng-change="funChangeTestParameterValueAccToClaimUnit('[[subCategoryParameters.product_test_dtl_id]]')" ng-model="subCategoryParameters.claim_value_unit" placeholder="Claim Unit">
                                                    <input type="hidden" ng-if="subCategoryParameters.claim_dependent" ng-model="subCategoryParameters.claim_dependent" name="order_parameters_detail[claim_dependent][]" value="[[subCategoryParameters.claim_dependent]]">
                                                </td>
                                                <td id="standard_value_from_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value From">[[subCategoryParameters.standard_value_from]]&nbsp;[[subCategoryParameters.claim_value_unit]]</td>
                                                <td id="standard_value_to_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value To">[[subCategoryParameters.standard_value_to]]&nbsp;[[subCategoryParameters.claim_value_unit]]</td>
                                                <td id="display_decimal_place_text[[subCategoryParameters.product_test_dtl_id]]" align="left" data-title="Decimal Place">
                                                    Upto :
                                                    <input type="text" class="form-control widthPx80" id="edit_display_decimal_place[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderEditProductTest.display_decimal_place[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][display_decimal_place]" ng-value="subCategoryParameters.display_decimal_place" placeholder="Decimal Place">
                                                </td>
                                                <td id="order_parameter_nabl_scope_text[[subCategoryParameters.product_test_dtl_id]]" data-title="NABL Scope">
                                                    <select id="edit_order_parameter_nabl_scope[[subCategoryParameters.product_test_dtl_id]]" class="form-control widthPx80" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][order_parameter_nabl_scope]" ng-model="orderEditProductTest.order_parameter_nabl_scope[[subCategoryParameters.product_test_dtl_id]]">
                                                        <option value="">Select Scope</option>
                                                        <option ng-selected="subCategoryParameters.order_parameter_nabl_scope == 1" value="1">Yes</option>
                                                        <option ng-selected="subCategoryParameters.order_parameter_nabl_scope == 0" value="0">No</option>
                                                    </select>
                                                </td>
                                                <td data-title="time taken">
                                                    <span id="time_taken_days_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_days ? subCategoryParameters.time_taken_days+' Days' : '']]</span>
                                                    <span id="time_taken_mins_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_mins ? subCategoryParameters.time_taken_mins+' Mins' : '']]</span>
                                                </td>
                                                <td ng-if="istableTrTdVisibleID" align="left" id="running_time_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Running Time">[[subCategoryParameters.invoicing_running_time]]</td>
                                                <td ng-if="istableTrTdVisibleID" align="left" id="no_of_injection_text[[subCategoryParameters.product_test_dtl_id]]" data-title="No. of Injection">[[subCategoryParameters.no_of_injection]]</td>
                                                <td>
                                                    <input type="hidden" ng-value="subCategoryParameters.test_id" name="product_test_id" ng-model="orderEditProductTest.product_test_id[[subCategoryParameters.product_test_dtl_id]]" id="product_test_id">
                                                    <input type="hidden" ng-value="subCategoryParameters.standard_value_type" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][standard_value_type]" ng-model="orderEditProductTest.standard_value_type[[subCategoryParameters.product_test_dtl_id]]" id="standard_value_type[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" ng-value="subCategoryParameters.standard_value_from" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][standard_value_from]" ng-model="orderEditProductTest.standard_value_from[[subCategoryParameters.product_test_dtl_id]]" id="standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" ng-value="subCategoryParameters.standard_value_to" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][standard_value_to]" ng-model="orderEditProductTest.standard_value_to[[subCategoryParameters.product_test_dtl_id]]" id="standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" ng-value="subCategoryParameters.measurement_uncertainty" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][measurement_uncertainty]" ng-model="orderEditProductTest.measurement_uncertainty[[subCategoryParameters.product_test_dtl_id]]" id="measurement_uncertainty[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" ng-value="subCategoryParameters.limit_determination" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][limit_determination]" ng-model="orderEditProductTest.limit_determination[[subCategoryParameters.product_test_dtl_id]]" id="limit_determination[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" ng-value="subCategoryParameters.lod" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][lod]" ng-model="orderEditProductTest.lod[[subCategoryParameters.product_test_dtl_id]]" id="lod[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" ng-value="subCategoryParameters.mrpl" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][mrpl]" ng-model="orderEditProductTest.mrpl[[subCategoryParameters.product_test_dtl_id]]" id="mrpl[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" ng-value="subCategoryParameters.validation_protocol" name="order_parameters_detail['[[subCategoryParameters.analysis_id]]'][validation_protocol]" ng-model="orderEditProductTest.validation_protocol[[subCategoryParameters.product_test_dtl_id]]" id="validation_protocol[[subCategoryParameters.product_test_dtl_id]]">
                                                    <button type="button" class="btn btn-danger btn-sm" title="Delete" ng-click="funConfirmDeleteParameter([[order_id]],[[subCategoryParameters.analysis_id]])">D</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <!--For adding New Parameter from Popup Window-->
                            <tr ng-if="testProductParamenters.length">
                                <td class="order-section" colspan="[[tableTrTdColspanLevelI]]"><strong>New Parameter List([[testProductParamenters.length]])<strong></td>
                            </tr>
                            <tr ng-repeat="categoryParameters in testProductParamenters">
                                <td ng-show="categoryParameters.categoryParams.length" colspan="[[tableTrTdColspanLevelI]]" data-title="Test Parameter Category Name">
                                    <table border="1" id="parameters" class="col-sm-12 table-striped table-condensed">
                                        <thead>
                                            <th align="left" colspan="[[tableTrTdColspanLevelI]]">[[categoryParameters.categoryName]]</th>
                                        </thead>
                                        <tbody ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">
                                            <tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="!subCategoryParameters.description.length">
                                                <td id="new_test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name">
                                                    <span class="parameterLft" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
                                                    <span class="parameterRht" ng-if="subCategoryParameters.defined_invoicing_rate == '0' && customerInvoicingTypeID == '4' "><i class="fa fa-times-circle btn-danger parameterIcon" aria-hidden="true"></i></span>
                                                    <span class="parameterRht" ng-if="subCategoryParameters.defined_invoicing_rate && subCategoryParameters.defined_invoicing_rate != '0' && customerInvoicingTypeID == '4' "><i class="fa fa-check-circle btn-success parameterIcon" aria-hidden="true"></i></span>
                                                </td>
                                                <td id="new_equipment_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Equipment Name">[[subCategoryParameters.equipment_name]]</td>
                                                <td id="new_method_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Method Name">[[subCategoryParameters.method_name]]</td>
                                                <td id="new_detector_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Detector Name">[[subCategoryParameters.detector_name]]</td>
                                                <td align="left" id="claim_value_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Claim Value">
                                                    <input type="text" ng-if="subCategoryParameters.claim_dependent" class="form-control widthPx80" id="new_claim_value[[subCategoryParameters.product_test_dtl_id]]" ng-change="funOnNewChangeTestParameterValueAccToClaim('[[subCategoryParameters.product_test_dtl_id]]')" ng-model="orderProductTest.claim_value[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][claim_value]" placeholder="Claim Value">
                                                    <input type="hidden" ng-if="!subCategoryParameters.claim_dependent" id="new_claim_value[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderProductTest.claim_value[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][claim_value]" value="">
                                                    <input type="hidden" ng-if="subCategoryParameters.claim_dependent" name="order_parameters_detail[claim_dependent][]" value="[[subCategoryParameters.claim_dependent]]">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_from]]" id="new_org_standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_to]]" id="new_org_standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
                                                </td>
                                                <td align="left" id="new_claim_value_unit_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Claim Value Unit">
                                                    <input type="text" ng-if="subCategoryParameters.claim_dependent" class="form-control widthPx80" id="new_claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderProductTest.claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][claim_value_unit]" ng-change="funOnNewChangeTestParameterValueAccToClaimUnit('[[subCategoryParameters.product_test_dtl_id]]')" placeholder="Claim Unit">
                                                    <input ng-if="!subCategoryParameters.claim_dependent" type="hidden" id="claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderProductTest.claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][claim_value_unit]" value="">
                                                </td>
                                                <td id="new_standard_value_from_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value From">
                                                    <span ng-if="subCategoryParameters.standard_value_from">[[subCategoryParameters.standard_value_from]]</span>
                                                </td>
                                                <td id="new_standard_value_to_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value To" ng-if="subCategoryParameters.test_parameter_id != 25235">[[subCategoryParameters.standard_value_to]]</td>
                                                <td id="new_standard_value_to_text_dt[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value To" ng-if="subCategoryParameters.test_parameter_id == 25235"><input type="text" class="form-control min-width120" readonly ng-if="subCategoryParameters.test_parameter_id == 25235" value="[[dt_standard_value_to_edit]]" placeholder="select Header Note" id="dt_standard_value_to_edit[[subCategoryParameters.product_test_dtl_id]]"></td>
                                                <td id="new_display_decimal_place_text[[subCategoryParameters.product_test_dtl_id]]" align="left" data-title="Decimal Place">
                                                    Upto : <input type="text" class="form-control widthPx40" id="new_display_decimal_place[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderProductTest.display_decimal_place[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][display_decimal_place]" ng-value="subCategoryParameters.display_decimal_place" placeholder="Decimal Place">
                                                </td>
                                                <td id="new_order_parameter_nabl_scope_text[[subCategoryParameters.product_test_dtl_id]]" data-title="NABL Scope">
                                                    <select id="new_order_parameter_nabl_scope[[subCategoryParameters.product_test_dtl_id]]" class="form-control widthPx40" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][order_parameter_nabl_scope]" ng-model="orderProductTest.order_parameter_nabl_scope[[subCategoryParameters.product_test_dtl_id]]">
                                                        <option value="">Select Scope</option>
                                                        <option ng-selected="subCategoryParameters.parameter_nabl_scope == 1" value="1">Yes</option>
                                                        <option ng-selected="subCategoryParameters.parameter_nabl_scope == 0" value="0">No</option>
                                                    </select>
                                                </td>
                                                <td data-title="time taken">
                                                    <span id="time_taken_days_text[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.time_taken_days != 0.00 || subCategoryParameters.time_taken_days != 0">[[subCategoryParameters.time_taken_days ? subCategoryParameters.time_taken_days+' Days' : '']]</span>
                                                    <span id="time_taken_mins_text[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.time_taken_mins != 0.00 || subCategoryParameters.time_taken_mins != 0">[[subCategoryParameters.time_taken_mins ? ' & ' +subCategoryParameters.time_taken_mins+' Mins' : '']]</span>
                                                </td>
                                                <td ng-if="istableTrTdVisibleID" align="left" id="running_time_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Running Time">
                                                    <select ng-if="subCategoryParameters.cwap_invoicing_required == 1" id="new_running_time_id_[[subCategoryParameters.product_test_dtl_id]]" class="form-control min-width120" ng-change="funRequiredUnRequiredRunningTimeOrNot(subCategoryParameters.product_test_dtl_id,'edit')" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][running_time_id]" ng-model="orderProductTest.running_time_id[[subCategoryParameters.product_test_dtl_id]]" ng-options="item.name for item in subCategoryParameters.runningTimeSelectboxList track by item.id">
                                                        <option value="">Select Running Time</option>
                                                    </select>
                                                    <input type="hidden" ng-if="subCategoryParameters.cwap_invoicing_required == 0" value="" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][running_time_id]" id="new_running_time_id_[[subCategoryParameters.product_test_dtl_id]]">
                                                </td>
                                                <td ng-if="istableTrTdVisibleID" align="left" id="no_of_injection_text[[subCategoryParameters.product_test_dtl_id]]" data-title="No. of Injection">
                                                    <input type="text" ng-if="subCategoryParameters.cwap_invoicing_required == 1" valid-number class="form-control widthPx40" id="new_no_of_injection_[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderProductTest.no_of_injection[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][no_of_injection]" placeholder="No. of Injection">
                                                    <input type="hidden" ng-if="subCategoryParameters.cwap_invoicing_required == 0" value="" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][no_of_injection]" id="new_no_of_injection_[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.cwap_invoicing_required]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][cwap_invoicing_required]" id="new_cwap_invoicing_required_[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.test_parameter_invoicing_parent_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][test_parameter_invoicing_parent_id]" id="new_test_parameter_invoicing_parent[[subCategoryParameters.product_test_dtl_id]]">
                                                </td>
                                                <td align="center">
                                                    <input type="hidden" value="[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][product_test_parameter]" ng-model="orderProductTest.product_test_parameter" id="new_product_test_parameter[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.test_parameter_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][test_parameter_id]" ng-model="orderProductTest.test_parameter_id" id="new_test_parameter_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.equipment_type_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][equipment_type_id]" ng-model="orderProductTest.equipment_type_id" id="new_equipment_type_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.method_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][method_id]" ng-model="orderProductTest.method_id" id="new_method_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.detector_id]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][detector_id]" ng-model="orderProductTest.detector_id" id="new_detector_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_type]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][standard_value_type]" ng-model="orderProductTest.standard_value_type" id="new_standard_value_type[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_from]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][standard_value_from]" ng-model="orderProductTest.standard_value_from" id="new_standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" ng-if="subCategoryParameters.test_parameter_id != 25235" value="[[subCategoryParameters.standard_value_to]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][standard_value_to]" ng-model="orderProductTest.standard_value_to[[subCategoryParameters.product_test_dtl_id]]" id="new_standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" ng-if="subCategoryParameters.test_parameter_id == 25235" value="[[dt_standard_value_to_edit]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][standard_value_to]" ng-model="orderProductTest.standard_value_to[[subCategoryParameters.product_test_dtl_id]]" id="new_standard_value_to_dt[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.time_taken_days]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][time_taken_days]" ng-model="orderProductTest.time_taken_days" id="new_time_taken_days[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.time_taken_mins]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][time_taken_mins]" ng-model="orderProductTest.time_taken_mins" id="new_time_taken_mins[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.cost_price]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][cost_price]" ng-model="orderProductTest.cost_price" id="new_cost_price[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.selling_price]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][selling_price]" ng-model="orderProductTest.selling_price" id="new_selling_price[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="0" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][test_param_alternative_id]" ng-model="orderProductTest.test_param_alternative_id" id="new_test_param_alternative_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.measurement_uncertainty]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][measurement_uncertainty]" ng-model="orderProductTest.measurement_uncertainty" id="new_measurement_uncertainty[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.limit_determination]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][limit_determination]" ng-model="orderProductTest.limit_determination" id="new_limit_determination[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.lod]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][lod]" ng-model="orderProductTest.lod" id="new_lod[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.mrpl]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][mrpl]" ng-model="orderProductTest.mrpl" id="new_mrpl[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.validation_protocol]]" name="order_parameters_detail['new']['[[subCategoryParameters.test_parameter_id]]'][validation_protocol]" ng-model="orderProductTest.validation_protocol" id="new_validation_protocol[[subCategoryParameters.product_test_dtl_id]]">
                                                    <!--<button type="button" title="Remove Parameter" class="btn btn-primary btn-sm" ng-click="funRemoveTestParameterRow([[subCategoryParameters.product_test_dtl_id]])">R</button>-->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!--/For adding New Parameter from Popup Window-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--/Test to perform-->
</div>

<div class="row order-section" ng-if="isTatInDaysEditableDivFlag || updateOrder.defined_test_standard_name  == 'Method Validation & Verification'">Additional Detail</div>
<div class="order_detail mT20">
    
    <!--TAT Detail-->
    <div class="col-xs-3 form-group" ng-if="isTatInDaysEditableDivFlag" ng-init="updateOrder.is_checked_tat_in_days=false">
        <label class="width100" for="tat_in_days">
            <span class="txt-left">TAT(In Days)<em class="asteriskRed">*</em></span>
            <span class="txt-right"><input type="checkbox" title="Checked for Re-Generation of Expected Due Date(EDD)" ng-model="updateOrder.is_checked_tat_in_days" name="is_checked_tat_in_days" id="is_checked_tat_in_days" ng-value="1"></span>
        </label>
        <input type="text" valid-number ng-if="isTatInDaysEditableDivFlag" class="form-control" name="tat_in_days" id="edit_tat_in_days" ng-model="updateOrder.tat_in_days" ng-value="updateOrder.tat_in_days" ng-required="true" placeholder="TAT in Days">
        <span ng-messages="erpUpdateOrderForm.tat_in_days.$error" ng-if="erpUpdateOrderForm.tat_in_days.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">TAT(In Days) is required</span>
        </span>
    </div>
    <!--/TAT Detail-->

    <!--Booked Order Amount-->
    <div class="col-xs-3 form-group" ng-if="updateOrder.defined_test_standard_name == 'Method Validation & Verification'">
        <label for="tat_in_days">Sample Amount(Rs.)<em class="asteriskRed">*</em></label>
        <input 
			type="text" 
			valid-number 
			class="form-control"
			name="booked_order_amount" 
			ng-model="updateOrder.booked_order_amount"
			ng-value="updateOrder.booked_order_amount" 
			id="booked_order_amount_edit" 
			ng-required="true" 
			placeholder="Sample Amount(Rs.)">
        <span ng-messages="erpUpdateOrderForm.booked_order_amount.$error" ng-if="erpUpdateOrderForm.booked_order_amount.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Sample Amount(Rs.) is required</span>
        </span>
    </div>
    <!--/Booked Order Amount-->
    
</div>

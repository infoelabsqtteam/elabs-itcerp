<div class="row" ng-if="orderParametersList.length">
    <div class="headingGray">
        <strong class="text-left"> Tests Parameters Details &nbsp;(Order Number : [[viewOrderReport.order_no]])
        </strong>
        <button ng-click="closeEditOrder()" style="margin-top:-3px;width:70px;" title="Close"
            class="right-nav btn btn-default btn-sm">Close</button>
        <a ng-if="!viewOrderReport.invoice_generated_id" class="btn btn-primary btn-sm"
            style="font-size: 10px; float: right; margin-top: -3px;" title="Add New Row" id="addNewRow"
            href="javascript:;"
            ng-click="funAddMoreTestProductStandardParamentersPopup(viewOrderReport.order_id,viewOrderReport.product_test_id);">Add
            More</a>
    </div>
    <div class="row mT10">
        <div class="panel">
            <div class="col-xs-12 form-group view-record">
                <div id="no-more-tables" class="row" style="margin: 0;">
                    <div class="fixed_table_view">
                        <form method="POST" name='updateTestParameterDetailForm' id="updateTestParameterDetailForm"
                            novalidate>
                            <table border="1" class="col-sm-12 table-striped table-condensed cf">
                                <thead class="cf">
                                    <tr>
                                        <th class="width20">
                                            <label class="sortlabel">Test Parameter </label>
                                        </th>
                                        <th class="width10">
                                            <label class="sortlabel" ng-click="sortBy('equipment_name')">Equipment
                                            </label>
                                            <span class="sortorder" ng-show="predicate === 'equipment_name'"
                                                ng-class="{reverse:reverse}"></span>
                                        </th>
                                        <th class="width10">
                                            <label class="sortlabel">Method </label>
                                        </th>

                                        <th class="width10">
                                            <label class="sortlabel">Claim(%)</label>
                                        </th>
                                        <th class="width10">
                                            <label class="sortlabel">Standard Value Type</label>
                                        </th>
                                        <th class="width10">
                                            <label title="Standard Value From" class="sortlabel">Standard Value
                                                From</label>
                                        </th>
                                        <th class="width10">
                                            <label title="Standard Value To" class="sortlabel">Standard Value To</label>
                                        </th>
                                        <th class="width10">
                                            <label class="sortlabel">NABL </label>
                                        </th>
                                        <th class="width10">
                                            <label class="sortlabel">Time Taken</label>
                                        </th>
                                        <th class="width10">
                                            <label class="text-left"> Action </label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="categoryParameters in orderParametersList">
                                        <td colspan="10" data-title="Test Parameter Category Name">
                                            <strong>[[categoryParameters.categoryName]] </strong>
                                            <table class="col-sm-12 table-striped table-condensed">
                                                <tbody ng-repeat="subCatPara in categoryParameters.categoryParams">
                                                    <tr ng-if="subCatPara.description.length">
                                                        <span>
                                                            <td
                                                                id="test_parameter_name_text[[subCatPara.product_test_dtl_id]]">
                                                                <span
                                                                    ng-bind-html="subCatPara.test_parameter_name"></span>
                                                            </td>
                                                            <td colspan="10">[[subCatPara.description]] </td>
                                                        </span>
                                                    </tr>
                                                    <tr
                                                        ng-if="!subCatPara.description.length && subCatPara.analysis_id != AnalysisId">
                                                        <td class="width20" data-title="Test Parameter Name"><span
                                                                ng-bind-html="subCatPara.test_parameter_name"></span>
                                                        </td>
                                                        <td class="width10" data-title="Equipment Name">
                                                            [[subCatPara.equipment_name]]</td>
                                                        <td class="width10 text-center" data-title="Method Name">
                                                            [[subCatPara.method_name | capitalize]] </td>
                                                        <td class="width10" data-title="Claim Value">
                                                            <span ng-if="subCatPara.claim_value">
                                                                [[subCatPara.claim_value ? subCatPara.claim_value :
                                                                '-']]&nbsp;
                                                                <span
                                                                    ng-if="subCatPara.claim_value_unit">[[subCatPara.claim_value_unit
                                                                    | capitalizeAll]]</span>
                                                            </span>
                                                        </td>
                                                        <td class="width10 text-center"
                                                            data-title="Standard Value Type">
                                                            <span ng-if="subCatPara.standard_value_type">
                                                                [[subCatPara.standard_value_type | capitalize]]
                                                            </span>
                                                        </td>
                                                        <td class="width10" data-title="Standard Value From">
                                                            <span ng-if="subCatPara.standard_value_from">
                                                                <span>[[subCatPara.standard_value_from]]</span>
                                                                &nbsp;<span
                                                                    ng-if="subCatPara.claim_value_unit">[[subCatPara.claim_value_unit
                                                                    | capitalizeAll]]</span>
                                                            </span>
                                                        </td>
                                                        <td class="width10 text-center" data-title="Standard Value To">
                                                            <span ng-if="subCatPara.standard_value_to">
                                                                <span>[[subCatPara.standard_value_to]]</span>
                                                                &nbsp;<span ng-if="subCatPara.claim_value_unit">
                                                                    [[subCatPara.claim_value_unit |
                                                                    capitalizeAll]]</span>
                                                            </span>
                                                        </td>
                                                        <td class="width10 text-center" data-title="NABL scope">
                                                            [[(subCatPara.order_parameter_nabl_scope=='0' ||
                                                            !subCatPara.order_parameter_nabl_scope ) ? 'No' : 'Yes']]
                                                        </td>
                                                        <td class="width10 text-center" data-title="time taken">
                                                            <span
                                                                id="time_taken_days_text[[subCatPara.product_test_dtl_id]]">[[subCatPara.time_taken_days
                                                                ? subCatPara.time_taken_days+' Days' : '']]</span>
                                                            <span
                                                                id="time_taken_mins_text[[subCatPara.product_test_dtl_id]]">[[subCatPara.time_taken_mins
                                                                ? subCatPara.time_taken_mins+' Mins' : '']]</span>
                                                        </td>
                                                        <td class="width10 text-center"
                                                            ng-hide="AnalysisId==subCatPara.analysis_id">
                                                            <div class="report_btn_div"
                                                                ng-if="{{ defined('EDIT') && EDIT }}">
                                                                <span ng-if="{{ defined('IS_ADMIN') && IS_ADMIN }}">
                                                                    <button title="Edit Parameters"
                                                                        ng-click='openUpdateOrderForm(categoryParameters.categoryId,subCatPara.product_category_id,subCatPara)'
                                                                        class="btn btn-primary btn-sm"><i
                                                                            class="fa fa-pencil-square-o"
                                                                            aria-hidden="true"></i></button>
                                                                </span>
                                                                <span
                                                                    ng-if="{{ defined('IS_JOB_SCHEDULER') && IS_JOB_SCHEDULER }}">
                                                                    <button title="Edit Parameters"
                                                                        ng-click='openUpdateOrderForm(categoryParameters.categoryId,subCatPara.product_category_id,subCatPara)'
                                                                        class="btn btn-primary btn-sm"><i
                                                                            class="fa fa-pencil-square-o"
                                                                            aria-hidden="true"></i></button>
                                                                </span>
                                                            </div>
                                                            <div class="report_btn_div"
                                                                ng-if="{{ defined('DELETE') && DELETE }}">
                                                                <span ng-if="{{ defined('IS_ADMIN') && IS_ADMIN }}">
                                                                    <button
                                                                        ng-click="funConfirmDeleteMessage(subCatPara.analysis_id,subCatPara.order_id,'delete')"
                                                                        title="Delete Order"
                                                                        class="btn btn-danger btn-sm"> <i
                                                                            class="fa fa-trash-o"
                                                                            aria-hidden="true"></i></button>
                                                                </span>
                                                                <span class="action-icon-padding"
                                                                    ng-if="{{ defined('IS_JOB_SCHEDULER') && IS_JOB_SCHEDULER }}">
                                                                    <button ng-disabled="disableDeleteIcon"
                                                                        ng-click="funConfirmDeleteMessage(subCatPara.analysis_id,subCatPara.order_id,'delete')"
                                                                        title="Delete Order"
                                                                        class="btn btn-danger btn-sm"> <i
                                                                            class="fa fa-trash-o"
                                                                            aria-hidden="true"></i></button>
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!--- edit order parameter form -->
                                                    <tr
                                                        ng-if="!subCatPara.description.length && subCatPara.analysis_id == AnalysisId">
                                                        <td class="width20 text-center">
                                                            <input type="text" ng-model="searchParameter"
                                                                placeholder="keyword">
                                                            <select class="form-control"
                                                                name="[[subCatPara.analysis_id]][test_parameter_id]"
                                                                ng-model="test_parameter_id.selectedOption"
                                                                ng-options="item.name for item in parameterList | filter:searchParameter track by item.id">
                                                                <option value="">Select Parameter</option>
                                                            </select>
                                                        </td>
                                                        <td class="width10" data-title="Equipment Name">
                                                            <input type="text" ng-model="searchEquipment"
                                                                placeholder="keyword">
                                                            <select class="form-control"
                                                                name="[[subCatPara.analysis_id]][equipment_type_id]"
                                                                ng-model="equipment_type_id.selectedOption"
                                                                ng-options="item.name for item in equipmentList | filter:searchEquipment track by item.id">
                                                                <option value="">Select Equipment</option>
                                                            </select>
                                                        </td>
                                                        <td class="width10 text-center" data-title="Method Name">
                                                            <input type="text" ng-model="searchMethod"
                                                                placeholder="keyword">
                                                            <select class="form-control"
                                                                name="[[subCatPara.analysis_id]][method_id]"
                                                                ng-model="method_id.selectedOption"
                                                                ng-options="item.name for item in methodList| filter:searchMethod track by item.id">
                                                                <option value="">Select Method</option>
                                                            </select>
                                                        </td>

                                                        <td class="width10 text-center" data-title="Claim Value">
                                                            <input valid-number ng-if="subCatPara.claim_dependent"
                                                                type="text" class="form-control"
                                                                id="claim_value[[subCatPara.analysis_id]]"
                                                                ng-change="funEditChangeTestParameterValueAccToClaim('[[subCatPara.product_test_dtl_id]]','[[subCatPara.analysis_id]]')"
                                                                ng-model="claim.claim_value[[subCatPara.analysis_id]]"
                                                                name="[[subCatPara.analysis_id]][claim_value]"
                                                                placeholder="Claim Value" style="width: 105px;">
                                                            <span ng-if="!subCatPara.claim_dependent"> - </span>
                                                        </td>
                                                        <td class="width10 text-center"
                                                            data-title="Standard standard_value_type From">
                                                            <span>
                                                                <select class="width100pc form-control"
                                                                    name="[[subCatPara.analysis_id]][standard_value_type]"
                                                                    id="[[subCatPara.analysis_id]][standard_value_type]"
                                                                    ng-options="item.name for item in standardValueTypes.data_types.availableTypeOptions track by item.id"
                                                                    ng-model="standardValueType.selectedOption">
                                                                    <option value="">Select Standard Value Type</option>
                                                                </select>
                                                            </span>
                                                        </td>
                                                        <td class="width10 text-center"
                                                            data-title="Standard Value From">
                                                            <input type="hidden"
                                                                value="[[subCatPara.productTestDtlStdValFrom]]"
                                                                id="edit_standard_value_from[[subCatPara.analysis_id]]">
                                                            <input type="text" class="form-control"
                                                                id="standard_value_from[[subCatPara.analysis_id]]"
                                                                ng-model="standard_value.standard_value_from[[subCatPara.analysis_id]]"
                                                                name="[[subCatPara.analysis_id]][standard_value_from]"
                                                                placeholder="Standard Value From" style="width: 105px;">
                                                        </td>
                                                        <td class="width10 text-center" data-title="Standard Value To">
                                                            <input type="hidden"
                                                                value="[[subCatPara.productTestDtlStdValTo]]"
                                                                id="edit_standard_value_to[[subCatPara.analysis_id]]">
                                                            <input type="text" class="form-control"
                                                                id="standard_value_to[[subCatPara.analysis_id]]"
                                                                ng-model="standard_value.standard_value_to[[subCatPara.analysis_id]]"
                                                                name="[[subCatPara.analysis_id]][standard_value_to]"
                                                                placeholder="Standard Value To" style="width: 105px;">
                                                        </td>
                                                        <td class="width10 text-center" data-title="NABL scope">
                                                            <select class="form-control"
                                                                name="[[subCatPara.analysis_id]][order_parameter_nabl_scope]"
                                                                ng-model="order_parameter_nabl_scope.selectedOption"
                                                                ng-options="item.name for item in nablScopeList track by item.id">

                                                            </select>
                                                        </td>
                                                        <td class="width10 text-center" data-title="time taken">
                                                            <span
                                                                id="time_taken_days_text[[subCatPara.product_test_dtl_id]]">[[subCatPara.time_taken_days
                                                                ? subCatPara.time_taken_days+' Days' : '']]</span>
                                                            <span
                                                                id="time_taken_mins_text[[subCatPara.product_test_dtl_id]]">[[subCatPara.time_taken_mins
                                                                ? subCatPara.time_taken_mins+' Mins' : '']]</span>
                                                        </td>
                                                        <td class="width10 text-center">
                                                            <button
                                                                ng-click='updateOrderParameterDetails(GlobalOrderId)'
                                                                title="Update Details"
                                                                class="btn btn-primary btn-sm">Update</button>
                                                            <button ng-click='cancelEditOrder()' title="Cancel"
                                                                class="btn btn-default btn-sm">Cancel</button>
                                                        </td>

                                                    </tr>
                                                    <!--- edit order parameter form --->
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <!-- Add new parameter form-->
                    <div class="fixed_table_view mT30" ng-if="testProductParamenters.length">
                        <div class="col-sm-12 headingGray" style="margin: 14px 0px;"><strong class="text-left">Add More
                                Parameter</strong></div>
                        <form method="POST" name="addNewParametersForm" novalidate>
                            <table border="1" class="col-sm-12 table-striped table-condensed cf">
                                <tbody>
                                    <tr ng-repeat="categoryParameters in testProductParamenters">
                                        <td ng-show="categoryParameters.categoryParams.length" colspan="10"
                                            data-title="Test Parameter Category Name">
                                            <strong>[[categoryParameters.categoryName]]</strong>
                                            <table class="col-sm-12 table-striped table-condensed">
                                                <thead class="cf">
                                                    <tr>
                                                        <th class="width20">
                                                            <label class="sortlabel">Test Parameter </label>
                                                        </th>
                                                        <th class="width10">
                                                            <label class="sortlabel"
                                                                ng-click="sortBy('equipment_name')">Equipment </label>
                                                            <span class="sortorder"
                                                                ng-show="predicate === 'equipment_name'"
                                                                ng-class="{reverse:reverse}"></span>
                                                        </th>
                                                        <th class="width10">
                                                            <label class="sortlabel">Method </label>
                                                        </th>

                                                        <th class="width10">
                                                            <label class="sortlabel">Claim(%)</label>
                                                        </th>
                                                        <th class="width10">
                                                            <label class="sortlabel">Claim Unit</label>
                                                        </th>
                                                        <th class="width10">
                                                            <label title="Standard Value Type"
                                                                class="sortlabel">Standard Value Type</label>
                                                        </th>
                                                        <th class="width10">
                                                            <label title="Standard Value From"
                                                                class="sortlabel">Standard Value From</label>
                                                        </th>
                                                        <th class="width10">
                                                            <label title="Standard Value To" class="sortlabel">Standard
                                                                Value To</label>
                                                        </th>
                                                        <th class="width10">
                                                            <label class="sortlabel">NABL </label>
                                                        </th>
                                                        <th class="width10">
                                                            <label class="sortlabel">Time Taken</label>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">
                                                    <tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]"
                                                        ng-if="subCategoryParameters.description.length">
                                                        <td id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]"
                                                            ng-bind-html="subCategoryParameters.test_parameter_name">
                                                        </td>
                                                        <td class="width100pc" colspan="10">
                                                            [[subCategoryParameters.description]]</td>
                                                        <td>
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.product_test_dtl_id]]"
                                                                name="order_parameters_detail[product_test_parameter][]">
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.test_parameter_id]]"
                                                                name="order_parameters_detail[test_parameter_id][]">
                                                            <input type="hidden" value=""
                                                                name="order_parameters_detail[equipment_type_id][]">
                                                            <input type="hidden" value=""
                                                                name="order_parameters_detail[method_id][]">
                                                            <input type="hidden" value=""
                                                                name="order_parameters_detail[order_parameter_nabl_scope][]">
                                                            <input type="hidden" value=""
                                                                name="order_parameters_detail[standard_value_type][]">
                                                            <input type="hidden" value=""
                                                                name="order_parameters_detail[standard_value_from][]">
                                                            <input type="hidden" value=""
                                                                name="order_parameters_detail[standard_value_to][]">
                                                            <input type="hidden" value=""
                                                                name="order_parameters_detail[time_taken_days][]"
                                                                ng-model="orderProductTest.time_taken_days">
                                                            <input type="hidden" value=""
                                                                name="order_parameters_detail[time_taken_mins][]"
                                                                ng-model="orderProductTest.time_taken_mins">
                                                            <input type="hidden" value=""
                                                                name="order_parameters_detail[cost_price][]">
                                                            <input type="hidden" value=""
                                                                name="order_parameters_detail[selling_price][]"
                                                                ng-model="orderProductTest.selling_price"
                                                                id="selling_price[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden" value="0"
                                                                name="order_parameters_detail[test_param_alternative_id][]">
                                                            <input type="hidden"
                                                                name="order_parameters_detail[claim_value][]" value="">
                                                            <input type="hidden" value=""
                                                                name="order_parameters_detail[claim_value_unit][]">
                                                        </td>
                                                    </tr>
                                                    <tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]"
                                                        ng-if="!subCategoryParameters.description.length">
                                                        <td class="width20"
                                                            id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]"
                                                            data-title="Test Parameter Name"><span
                                                                ng-bind-html="subCategoryParameters.test_parameter_name"></span>
                                                        </td>
                                                        <td class="width10"
                                                            id="equipment_name_text[[subCategoryParameters.product_test_dtl_id]]"
                                                            data-title="Equipment Name">
                                                            [[subCategoryParameters.equipment_name]]</td>
                                                        <td class="width10"
                                                            id="method_name_text[[subCategoryParameters.product_test_dtl_id]]"
                                                            data-title="Method Name">
                                                            [[subCategoryParameters.method_name]]

                                                        <td class="width10" align="left"
                                                            id="claim_value_text[[subCategoryParameters.product_test_dtl_id]]"
                                                            data-title="Claim Value">
                                                            <input valid-number
                                                                ng-if="subCategoryParameters.claim_dependent"
                                                                type="text" class="form-control"
                                                                id="claim_value[[subCategoryParameters.product_test_dtl_id]]"
                                                                ng-change="funChangeTestParameterValueAccToClaim('[[subCategoryParameters.product_test_dtl_id]]','add')"
                                                                ng-model="orderProductTest.claim_value[[subCategoryParameters.product_test_dtl_id]]"
                                                                name="order_parameters_detail[claim_value][]"
                                                                placeholder="Claim Value" style="width: 105px;"
                                                                ng-required="true">
                                                            <input ng-if="subCategoryParameters.claim_dependent"
                                                                type="hidden" class="form-control"
                                                                id="claim_dependent[[subCategoryParameters.product_test_dtl_id]]"
                                                                ng-model="orderProductTest.claim_dependent[[subCategoryParameters.product_test_dtl_id]]"
                                                                name="order_parameters_detail[claim_dependent][]"
                                                                placeholder="Claim Value" style="width: 105px;">
                                                            <span ng-if="!subCategoryParameters.claim_dependent">
                                                                <input ng-if="!subCategoryParameters.claim_dependent"
                                                                    type="hidden"
                                                                    id="claim_value[[subCategoryParameters.product_test_dtl_id]]"
                                                                    ng-model="orderProductTest.claim_value[[subCategoryParameters.product_test_dtl_id]]"
                                                                    name="order_parameters_detail[claim_value][]"
                                                                    value="">-
                                                            </span>
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.standard_value_from]]"
                                                                id="org_standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.standard_value_to]]"
                                                                id="org_standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
                                                        </td>
                                                        <td align="left" class="width10"
                                                            id="claim_value_unit_text[[subCategoryParameters.product_test_dtl_id]]"
                                                            data-title="Claim Value Unit">
                                                            <input valid-alphabet
                                                                ng-if="subCategoryParameters.claim_dependent"
                                                                type="text" class="form-control"
                                                                id="claim_value_unit[[subCategoryParameters.product_test_dtl_id]]"
                                                                ng-model="orderProductTest.claim_value_unit[[subCategoryParameters.product_test_dtl_id]]"
                                                                name="order_parameters_detail[claim_value_unit][]"
                                                                ng-change="funChangeTestParameterValueAccToClaimUnit('[[subCategoryParameters.product_test_dtl_id]]')"
                                                                placeholder="Claim Unit" style="width: 105px;"
                                                                ng-required="true">
                                                            <span ng-if="!subCategoryParameters.claim_dependent">
                                                                <input ng-if="!subCategoryParameters.claim_dependent"
                                                                    type="hidden"
                                                                    id="claim_value_unit[[subCategoryParameters.product_test_dtl_id]]"
                                                                    ng-model="orderProductTest.claim_value_unit[[subCategoryParameters.product_test_dtl_id]]"
                                                                    name="order_parameters_detail[claim_value_unit][]"
                                                                    value="">-
                                                            </span>
                                                        </td>
                                                        <td class="width10" data-title="Standard Value Type">
                                                            <span ng-if="subCategoryParameters.standard_value_type"
                                                                ng-bind-html="subCategoryParameters.standard_value_type | capitalize">
                                                            </span>
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.standard_value_type]]"
                                                                id="org_standard_value_type[[subCategoryParameters.product_test_dtl_id]]">
                                                        </td>
                                                        <td class="width10"
                                                            id="standard_value_from_text[[subCategoryParameters.product_test_dtl_id]]"
                                                            data-title="Standard Value From">
                                                            <span ng-if="subCategoryParameters.standard_value_from!=''">
                                                                <span>[[subCategoryParameters.standard_value_from]]</span>
                                                            </span>
                                                            <span
                                                                ng-if="subCategoryParameters.standard_value_from == ''">
                                                                -
                                                            </span>
                                                        </td>
                                                        <td class="width10"
                                                            id="standard_value_to_text[[subCategoryParameters.product_test_dtl_id]]"
                                                            data-title="Standard Value To">
                                                            <span ng-if="subCategoryParameters.standard_value_to!=''">
                                                                <span>[[subCategoryParameters.standard_value_to]]</span>
                                                            </span>
                                                            <span ng-if="subCategoryParameters.standard_value_to == ''">
                                                                -
                                                            </span>
                                                        </td>
                                                        <td class="width10"
                                                            id="nabl_scope_text[[subCategoryParameters.product_test_dtl_id]]"
                                                            data-title="NABL scope">
                                                            [[(subCategoryParameters.test_parameter_nabl_scope=='0' ||
                                                            !subCategoryParameters.test_parameter_nabl_scope ) ? 'No' :
                                                            'Yes']]
                                                        </td>
                                                        <td class="width10" data-title="time taken">
                                                            <span
                                                                id="time_taken_days_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_days
                                                                ? subCategoryParameters.time_taken_days+' Days' :
                                                                '']]</span><br>
                                                            <span
                                                                id="time_taken_mins_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_mins
                                                                ? subCategoryParameters.time_taken_mins+' Mins' :
                                                                '']]</span>
                                                        </td>
                                                        <td>

                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.product_test_dtl_id]]"
                                                                name="order_parameters_detail[product_test_parameter][]"
                                                                ng-model="orderProductTest.product_test_parameter"
                                                                id="product_test_parameter[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.test_parameter_id]]"
                                                                name="order_parameters_detail[test_parameter_id][]"
                                                                ng-model="orderProductTest.test_parameter_id"
                                                                id="test_parameter_id[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.equipment_type_id]]"
                                                                name="order_parameters_detail[equipment_type_id][]"
                                                                ng-model="orderProductTest.equipment_type_id"
                                                                id="equipment_type_id[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.method_id]]"
                                                                name="order_parameters_detail[method_id][]"
                                                                ng-model="orderProductTest.method_id"
                                                                id="method_id[[subCategoryParameters.product_test_dtl_id]]">

                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.test_parameter_nabl_scope]]"
                                                                name="order_parameters_detail[order_parameter_nabl_scope][]"
                                                                ng-model="orderProductTest.test_parameter_nabl_scope"
                                                                id="test_parameter_nabl_scope[[subCategoryParameters.product_test_dtl_id]]">

                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.standard_value_type]]"
                                                                name="order_parameters_detail[standard_value_type][]"
                                                                ng-model="orderProductTest.standard_value_type"
                                                                id="standard_value_type[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.standard_value_from]]"
                                                                name="order_parameters_detail[standard_value_from][]"
                                                                ng-model="orderProductTest.standard_value_from"
                                                                id="standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.standard_value_to]]"
                                                                name="order_parameters_detail[standard_value_to][]"
                                                                ng-model="orderProductTest.standard_value_to"
                                                                id="standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.time_taken_days]]"
                                                                name="order_parameters_detail[time_taken_days][]"
                                                                ng-model="orderProductTest.time_taken_days"
                                                                id="time_taken_days[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.time_taken_mins]]"
                                                                name="order_parameters_detail[time_taken_mins][]"
                                                                ng-model="orderProductTest.time_taken_mins"
                                                                id="time_taken_mins[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.cost_price]]"
                                                                name="order_parameters_detail[cost_price][]"
                                                                ng-model="orderProductTest.cost_price"
                                                                id="cost_price[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden"
                                                                value="[[subCategoryParameters.selling_price]]"
                                                                name="order_parameters_detail[selling_price][]"
                                                                ng-model="orderProductTest.selling_price"
                                                                id="selling_price[[subCategoryParameters.product_test_dtl_id]]">
                                                            <input type="hidden" value="0"
                                                                name="order_parameters_detail[test_param_alternative_id][]"
                                                                ng-model="orderProductTest.test_param_alternative_id"
                                                                id="test_param_alternative_id[[subCategoryParameters.product_test_dtl_id]]">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td align="right" colspan="10">
                                            <input type="hidden" name="order_id" ng-value="orderPopUpID">
                                            <button type="button" ng-disabled="addNewParametersForm.$invalid"
                                                title="Update Details" class="btn btn-primary btn-sm"
                                                ng-click="funUpdateOrderParameterDetailList(orderPopUpID)">Save</button>
                                            <button ng-click='cancelAddOrder()' title="Cancel"
                                                class="btn btn-default btn-sm">Cancel</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                    <!-- Add new parameter form-->
                </div>
            </div>
        </div>
    </div>
</div>

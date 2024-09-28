<div class="row edit" style="background: #ccc;padding: 5;margin: 0;width:100%;">Products & Tests Detail</div>

<div class="row mT10">	
    
    <!--Test Product-->
    <div class="col-xs-4 form-group">
        <label for="product_id">Testing Product <em class="asteriskRed">*</em></label>
        <select class="form-control" 
            name="product_id" 
            ng-model="saveAsOrder.product_id.selectedOption" 
            id="product_id"
            ng-required="true"
            ng-options="testProduct.product_name for testProduct in testProductList track by testProduct.product_id">
        </select>
        <span ng-messages="erpSaveAsOrderForm.product_id.$error" ng-if='erpSaveAsOrderForm.product_id.$dirty  || erpSaveAsOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Testing Product is required</span>
        </span>
    </div>
    <!--/Test Product-->
    
    <!--Product Tests-->
    <div class="col-xs-4 form-group">
        <label for="test_standard">Product Tests<em class="asteriskRed">*</em></label>
        <a href="javascript:;" ng-click="funAddMoreSaveAsParameters(saveAsOrder.selectedTestId,order_id);" class="generate" aria-hidden="false" style=""><i class="fa fa-plus" aria-hidden="true"></i>Add More</a>
        <select
            class="form-control"
            name="product_test_id"
            ng-model="saveAsOrder.test_id.selectedOption" 
            id="product_test_id"
            ng-required="true"
            ng-options="testRecevied.test_code for testRecevied in productTestListing track by testRecevied.test_id">
        </select>
        <span ng-messages="erpSaveAsOrderForm.product_test_id.$error" ng-if='erpSaveAsOrderForm.product_test_id.$dirty  || erpSaveAsOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Product Tests is required</span>
        </span>
    </div>
    <!--/Product Tests-->
    
    <!--Billing Type-->
    <div>
        <input type="hidden" name="test_standard" ng-model="saveAsOrder.test_standard" ng-value="saveAsOrder.test_standard" id="test_standard">
        <input type="hidden" name="product_category_id" id="product_category_id" ng-model="saveAsOrder.test_standard" ng-value="saveAsOrder.product_category_id">
    </div>
    <!--/Billing Type-->
    
    <!--Header Note-->                         
    <div class="col-xs-4 form-group" ng-if="saveAsHeaderNoteStatus">
        <label for="header_note">Header note<em class="asteriskRed">*</em></label>
        <input class="form-control"
            name="header_note" 			
            ng-model="saveAsOrder.header_note"
            id="header_note"
            ng-change="getAutoSearchHeaderNoteMatches(saveAsOrder.header_note);"
            placeholder="Header note"
            ng-required='true'
            autocomplete="off">
        <span ng-messages="erpCreateOrderForm.header_note.$error" ng-if='erpCreateOrderForm.header_note.$dirty || erpCreateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Header note is required</span>
        </span>
        <ul ng-if="showHeaderNoteAutoSearchList" class="autoSearch">
            <li ng-if="headerNotesList.length" ng-repeat="headerNoteObj in headerNotesList" ng-click="funSetSelectedHeaderNote(headerNoteObj.name,'edit');" ng-bind="headerNoteObj.name"></li>
        </ul>        
    </div>
    <!--/Header Note-->
    
    <!--Stability Note-->                         
    <div class="col-xs-4 form-group" ng-if="saveAsRealTimeStabilityStatus">
        <label for="stability_note">Real Time Stability<em class="asteriskRed">*</em></label>
        <input class="form-control"
            name="stability_note" 			
            ng-model="saveAsOrder.stability_note"
            id="stability_note"
            ng-change="getAutoSearchStabilityNoteMatches(saveAsOrder.stability_note);"
            placeholder="Stability Note"
            ng-required='true'
            autocomplete="off">
        <span ng-messages="erpCreateOrderForm.stability_note.$error" ng-if='erpCreateOrderForm.stability_note.$dirty || erpCreateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Real Time Stability is required</span>
        </span>
        <ul ng-if="showStabilityNoteAutoSearchList" class="autoSearch">
            <li ng-if="stabilityNotesList.length" ng-repeat="stabilityNoteObj in stabilityNotesList" ng-click="funSetSelectedStabilityNote(stabilityNoteObj.name,'edit');" ng-bind="stabilityNoteObj.name"></li>
        </ul>        
    </div>
    <!--/Stability Note-->
    
    <!--Test to perform-->
    <div ng-show="saveAsOrderParameters.length">                    
        <div class="col-xs-12 form-group">            
            <div class="row">
                <div id="no-more-tables" class="fixed_table">
                    <table class="col-sm-12 table-striped table-condensed cf">
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
                                    <label class="sortlabel" ng-click="sortBy('time_taken')">Time Taken</label>
                                    <span class="sortorder" ng-show="predicate === 'time_taken'" ng-class="{reverse:reverse}"></span>
                                </th>
                                <th colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="tableParameterList">									
                            <tr ng-repeat="categoryParameters in saveAsOrderParameters">
                                <td ng-show="categoryParameters.categoryParams.length" colspan="9" data-title="Test Parameter Category Name">
                                    <strong>[[categoryParameters.categoryName]]</strong>
                                    <table class="col-sm-12 table-striped table-condensed">
                                        <tbody ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">
                                            <tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.description.length">
                                                <td id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" ng-bind-html="subCategoryParameters.test_parameter_name"></td>
                                                <td class="width100pc" colspan="7">[[subCategoryParameters.description]]</td>
                                                <td>
                                                    <input type="hidden" value="[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[product_test_parameter][]">
                                                    <input type="hidden" value="[[subCategoryParameters.test_parameter_id]]" name="order_parameters_detail[test_parameter_id][]" >
                                                    <input type="hidden" value="" name="order_parameters_detail[equipment_type_id][]">
                                                    <input type="hidden" value="" name="order_parameters_detail[method_id][]" >
                                                    <input type="hidden" value="" name="order_parameters_detail[standard_value_type][]" >
                                                    <input type="hidden" value="" name="order_parameters_detail[standard_value_from][]" >
                                                    <input type="hidden" value="" name="order_parameters_detail[standard_value_to][]">
                                                    <input type="hidden" value="" name="order_parameters_detail[time_taken_days][]" ng-model="orderProductTest.time_taken_days">
                                                    <input type="hidden" value="" name="order_parameters_detail[time_taken_mins][]" ng-model="orderProductTest.time_taken_mins">
                                                    <input type="hidden" value="" name="order_parameters_detail[cost_price][]">
                                                    <input type="hidden" value="" name="order_parameters_detail[selling_price][]" ng-model="orderProductTest.selling_price" id="selling_price[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="0" name="order_parameters_detail[test_param_alternative_id][]">
                                                    <input type="hidden" name="order_parameters_detail[claim_value][]" value="">
                                                    <input type="hidden"  value="" name="order_parameters_detail[claim_value_unit][]">
                                                </td>
                                            </tr>
                                            <tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="!subCategoryParameters.description.length">
                                                <td id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name"><span ng-bind-html="subCategoryParameters.test_parameter_name"></span></td>
                                                <td id="equipment_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Equipment Name">[[subCategoryParameters.equipment_name]]</td>
                                                <td id="method_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Method Name">[[subCategoryParameters.method_name]]</td>
                                                <td align="left" id="saveas_claim_value_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Claim Value">
                                                    <input ng-if="subCategoryParameters.claim_dependent" valid-number type="text" class="form-control" id="saveas_claim_value[[subCategoryParameters.product_test_dtl_id]]" ng-change="funChangeSaveAsTestParameterValueAccToClaim('[[subCategoryParameters.product_test_dtl_id]]','add')" ng-value="subCategoryParameters.claim_value" ng-model="orderProductTest.claim_value[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[claim_value][]" placeholder="Claim Value" style="width: 105px;">
                                                    <input ng-if="subCategoryParameters.claim_dependent" type="hidden" name="order_parameters_detail[claim_dependent][]" value="[[subCategoryParameters.claim_dependent]]">
                                                    <input ng-if="!subCategoryParameters.claim_dependent" type="hidden" id="saveas_claim_value[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderProductTest.claim_value[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[claim_value][]" value="">
                                                    <input type="hidden" value="[[subCategoryParameters.productTestDtlStdValFrom]]" id="saveas_org_standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.productTestDtlStdValTo]]" id="saveas_org_standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
                                                </td>																	
                                                <td align="left" id="saveas_claim_value_unit_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Claim Value Unit">
                                                    <input ng-if="subCategoryParameters.claim_dependent" type="text" class="form-control" id="saveas_claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderProductTest.claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" ng-value="subCategoryParameters.claim_value_unit" name="order_parameters_detail[claim_value_unit][]" ng-change="funChangeSaveAsTestParameterValueAccToClaimUnit('[[subCategoryParameters.product_test_dtl_id]]')" placeholder="Claim Unit" style="width: 105px;">
                                                    <input ng-if="!subCategoryParameters.claim_dependent" type="hidden" id="saveas_claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderProductTest.claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[claim_value_unit][]" value="">
                                                </td>																	
                                                <td id="saveas_standard_value_from_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value From">
                                                    <span ng-if="subCategoryParameters.standard_value_from!=''"><span>[[subCategoryParameters.standard_value_from]]</span></span>
                                                    <span ng-if="subCategoryParameters.standard_value_from == ''"></span>
                                                </td>
                                                <td id="saveas_standard_value_to_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value To">
                                                    <span ng-if="subCategoryParameters.standard_value_to!=''"><span>[[subCategoryParameters.standard_value_to]]</span></span>
                                                    <span ng-if="subCategoryParameters.standard_value_to == ''"></span>
                                                </td>
                                                <td data-title="time taken">
                                                    <span id="time_taken_days_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_days ? subCategoryParameters.time_taken_days+' Days' : '']]</span><br>
                                                    <span id="time_taken_mins_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_mins ? subCategoryParameters.time_taken_mins+' Mins' : '']]</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" value="[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[product_test_parameter][]" ng-model="orderProductTest.product_test_parameter" id="product_test_parameter[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.test_parameter_id]]" name="order_parameters_detail[test_parameter_id][]" ng-model="orderProductTest.test_parameter_id" id="test_parameter_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.equipment_type_id]]" name="order_parameters_detail[equipment_type_id][]" ng-model="orderProductTest.equipment_type_id" id="equipment_type_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.method_id]]" name="order_parameters_detail[method_id][]" ng-model="orderProductTest.method_id" id="method_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_type]]" name="order_parameters_detail[standard_value_type][]" ng-model="orderProductTest.standard_value_type" id="saveas_standard_value_type[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_from]]" name="order_parameters_detail[standard_value_from][]" ng-model="orderProductTest.standard_value_from" id="saveas_standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_to]]" name="order_parameters_detail[standard_value_to][]" ng-model="orderProductTest.standard_value_to" id="saveas_standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.time_taken_days]]" name="order_parameters_detail[time_taken_days][]" ng-model="orderProductTest.time_taken_days" id="time_taken_days[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.time_taken_mins]]" name="order_parameters_detail[time_taken_mins][]" ng-model="orderProductTest.time_taken_mins" id="time_taken_mins[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.cost_price]]" name="order_parameters_detail[cost_price][]" ng-model="orderProductTest.cost_price" id="cost_price[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.selling_price]]" name="order_parameters_detail[selling_price][]" ng-model="orderProductTest.selling_price" id="selling_price[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="0" name="order_parameters_detail[test_param_alternative_id][]" ng-model="orderProductTest.test_param_alternative_id" id="test_param_alternative_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <button type="button" title="Select Alternative" class="btn btn-primary btn-sm" ng-click="funSaveAsAlternativeTestParameterPopup([[subCategoryParameters.product_test_dtl_id]])">A</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>											
                            </tr>
                            <tr ng-repeat="categoryParameters in testProductParamentersSaveAs">
                                <td ng-show="categoryParameters.categoryParams.length" colspan="9" data-title="Test Parameter Category Name">
                                    <strong>[[categoryParameters.categoryName]]</strong>
                                    <table class="col-sm-12 table-striped table-condensed">
                                        <tbody ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">
                                            <tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="!subCategoryParameters.description.length">
                                                <td id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name"><span ng-bind-html="subCategoryParameters.test_parameter_name"></span></td>
                                                <td id="equipment_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Equipment Name">[[subCategoryParameters.equipment_name]]</td>
                                                <td id="method_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Method Name">[[subCategoryParameters.method_name]]</td>
                                                <td align="left" id="saveas_claim_value_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Claim Value">
                                                    <input valid-number ng-if="subCategoryParameters.claim_dependent" type="text" class="form-control" id="saveas_claim_value[[subCategoryParameters.product_test_dtl_id]]" ng-change="funChangeSaveAsTestParameterValueAccToClaim('[[subCategoryParameters.product_test_dtl_id]]','add')" ng-model="orderProductTest.claim_value[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[claim_value][]" placeholder="Claim Value" style="width: 105px;">
                                                    <input ng-if="subCategoryParameters.claim_dependent" type="hidden" name="order_parameters_detail[claim_dependent][]" value="[[subCategoryParameters.claim_dependent]]">
                                                    <input ng-if="!subCategoryParameters.claim_dependent" type="hidden" id="saveas_claim_value[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderProductTest.claim_value[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[claim_value][]"  value="">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_from]]" id="saveas_org_standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_to]]" id="saveas_org_standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
                                                </td>																	
                                                <td align="left" id="saveas_claim_value_unit_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Claim Value Unit">
                                                    <input ng-if="subCategoryParameters.claim_dependent" type="text" class="form-control" id="saveas_claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderProductTest.claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[claim_value_unit][]" ng-change="funChangeSaveAsTestParameterValueAccToClaimUnit('[[subCategoryParameters.product_test_dtl_id]]')" placeholder="Claim Unit" style="width: 105px;">
                                                    <span ng-if="!subCategoryParameters.claim_dependent">
                                                        <input type="hidden" id="saveas_claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" ng-model="orderProductTest.claim_value_unit[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[claim_value_unit][]" value="">
                                                    </span>
                                                </td>																	
                                                <td id="saveas_standard_value_from_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value From">
                                                    <span ng-if="subCategoryParameters.standard_value_from!=''"><span>[[subCategoryParameters.standard_value_from]]</span></span>
                                                    <span ng-if="subCategoryParameters.standard_value_from == ''"></span>
                                                </td>
                                                <td id="saveas_standard_value_to_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value To">
                                                    <span ng-if="subCategoryParameters.standard_value_to!=''"><span>[[subCategoryParameters.standard_value_to]]</span></span>
                                                    <span ng-if="subCategoryParameters.standard_value_to == ''"></span>
                                                </td>
                                                <td data-title="time taken">
                                                    <span id="time_taken_days_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_days ? subCategoryParameters.time_taken_days+' Days' : '']]</span><br>
                                                    <span id="time_taken_mins_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_mins ? subCategoryParameters.time_taken_mins+' Mins' : '']]</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" value="[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[product_test_parameter][]" ng-model="orderProductTest.product_test_parameter" id="product_test_parameter[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.test_parameter_id]]" name="order_parameters_detail[test_parameter_id][]" ng-model="orderProductTest.test_parameter_id" id="test_parameter_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.equipment_type_id]]" name="order_parameters_detail[equipment_type_id][]" ng-model="orderProductTest.equipment_type_id" id="equipment_type_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.method_id]]" name="order_parameters_detail[method_id][]" ng-model="orderProductTest.method_id" id="method_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_type]]" name="order_parameters_detail[standard_value_type][]" ng-model="orderProductTest.standard_value_type" id="saveas_standard_value_type[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_from]]" name="order_parameters_detail[standard_value_from][]" ng-model="orderProductTest.standard_value_from" id="saveas_standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.standard_value_to]]" name="order_parameters_detail[standard_value_to][]" ng-model="orderProductTest.standard_value_to" id="saveas_standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.time_taken_days]]" name="order_parameters_detail[time_taken_days][]" ng-model="orderProductTest.time_taken_days" id="time_taken_days[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.time_taken_mins]]" name="order_parameters_detail[time_taken_mins][]" ng-model="orderProductTest.time_taken_mins" id="time_taken_mins[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.cost_price]]" name="order_parameters_detail[cost_price][]" ng-model="orderProductTest.cost_price" id="cost_price[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="[[subCategoryParameters.selling_price]]" name="order_parameters_detail[selling_price][]" ng-model="orderProductTest.selling_price" id="selling_price[[subCategoryParameters.product_test_dtl_id]]">
                                                    <input type="hidden" value="0" name="order_parameters_detail[test_param_alternative_id][]" ng-model="orderProductTest.test_param_alternative_id" id="test_param_alternative_id[[subCategoryParameters.product_test_dtl_id]]">
                                                    <button type="button" title="Select Alternative" class="btn btn-primary btn-sm" ng-click="funSaveAsAlternativeTestParameterPopup([[subCategoryParameters.product_test_dtl_id]])">A</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>											
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--/Test to perform-->
</div>
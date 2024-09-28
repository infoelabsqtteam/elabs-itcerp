<div class="row" style="background: #ccc;padding: 5;margin: 0;width:100%;">Products & Tests Detail</div>

<div class="row mT10">	

    <!--Test Product-->
    <div class="col-xs-4 form-group">
        <label for="product_id">Testing Product</label> :             
        <span ng-if="viewOrder.product_name.length" ng-bind="viewOrder.product_name"></span>
        <span ng-if="!viewOrder.product_name.length">-</span>
    </div>
    <!--/Test Product-->
    
    <!--Product As Per Customer-->
    <div ng-show="productTestList.length" class="col-xs-4 form-group" ng-if="viewOrder.product_as_per_customer.length">
        <label for="product_as_per_customer">Product As Per Customer</label> :         
        <span ng-if="viewOrder.product_as_per_customer.length" ng-bind="viewOrder.product_as_per_customer"></span>
        <span ng-if="!viewOrder.product_as_per_customer.length">-</span>
    </div>
    <!--/Product As Per Customer-->	
    
    <!--Product Tests-->
    <div class="col-xs-4 form-group">
        <label for="product_test_id">Product Tests<em class="asteriskRed">*</em></label> :         
        <span ng-if="viewOrder.test_code.length" ng-bind="viewOrder.test_code"></span>
        <span ng-if="!viewOrder.test_code.length">-</span>
    </div>
    <!--/Product Tests-->				

    <!--Test to perform-->
    <div ng-show="orderParametersList.length" class="panel">  
        <div class="col-xs-12 form-group view-record">
            <div class="row" style="margin: 0;">
                <div id="no-more-tables" class="fixed_table_view">
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
                                    <label class="sortlabel" ng-click="sortBy('standard_value_from')">Standard Value From </label>
                                    <span class="sortorder" ng-show="predicate === 'standard_value_from'" ng-class="{reverse:reverse}"></span>
                                </th>					
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('standard_value_to')">Standard Value To </label>
                                    <span class="sortorder" ng-show="predicate === 'standard_value_to'" ng-class="{reverse:reverse}"></span>
                                </th>					
                                <th>
                                    <label class="sortlabel" ng-click="sortBy('time_taken')">Time Taken</label>
                                    <span class="sortorder" ng-show="predicate === 'time_taken'" ng-class="{reverse:reverse}"></span>
                                </th>											
                            </tr>
                        </thead>
                        <tbody>									
                            <tr ng-repeat="categoryParameters in orderParametersList">
                                <td colspan="7" data-title="Test Parameter Category Name">
                                    <strong>[[categoryParameters.categoryName]]</strong>
                                    <table class="col-sm-12 table-striped table-condensed">
                                        <tbody>
                                            <tr ng-if="categoryParameters.categoryParams" ng-repeat="subCategoryParameters in categoryParameters.categoryParams">
                                                <td data-title="Test Parameter Name"><span ng-bind-html="subCategoryParameters.test_parameter_name"></span></td>
                                                <td data-title="Equipment Name">[[subCategoryParameters.equipment_name | capitalize]]</td>
                                                <td data-title="Method Name">[[subCategoryParameters.method_name | capitalize]]</td>
                                                <td data-title="Claim Value">
													<span ng-if="subCategoryParameters.claim_value!=''">
														[[subCategoryParameters.claim_value ? subCategoryParameters.claim_value : '-']]
														&nbsp;<span ng-if="subCategoryParameters.claim_value_unit!=''">[[subCategoryParameters.claim_value_unit | capitalizeAll]]</span>
													</span>
													<span ng-if="subCategoryParameters.claim_value == ''">
														-
													</span>
												</td>
                                                <td data-title="Standard Value From">
                                                    <span ng-if="subCategoryParameters.standard_value_from!=''">
														<span>[[subCategoryParameters.standard_value_from]]</span>
														&nbsp;<span ng-if="subCategoryParameters.claim_value_unit!=''">[[subCategoryParameters.claim_value_unit | capitalizeAll]]</span>
													</span>
													<span ng-if="subCategoryParameters.standard_value_from == ''">
														-
													</span>
                                                </td>
                                                <td data-title="Standard Value To">
													<span ng-if="subCategoryParameters.standard_value_to!=''">
														<span>[[subCategoryParameters.standard_value_to]]</span>
														&nbsp;<span ng-if="subCategoryParameters.claim_value_unit!=''"> [[subCategoryParameters.claim_value_unit | capitalizeAll]]</span>
													</span>
													<span ng-if="subCategoryParameters.standard_value_to == ''">
														-
													</span>
												</td>
                                                <td data-title="time taken">
                                                    <span id="time_taken_days_text[[subCategoryParameters.product_test_dtl_id]]">
                                                        [[subCategoryParameters.time_taken_days ? subCategoryParameters.time_taken_days+' Days' : '']]
                                                    </span>
                                                    <span ng-if="subCategoryParameters.time_taken_mins != '00:00'" id="time_taken_mins_text[[subCategoryParameters.product_test_dtl_id]]">
                                                        [[subCategoryParameters.time_taken_mins ? subCategoryParameters.time_taken_mins+' Mins' : '']]
                                                    </span>
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
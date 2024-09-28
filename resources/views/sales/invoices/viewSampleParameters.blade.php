<div class="row order-section">
    <!--Invoicing Rate-->
    <div class="col-xs-6 text-left" style="">Products &amp; Tests Detail</div>
    <div class="col-xs-6 text-right" ng-if="viewOrderReport.invoiceRate && stateAndCustomerWiseinvoiceRate">
        <label for="product_test_id">Invoicing Rate</label> :
        <span ng-if="viewOrderReport.invoiceRate != 0" class="color-green" ng-bind="viewOrderReport.invoiceRate"></span>
        <span ng-if="viewOrderReport.invoiceRate == 0" class="color-red" class="" ng-bind="viewOrderReport.invoiceRate"></span>
    </div>
    <!--/Invoicing Rate-->
</div>

<div class="row mT10">

    <!--Test Product-->
    <div class="col-xs-6 form-group">
        <label for="product_id">Testing Product</label> :
        <span ng-if="viewOrderReport.product_name.length" ng-bind="viewOrderReport.product_name"></span>
        <span ng-if="!viewOrderReport.product_name.length">-</span>
    </div>
    <!--/Test Product-->

    <!--Product Tests-->
    <div class="col-xs-6 form-group">
        <label for="product_test_id">Product Tests<em class="asteriskRed">*</em></label> :
        <span ng-if="viewOrderReport.test_code.length" ng-bind="viewOrderReport.test_code"></span>
        <span ng-if="!viewOrderReport.test_code.length">-</span>
    </div>
    <!--/Product Tests-->

</div>

<div class="row">
    <!--Test to perform-->
    <div ng-show="orderParametersList.length" class="panel">
        <div class="col-xs-12 form-group view-record">
            <div id="no-more-tables" class="fixed_table">
                <table border="1" class="col-sm-12 table-striped table-condensed cf">
                    <thead class="cf">
                        <tr>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('test_parameter_name')">Test Parameter Name</label>
                                <span class="sortorder" ng-show="predicate === 'test_parameter_name'" ng-class="{reverse:reverse}"></span>
                            </th>

                            <th>
                                <label class="sortlabel" ng-click="sortBy('equipment_name')">Equipment Name</label>
                                <span class="sortorder" ng-show="predicate === 'equipment_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('method_name')">Method Name</label>
                                <span class="sortorder" ng-show="predicate === 'method_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('detector_name')">Detector Name</label>
                                <span class="sortorder" ng-show="predicate === 'detector_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('invoicing_running_time')">Running Time</label>
                                <span class="sortorder" ng-show="predicate === 'invoicing_running_time'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('column_name')">Column Name</label>
                                <span class="sortorder" ng-show="predicate === 'column_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('instance_name')">Instance Name</label>
                                <span class="sortorder" ng-show="predicate === 'instance_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('no_of_injection')">No. of Injection</label>
                                <span class="sortorder" ng-show="predicate === 'no_of_injection'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('claim_dependent')">Claim(%)</label>
                                <span class="sortorder" ng-show="predicate === 'claim_dependent'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label title="Standard Value From" class="sortlabel" ng-click="sortBy('standard_value_from')">Standard Value From</label>
                                <span class="sortorder" ng-show="predicate === 'standard_value_from'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label title="Standard Value To" class="sortlabel" ng-click="sortBy('standard_value_to')">Standard Value To</label>
                                <span class="sortorder" ng-show="predicate === 'standard_value_to'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('time_taken')">Time Taken</label>
                                <span class="sortorder" ng-show="predicate === 'time_taken'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label>Result</label>
                            </th>
                            <th ng-if="invoiceRate">
                                <label class="sortlabel">Rates</label>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="categoryParameters in orderParametersList">
                            <td colspan="14" data-title="Test Parameter Category Name">
                                <table border="1" class="col-sm-12 table-striped table-condensed">
                                    <thead>
                                        <th ng-if="invoiceRate" colspan="9"><strong>[[categoryParameters.categoryName]]</strong></th>
                                        <th ng-if="!invoiceRate" colspan="8"><strong>[[categoryParameters.categoryName]]</strong></th>
                                    </thead>
                                    <tbody ng-repeat="subCategoryParameters in categoryParameters.categoryParams">
                                        <tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.description.length">
                                            <td id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" ng-bind-html="subCategoryParameters.test_parameter_name"></td>
                                            <td class="width100pc" colspan="9">[[subCategoryParameters.description]]</td>
                                        </tr>
                                        <tr ng-if="!subCategoryParameters.description.length">
                                            <td data-title="Test Parameter Name"><span ng-bind-html="subCategoryParameters.test_parameter_name"></td>
                                            <td data-title="Equipment Name"><span ng-bind-html="subCategoryParameters.equipment_name"></span></td>
                                            <td data-title="Method Name">[[subCategoryParameters.method_name]]</td>
                                            <td data-title="Detector Name">[[subCategoryParameters.detector_name]]</td>
                                            <td data-title="Running Time">[[subCategoryParameters.invoicing_running_time]]</td>
                                            <td data-title="Column Name">[[subCategoryParameters.column_name]]</td>
                                            <td data-title="Instance Name">[[subCategoryParameters.instance_name]]</td>
                                            <td data-title="No. of Injection">[[subCategoryParameters.no_of_injection]]</td>
                                            <td data-title="Claim Value"><span>[[subCategoryParameters.claim_value != 'NULL' ? subCategoryParameters.claim_value : '']]</span></td>
                                            <td data-title="Standard Value From">[[subCategoryParameters.standard_value_from]]&nbsp;<span ng-if="subCategoryParameters.claim_value_unit != 'NULL'"> [[subCategoryParameters.claim_value_unit | capitalize ]]</span></td>
                                            <td data-title="Standard Value To">[[subCategoryParameters.standard_value_to]]&nbsp;<span ng-if="subCategoryParameters.claim_value_unit != 'NULL'"> [[subCategoryParameters.claim_value_unit | capitalize ]]</span></td>
                                            <td data-title="time taken">
                                                <span id="time_taken_days_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_days ? subCategoryParameters.time_taken_days+' Days' : '']]</span>
                                                <span id="time_taken_mins_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_mins ? subCategoryParameters.time_taken_mins+' Mins' : '']]</span>
                                            </td>
                                            <td data-title="Test Result">[[subCategoryParameters.test_result]]</td>
                                            <td ng-if="invoiceRate" data-title="ITC Rates">
                                                <span ng-if="viewOrderReport.invoicing_type_id == 1" class="color-green">[[subCategoryParameters.selling_price]]</span>
                                                <span ng-if="viewOrderReport.invoicing_type_id == 4 && subCategoryParameters.invoicingRates != '0.00'" class="color-green">[[subCategoryParameters.invoicingRates]]</span>
                                                <span ng-if="viewOrderReport.invoicing_type_id == 4 && subCategoryParameters.invoicingRates == '0.00'" class="color-red">[[subCategoryParameters.invoicingRates]]</span>
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
    <!--/Test to perform-->
</div>

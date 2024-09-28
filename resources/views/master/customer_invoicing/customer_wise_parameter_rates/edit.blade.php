<div class="row" ng-if="editCustomerWiseProductRateDiv">

    <!--heading-->
    <div class="row header">
        <div role="new" class="navbar-form navbar-left">
            <div><strong>Edit Customer Wise Product : <span ng-if="customerListData.name">[[customerListData.name]]</span><span ng-if="customerWiseParameterRateEditListing.length">([[customerWiseParameterRateEditListing.length]])</span></strong></div>
        </div>
        <div role="new" class="navbar-form navbar-right">
            <div class="nav-custom">
                <input type="text" placeholder="Search" ng-model="filtercustomerWiseParameterRate" class="form-control ng-pristine ng-untouched ng-valid">
                <span ng-if="{{defined('ADD') && ADD}}">
                    <button type="button" class="btn btn-primary" ng-click="navigateFormPage('list');">Back</button>
                </span>
            </div>
        </div>
    </div>
    <!--/heading-->

    <!--display record-->
    <div class="row" id="no-more-tables">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <form method="POST" role="form" id="erpEditCustomerWiseProductRateForm" name="erpEditCustomerWiseProductRateForm" novalidate>
                        <table class="col-sm-12 table-striped table-condensed cf font15">
                            <thead class="cf">
                                <tr>
                                    <th>
                                        <label ng-click="sortBy('p_category_name')" class="sortlabel">Product Category</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'p_category_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('test_para_cat_name')" class="sortlabel">Parameter Category</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'test_para_cat_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('test_parameter_name')" class="sortlabel">Parameter Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'test_parameter_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('equipment_name')" class="sortlabel">Equipment Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'equipment_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th><label>Test Standard</label></th>
                                    <th>
                                        <label ng-click="sortBy('invoicing_rate')" class="sortlabel">Rate</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoicing_rate'" class="sortorder reverse"></span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr dir-paginate="customerWiseParameterRateObj in customerWiseParameterRateEditListing | filter:filtercustomerWiseParameterRate | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                                    <td data-title="Product Category" class="ng-binding">[[customerWiseParameterRateObj.p_category_name]]</td>
                                    <td data-title="Parameter Category" class="ng-binding">[[customerWiseParameterRateObj.test_para_cat_name]]</td>
                                    <td data-title="Parameter Name" class="ng-binding" ng-bind-html="customerWiseParameterRateObj.test_parameter_name"></td>
                                    <td data-title="Equipment Name" class="ng-binding">[[customerWiseParameterRateObj.equipment_name]]</td>
                                    <td data-title="Test Standard" class="ng-binding">
                                        <select class="form-control" ng-init="funEditSetSelectedTestStandard(customerWiseParameterRateObj.cir_test_standard_id,customerWiseParameterRateObj.cir_id)" name="cir_test_standard_id['[[customerWiseParameterRateObj.cir_id]]']" id="cir_test_standard_id_[[customerWiseParameterRateObj.cir_id]]" ng-model="editCustomerWiseParametersRateBottom.cir_test_standard_id_[[customerWiseParameterRateObj.cir_id]]" ng-options="item.test_std_name for item in customerWiseParameterRateObj.testStandardList track by item.test_std_id">
                                            <option value="">Select Test Standard</option>
                                        </select>
                                    </td>
                                    <td data-title="Test Standard" class="ng-binding">
                                        <input type="number" min="0" class="form-control invoicing_rate" ng-model="editCustomerWiseParametersRateBottom.invoicing_rate_[[customerWiseParameterRateObj.cir_id]]" name="invoicing_rate['[[customerWiseParameterRateObj.cir_id]]']" id="invoicing_rate_[[$index]]" class="form-control" ng-value="customerWiseParameterRateObj.invoicing_rate" placeholder="Rate">
                                    </td>
                                </tr>
                            </tbody>

                            <tfoot ng-if="customerWiseParameterRateEditListing.length">
                                <tr>
                                    <td colspan="8">
                                        <div class="box-footer clearfix">
                                            <dir-pagination-controls></dir-pagination-controls>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <!--Save Button-->
                                        <div class="row">
                                            <div class="col-xs-12 form-group text-right mT10">
                                                <label for="submit">{{ csrf_field() }}</label>
                                                <button type="button" ng-disabled="erpEditCustomerWiseProductRateForm.$invalid" class="btn btn-primary" ng-click="funUpdateCustomerWiseParametersRate(cirCustomerID)">Update</button>
                                                <button type="button" class="btn btn-default" ng-click="navigateFormPage('list');">Cancel</button>
                                            </div>
                                        </div>
                                        <!--Save Button-->
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

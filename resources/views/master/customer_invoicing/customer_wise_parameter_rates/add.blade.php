<div class="row" ng-if="addCustomerWiseParametersRateDiv">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row header-form">
                <span class="pull-left headerText"><strong>Add Customer Wise Parameters</strong></span>
                <span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="navigateFormPage('list')">Back</button></span>
            </div>
            <form method="POST" role="form" id="erpAddCustomerWiseParametersRateForm" name="erpAddCustomerWiseParametersRateForm" novalidate>
                <div class="row">
                    <div class="col-xs-3">
                        <label for="cir_city_id">Select Customer<em class="asteriskRed">*</em></label>
                        <!--<a title="Select Custome State" ng-init="funGetCountryStateTreeViewList()" ng-click="funShowCountryStateTreeViewPopup(19)" class='generate cursor-pointer'>Tree View</a>-->
                        <a title="Filter Customer City" class="generate mL5 cursor-pointer" ng-click="funShowCountryStateViewPopup(1)">Select Country</a>
                        <select class="form-control" name="cir_customer_id" id="cir_customer_id" ng-required="true" ng-model="addCustomerWiseParametersRateTop.cir_customer_id" ng-change="funSetSelectedCustomer(addCustomerWiseParametersRateTop.cir_customer_id.id);" ng-options="customerList.name for customerList in customerListData track by customerList.id">
                            <option value="">Select Customer</option>
                        </select>
                        <span ng-messages="erpAddCustomerWiseParametersRateForm.cir_customer_id.$error" ng-if="erpAddCustomerWiseParametersRateForm.cir_customer_id.$dirty || erpAddCustomerWiseParametersRateForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Customer is required</span>
                        </span>
                    </div>
                    <!--Branch-->
                    <div class="col-xs-3">
                        <label for="product_category_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="cir_division_id" id="cir_division_id" ng-model="addCustomerWiseParametersRateTop.cir_division_id" ng-options="item.name for item in divisionsCodeList track by item.id" ng-required='true'>
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpAddCustomerWiseParametersRateForm.cir_division_id.$error" ng-if='erpAddCustomerWiseParametersRateForm.cir_division_id.$dirty || erpAddCustomerWiseParametersRateForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <!--/Branch-->
                    <!--Department-->
                    <div class="col-xs-3">
                        <label for="product_category_id">Department<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="cir_product_category_id" id="product_category_id" ng-change="funGetParameterCatgeoryList(addCustomerWiseParametersRateTop.product_category_id.id)" ng-model="addCustomerWiseParametersRateTop.product_category_id" ng-options="item.name for item in parentCategoryList track by item.id" ng-required='true'>
                            <option value="">Select Department</option>
                        </select>
                        <span ng-messages="erpAddCustomerWiseParametersRateForm.product_category_id.$error" ng-if='erpAddCustomerWiseParametersRateForm.product_category_id.$dirty || erpAddCustomerWiseParametersRateForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Department is required</span>
                        </span>
                    </div>
                    <!--/Department-->

                    <div class="col-xs-3">
                        <label for="parameter_category_id">Parameter Category<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="parameter_category_id" id="parameter_category_id" ng-model="addCustomerWiseParametersRateTop.parameter_category_id" ng-change="funGetParameterListFromParaCategory()" ng-options="item.name for item in parametersCategoryList track by item.id" ng-required='true'>
                            <option value="">Select Parameter Category</option>
                        </select>
                        <span ng-messages="erpAddCustomerWiseParametersRateForm.parameter_category_id.$error" ng-if='erpAddCustomerWiseParametersRateForm.parameter_category_id.$dirty || erpAddCustomerWiseParametersRateForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Parameter Category is required</span>
                        </span>
                    </div>
                </div>
                <!-- /Customer-->

                <!-- Product Listing-->
                <div class="col-xs-12 mT30">

                    <div class="row header">
                        <div class="col-xs-4 text-left mT5"><label>Parameter Name<span ng-if="customerWiseParametersRateAddListing.length">([[customerWiseParametersRateAddListing.length]])</span></label></div>
                        <div class="col-xs-2 text-center mT5"><label>Equipment Name</div>
                        <div class="col-xs-2 text-center mT5"><label>Test Standard</div>
                        <div class="col-xs-2 text-center mT5"><label>Enter Rate</label></div>
                        <div class="col-xs-2 text-center"><input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search Product" ng-model="filterParameterRateList"></div>
                    </div>

                    <div class="row mT10" ng-if="customerWiseParametersRateAddListing">
                        <div class="paraList" dir-paginate="customerWiseParametersRateAddListingObj in customerWiseParametersRateAddListing | filter : filterParameterRateList | itemsPerPage : {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" ng-if="customerWiseParametersRateAddListingObj.name != 'description'">

                            <div class="col-xs-4 text-left" title="[[customerWiseParametersRateAddListingObj.test_parameter_id]]">
                                <span ng-bind-html="customerWiseParametersRateAddListingObj.test_parameter_name"></span>
                                <input type="hidden" name="cir_parameter_id[]" ng-model="addCustomerWiseParametersRateBottom.test_parameter_id_[[$index]]" ng-value="customerWiseParametersRateAddListingObj.test_parameter_id">
                            </div>
                            <div class="col-xs-2 text-center">
                                [[customerWiseParametersRateAddListingObj.equipment_name]]
                                <input type="hidden" name="cir_equipment_type_id[]" ng-model="addCustomerWiseParametersRateBottom.cir_equipment_type_id_[[$index]]" ng-value="customerWiseParametersRateAddListingObj.equipment_type_id">
                            </div>
                            <div ng-if="customerWiseParametersRateAddListingObj.test_standard" class="col-xs-2 text-center">
                                <select class="form-control" ng-init="funEditSetSelectedTestStandard(customerWiseParameterRateObj.cir_test_standard_id,$index)" name="cir_test_standard_id[]" id="cir_test_standard_id_[[$index]]" ng-model="addCustomerWiseParametersRateBottom.cir_test_standard_id_[[$index]]" ng-options="item.test_std_name for item in customerWiseParametersRateAddListingObj.test_standard track by item.test_std_id">
                                    <option value="">Select Test Standard</option>
                                </select>
                            </div>
                            <div class="col-xs-2 text-center">
                                <input type="number" min="0" class="form-control invoicing_rate" ng-model="addCustomerWiseParametersRateBottom.invoicing_rate_[[$index]]" name="invoicing_rate[]" id="invoicing_rate_[[$index]]" class="form-control" ng-value="customerWiseParametersRateAddListingObj.selected_invoicing_rate" placeholder="Rate">
                            </div>
                        </div>
                        <div ng-if="customerWiseParametersRateAddListing.length">
                            <div class="box-footer clearfix">
                                <dir-pagination-controls></dir-pagination-controls>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-2 form-group" ng-if="!customerWiseParametersRateAddListing.length">No record found.</div>
                </div>
                <!--/Product Listing----->

                <!--Save Button-->
                <div class="row" ng-if="customerWiseParametersRateAddListing">
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>
                        <button type="button" class="btn btn-primary" ng-click="funAddCustomerWiseParametersRate()">Save</button>
                        <button type="button" class="btn btn-default" ng-click="resetButton()">Reset</button>
                    </div>
                </div>
                <!--Save Button-->
            </form>
        </div>
    </div>
</div>

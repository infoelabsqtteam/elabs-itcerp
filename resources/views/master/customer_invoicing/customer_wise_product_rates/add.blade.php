<div class="row" ng-hide="addCustomerWiseProductRateDiv">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row header-form">
                <span class="pull-left headerText"><strong>Add Customer Wise Product</strong></span>
                <span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="navigateFormPage('list')">Back</button></span>
            </div>
            <form method="POST" role="form" id="erpAddCustomerWiseProductRateForm" name="erpAddCustomerWiseProductRateForm" novalidate>
                <div class="row">
                    <div class="col-xs-3">
                        <!--<span class="pull-left"><label for="cir_city_id">Select Customer<em class="asteriskRed">*</em><a ng-init="funGetStateCityTreeViewList()" title="Filter Customer City" class="generate mL5 cursor-pointer" ng-click="funShowStateCityTreeViewPopup(15)">Tree View</a></span>-->
                        <span class="pull-left"><label for="cir_city_id">Select Customer<em class="asteriskRed">*</em><a title="Filter Customer City" class="generate mL5 cursor-pointer" ng-click="funShowCountryStateViewPopup(1)">Select Country</a></span>
                        <span class="pull-right"><input type="checkbox" ng-true-value="true" ng-false-value="false" ng-init="for_fixed_rate" ng-model="for_fixed_rate" name="for_fixed_rate">&nbsp;For Fixed Rate</span></label>
                        <select class="form-control" name="cir_customer_id" id="cir_customer_id" ng-change="funGetCustomersCity(addCustomerWiseProductRate.cir_customer_id.selectedOption.city_id,addCustomerWiseProductRate.cir_customer_id.selectedOption.city_name,'add');" ng-required="true" ng-model="addCustomerWiseProductRate.cir_customer_id.selectedOption" ng-options="customerList.name for customerList in customerListData track by customerList.id">
                            <option value="">Select Customer</option>
                        </select>
                        <span ng-messages="erpAddCustomerWiseProductRateForm.cir_customer_id.$error" ng-if="erpAddCustomerWiseProductRateForm.cir_customer_id.$dirty || erpAddCustomerWiseProductRateForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Customer is required</span>
                        </span>
                    </div>

                    <div class="col-xs-3">
                        <label for="cir_city_id">Customer City<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="cir_city_id" ng-model="addCustomerWiseProductRate.cir_city_id.selectedOption" id="cir_city_id" ng-required='true' ng-options="item.name for item in stateCitiesList track by item.id">
                            <option value="">Customer City</option>
                        </select>
                        <span ng-messages="erpAddCustomerWiseProductRateForm.cir_city_id.$error" ng-if='erpAddCustomerWiseProductRateForm.cir_city_id.$dirty  || erpAddCustomerWiseProductRateForm.$submitted' role="alert">
                            <span ng-message="required" class="error">City is required</span>
                        </span>
                    </div>
                    <div class="col-xs-3">
                        <label for="cir_city_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="cir_division_id" ng-model="addCustomerWiseProductRate.divisionId.selectedOption" id="cir_division_id" ng-required='true' ng-options="item.name for item in divisionsCodeList track by item.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpAddCustomerWiseProductRateForm.cir_division_id.$error" ng-if='erpAddCustomerWiseProductRateForm.cir_division_id.$dirty  || erpAddCustomerWiseProductRateForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <div class="col-xs-3">
                        <label for="cir_city_id">Department<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="cir_product_category_id" ng-model="addCustomerWiseProductRate.deptID.selectedOption" id="cir_product_category_id" ng-required='true' ng-change="getProductAliasRateList(addCustomerWiseProductRate.deptID.selectedOption.id,for_fixed_rate);" ng-options="item.name for item in parentCategoryList track by item.id">
                            <option value="">Select Department</option>
                        </select>
                        <span ng-messages="erpAddCustomerWiseProductRateForm.cir_product_category_id.$error" ng-if='erpAddCustomerWiseProductRateForm.cir_product_category_id.$dirty  || erpAddCustomerWiseProductRateForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Department is required</span>
                        </span>
                    </div>
                    <div class="col-xs-3 form-group" ng-if="for_fixed_rate==true">
                        <label for="invoicing_rate">Rate:<em class="asteriskRed">*</em></label>
                        <input type="text" id="invoicing_rate" ng-model="addCustomerWiseProductRate.invoicing_rate" ng-required="true" name="invoicing_rate" class="form-control" placeholder="Rate">
                        <span ng-messages="erpAddCustomerWiseProductRateForm.invoicing_rate.$error" ng-if="erpAddCustomerWiseProductRateForm.invoicing_rate.$dirty || erpAddCustomerWiseProductRateForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Rate is required</span>
                        </span>
                    </div>
                </div>
                <!-- /Customer--->

                <!-- Product Listing--->
                <hr ng-if="for_fixed_rate==false">

                <div class="row" ng-if="for_fixed_rate==false">
                    <div class="header">
                        <div class="col-xs-4 text-left mT5"><label>Product Name</div>
                        <div class="col-xs-4 text-left mT5"><label>Product Alias Name<em class="asteriskRed">*</em></div>
                        <div class="col-xs-4 text-left mT5"><label>Enter Rate</label><span class="width30" style="float: right; margin-top: -5px;"><input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search Product" ng-model="filterInvoicingProductRateList"></span></div>
                    </div>

                    <div class="mT20" dir-paginate="productObj in productAliasRateList | filter : filterInvoicingProductRateList | itemsPerPage : {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:'-productObj.rate'">

                        <div class="col-xs-12">
                            <div class="col-xs-4 form-group text-left">[[productObj.product_name]]</div>
                            <div class="col-xs-4 form-group text-left">[[productObj.name]]
                                <input type="hidden" name="cir_c_product_id[]" ng-model="productObj.id" ng-value="productObj.id">
                            </div>

                            <div class="col-xs-4 form-group text-left">
                                <input type="number" min="0" ng-if="!productObj.rate" class="form-control invoicing_rate width30" ng-model="addCWiseProductRate.invoicing_rate_[[productObj.id]]" name="invoicing_rate[]" class="form-control" placeholder="Rate">
                                <input type="number" min="0" ng-if="productObj.rate" class="form-control invoicing_rate width30" ng-model="addCWiseProductRate.invoicing_rate_[[productObj.id]]" name="invoicing_rate[]" value="[[productObj.rate]]" class="form-control" placeholder="Rate">
                            </div>
                        </div>
                    </div>
                    <div ng-if="productAliasRateList.length">
                        <div class="box-footer clearfix">
                            <dir-pagination-controls></dir-pagination-controls>
                        </div>
                    </div>
                    <div ng-if="!productAliasRateList.length">No record found.</div>
                </div>
                <hr>
                <!--/Product Listing----->

                <!--Save Button-->
                <div class="row">
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>
                        <span ng-if="hideSaveBtn">
                            <button type="submit" ng-disabled="erpAddCustomerWiseProductRateForm.$invalid" class="btn btn-primary" ng-click="funAddCustomerWiseProductRate(cirCustomerID,addCustomerWiseProductRate.deptID.selectedOption,'add')">Save</button>
                        </span>
                        <span ng-if="hideUpdateBtn">
                            <button type="submit" ng-disabled="erpAddCustomerWiseProductRateForm.$invalid" class="btn btn-primary" ng-click="funAddCustomerWiseProductRate(cirCustomerID,addCustomerWiseProductRate.deptID.selectedOption,'modify')">Update</button>
                        </span>
                        <a ng-if="hideResetBtn" type="button" class="btn btn-default" ng-click="resetButton()">Reset</a>
                        <a ng-if="hideCancelBtn" type="button" class="btn btn-default" ng-click="navigateFormPage(cirStateID,'add')">Cancel</a>
                    </div>
                </div>
                <!--Save Button-->
            </form>
        </div>
    </div>
</div>

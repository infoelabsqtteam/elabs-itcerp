<div class="row" ng-hide="editCustomerWiseProductRateDiv">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row header-form">
                <span class="pull-left headerText"><strong>Edit Customer Wise Fixed Rate Product</strong></span>
                <span class="pull-right pull-custom">
                    <button type="button" class="btn btn-primary" ng-click="navigateFormPage('list');">Back</button>
                </span>
            </div>
            <form method="POST" role="form" id="erpEditCustomerWiseProductRateForm" name="erpEditCustomerWiseProductRateForm" novalidate>

                <!--Customer -->
                <div class="row">
                    <div class="col-xs-3 form-group"><label for="cir_customer_id">Select Customer:<em class="asteriskRed">*</em></label></div>
                    <div class="col-xs-6 form-group">
                        <select class="form-control" name="cir_customer_id" id="cir_customer_id" ng-required="true" ng-model="editCustomerWiseProductRate.cir_customer_id.selectedOption" ng-options="customerList.name for customerList in customerEditListData track by customerList.id">
                            <option value="">Select Customer</option>
                        </select>
                        <span ng-messages="erpEditCustomerWiseProductRateForm.cir_customer_id.$error" ng-if="erpEditCustomerWiseProductRateForm.cir_customer_id.$dirty || erpEditCustomerWiseProductRateForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Customer is required</span>
                        </span>
                    </div>
                </div>
                <!--/Customer -->

                <!--City-->
                <div class="row">
                    <div class="col-xs-3 form-group"><label for="cir_state_id">Select City:<em class="asteriskRed">*</em></label></div>
                    <div class="col-xs-6 form-group">
                        <select class="form-control" name="cir_city_id" ng-model="editCustomerWiseProductRate.cir_city_id.selectedOption" id="cir_city_id" ng-required='true' ng-options="item.name for item in stateCitiesList track by item.id">
                            <option value="">Select City</option>
                        </select>
                        <span ng-messages="erpEditCustomerWiseProductRateForm.cir_city_id.$error" ng-if='erpEditCustomerWiseProductRateForm.cir_city_id.$dirty  || erpEditCustomerWiseProductRateForm.$submitted' role="alert">
                            <span ng-message="required" class="error">City is required</span>
                        </span>
                    </div>

                </div>
                <!--/City--->
                <!--Department-->
                <div class="row">
                    <div class="col-xs-3 form-group"><label for="cir_state_id">Branch:<em class="asteriskRed">*</em></label></div>
                    <div class="col-xs-6 form-group">
                        <select class="form-control" name="cir_division_id" ng-model="editCustomerWiseProductRate.cir_division_id.selectedOption" id="cir_product_category_id" ng-required='true' ng-options="item.name for item in divisionsCodeList track by item.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpEditCustomerWiseProductRateForm.cir_division_id.$error" ng-if='erpEditCustomerWiseProductRateForm.cir_division_id.$dirty  || erpEditCustomerWiseProductRateForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                </div>
                <!--Department-->
                <!--Department-->
                <div class="row">
                    <div class="col-xs-3 form-group"><label for="cir_state_id">Department:<em class="asteriskRed">*</em></label></div>
                    <div class="col-xs-6 form-group">
                        <select class="form-control" name="cir_product_category_id" ng-model="editCustomerWiseProductRate.cir_product_category_id.selectedOption" id="cir_product_category_id" ng-required='true' ng-options="item.name for item in parentCategoryList track by item.id">
                            <option value="">Select Department</option>
                        </select>
                        <span ng-messages="erpEditCustomerWiseProductRateForm.cir_product_category_id.$error" ng-if='erpEditCustomerWiseProductRateForm.cir_product_category_id.$dirty  || erpEditCustomerWiseProductRateForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Department is required</span>
                        </span>
                    </div>
                </div>
                <!--Department-->
                <!-- Rates-->
                <div class="row">
                    <div class="col-xs-3 form-group"><label for="invoicing_rate">Rate:<em class="asteriskRed">*</em></label></div>
                    <div class="col-xs-6 form-group">
                        <input type="text" id="invoicing_rate" ng-model="editCustomerWiseProductRate.invoicing_rate" ng-required="true" name="invoicing_rate" class="form-control" placeholder="Rate">
                        <span ng-messages="erpEditCustomerWiseProductRateForm.invoicing_rate.$error" ng-if="erpEditCustomerWiseProductRateForm.invoicing_rate.$dirty || erpEditCustomerWiseProductRateForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Rate is required</span>
                        </span>
                    </div>
                </div>
                <!-- /Rates--->

                <!--Save Button-->
                <div class="row">
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>
                        <input type="hidden" ng-value="editCustomerWiseProductRate.cir_id" name="cir_id" ng-model="editCustomerWiseProductRate.cir_id">
                        <button type="submit" ng-disabled="erpEditCustomerWiseProductRateForm.$invalid" class="btn btn-primary" ng-click="funUpdateCustomerWiseFixedProductRate(editCustomerWiseProductRate.cir_id)">Update</button>
                        <button type="button" class="btn btn-default" ng-click="navigateFormPage('list');">Cancel</button>
                    </div>
                </div>
                <!--Save Button-->
            </form>
        </div>
    </div>
</div>

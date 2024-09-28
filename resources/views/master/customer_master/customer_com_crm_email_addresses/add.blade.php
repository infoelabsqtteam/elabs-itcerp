<div class="row" ng-hide="addCustomerComCrmFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row header-form">
                <span class="pull-left headerText"><strong>Add New CRM</strong></span>
            </div>

            <form method="POST" role="form" id="erpAddBWCustomerComCrmForm" name="erpAddBWCustomerComCrmForm" novalidate>

                <div class="row">

                    <!--Branch -->
                    <div class="col-xs-3 form-group">
                        <label for="cccea_division_id">Branch<em class="asteriskRed">*</em></label>
                        <select 
							class="form-control" 
							name="cccea_division_id"
							id="cccea_division_id_add"
                            ng-model="addBWCustomerComCrm.cccea_division_id" 
							ng-required="true"
                            ng-options="branchDropDownOption.name for branchDropDownOption in branchDropDownOptions track by branchDropDownOption.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpAddBWCustomerComCrmForm.cccea_division_id.$error" ng-if="erpAddBWCustomerComCrmForm.cccea_division_id.$dirty || erpAddBWCustomerComCrmForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <!--/Branch -->

                    <!--Department-->
                    <div class="col-xs-3 form-group">
                        <label for="cccea_product_category_id">Department<em class="asteriskRed">*</em></label>
                        <select 
							class="form-control" 
							name="cccea_product_category_id" 
							id="cccea_product_category_id_add"
                            ng-model="addBWCustomerComCrm.cccea_product_category_id"
                            ng-options="parentCategory.name for parentCategory in parentCategoryList track by parentCategory.id" 
							ng-required='true'>
                            <option value="">Select Department</option>
                        </select>
                        <span ng-messages="erpAddBWCustomerComCrmForm.cccea_product_category_id.$error" ng-if="erpAddBWCustomerComCrmForm.cccea_product_category_id.$dirty || erpAddBWCustomerComCrmForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Department is required</span>
                        </span>
                    </div>
                    <!--/Department-->

                    <!--CRM Name -->
                    <div class="col-xs-3 form-group">
                        <label for="cccea_name">CRM Name<em class="asteriskRed">*</em></label>
                        <input 
							type="text" 
							id="cccea_name_add" 
							ng-model="addBWCustomerComCrm.cccea_name"
                            ng-required="true" 
							name="cccea_name" 
							class="form-control"
                            placeholder="CRM Name">
                        <span ng-messages="erpAddBWCustomerComCrmForm.cccea_name.$error" ng-if="erpAddBWCustomerComCrmForm.cccea_name.$dirty || erpAddBWCustomerComCrmForm.$submitted" role="alert">
                            <span ng-message="required" class="error">CRM Name is required</span>
                        </span>
                    </div>
                    <!--/CRM Name-->

					<!--CRM Email -->
                    <div class="col-xs-3 form-group">
                        <label for="cccea_email">CRM Email<em class="asteriskRed">*</em></label>
                        <input 
							type="text" 
							id="cccea_email_add" 
							ng-model="addBWCustomerComCrm.cccea_email"
                            ng-required="true" 
							name="cccea_email" 
							class="form-control"
                            placeholder="CRM Email">
                        <span ng-messages="erpAddBWCustomerComCrmForm.cccea_email.$error" ng-if="erpAddBWCustomerComCrmForm.cccea_email.$dirty || erpAddBWCustomerComCrmForm.$submitted" role="alert">
                            <span ng-message="required" class="error">CRM Email is required</span>
                        </span>
                    </div>
                    <!--/CRM Email-->

                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>
                        <button type="submit" ng-disabled="erpAddBWCustomerComCrmForm.$invalid" class="btn btn-primary" ng-click="funAddBranchWiseCustomerComCrm()">Save</button>
                        <button type="button" class="btn btn-default" ng-click="resetButton()">Reset</button>
                    </div>
                    <!--Save Button-->
                </div>
            </form>
        </div>
    </div>
</div>

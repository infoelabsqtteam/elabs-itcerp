<div class="row" ng-hide="editCustomerComCrmFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row header-form">
                <span class="pull-left headerText"><strong>Edit CRM : <span ng-bind="editBWCustomerComCrm.credit_note_no"></span></strong></span>
                <span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></span>
            </div>
            <form method="POST" role="form" id="erpEditBWCustomerComCrmForm" name="erpEditBWCustomerComCrmForm" novalidate>

                <div class="row">

                    <!--Branch -->
                    <div class="col-xs-2 form-group">
                        <label for="cccea_division_id">Branch<em class="asteriskRed">*</em></label>
                        <select 
							class="form-control" 
							name="cccea_division_id"
							id="cccea_division_id_edit"
                            ng-model="editBWCustomerComCrm.cccea_division_id" 
							ng-required="true"
                            ng-options="branchDropDownOption.name for branchDropDownOption in branchDropDownOptions track by branchDropDownOption.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpEditBWCustomerComCrmForm.cccea_division_id.$error" ng-if="erpEditBWCustomerComCrmForm.cccea_division_id.$dirty || erpEditBWCustomerComCrmForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <!--/Branch -->

                    <!--Department-->
                    <div class="col-xs-2 form-group">
                        <label for="cccea_product_category_id">Department<em class="asteriskRed">*</em></label>
                        <select 
							class="form-control" 
							name="cccea_product_category_id" 
							id="cccea_product_category_id_edit"
                            ng-model="editBWCustomerComCrm.cccea_product_category_id"
                            ng-options="parentCategory.name for parentCategory in parentCategoryList track by parentCategory.id" 
							ng-required='true'>
                            <option value="">Select Department</option>
                        </select>
                        <span ng-messages="erpEditBWCustomerComCrmForm.cccea_product_category_id.$error" ng-if="erpEditBWCustomerComCrmForm.cccea_product_category_id.$dirty || erpEditBWCustomerComCrmForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Department is required</span>
                        </span>
                    </div>
                    <!--/Department-->

                    <!--CRM Name -->
                    <div class="col-xs-3 form-group">
                        <label for="cccea_name">CRM Name<em class="asteriskRed">*</em></label>
                        <input 
							type="text" 
							id="cccea_name_edit" 
							ng-model="editBWCustomerComCrm.cccea_name"
                            ng-required="true" 
							name="cccea_name" 
							class="form-control"
                            placeholder="CRM Name">
                        <span ng-messages="erpEditBWCustomerComCrmForm.cccea_name.$error" ng-if="erpEditBWCustomerComCrmForm.cccea_name.$dirty || erpEditBWCustomerComCrmForm.$submitted" role="alert">
                            <span ng-message="required" class="error">CRM Name is required</span>
                        </span>
                    </div>
                    <!--/CRM Name-->

                    <!--CRM Email -->
                    <div class="col-xs-3 form-group">
                        <label for="cccea_email">CRM Email<em class="asteriskRed">*</em></label>
                        <input 
							type="text" 
							id="cccea_email_edit" 
							ng-model="editBWCustomerComCrm.cccea_email"
                            ng-required="true" 
							name="cccea_email" 
							class="form-control"
                            placeholder="CRM Email">
                        <span ng-messages="erpEditBWCustomerComCrmForm.cccea_email.$error" ng-if="erpEditBWCustomerComCrmForm.cccea_email.$dirty || erpEditBWCustomerComCrmForm.$submitted" role="alert">
                            <span ng-message="required" class="error">CRM Email is required</span>
                        </span>
                    </div>
                    <!--/CRM Email-->

                    <!--CRM Status -->
                    <div class="col-xs-2 form-group">
                        <label for="cccea_status">CRM Status<em class="asteriskRed">*</em></label>
                        <select 
							class="form-control" 
							name="cccea_status" 
							id="cccea_status_edit"
                            ng-model="editBWCustomerComCrm.cccea_status"
                            ng-options="activeInactiveDropdown.name for activeInactiveDropdown in activeInactiveDropdownList track by activeInactiveDropdown.id" 
							ng-required='true'>
                            <option value="">Select Status</option>
                        </select>
                        <span ng-messages="erpEditBWCustomerComCrmForm.cccea_status.$error" ng-if="erpEditBWCustomerComCrmForm.cccea_status.$dirty || erpEditBWCustomerComCrmForm.$submitted" role="alert">
                            <span ng-message="required" class="error">CRM Status is required</span>
                        </span>
                    </div>
                    <!--/CRM Status-->

                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>
                        <input type="hidden" ng-model="editBWCustomerComCrm.cccea_id" name="cccea_id" ng-value="editBWCustomerComCrm.cccea_id" id="cccea_id">
                        <button type="submit" ng-disabled="erpEditBWCustomerComCrmForm.$invalid" class="btn btn-primary" ng-click="funUpdateBranchWiseCustomerComCrm()">Update</button>
                        <button type="button" class="btn btn-default" ng-click="backButton()">Cancel</button>
                    </div>
                    <!--Save Button-->

                </div>

            </form>
        </div>
    </div>
</div>

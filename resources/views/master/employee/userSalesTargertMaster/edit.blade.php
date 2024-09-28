<div class="row" ng-hide="isViewEditDiv">
    <div class="panel panel-default">
        <div class="panel-body">

            <!--Header-->
            <div class="row header1">
                <strong class="pull-left headerText">Edit Sales Target : <span
                        ng-bind="editUserSalesTargetDtl.employee_name">name</span></strong>
            </div>
            <!--/Header-->

            <!--Edit User Sales Target Form-->
            <form method="POST" name="erpEditUserSalesTargetForm" id="erpEditUserSalesTargetForm" novalidate>
                <div class="row">

                    <!--Branch-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_division_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control" ng-model="editUserSalesTargetDtl.ust_division_id"
                            ng-change="funGetEmployeeOnDivisionChange(editUserSalesTargetDtl.ust_division_id.id)"
                            id="ust_division_id" name="ust_division_id" ng-required='true'
                            ng-options="item.name for item in DivisionsList track by item.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpEditUserSalesTargetForm.ust_division_id.$error"
                            ng-if='erpEditUserSalesTargetForm.ust_division_id.$dirty  || erpEditUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <!--/Branch-->

                    <!--department-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_product_category_id">Department<em class="asteriskRed">*</em></label>
                        <select class="form-control" ng-model="editUserSalesTargetDtl.ust_product_category_id"
                            id="ust_product_category_id" ng-required='true' name="ust_product_category_id"
                            ng-options="item.name for item in parentCategoryDropdownList track by item.id">
                            <option value="">Select Department</option>
                        </select>
                        <span ng-messages="erpEditUserSalesTargetForm.ust_product_category_id.$error"
                            ng-if='erpEditUserSalesTargetForm.ust_product_category_id.$dirty  || erpEditUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Department is required</span>
                        </span>
                    </div>
                    <!--/department-->

                    <!--Employee-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_user_id">Employee<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="ust_user_id" id="ust_user_id" ng-required="true"
                            ng-change="funGetCustomerList(editUserSalesTargetDtl.ust_user_id.id)"
                            ng-model="editUserSalesTargetDtl.ust_user_id"
                            ng-options="employeeList.name for employeeList in employeeListData track by employeeList.id">
                            <option value="">Select Employee</option>
                        </select>
                        <span ng-messages="erpEditUserSalesTargetForm.ust_user_id.$error"
                            ng-if='erpEditUserSalesTargetForm.ust_user_id.$dirty || erpEditUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Employee is required</span>
                        </span>
                    </div>
                    <!--/Employee-->

                    <!--Customer-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_customer_id">Customer<em class="asteriskRed">*</em></label>
                        <select class="form-control" ng-model="editUserSalesTargetDtl.ust_customer_id"
                            id="ust_customer_id" name="ust_customer_id" ng-required='true'
                            ng-options="item.name for item in customerListData track by item.id">
                            <option value="">Select Customer</option>
                        </select>
                        <span ng-messages="erpEditUserSalesTargetForm.ust_customer_id.$error"
                            ng-if='erpEditUserSalesTargetForm.ust_customer_id.$dirty  || erpEditUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Customer is required</span>
                        </span>
                    </div>
                    <!--/Customer-->

                    <!--Target Type-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_type_id">Type<em class="asteriskRed">*</em></label>
                        <select class="form-control" id="e_ust_type_id" name="ust_type_id"
                            ng-model="editUserSalesTargetDtl.ust_type_id" ng-required='true'
                            ng-options="item.name for item in userSalesTargetTypeList track by item.id">
                            <option value="">Select Type</option>
                        </select>
                        <span ng-messages="erpEditUserSalesTargetForm.ust_type_id.$error"
                            ng-if='erpEditUserSalesTargetForm.ust_type_id.$dirty || erpEditUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Type is required</span>
                        </span>
                    </div>
                    <!--Target Type-->

                    <!--Target Amount-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_amount">Target Amount<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" name="ust_amount" id="ust_amount"
                            ng-model="editUserSalesTargetDtl.ust_amount" ng-required='true' placeholder="Target Amount">
                        <span ng-messages="erpEditUserSalesTargetForm.ust_amount.$error"
                            ng-if='erpEditUserSalesTargetForm.ust_amount.$dirty || erpEditUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Target Amount is required</span>
                        </span>
                    </div>
                    <!--/Target Amount-->

                </div>

                <div class="row">

                    <!--Target Date-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_date">Target Date<em class="asteriskRed">*</em></label>
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" class="bgwhite form-control" ng-model="editUserSalesTargetDtl.ust_date"
                                name="ust_date" id="ust_date" placeholder="Target Date" ng-required="true" />
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            <span ng-messages="erpEditUserSalesTargetForm.ust_date.$error"
                                ng-if='erpEditUserSalesTargetForm.ust_date.$dirty || erpEditUserSalesTargetForm.$submitted'
                                role="alert">
                                <span ng-message="required" class="error">Target Date is required</span>
                            </span>
                        </div>
                    </div>
                    <!--/Target Date-->

                    <!--Status-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_status">Status<em class="asteriskRed">*</em></label>
                        <select class="form-control" id="ust_status" name="ust_status"
                            ng-model="editUserSalesTargetDtl.ust_status"
                            ng-options="activeInactionList.name for activeInactionList in activeInactionSelectboxList track by activeInactionList.id"
                            ng-required='true'>
                            <option value="">Select Status</option>
                        </select>
                        <span ng-messages="erpEditUserSalesTargetForm.ust_status.$error"
                            ng-if='erpEditUserSalesTargetForm.ust_status.$dirty || erpEditUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Status is required</span>
                        </span>
                    </div>
                    <!--/Status-->

                    <!--Button-->
                    <div class="col-xs-2 form-group mT25">
                        <div class="pull-left">
                            <label for="csrf_field">{{ csrf_field() }}</label>
                            <input type="hidden" ng-value="editUserSalesTargetDtl.ust_id"
                                ng-model="editUserSalesTargetDtl.ust_id" name="ust_id" id="ust_id">
                            <button type="submit" title="Save" ng-disabled="erpEditUserSalesTargetForm.$invalid"
                                id="add_button" class="btn btn-primary"
                                ng-click="funUpdateUserSalesTargetDtl()">Update</button>
                            <button type="button" title="Back" class="btn btn-default"
                                ng-click="backButton(1)">Back</button>
                        </div>
                    </div>
                    <!--/Button-->

                </div>
            </form>
            <!--/Edit User Sales Target Form-->
        </div>
    </div>
</div>

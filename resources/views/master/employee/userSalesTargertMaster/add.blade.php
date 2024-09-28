<div class="row" ng-hide="isViewAddDiv">
    <div class="panel panel-default">
        <div class="panel-body">

            <!--Header-->
            <div class="row header1">
                <div class="pull-left headerText font-weight-bold">Add Sales Target</div>
                <div ng-if="{{ defined('ADD') && ADD }}" class="pull-right headerText p0 m0">
                    <a title="Upload Sales Target" class="btn btn-primary" ng-click="navigatePage(1)">Upload</a>
                </div>
            </div>
            <!--/Header-->

            <!--Add User Sales Target Form-->
            <form method="POST" name="erpAddUserSalesTargetForm" enctype="multipart/form-data"
                id="erpAddUserSalesTargetForm" novalidate>
                <div class="row">

                    <!--Branch-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_division_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control" ng-model="userSalesTargetDtl.ust_division_id"
                            ng-change="funGetEmployeeOnDivisionChange(userSalesTargetDtl.ust_division_id.id)"
                            id="ust_division_id" ng-required='true' name="ust_division_id"
                            ng-options="item.name for item in DivisionsList track by item.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpAddUserSalesTargetForm.ust_division_id.$error"
                            ng-if='erpAddUserSalesTargetForm.ust_division_id.$dirty  || erpAddUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <!--/Branch-->

                    <!--department-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_product_category_id">Department<em class="asteriskRed">*</em></label>
                        <select class="form-control" ng-model="userSalesTargetDtl.ust_product_category_id"
                            id="ust_product_category_id" ng-required='true' name="ust_product_category_id"
                            ng-options="item.name for item in parentCategoryDropdownList track by item.id">
                            <option value="">Select Department</option>
                        </select>
                        <span ng-messages="erpAddUserSalesTargetForm.ust_product_category_id.$error"
                            ng-if='erpAddUserSalesTargetForm.ust_product_category_id.$dirty  || erpAddUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Department is required</span>
                        </span>
                    </div>
                    <!--/department-->

                    <!--Employee-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_user_id">Employee<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="ust_user_id" id="ust_user_id" ng-required="true"
                            ng-change="funGetCustomerList(userSalesTargetDtl.ust_user_id.id)"
                            ng-model="userSalesTargetDtl.ust_user_id"
                            ng-options="employeeList.name for employeeList in employeeListData track by employeeList.id">
                            <option value="">Select Employee</option>
                        </select>
                        <span ng-messages="erpAddUserSalesTargetForm.ust_user_id.$error"
                            ng-if='erpAddUserSalesTargetForm.ust_user_id.$dirty || erpAddUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Employee is required</span>
                        </span>
                    </div>
                    <!--/Employee-->

                    <!--Customer-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_customer_id">Customer<em class="asteriskRed">*</em></label>
                        <select class="form-control" ng-model="userSalesTargetDtl.ust_customer_id" id="ust_customer_id"
                            name="ust_customer_id" ng-required='true'
                            ng-options="item.name for item in customerListData track by item.id">
                            <option value="">Select Customer</option>
                        </select>
                        <span ng-messages="erpAddUserSalesTargetForm.ust_customer_id.$error"
                            ng-if='erpAddUserSalesTargetForm.ust_customer_id.$dirty  || erpAddUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Customer is required</span>
                        </span>
                    </div>
                    <!--/Customer-->

                    <!--Target Type-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_type_id">Type<em class="asteriskRed">*</em></label>
                        <select class="form-control" id="ust_type_id" name="ust_type_id"
                            ng-model="userSalesTargetDtl.ust_type_id" ng-required='true'
                            ng-options="item.name for item in userSalesTargetTypeList track by item.id">
                            <option value="">Select Type</option>
                        </select>
                        <span ng-messages="erpAddUserSalesTargetForm.ust_type_id.$error"
                            ng-if='erpAddUserSalesTargetForm.ust_type_id.$dirty || erpAddUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Type is required</span>
                        </span>
                    </div>
                    <!--Target Type-->

                    <!--Target Amount-->
                    <div class="col-xs-2 form-group">
                        <label for="ust_amount">Target Amount<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" name="ust_amount" id="ust_amount"
                            ng-model="userSalesTargetDtl.ust_amount" ng-required='true' placeholder="Target Amount">
                        <span ng-messages="erpAddUserSalesTargetForm.ust_amount.$error"
                            ng-if='erpAddUserSalesTargetForm.ust_amount.$dirty || erpAddUserSalesTargetForm.$submitted'
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
                            <input type="text" class="bgwhite form-control" ng-model="userSalesTargetDtl.ust_date"
                                name="ust_date" id="ust_date" placeholder="Target Date" ng-required="true" />
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            <span ng-messages="erpAddUserSalesTargetForm.ust_date.$error"
                                ng-if='erpAddUserSalesTargetForm.ust_date.$dirty || erpAddUserSalesTargetForm.$submitted'
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
                            ng-model="userSalesTargetDtl.ust_status"
                            ng-options="activeInactionList.name for activeInactionList in activeInactionSelectboxList track by activeInactionList.id"
                            ng-required='true'>
                            <option value="">Select Status</option>
                        </select>
                        <span ng-messages="erpAddUserSalesTargetForm.ust_status.$error"
                            ng-if='erpAddUserSalesTargetForm.ust_status.$dirty || erpAddUserSalesTargetForm.$submitted'
                            role="alert">
                            <span ng-message="required" class="error">Status is required</span>
                        </span>
                    </div>
                    <!--/Status-->

                    <!--Button-->
                    <div class="col-xs-2 form-group mT25">
                        <div class="pull-left">
                            <label for="csrf_field">{{ csrf_field() }}</label>
                            <button type="submit" title="Save" ng-disabled="erpAddUserSalesTargetForm.$invalid"
                                id="add_button" class="btn btn-primary"
                                ng-click="funAddUserSalesTargetDtl()">Save</button>
                            <button type="button" title="Reset" class="btn btn-default"
                                ng-click="resetForm()">Reset</button>
                        </div>
                    </div>
                    <!--/Button-->

                </div>
            </form>
            <!--/Add User Sales Target Form-->
        </div>
    </div>
</div>

<div class="row" ng-hide="isEmployeeAddDiv">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row header1">
                <span><strong class="pull-left headerText">Add Employee</strong></span>
                <span><a title="Back" class="btn btn-primary pull-right mT3 mR5" ng-click="backButton()">Back</a></span>
            </div>
            <form name="erpAddEmployeeForm" id="erpAddEmployeeForm" novalidate>

                <!--Basic Detail-->
                <div class="row head-title">Basic Detail</div>
                <div class="row">

                    <!--Branch-->
                    <div class="col-xs-3 form-group">
                        <label>Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="division_id" ng-model="addEmployee.division_id" id="division_id" ng-required='true' ng-options="item.name for item in DivisionsList track by item.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpAddEmployeeForm.division_id.$error" ng-if='erpAddEmployeeForm.division_id.$dirty  || erpAddEmployeeForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <!--/Branch-->

                    <!--Name-->
                    <div class="col-xs-3 form-group">
                        <label>Name<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" ng-model="addEmployee.name" name="name" id="name" ng-required='true' placeholder="Employee Name" />
                        <span ng-messages="erpAddEmployeeForm.name.$error" ng-if='erpAddEmployeeForm.name.$dirty  || erpAddEmployeeForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Name is required</span>
                        </span>
                    </div>
                    <!--/Name-->

                    <!--Email-->
                    <div class="col-xs-3 form-group">
                        <label>Email<em class="asteriskRed">*</em></label>
                        <input type="email" class="form-control" ng-model="addEmployee.email" name="email" id="email" ng-required='true' placeholder="Email" />
                        <span ng-messages="erpAddEmployeeForm.email.$error" ng-messagesInvalid="erpAddEmployeeForm.email.$Invalid" ng-if='erpAddEmployeeForm.email.$dirty  || erpAddEmployeeForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Email is required</span>
                            <span ng-show="erpAddEmployeeForm.email.$error.email" class="error">Enter a valid
                                email!</span>
                        </span>
                    </div>
                    <!--/Email-->

                    <!--Password-->
                    <div class="col-xs-3 form-group">
                        <label>Password<em class="asteriskRed">*</em></label>
                        <input type="password" name="password" id="password" class="form-control" ng-required='true' ng-model="addEmployee.password" placeholder="password" />
                        <span ng-messages="erpAddEmployeeForm.password.$error" ng-if='erpAddEmployeeForm.password.$dirty  || erpAddEmployeeForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Please enter the password</span>
                        </span>
                    </div>
                    <!--/Password-->
                </div>

                <div class="row">

                    <!--Confirm Password-->
                    <div class="col-xs-3 form-group">
                        <label>Confirm Password<em class="asteriskRed">*</em></label>
                        <input type="password" class="form-control" name="password_confirmation" id="user-cpassword" ng-model="addEmployee.password_confirmation" confirm-pwd="addEmployee.password" placeholder="confirm password" />
                        <span ng-messages="erpAddEmployeeForm.password_confirmation.$error" ng-if='erpAddEmployeeForm.password_confirmation.$dirty  || erpAddEmployeeForm.$submitted' role="alert">
                            <span class="error" ng-message="required">Password confirmation required</span>
                            <span class="error" ng-message="password">Password different</span>
                        </span>
                    </div>
                    <!--/Confirm Password-->

                    <!--Status-->
                    <div class="col-xs-3 form-group">
                        <label>Status<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="status" id="status" ng-required='true' ng-model="addEmployee.status.selectedOption" ng-options="status.name for status in employeeStatusList track by status.id">
                            <option value="">Select Status</option>
                        </select>
                        <span ng-messages="erpAddEmployeeForm.status.$error" ng-if='erpAddEmployeeForm.status.$dirty  || erpAddEmployeeForm.$submitted' role="alert">
                            <span class="error" ng-message="required">Status required</span>
                        </span>
                    </div>
                    <!--/Status-->

                    <!--Sales Person-->
                    <div class="col-xs-3 form-group mT26">
                        <input ng-init="IsDepartRoleVisible == false" ng-change="funShowHideIsDepartRoleDetail('is_sales_person');" type="checkbox" value="1" id="is_sales_person" name="is_sales_person" ng-model="addEmployee.is_sales_person">&nbsp;<label>Is Sales Person</label>
                    </div>
                    <!--/Sales Person-->

                    <!--Sampler Person-->
                    <div class="col-xs-3 form-group mT26">
                        <input ng-init="IsDepartRoleVisible == false" ng-change="funShowHideIsDepartRoleDetail('is_sampler_person');" type="checkbox" value="1" id="is_sampler_person" name="is_sampler_person" ng-model="addEmployee.is_sampler_person">&nbsp;<label>Is Sampler</label>
                    </div>
                    <!--/Sampler Person-->

                </div>
                <!--Basic Detail-->

                <!--Department Detail-->
                <div class="row head-title mT30" ng-if="!IsDepartRoleVisible">
                    <span style="float:left;">Department Detail<em class="asteriskRed">*</em></span>
                    <span style="float:right;"><input type="text" placeholder="Search" ng-hide="searchHide" ng-model="searchDepartment" class="ng-pristine ng-untouched ng-valid">
                    </span>
                </div>
                <div class="row mT10" ng-if="!IsDepartRoleVisible">
                    <div class="col-xs-12">
                        <div class="col-xs-3" ng-repeat="deptObj in departmentList | filter:searchDepartment">
                            <input type="checkbox" ng-model="addEmployee.department_id[[deptObj.id]]" name="department_id[]" value="[[deptObj.id]]" />
                            <label class="text-overflow" title="[[deptObj.name]]" for="text1">[[deptObj.name]]
                                ([[deptObj.department_type_name]])</label>
                        </div>
                    </div>
                </div>
                <!--/Department Detail-->

                <!--Role Detail-->
                <div class="row head-title mT30" ng-if="!IsDepartRoleVisible">
                    <span style="float:left;">Role Detail<em class="asteriskRed">*</em></span>
                    <span style="float:right;">
                        <input type="text" placeholder="Search" ng-hide="searchHide" ng-model="searchRoles" class="ng-pristine ng-untouched ng-valid">
                    </span>
                </div>
                <div class="row" ng-if="!IsDepartRoleVisible">
                    <div class="col-xs-12">
                        <div class="col-xs-3" ng-repeat="roleDataObj in roleDataList | filter:searchRoles">
                            <input ng-if="roleDataObj.id == 6 || roleDataObj.id == 7 || roleDataObj.id == 13" type="checkbox" class="addSelectedRoleClass" ng-model="addEmployee.role_id[[roleDataObj.id]]" id="add_role_id_[[roleDataObj.id]]" name="role_id[]" ng-value="roleDataObj.id" ng-click="funAddShowHideEquipmentTypes(roleDataObj.id);" />
                            <input ng-if="roleDataObj.id != 6 && roleDataObj.id != 7 && roleDataObj.id != 13" type="checkbox" ng-model="addEmployee.role_id[[roleDataObj.id]]" id="add_role_id_[[roleDataObj.id]]" name="role_id[]" ng-value="roleDataObj.id" />
                            <label class="text-overflow" title="[[roleDataObj.name]]" for="text1">[[roleDataObj.name]]</label>
                        </div>
                    </div>
                </div>
                <!--/Role Detail-->

                <!--Equipment Detail  -->
                <div class="row head-title mT30" ng-if="!IsDepartRoleVisible && !isEquipmentTypeAddFlag">
                    <span style="float:left;">Equipment Detail<em class="asteriskRed">*</em></span>
                    <span style="float:right;"><input type="text" placeholder="Search" ng-hide="searchHide" ng-model="searchEquipmentType" class="ng-pristine ng-untouched ng-valid"></span>
                </div>
                <div class="row" ng-if="!IsDepartRoleVisible && !isEquipmentTypeAddFlag">
                    <div class="col-xs-12">
                        <div class="col-xs-3" ng-repeat="eqObj in equipmentTypesList | filter:searchEquipmentType">
                            <input type="checkbox" ng-model="addEmployee.equipment_type_id[[eqObj.id]]" name="equipment_type_id[]" value="[[eqObj.id]]" />
                            <label class="text-overflow" title="[[eqObj.name]]" for="text1">[[eqObj.name]]</label>
                        </div>
                    </div>
                </div>
                <!--/Equipment Detail-->

                <!--Custom Permission-->
                <div class="row head-title mT30" ng-if="!IsDepartRoleVisible && customPermissionList && !isCustomPermissionFlag">
                    <span style="float:left;">Hold/Unhold Permission<em class="asteriskRed">*</em></span>
                </div>
                <div class="row" ng-if="!IsDepartRoleVisible && customPermissionList && !isCustomPermissionFlag">
                    <div class="col-xs-12">
                        <div class="col-xs-3" ng-repeat="cpObj in customPermissionList">
                            <input type="checkbox" ng-model="addEmployee.custom_permission[[cpObj.per_key]]" name="custom_permission['[[cpObj.per_key]]']" value="[[cpObj.per_value]]" />
                            <label class="text-overflow" title="[[cpObj.per_name]]" for="[[cpObj.per_name]]">[[cpObj.per_name]]</label>
                        </div>
                    </div>
                </div>
                <!--/Custom Permission-->

                <!--Save button-->
                <div class="row pull-right mR10">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button title="Save" type="submit" class="btn btn-primary" ng-click="funAddNewEmployee(divisionID)">Save</button>
                    <button type='button' id='reset_button' class='btn btn-default' ng-click='resetAddForm()' title="Reset">Reset</button>
                </div>
                <!--/Save button-->

            </form>
        </div>
    </div>
</div>

<div class="row" ng-hide="isEmployeeEditDiv">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row header1">
                <span><strong class="pull-left headerText">Edit Employee : <span class="capitalize" ng-bind="editEmployee.name"></span></strong></span>
                <span><a title="Back" class="btn btn-primary pull-right mT3 mR5" ng-click="backButton()">Back</a></span>
            </div>
            <form name="erpEditEmployeeForm" id="erpEditEmployeeForm" novalidate>

                <!--Basic Detail-->
                <div class="row head-title">Basic Detail</div>
                <div class="row">
                    <!--Branch-->
                    <div class="col-xs-3 form-group">
                        <label>Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="division_id" ng-model="editEmployee.division_id.selectedOption" id="division_id" ng-required='true' ng-options="item.name for item in DivisionsList track by item.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpEditEmployeeForm.division_id.$error" ng-if='erpEditEmployeeForm.division_id.$dirty  || erpEditEmployeeForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <!--/Branch-->

                    <!--Name-->
                    <div class="col-xs-3 form-group">
                        <label>Name<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" ng-model="editEmployee.name" name="name" id="name" ng-required='true' placeholder="Employee Name" />
                        <span ng-messages="erpEditEmployeeForm.name.$error" ng-if='erpEditEmployeeForm.name.$dirty  || erpEditEmployeeForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Name is required</span>
                        </span>
                    </div>
                    <!--/Name-->

                    <!--Email-->
                    <div class="col-xs-3 form-group">
                        <label>Email<em class="asteriskRed">*</em></label>
                        <input type="email" class="form-control" ng-model="editEmployee.email" name="email" id="email" ng-required='true' placeholder="Email" />
                        <span ng-messages="erpEditEmployeeForm.email.$error" ng-messagesInvalid="erpEditEmployeeForm.email.$Invalid" ng-if='erpEditEmployeeForm.email.$dirty  || erpEditEmployeeForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Email is required</span>
                            <span ng-show="erpEditEmployeeForm.email.$error.email" class="error">Enter a valid email!</span>
                        </span>
                    </div>
                    <!--/Email-->

                    <!--Password-->
                    <div class="col-xs-3 form-group">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control" ng-model="editEmployee.password" placeholder="password" />
                        <span ng-messages="erpEditEmployeeForm.password.$error" ng-if='erpEditEmployeeForm.password.$dirty  || erpEditEmployeeForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Please enter the password</span>
                        </span>
                    </div>
                    <!--/Password-->
                </div>

                <div class="row">
                    <div class="col-xs-3 form-group">
                        <label>Status <em class="asteriskRed">*</em></label>
                        <select class="form-control" name="status" ng-required='true' ng-model="editEmployee.status.selectedOption" ng-options="status.name for status in employeeStatusList track by status.id">
                            <option value="">Select Status</option>
                        </select>
                        <span ng-messages="erpEditEmployeeForm.status.$error" ng-if='erpEditEmployeeForm.status.$dirty  || erpEditEmployeeForm.$submitted' role="alert">
                            <span class="error" ng-message="required">Status required</span>
                        </span>
                    </div>
                    <!--Sales Person-->

                    <!-- if any role already selected then sales person field is disabled. this isAnyRoleFlag gives true if any role selected otherwise false-->
                    <div class="col-xs-3 form-group mT26">
                        <input ng-disabled="isAnyRoleFlag || editEmployee.is_sampler_person" ng-change="funShowHideIsDepartRoleDetail();" type="checkbox" value="1" id="is_sales_person" name="is_sales_person" ng-model="editEmployee.is_sales_person">&nbsp;<label>Is Sales Person</label>
                    </div>
                    <!--/Sales Person-->

					<!--Sampler Person-->
                    <div class="col-xs-3 form-group mT26">
                        <input ng-disabled="isAnyRoleFlag || editEmployee.is_sales_person" ng-change="funShowHideIsDepartRoleDetail('is_sampler_person');" type="checkbox" value="1" id="is_sampler_person_edit" name="is_sampler_person" ng-model="editEmployee.is_sampler_person">&nbsp;<label>Is Sampler</label>
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
                            <input type="checkbox" ng-model="editEmployee.department_id[[deptObj.id]]" ng-checked="selectedDepartments.indexOf(deptObj.id) > -1" name="department_id[]" value="[[deptObj.id]]" />
                            <label class="text-overflow" title="[[deptObj.name]]" for="text1">[[deptObj.name]] ([[deptObj.department_type_name]])</label>
                        </div>
                    </div>
                </div>
                <!--/Department Detail-->

                <!--Role Detail-->
                <div class="row head-title mT30" ng-if="!IsDepartRoleVisible">
                    <span style="float:left;">Role Detail<em class="asteriskRed">*</em></span>
                    <span style="float:right;"><input type="text" placeholder="Search" ng-hide="searchHide" ng-model="searchRoles" class="ng-pristine ng-untouched ng-valid"></span>
                </div>
                <div class="row" ng-if="!IsDepartRoleVisible">
                    <div class="col-xs-12">
                        <div class="col-xs-3" ng-repeat="roleDataObj in roleDataList | filter:searchRoles">
                            <input ng-if="roleDataObj.id == 6 || roleDataObj.id == 7 || roleDataObj.id == 13" class="editSelectedRoleClass" type="checkbox" ng-model="editEmployee.role_id[[roleDataObj.id]]" id="edit_role_id_[[roleDataObj.id]]" ng-checked="selectedRoles.indexOf(roleDataObj.id) > -1" name="role_id[]" ng-value="roleDataObj.id" ng-click="funEditShowHideEquipmentTypes(roleDataObj.id);" />
                            <input ng-if="roleDataObj.id != 6 && roleDataObj.id != 7 && roleDataObj.id != 13" type="checkbox" ng-model="editEmployee.role_id[[roleDataObj.id]]" id="edit_role_id_[[roleDataObj.id]]" ng-checked="selectedRoles.indexOf(roleDataObj.id) > -1" name="role_id[]" ng-value="roleDataObj.id" />
                            <label class="text-overflow" title="[[roleDataObj.name]]" for="text1">[[roleDataObj.name]]</label>
                        </div>
                    </div>
                </div>
                <!--/Role Detail-->

                <!--Equipment Detail-->
                <div class="row head-title mT30" ng-if="!IsDepartRoleVisible && !isEquipmentTypeEditFlag">
                    <span style="float:left;">Equipment Detail<em class="asteriskRed">*</em></span>
                    <span style="float:right;"><input type="text" placeholder="Search" ng-hide="searchHide" ng-model="searchEqType" class="ng-pristine ng-untouched ng-valid"></span>
                </div>

                <div class="row" ng-if="!IsDepartRoleVisible && !isEquipmentTypeEditFlag">
                    <div class="col-xs-12">
                        <div class="col-xs-3" ng-repeat="eqObj in equipmentTypesList | filter:searchEqType">
                            <input type="checkbox" ng-model="editEmployee.equipment_type_id[[eqObj.id]]" ng-checked="selectedEquipments.indexOf(eqObj.id) > -1" name="equipment_type_id[]" value="[[eqObj.id]]" />
                            <label class="text-overflow" title="[[eqObj.name]]" for="text1">[[eqObj.name]]</label>
                        </div>
                    </div>
                </div>
                <!--/Equipment Detail-->

                <!--Custom Permission-->
                <div class="row head-title mT30" ng-if="!IsDepartRoleVisible && customPermissionList && !isCustomPermissionEditFlag">
                    <span style="float:left;">Hold/Unhold Permission<em class="asteriskRed">*</em></span>
                </div>
                <div class="row" ng-if="!IsDepartRoleVisible && customPermissionList && !isCustomPermissionEditFlag">
                    <div class="col-xs-12">
                        <div class="col-xs-3" ng-repeat="cpObj in customPermissionList">
                            <input type="checkbox" ng-model="editEmployee.custom_permission[[cpObj.per_key]]" ng-checked="selectedCustomPermissions.indexOf(cpObj.per_key) > -1" name="custom_permission['[[cpObj.per_key]]']" value="[[cpObj.per_value]]" />
                            <label class="text-overflow" title="[[cpObj.per_name]]" for="[[cpObj.per_name]]">[[cpObj.per_name]]</label>
                        </div>
                    </div>
                </div>
                <!--/Custom Permission-->

                <!--Save button-->
                <div class="row pull-right mR10">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" ng-value="editEmployee.id" ng-model="editEmployee.user_id">
                    <button title="Update" type='submit' class='btn btn-primary' ng-click="funUpdateEmployee(divisionID)">Update</button>
                    <button title="Close" class="btn btn-default pull-right" ng-click="backButton()">Cancel</button>
                </div>
                <!--/Save button-->
            </form>
        </div>
    </div>
</div>

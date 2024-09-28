<div class="row" ng-hide="isEmployeeEditDiv">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Account Detail : <span class="capitalize" ng-bind="editEmployeeAccount.name"></span></strong></span>
			</div>         
			<form name="erpEditEmployeeAccountForm" id="erpEditEmployeeAccountForm" novalidate>			
				
				<!--Basic Detail-->
				<div ng-if="editEmployeeAccount" class="row head-title">Basic Detail</div>
				<div ng-if="editEmployeeAccount" class="row">					
					<!--Branch-->
					<div ng-if="editEmployeeAccount.division_name" class="col-xs-3 form-group">
						<label>Branch</label>						   
						<span readonly class="form-control" ng-bind="editEmployeeAccount.division_name" id="division_name">
						</span>
					</div>
					<!--/Branch-->
					
					<!--Name-->
					<div class="col-xs-3 form-group">                           
						<label>Name<em class="asteriskRed">*</em></label>						   
						<input
							type="text"
							class="form-control" 
							ng-model="editEmployeeAccount.name" 
							name="name" 
							id="name"
							ng-required='true'
							placeholder="Employee Name" />
						<span ng-messages="erpEditEmployeeAccountForm.name.$error" ng-if='erpEditEmployeeAccountForm.name.$dirty  || erpEditEmployeeAccountForm.$submitted' role="alert">
							<span ng-message="required" class="error">Name is required</span>
						</span>                            
					</div>
					<!--/Name-->
					
					<!--Email-->
					<div class="col-xs-3 form-group">
						<label>Email</label>
						<span readonly class="form-control" ng-bind="editEmployeeAccount.email" id="email">
					</div>
					<!--/Email-->
					
					<!--Roles-->
					<div class="col-xs-3 form-group">
						<label>Select Current Roles<em class="asteriskRed">*</em></label>						   
						<select class="form-control" 
							name="role_id"
							ng-model="editEmployeeAccount.role_id"
							ng-required='true'
							ng-options="item.name for item in userRoleList track by item.id ">
							<option value="">Select Role</option>
						</select>
						<span ng-messages="erpEditEmployeeAccountForm.role_id.$error" ng-if='erpEditEmployeeAccountForm.role_id.$dirty  || erpNavigationModuleForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Role</span>
						</span>
					</div>
					<!--/Roles-->
				
				</div>
				<!--Basic Detail-->
				
				<!--Department Detail-->
				<div ng-if="departmentList.length" class="row head-title mT30">Department Detail</div>
				<div ng-if="departmentList.length" class="row mT10">
					<div class="col-xs-12" >
						<div class="col-xs-3" ng-repeat="deptObj in departmentList">
							<span class="text-overflow" title="[[deptObj.department_name]]" for="text1">[[deptObj.department_name]]</span>
						</div>
					</div>
				</div>
				<!--/Department Detail-->
				
				<!--Role Detail-->
				<div ng-if="roleDataList.length" class="row head-title mT30">Role Detail</div>
				<div ng-if="roleDataList.length" class="row">					
					<div class="col-xs-12">
						<div class="col-xs-3" ng-repeat="roleDataObj in roleDataList">
							<span class="text-overflow" title="[[roleDataObj.name]]" for="text1">[[roleDataObj.name]]</span>
						</div>
					</div>
				</div>
				<!--/Role Detail-->
				
				<!--Equipment Detail-->
				<div ng-if="equipmentTypesList.length" class="row head-title mT30">Equipment Detail</div>
				<div ng-if="equipmentTypesList.length" class="row">					
					<div class="col-xs-12" >
						<div class="col-xs-3" ng-repeat="equipmentTypeObj in equipmentTypesList">
							<span class="text-overflow" title="[[equipmentTypeObj.equipment_name]]" for="text1">[[equipmentTypeObj.equipment_name]]</span>
						</div>
					</div>
				</div>
				<!--/Equipment Detail-->
				
				<!--Save button-->
				<div class="row pull-right mR10">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="user_id" ng-value="editEmployeeAccount.id" ng-model="editEmployeeAccount.user_id">
					<button type="submit" title="Update"  class="btn btn-primary" ng-click="funUpdateEmployeeAccount()">Update</button>
				</div>
				<!--/Save button-->
				
			</form>
		</div>
	</div>
</div>
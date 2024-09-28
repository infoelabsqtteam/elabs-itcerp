<div class="row" ng-hide="hideDepartmentEditForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">	Edit Department </strong></span>
			</div>
			<form name='editDepartmentForm' id="edit_department_form" novalidate>
			<label for="submit">{{ csrf_field() }}</label>	
			<div class="row">	
				<div class="col-xs-3">
				   <div class="">     
					<label for="company_id1">Company Name</label>						   
						<input class="backWhite form-control" readonly name="company_name1"
								ng-model="company_name1" ng-value="company_name1" 
								id="company_name1">
						
						<span ng-messages="editDepartmentForm.company_id1.$error" 
						ng-if='editDepartmentForm.company_id1.$dirty  || editDepartmentForm.$submitted' role="alert">
							<span ng-message="required" class="error">Company name is required</span>
						</span>
				   </div>
				</div>
				<div class="col-xs-2">
				   <div class="">     
					<label for="department_code1">Department Code<em class="asteriskRed">*</em></label>						   
						<input readonly type="text" class="backWhite form-control" ng-value="dept_code" ng-model="dept_code"	
								id="department_code1" placeholder="Department Code" ng-required='true' />
						<span ng-messages="editDepartmentForm.department_code1.$error" 
						ng-if='editDepartmentForm.department_code1.$dirty  || editDepartmentForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department code is required</span>
						</span>
				   </div>
				</div>
				<div class="col-xs-2">
				   <div class="">
					<label for="dep_name1">Department Name<em class="asteriskRed">*</em></label>
						<input type="text" class="form-control" ng-value="dept_name"
								name="department_name1" ng-model="dept_name"
								id="dep_name1" 
								ng-required='true'
								placeholder="Department Name"/>
						<span ng-messages="editDepartmentForm.department_name1.$error" 
						 ng-if='editDepartmentForm.department_name1.$dirty  || editDepartmentForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department name is required</span>
						</span>
				   </div>
				</div>
				<div class="col-xs-2">
				   <div class="">
					<label for="department_type1">Department Type<em class="asteriskRed">*</em></label>
						<select  class="form-control" 
							name="department_type1" 
							id="department_type1" 
							ng-options="item.name for item in deptTypesList track by item.id"
							ng-model="department_type1.selectedOption"><option value="">Select Department Type</option></select>
						<span ng-messages="editDepartmentForm.department_type1.$error" 
						 ng-if='editDepartmentForm.department_type1.$dirty  || editDepartmentForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department type is required</span>
						</span>
				   </div>
				</div>
				<div class="mT26 col-xs-2">
					<div class="pull-right">
						<input type='hidden' id='department_id' name="department_id" ng-value="department_id1" > 
						<button title="Update"  ng-disabled="editDepartmentForm.$invalid" type='submit' id='edit_button' class=' btn btn-primary' ng-click='updateDepartment()' > Update </button>
						<button title="Close" type="button" class="btn btn-default" ng-click="hideEditForm()">Close</button>
					</div>
				</div>
			</div>
		</form>			
		</div>
	</div>
</div>
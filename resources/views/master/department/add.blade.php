<div class="row" ng-hide="hideDepartmentAddForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span class="pull-left"><strong class="headerText">Add Department </strong></span>
				<span class="pull-right"><button type='button' title="link" id='linked_with_product_category' class='btn btn-primary' ng-click="funGetLinkedWithProductCatDetail()">Link</button></span>
			</div>
			<form name='departmentForm' id="add_department_form" novalidate>
				<label for="submit">{{ csrf_field() }}</label>
				<div class="row">
					<div class="col-xs-3">
						<label for="company_name">Company Name</label>						   
							<input class="backWhite form-control" readonly name="company_name"
									ng-model="company_name" ng-value="company_name" 
									id="company_name">
							<input type="hidden" class="form-control" name="company_id"
									ng-model="company_id" ng-value="company_id" 
									ng-required='false' id="company_id">
							<span ng-messages="departmentForm.company_id.$error" 
							ng-if='departmentForm.company_id.$dirty  || departmentForm.$submitted' role="alert">
								<span ng-message="required" class="error">Company name is required</span>
							</span>
					</div>	
					<div class="col-xs-2">
						<span class="generate"><a href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
						<label for="department_code">Department Code<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control"  readonly
									ng-model="department_code"
									ng-bind="department_code"
									name="department_code" 
									id="department_code"
									ng-required='true'
									placeholder="Department Code"/>
							<span ng-messages="departmentForm.department_code.$error" 
							ng-if='departmentForm.department_code.$dirty  || departmentForm.$submitted' role="alert">
								<span ng-message="required" class="error">Department code is required</span>
							</span>
					</div>
					<div class="col-xs-2">
						<label for="department_name">Department Name<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control"  
									ng-model="department.department_name"
									name="department_name" 
									id="department_name"
									ng-required='true'
									placeholder="Department Name"/>
							<span ng-messages="departmentForm.department_name.$error" 
							 ng-if='departmentForm.department_name.$dirty  || departmentForm.$submitted' role="alert">
								<span ng-message="required" class="error">Department name is required</span>
							</span>
					</div>

					<div class="col-xs-2">
						<label for="department_type">Department Type<em class="asteriskRed">*</em></label>
							<select class="form-control" 
									ng-required='true'  
									name="department_type" 
									id="department_type" 
									ng-options="item.name for item in deptTypesList track by item.id"
									ng-model="department_type">
								<option value="">Select Department Type</option>
							</select>
							<span ng-messages="departmentForm.department_type.$error" 
							 ng-if='departmentForm.department_type.$dirty  || departmentForm.$submitted' role="alert">
								<span ng-message="required" class="error">Department type is required</span>
							</span>
					</div>
					<div class="mT26 col-xs-2">
						<div class="pull-right">
							<button title="Save" ng-disabled="departmentForm.$invalid" type='submit' id='add_button' class='btn btn-primary' ng-click='addDepartment()' > Save </button>
							<button  type='button' id='reset_button' class=' btn btn-default' ng-click='resetDept()' title="Reset"> Reset </button>

						</div>
					</div>
				</div>
			</form>		
		</div>
	</div>
	
	<!--Linked With Product Category Popup Window-->
	@include('master.department.linkedWithProductCategoryPopupWindow')
	<!--/Linked With Product Category Popup Window-->
</div>
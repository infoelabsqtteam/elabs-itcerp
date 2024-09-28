<div class="row" ng-hide="isDynamicFieldEditForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Branch :  <b><span ng-model="company_name" ng-bind="company_name"></span></b></strong></span>
			</div>
			<form name='editDynamicFieldForm' id="edit_dynamic_field_form" novalidate>
				<div class="row">
					<div class="col-xs-4">
						<label for="dynamic_field_code1">Dynamic Field Code<em class="asteriskRed">*</em></label>						   
						<input type="text" class="form-control" readonly
								ng-model="dynamic_field_code"
								ng-bind="dynamic_field_code"
								name="dynamic_field_code" 
								id="dynamic_field_code"
								ng-required='true'
								placeholder="Dynamic Field Code" />
						<span ng-messages="editDynamicFieldForm.dynamic_field_code1.$error" 
						ng-if='editDynamicFieldForm.dynamic_field_code1.$dirty || editDynamicFieldForm.$submitted' role="alert">
							<span ng-message="required" class="error">Dynamic Field code is required</span>
						</span>
					</div>
					<div class="col-xs-4">
						<label for="dynamic_field_name1">Dynamic Field Name<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control"  
									ng-model="dynamic_field_name"
									name="dynamic_field_name" 
									id="dynamic_field_name"
									ng-required='true'
									placeholder="Dynamic Field Name"/>
							<span ng-messages="editDynamicFieldForm.dynamic_field_name1.$error" 
							 ng-if='editDynamicFieldForm.dynamic_field_name1.$dirty  || editDynamicFieldForm.$submitted' role="alert">
								<span ng-message="required" class="error">Dynamic Field name is required</span>
							</span>
					</div>
					<div class="col-xs-4">
						<label for="dynamic_field_status1">Dynamic Field Status<em class="asteriskRed">*</em></label>

						<select class="form-control" name="dynamic_field_status" ng-model="dynamic_field_status.selectedOption" id="dynamic_field_status" ng-required='true' ng-options="item.name for item in statusList track by item.id ">
						</select>
						<span ng-messages="editDynamicFieldForm.dynamic_field_status.$error" ng-if='editDynamicFieldForm.dynamic_field_status.$dirty  || editDynamicFieldForm.$submitted' role="alert">
								<span ng-message="required" class="error">Status is required</span>
						</span>
					</div>
					<div class="mT26 col-xs-3">
						<div class="pull-left">
							<label for="submit">{{ csrf_field() }}</label>
							<input type='hidden' id='odfs_id' name="odfs_id" ng-value="odfs_id" > 
							<button  ng-disabled="editDynamicFieldForm.$invalid" type='submit' id='edit_button' title="Update" class='btn btn-primary' ng-click='updateDynamicField()' > Update </button>			
							<button type="button" class="btn btn-default" ng-click="hideEditForm()" title="Close">Close</button>
						</div>
					</div>
				</div>
			</form>		
		</div>
	</div>
</div>
<div class="row" ng-hide="isDynamicFieldAddForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Dynamic Field</strong></span>
			</div>
			<form name='dynamicFieldForm' id="add_dynamic_field_form" novalidate>
				<label for="submit">{{ csrf_field() }}</label>	
				<div class="row">	
					<div class="col-xs-4">
						<label for="dynamic_field_code">Dynamic Field Code<em class="asteriskRed">*</em></label>						   
						<input type="text" class="form-control"
								ng-model="dynamic_field_code"
								ng-bind="dynamic_field_code"
								name="dynamic_field_code" 
								id="dynamic_field_code"
								ng-required='true'
								placeholder="Dynamic Field Code" />
						<span ng-messages="dynamicFieldForm.dynamic_field_code.$error" 
						ng-if='dynamicFieldForm.dynamic_field_code.$dirty || dynamicFieldForm.$submitted' role="alert">
							<span ng-message="required" class="error">Dynamic Field code is required</span>
						</span>
					</div>
					<div class="col-xs-4">
						<label for="dynamic_field_name">Dynamic Field Name<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control"  
									ng-model="dynamic_field_name"
									name="dynamic_field_name" 
									id="dynamic_field_name"
									ng-required='true'
									placeholder="Dynamic Field Name"/>
							<span ng-messages="dynamicFieldForm.dynamic_field_name.$error" 
							 ng-if='dynamicFieldForm.dynamic_field_name.$dirty  || dynamicFieldForm.$submitted' role="alert">
								<span ng-message="required" class="error">Dynamic Field name is required</span>
							</span>
					</div>
					<div class="col-xs-4">
						<label for="dynamic_field_status">Dynamic Field Status<em class="asteriskRed">*</em></label>
						<select class="form-control" name="dynamic_field_status" ng-model="dynamic_field_status.selectedOption" id="dynamic_field_status" ng-required='true' ng-options="item.name for item in statusList track by item.id ">
						</select>
						<span ng-messages="dynamicFieldForm.dynamic_field_status.$error" ng-if='dynamicFieldForm.dynamic_field_status.$dirty  || dynamicFieldForm.$submitted' role="alert">
								<span ng-message="required" class="error">Dynamic Field Status is required</span>
						</span>
					</div>
					<div class="mT26 col-xs-3">
						<div class="pull-left">
							<button ng-disabled="dynamicFieldForm.$invalid"  type='submit' id='add_button' class=' btn btn-primary' ng-click='addDynamicField()' title="Save"> Save </button>
							<button  type='button' id='reset_button' class=' btn btn-default' ng-click='resetDynamicField()' title="Reset"> Reset </button>
						</div>
					</div>
				</div>
			</form>		
		</div>
	</div>
</div>
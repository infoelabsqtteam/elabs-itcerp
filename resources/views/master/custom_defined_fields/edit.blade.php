<div class="row" ng-show="{{defined('EDIT') && EDIT}}">
	<div class="panel panel-default" ng-hide="editCustomDefinedFieldsFormDiv">
		<div class="panel-body" ng-model="editCustomDefinedFieldsFormDiv">			
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Custom Defined fields</strong></span>
				
			</div>			
			<!--Add method form-->
			<form name='erpEditCustomDefinedFieldsForm' id="erpEditDefaultRemarksForm" novalidate>							
				<div class="row">
					
					<div class="col-xs-3 form-group">
						<label for="label_name">Label Name<em class="asteriskRed">*</em></label>	
						<input type="text" class="form-control" class="form-control" 
							ng-model="editCustomDefinedFields.label_name"
							name="label_name" 
							id="label_name"
							ng-required='true'
							placeholder="Label Name">
						</textarea>
						<span ng-messages="erpEditCustomDefinedFieldsForm.label_name.$error" ng-if='erpEditCustomDefinedFieldsForm.label_name.$dirty || erpEditCustomDefinedFieldsForm.$submitted' role="alert">
							<span ng-message="required" class="error">Label Name is required</span>
						</span>
					</div>
					<div class="col-xs-2 form-group">
						<label for="label_value">Label Value<em class="asteriskRed">*</em></label>
						<input type="text" class="form-control"
						        name="label_value"
							id="label_value"
							ng-model="editCustomDefinedFields.label_value"
							ng-required='true'/>
							<span ng-messages="erpEditCustomDefinedFieldsForm.label_value.$error" ng-if='erpEditCustomDefinedFieldsForm.label_value.$dirty  || erpEditCustomDefinedFieldsForm.$submitted' role="alert">
							<span ng-message="required" class="error">Label Value is required</span>
							</span>
					</div>
							
                                      
                  
					
					<!--Remark Type-->
					
					<div class="col-xs-2 form-group">
						<label for="module_status">Status<em class="asteriskRed">*</em></label>
						<select class="form-control"
							name="label_status"
							id="label_status"
							ng-model="editCustomDefinedFields.label_status.selectedOption"
							ng-required='true'
							ng-options="statusTypes.label_status_name for statusTypes in labelStatusList track by statusTypes.label_status">
							<option value="">Select Label Status</option>
						</select>
						<span ng-messages="erpEditCustomDefinedFieldsForm.label_status.$error" ng-if='erpEditCustomDefinedFieldsForm.label_status.$dirty  || erpEditCustomDefinedFieldsForm.$submitted' role="alert">
							<span ng-message="required" class="error">LabelStatus is required</span>
						</span>
					</div>
                  
					<!--Remark Type-->
						
					
					
					<!--save button-->
					<div class="col-xs-2">
						<label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" name="label_id" ng-value="editCustomDefinedFields.label_id" ng-model="editCustomDefinedFields.label_id">
						<span ng-if="{{defined('EDIT') && EDIT}}">
							<button title="Save" ng-disabled="erpEditCustomDefinedFieldsForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funUpdateCustomDefinedFields()'>Save</button>
						<button title="Close" type='button' class='mT26 btn btn-default btn-sm' ng-click='showAddForm()'>Close</button>

						</span>
					</div>
					<!--/save button-->						
				</div>
			</form>
			<!--Add method form-->
		</div>
	</div>
</div>
<div class="row" ng-hide="addCustomDefinedFieldsFormDiv">
	<div class="panel panel-default">
		<div class="panel-body" ng-model="addCustomDefinedFieldsFormDiv">			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Custom Defined fields</strong></span>
				<!--<span><button class="pull-right btn btn-primary mT3 mR3" ng-click="showUploadForm()">Upload</button></span>-->
			</div>			
			<!--Add method form-->
			<form name='erpAddCustomDefinedFieldsForm' id="add_custom_defined_form" novalidate>							
				<div class="row">
					
					
					
					<!--Remarks-->
					<div class="col-xs-3 form-group">
						<label for="label_name">Label Name<em class="asteriskRed">*</em></label>	
						<input type="text" class="form-control"
								ng-model="addCustomDefinedFields.label_name"
								name="label_name" 
								id="label_name"
								ng-required='true'
								placeholder="Label Name"/>

						<span ng-messages="erpAddCustomDefinedFieldsForm.label_name.$error" ng-if='erpCustomDefinedFieldsForm.label_name.$dirty || erpCustomDefinedFieldsForm.$submitted' role="alert">
						<span ng-message="required" class="error">Label Name required</span>
						</span>
					</div>
					<!--Remarks-->
							
					<div class="col-xs-2 form-group">
						<label for="label_value">Label Value<em class="asteriskRed">*</em></label>
						<input type="text" class="form-control"
						        name="label_value"
							id="label_value"
							ng-model="addCustomDefinedFields.label_value"
							ng-required='true'/>
							<span ng-messages="erpAddCustomDefinedFieldsForm.label_value.$error" ng-if='erpAddCustomDefinedFieldsForm.label_value.$dirty  || erpAddCustomDefinedFieldsForm.$submitted' role="alert">
							<span ng-message="required" class="error">Label Value is required</span>
							</span>
					</div>
                  
					
					<!--Remark Type-->
					
					<div class="col-xs-2 form-group">
						<label for="label_status">Label Status<em class="asteriskRed">*</em></label>
						<select class="form-control"
							name="label_status"
							id="label_status"
							ng-model="addCustomDefinedFields.label_status"
							ng-required='true'
					               ng-options="statusTypes.label_status_name for statusTypes in labelStatusList track by statusTypes.label_status">
							<option value="">Select Label Status</option>
						</select>
						<span ng-messages="erpAddCustomDefinedFieldsForm.label_status.$error" ng-if='erpAddCustomDefinedFieldsForm.label_status.$dirty  || erpAddDefaultRemarksForm.$submitted' role="alert">
							<span ng-message="required" class="error">Label Status is required</span>
						</span>
					</div>
                  
					<!--Remark Type-->
						
					
					
					<!--save button-->
					<div class="col-xs-2">
						<label for="submit">{{ csrf_field() }}</label>		
							<span>
								<button title="Save" ng-disabled="erpAddCustomDefinedFieldsForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funAddCustomDefinedFields ()'>Save</button>
								<button title="Reset"  type="button" class="mT26 btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>

							</span>
					</div>
					<!--/save button-->						
				</div>
			</form>
			<!--Add method form-->
		</div>
	</div>
</div>
<div class="row" ng-hide="hideSourceEditForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Payment Source</strong></span>
			</div>
<form name='editPaymentSourcesForm' id="edit_department_form" novalidate>
		<label for="submit">{{ csrf_field() }}</label>	
				<div class="row">	
					<div class="col-xs-3">
					   <div class="">     
						<label for="edit_payment_source_name">Payment Source Name<em class="asteriskRed">*</em></label>						   
							<input class="form-control" 
								    name="edit_payment_source_name"
									ng-value="edit_payment_source_name"
									ng-model="edit_payment_source_name" 
									placeholder="Payment Source Name"
									id="edit_payment_source_name">
							<span ng-messages="editPaymentSourcesForm.edit_payment_source_name.$error" 
							ng-if='editPaymentSourcesForm.edit_payment_source_name.$dirty  || editPaymentSourcesForm.$submitted' role="alert">
								<span ng-message="required" class="error">Payment source name is required</span>
							</span>
					   </div>
					</div>
					<div class="col-xs-3">
					   <div class="">     
						<label for="edit_payment_source_description">Payment Source Description<em class="asteriskRed">*</em></label>						   
							<textarea class="backWhite form-control" rows=1
									name="edit_payment_source_description"	
									ng-value="edit_payment_source_description"	
									ng-model="edit_payment_source_description"	
									id="edit_payment_source_description" 
									placeholder="Payment Source Description" 
									ng-required='true'/></textarea>
							<span ng-messages="editPaymentSourcesForm.edit_payment_source_description.$error" 
							ng-if='editPaymentSourcesForm.edit_payment_source_description.$dirty  || editPaymentSourcesForm.$submitted' role="alert">
								<span ng-message="required" class="error">Payment source description is required</span>
							</span>
					   </div>
					</div>
					<div class="col-xs-3">
					   <div class="">
						<label for="status">Payment Source Status<em class="asteriskRed">*</em></label>
							<select class="form-control" name="status" id="status" 
							  ng-options="option.name for option in status_types.availableTypeOptions track by option.id"
							  ng-model="status_types.selectedOption"></select>
							<span ng-messages="editPaymentSourcesForm.status.$error" 
							 ng-if='editPaymentSourcesForm.status.$dirty  || editPaymentSourcesForm.$submitted' role="alert">
								<span ng-message="required" class="error">Status is required</span>
							</span>
					   </div>
					</div>
					<div class="mT26 col-xs-3">
						<div class="pull-left">
							<input type='hidden' name="payment_source_id" ng-value="edit_payment_source_id" ng-model="edit_payment_source_id" > 
							<button title="Update"  ng-disabled="editPaymentSourcesForm.$invalid" type='submit' id='edit_button' class=' btn btn-primary' ng-click='updatePaymentSources()' > Update </button>
							<button title="Close"  type="button" class="btn btn-default" ng-click="hideEditForm()">Close</button>
						</div>
					</div>
				</div>
			</form>			
		</div>
	</div>
</div>
<div class="row" ng-hide="hideSourceAddForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Payment Source</strong></span>
			</div>
			<form name='paymentSourceForm' id="add_department_form" novalidate>
				<label for="submit">{{ csrf_field() }}</label>	
				<div class="row">
					<div class="col-xs-4">
					   <div class="">     
						<label for="company_name">Payment Source Name<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									name="payment_source_name"
									ng-model="sources.payment_source_name" 
									ng-required='true'
									placeholder="Payment Source Name"
									id="payment_source_name">
							<span ng-messages="paymentSourceForm.payment_source_name.$error" 
							ng-if='paymentSourceForm.payment_source_name.$dirty  || paymentSourceForm.$submitted' role="alert">
								<span ng-message="required" class="error">Payment source name is required</span>
							</span>
					   </div>
					</div>				
					
					<div class="col-xs-4">
					   <div class="">     
						<label for="payment_source_description">Payment Source Description<em class="asteriskRed">*</em></label>						   
							<textarea type="text" class="form-control" 
									ng-model="sources.payment_source_description"
									name="payment_source_description" 
									id="payment_source_description"
									ng-required='true' rows=1
									placeholder="Payment Source Description" /></textarea>
							<span ng-messages="paymentSourceForm.payment_source_description.$error" 
							ng-if='paymentSourceForm.payment_source_description.$dirty  || paymentSourceForm.$submitted' role="alert">
								<span ng-message="required" class="error">Payment source description is required</span>
							</span>
					   </div>
					</div>
					<div class="mT26 col-xs-4">
						<div class="pull-left">
							<button title="Save" ng-disabled="paymentSourceForm.$invalid" type='submit' id='add_button' class='btn btn-primary' ng-click='addPaymentSources()' > Save </button>
							<button title="Reset"  type="button" class="btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>

						</div>
					</div>
				</div>
			</form>			
		</div>
	</div>
</div>
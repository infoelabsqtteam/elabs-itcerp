<div class="row" ng-hide="hideDivisionEditForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Branch :  <b><span ng-model="company_name" ng-bind="company_name"></span></b></strong></span>
			</div>
			<form name='editDivisionForm' id="edit_division_form" novalidate>
				<div class="row">
					<div class="col-xs-3">
					   <div class="">     
						<label for="division_code1">Branch Code<em class="asteriskRed">*</em></label>						   
							<input readonly type="text" class="backWhite form-control" 
								ng-value="divi_code" ng-model="divi_code" 
									id="division_code1" placeholder="Branch Code" />
							<span ng-messages="editDivisionForm.division_code1.$error" 
							ng-if='editDivisionForm.division_code1.$dirty  || editDivisionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch code is required</span>
							</span>
					   </div>
					</div>
					<div class="col-xs-3">
					   <div class="">
						<label for="division_name1">Branch Name<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control" ng-model="divi_name"
									name="division_name1" 
									id="division_name1"
									ng-required='true'
									placeholder="Branch Name"/>
							<span ng-messages="editDivisionForm.division_name1.$error" 
							 ng-if='editDivisionForm.division_name1.$dirty  || editDivisionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch name is required</span>
							</span>
					   </div>
					</div>
					
					<div class="col-xs-3">
						<label for="division_country">Branch Country<em class="asteriskRed">*</em></label>
						<select
						    class="form-control"
						    name="division_country1"
						    ng-model="division_country1.selectedOption"
						    ng-change="funGetStateOnCountryChange(division_country1.selectedOption.id)"
						    id="division_country1"
						    ng-required='true'
						    ng-options="item.name for item in countryCodeList track by item.id ">
						    <option value="">Select Branch Country</option>
						</select>
						<span ng-messages="divisionForm.division_country.$error" ng-if='divisionForm.division_country.$dirty  || divisionForm.$submitted' role="alert">
						    <span ng-message="required" class="error">Branch Country is required</span>
						</span>
					</div>
						
					<div class="col-xs-3">
						<label for="division_state">Branch State:<em class="asteriskRed">*</em></label>						   
						<select class="form-control"
						    name="division_state1"
						    ng-model="division_state1.selectedOption"
						    id="division_state1"
						    ng-required='true'
						    ng-change="funGetCityOnStateChange(division_state1.selectedOption.id)"
						    ng-options="item.name for item in statesCodeList track by item.id">
						    <option value="">Select Branch State</option>
						</select>
						<span ng-messages="divisionForm.division_state.$error" ng-if='divisionForm.division_state.$dirty  || divisionForm.$submitted' role="alert">
						    <span ng-message="required" class="error">Branch state is required</span>
						</span>
					</div>
						
					<div class="col-xs-3">
						<label for="division_city">Customer City:<em class="asteriskRed">*</em></label>						   
						<select
						    class="form-control"
						    name="division_city1"
						    ng-model="division_city1.selectedOption"
						    id="division_city1"
						    ng-required='true'
						    ng-options="item.name for item in citiesCodesList track by item.id ">
						<option value="">Select Branch City</option>
						</select>
						<span ng-messages="divisionForm.customer_city.$error" ng-if='divisionForm.customer_city.$dirty  || divisionForm.$submitted' role="alert">
						    <span ng-message="required" class="error">Customer city is required</span>
						</span>
					</div>
					
					<div class="col-xs-3">
					   <div class="">     
						<label for="division_PAN1">Branch PAN<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" name="division_PAN1"
								ng-value="divi_pan" ng-model="divi_pan" ng-required='true'
									id="division_PAN1" placeholder="Branch PAN" />
							<span ng-messages="editDivisionForm.division_PAN1.$error" 
							ng-if='editDivisionForm.division_PAN1.$dirty  || editDivisionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch PAN is required</span>
							</span>
					   </div>
					</div>
					<div class="col-xs-3">
					   <div class="">     
						<label for="division_VAT1">Branch VAT<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" ng-required='true'
								ng-value="divi_vat" ng-model="divi_vat" name="division_VAT1"
									id="division_VAT1" placeholder="Branch VAT" />
							<span ng-messages="editDivisionForm.division_VAT1.$error" 
							ng-if='editDivisionForm.division_VAT1.$dirty  || editDivisionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch VAT is required</span>
							</span>
					   </div>
					</div>
					<div class="col-xs-6">
					   <div class="">     
						<label for="division_address1">Branch Address<em class="asteriskRed">*</em></label>						   
							<textarea
								class="form-control"
								name="division_address1"
								rows=5
								ng-value="divi_address"
								ng-model="divi_address"
								ng-required='true'
								id="division_address1"
								placeholder="Branch Address" />
							</textarea>
							<span ng-messages="editDivisionForm.division_address1.$error" ng-if='editDivisionForm.division_address1.$dirty  || editDivisionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch Address is required</span>
							</span>
					   </div>
					</div>
					<div class="mT26 col-xs-3">
						<div class="pull-left">
							<label for="submit">{{ csrf_field() }}</label>
							<input type='hidden' id='division_id' name="division_id" ng-value="divi_id" > 
							<button  ng-disabled="editDivisionForm.$invalid" type='submit' id='edit_button' title="Update" class=' btn btn-primary' ng-click='updateDivision()' > Update </button>			
							<button type="button" class="btn btn-default" ng-click="hideEditForm()" title="Close">Close</button>
						</div>
					</div>
				</div>
			</form>		
		</div>
	</div>
</div>
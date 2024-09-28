<div class="row" ng-hide="hideDivisionAddForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Branch :  <b><span ng-model="company_name" ng-bind="company_name"></span></b></strong></span>
			</div>
			<form name='divisionForm' id="add_division_form" novalidate>
				<label for="submit">{{ csrf_field() }}</label>	
				<div class="row">	
					<div class="col-xs-3">
					<span class="generate"><a href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
						<label for="division_code">Branch Code<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" readonly
									ng-model="division_code"
									ng-bind="division_code"
									name="division_code" 
									id="division_code"
									ng-required='true'
									placeholder="Branch Code" />
							<span ng-messages="divisionForm.division_code.$error" 
							ng-if='divisionForm.division_code.$dirty || divisionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch code is required</span>
							</span>
					</div>
					<div class="col-xs-3">
						<label for="division_name">Branch Name<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control"  
									ng-model="division_name"
									name="division_name" 
									id="division_name"
									ng-required='true'
									placeholder="Branch Name"/>
							<span ng-messages="divisionForm.division_name.$error" 
							 ng-if='divisionForm.division_name.$dirty  || divisionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch name is required</span>
							</span>
					</div>
						
					<div class="col-xs-3">
						<label for="division_country">Branch Country<em class="asteriskRed">*</em></label>
						<select
						    class="form-control"
						    name="division_country"
						    ng-model="division_country"
						    ng-change="funGetStateOnCountryChange(division_country.id)"
						    id="division_country"
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
						    name="division_state"
						    ng-model="division_state"
						    id="division_state"
						    ng-required='true'
						    ng-change="funGetCityOnStateChange(division_state.id)"
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
						    name="division_city"
						    ng-model="division_city"
						    id="division_city"
						    ng-required='true'
						    ng-options="item.name for item in citiesCodesList track by item.id ">
						<option value="">Select Branch City</option>
						</select>
						<span ng-messages="divisionForm.customer_city.$error" ng-if='divisionForm.customer_city.$dirty  || divisionForm.$submitted' role="alert">
						    <span ng-message="required" class="error">Customer city is required</span>
						</span>
					</div>
					
					<div class="col-xs-3">
						<label for="division_PAN">Branch PAN No<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control"  
									ng-model="division_PAN"
									name="division_PAN" 
									id="division_PAN"
									ng-required='true'
									placeholder="Branch PAN No"/>
							<span ng-messages="divisionForm.division_PAN.$error" 
							 ng-if='divisionForm.division_PAN.$dirty  || divisionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch PAN is required</span>
							</span>
					</div>
					
					<div class="col-xs-3">
						<label for="division_VAT_no">Branch VAT No<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control"  
									ng-model="division_VAT_no"
									name="division_VAT_no" 
									id="division_VAT_no"
									ng-required='true'
									placeholder="Branch VAT No"/>
							<span ng-messages="divisionForm.division_VAT_no.$error" 
							 ng-if='divisionForm.division_VAT_no.$dirty  || divisionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch VAT No is required</span>
							</span>
					</div>
								
					<div class="col-xs-3">
						<label for="division_address">Branch Address<em class="asteriskRed">*</em></label>						   
							<textarea
								class="form-control" 
								ng-model="division_address"
								rows='2'
								name="division_address" 
								id="division_address"
								ng-required='true'
								placeholder="Branch Address" />
							</textarea>
							<span ng-messages="divisionForm.division_address.$error" ng-if='divisionForm.division_address.$dirty  || divisionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch Address is required</span>
							</span>
					</div>
					<div class="mT26 col-xs-3">
						<div class="pull-left">
							<input type="hidden" name="company_id"  name="company_id" ng-model="company_id" readonly ng-value="company_id" id="company_id">
							<button ng-disabled="divisionForm.$invalid"  type='submit' id='add_button' class=' btn btn-primary' ng-click='addDivision()' title="Save"> Save </button>
							<button  type='button' id='reset_button' class=' btn btn-default' ng-click='resetDivision()' title="Reset"> Reset </button>

						</div>
					</div>
				</div>
			</form>		
		</div>
	</div>
</div>
<div class="row" ng-hide="hideCompanyEditForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Company Type</strong></span>
					
			</div>
			<form name='editCompanyForm' id="edit_company_form" novalidate>
			<label for="submit">{{ csrf_field() }}</label>	
				<div class="row">
					<div class="col-xs-2">
					   <div class="">     
						<label for="company_code1">Company Code<em class="asteriskRed">*</em></label>						   
							<input readonly type="text" class="backWhite form-control"  ng-model="company_code1"
									id="company_code1"
									placeholder="Company Code" />
					   </div>
					</div>
					<div class="col-xs-3">
						<label for="company_name1">Company Name<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="company_name1"
									name="company_name1"  ng-value="company_name1"
									id="company_name1"
									ng-required='true'
									placeholder="Company Name" />
							<span ng-messages="editCompanyForm.company_name1.$error" 
							ng-if='editCompanyForm.company_name1.$dirty  || editCompanyForm.$submitted' role="alert">
								<span ng-message="required" class="error">Company name is required</span>
							</span>
					</div>
					<div class="col-xs-3">
					   <div class="">     
						<label for="company_city1">Company City<em class="asteriskRed">*</em></label>						   
							<select class="form-control"   
									name="company_city1" ng-required='true'
									id="company_city1" 
									ng-options="item.name for item in citiesCodeList track by item.id "
							  ng-model="cityData.selectedOption">
							  <option value="">Select Company City</option>
							  </select>
							<span ng-messages="editCompanyForm.company_city1.$error" 
							ng-if='editCompanyForm.company_city1.$dirty  || editCompanyForm.$submitted' role="alert">
								<span ng-message="required" class="error">Company city is required</span>
							</span>
					   </div>
					</div>
					<div class="col-xs-3">
					   <div class="">
						<label for="company_address1">Company Address<em class="asteriskRed">*</em></label>
							<textarea rows=1 type="text" class="form-control" 
									ng-model="company_address1"  ng-value="company_address1"
									name="company_address1" 
									id="company_address1"
									ng-required='true'
									placeholder="Company Address" /></textarea>
							<span ng-messages="editCompanyForm.company_address1.$error" 
							 ng-if='editCompanyForm.company_address1.$dirty  || editCompanyForm.$submitted' role="alert">
								<span ng-message="required" class="error">Company address is required</span>
							</span>
					   </div>
					</div>
				</div>
					<div class="row pull-right">
						<div class="col-xs-12 mT10">
							<input type="hidden" name="company_id1" ng-value="company_id1" ng-model="company_id1">
							<button ng-disabled="editCompanyForm.$invalid" type='submit' id='edit_button' class='btn btn-primary'  title="Update" ng-click='updateCompany()' > Update </button>
							<button ng-click="hideEditForm()" type="button" class="btn btn-default" title="Close">Close</button>
						</div>
					</div>
			</form>
		</div>
	</div>
</div>
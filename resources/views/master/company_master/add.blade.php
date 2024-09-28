<div id="add_form" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Add Company</h4>
		  </div>
		  <div class="modal-body">	
			
			<!--display Messge Div-->
			@include('includes.alertMessagePopup')
			<!--/display Messge Div-->
	
			<form name='companyForm' id="add_company_form" novalidate>
			    <label for="submit">{{ csrf_field() }}</label>	
				<div class="row">
					<div class="col-xs-12">
						<label for="company_code">Company Name<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="company_code"
									name="company_code" 
									id="company_code"
									ng-required='true'
									placeholder="Company Code" />
							<span ng-messages="companyForm.company_code.$error" 
							ng-if='companyForm.company_code.$dirty  || companyForm.$submitted' role="alert">
								<span ng-message="required" class="error">Company code is required</span>
							</span>
					</div>
					<div class="col-xs-12">
						<label for="company_name">Company Name<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="company_name"
									name="company_name" 
									id="company_name"
									ng-required='true'
									placeholder="Company Name" />
							<span ng-messages="companyForm.company_name.$error" 
							ng-if='companyForm.company_name.$dirty  || companyForm.$submitted' role="alert">
								<span ng-message="required" class="error">Company name is required</span>
							</span>
					</div>
					<div class="col-xs-12">
						<label for="company_city">Company City<em class="asteriskRed">*</em></label>						   
							<select class="form-control" name="company_city"
									ng-model="company_city"
									id="company_city" ng-required='true'
									ng-options="item.name for item in citiesCodeList track by item.id ">
								<option value="">Select Company City</option>
							</select>
							<span ng-messages="companyForm.company_city.$error" 
							ng-if='companyForm.company_city.$dirty  || companyForm.$submitted' role="alert">
								<span ng-message="required" class="error">Company city is required</span>
							</span>
					</div>
					<div class="col-xs-12">
						<label for="company_address">Company Address<em class="asteriskRed">*</em></label>
							<textarea rows=1 type="text" class="form-control" 
									ng-model="company_address"
									name="company_address" 
									id="company_address"
									ng-required='true'
									placeholder="Company Address" /></textarea>
							<span ng-messages="companyForm.company_address.$error" 
							 ng-if='companyForm.company_address.$dirty  || companyForm.$submitted' role="alert">
								<span ng-message="required" class="error">Company address is required</span>
							</span>
					</div>
					<div class="mT26 col-xs-12 ">
						<div class="pull-right">
							<button  ng-disabled="companyForm.$invalid" type='submit'  title="Save" id='add_button' class='btn btn-primary' ng-click='addCompany()' > Save </button>
							<button type="button" class="btn btn-default" data-dismiss="modal" title="Close" >Close</button>
						</div>
					</div>
				</div>
		  </div>
	 </form>	
    </div>
  </div>
</div>
			<div ng-model="addCompanyFormDiv" ng-hide="addCompanyFormDiv" >
				<div class="row header1">
					<strong class="pull-left headerText">Add Company</strong>
				</div>
				<form name='companyForm' id="add_company_form" novalidate>	
				<label for="submit">{{ csrf_field() }}</label>					
					<div class="row">						
						<div class="col-xs-3">
							<label for="company_type_code">Company Type Code<em class="asteriskRed">*</em></label>						   
								<span class="generate"><a href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
								<input type="text" class="form-control" readonly
										ng-model="company_type_code"
										ng-bind="company_type_code" 
										name="company_type_code" 
										id="company_type_code"
										ng-required='true'
										placeholder="Company Code" />
								<span ng-messages="companyForm.company_type_code.$error" 
								ng-if='companyForm.company_type_code.$dirty  || companyForm.$submitted' role="alert">
									<span ng-message="required" class="error">Company code is required</span>
								</span>
						</div>
						<div class="col-xs-3">
							<label for="company_type_name">Company Type Name<em class="asteriskRed">*</em></label>						   
								<input type="text" class="form-control" 
										ng-model="company.company_type_name"
										name="company_type_name" 
										id="company_type_name"
										ng-required='true'
										placeholder="Company Name" />
								<span ng-messages="companyForm.company_type_name.$error" 
								ng-if='companyForm.company_type_name.$dirty  || companyForm.$submitted' role="alert">
									<span ng-message="required" class="error">Company name is required</span>
								</span>
						</div>
						<div class="col-xs-3">
							<div class="">
								<button title="Save"   ng-disabled="companyForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='addCompany()' > Save </button>
		<button title="Reset"  type="button" class="mT26 btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>

							</div>
						</div>
					</div>
				</form>	
			</div>
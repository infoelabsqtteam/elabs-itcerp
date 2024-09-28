			<div ng-model="editCompanyFormDiv" id="editCompanyDiv" ng-hide="editCompanyFormDiv" >
				<div class="row header1">
					<strong class="pull-left headerText">Edit Company</strong>
				</div>
				<form name='editCompanyForm' id="edit_company_form" novalidate>
				    <label for="submit">{{ csrf_field() }}</label>	
					<div class="row">
						<div class="col-xs-3">
							<label for="company_type_code1">Company Type Code<em class="asteriskRed">*</em></label>						   
							<input  readonly type="text"
									class="form-control" 
									ng-model="edit_company.company_type_code"
									placeholder="Company Code" />
						</div>
						<div class="col-xs-3">
							<label for="company_type_name1">Company Type Name<em class="asteriskRed">*</em></label>						   
								<input type="text" class="form-control" 
										ng-model="edit_company.company_type_name" 
										name="company_type_name" 
										ng-required='true'
										placeholder="Company Name" />
								<span ng-messages="editCompanyForm.company_type_name1.$error" 
								ng-if='editCompanyForm.company_type_name1.$dirty  || editCompanyForm.$submitted' role="alert">
									<span ng-message="required" class="error">Company name is required</span>
								</span>
						</div>
						<div class="col-xs-3">
								<input type="hidden" name="company_type_id" ng-model="company_type_id" ng-value="company_type_id">
								<button title="Update"  ng-disabled="editCompanyForm.$invalid" type='submit' class='mT26 btn btn-primary  btn-sm' ng-click='updateCompany()' > Update </button>
								<button title="Close"  type='button' class='mT26 btn btn-default  btn-sm' ng-click='showAddForm()' > Close </button>
						</div>
					</div>
				</form>	
			</div>
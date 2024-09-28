<div ng-model="editCityFormDiv" id="editCityDiv" ng-hide="editCityFormDiv" >
				<div class="row header1">
					<strong class="pull-left headerText">Edit City</strong>
				</div>
				<form name='editCityForm' id="edit_city_form" novalidate>
				    <label for="submit">{{ csrf_field() }}</label>	
					<div class="row">
						<div class="col-xs-3">
							<label for="city_code1">City Code<em class="asteriskRed">*</em></label>						   
							<input readonly type="text"
									class="form-control" 
									ng-model="edit_city.city_code"
									placeholder="City Code" />
						</div>
						<div class="col-xs-3">
							<label for="city_name1">City Name<em class="asteriskRed">*</em></label>						   
								<input type="text" class="form-control" 
										ng-model="edit_city.city_name" 
										name="city_name" 
										ng-required='true'
										placeholder="City Name" />
								<span ng-messages="editCityForm.city_name1.$error" 
								ng-if='editCityForm.city_name1.$dirty  || editCityForm.$submitted' role="alert">
									<span ng-message="required" class="error">City name is required</span>
								</span>
						</div>
				                <div class="col-xs-3">
								<label for="country_id">Country<em class="asteriskRed">*</em>
								</label>
								<select
									class="form-control"
									ng-model="edit_city.country_id.selectedOption"
									id="country_id"
									ng-required='true'
									ng-options="item.name for item in countryCodeList track by item.id"
									ng-change="funGetStateOnCountryChange(edit_city.country_id.selectedOption.id)">
								    <option value="">Select Country</option>
								</select>
								<span ng-messages="stateForm.country_id.$error" ng-if='stateForm.country_id.$dirty  || stateForm.$submitted' role="alert">
								    <span ng-message="required" class="error">Country is required</span>
								</span>
						</div>
						<div class="col-xs-3">
							<label for="state_id1">State<em class="asteriskRed">*</em></label>
								<select class="form-control" 
										name="state_id"
										ng-model="edit_city.state_id.selectedOption"
										ng-required='true'
										ng-options="item.name for item in statesList track by item.id ">
										<option value="">Select State</option>
								</select>
								<span ng-messages="editCityForm.state_id1.$error" 
								 ng-if='editCityForm.state_id1.$dirty  || editCityForm.$submitted' role="alert">
									<span ng-message="required" class="error">State is required</span>
								</span>
						</div>
						<div class="col-xs-3">
								<input type="hidden" name="city_id" ng-model="city_id" ng-value="city_id">
								<button title="Update"  ng-disabled="editCityForm.$invalid" type='submit' class='mT26 btn btn-primary  btn-sm' ng-click='updateCity()' > Update </button>
								<button title="Close"  type='button' class='mT26 btn btn-default  btn-sm' ng-click='showAddForm()' > Close </button>
						</div>
					</div>
				</form>	
			</div>
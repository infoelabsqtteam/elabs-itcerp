<div ng-model="editStateFormDiv" id="editStateDiv" ng-hide="editStateFormDiv" >
			<div class="row header1">
				<strong class="pull-left headerText">Edit State</strong>
			</div>
			<form name='editStateForm' id="edit_state_form" novalidate>
				<label for="submit">{{ csrf_field() }}</label>	
				<div class="row">
					<div class="col-xs-3">
						<label for="state_code1">State Code<em class="asteriskRed">*</em></label>						   
						<input  type="text"
						class="form-control" 
						ng-model="edit_state.state_code"
						name="state_code"
						id="state_code"
						ng-required='true'
						placeholder="State Code" />
					</div>
					<div class="col-xs-3">
						<label for="state_name1">State Name<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="edit_state.state_name" 
									name="state_name" 
									ng-required='true'
									placeholder="State Name" />
							<span ng-messages="editStateForm.state_name1.$error" 
							ng-if='editStateForm.state_name1.$dirty  || editStateForm.$submitted' role="alert">
								<span ng-message="required" class="error">State name is required</span>
							</span>
					</div>
					<div class="col-xs-3">
						<label for="country_id1">Country<em class="asteriskRed">*</em></label>
							<select class="form-control" 
									name="country_id"
									ng-model="country_id.selectedOption"
									ng-required='true'
									ng-options="item.name for item in countryCodeList track by item.id ">
									<option value="">Select Country</option>
							</select>
							<span ng-messages="editStateForm.country_id1.$error" 
							 ng-if='editStateForm.country_id1.$dirty  || editStateForm.$submitted' role="alert">
								<span ng-message="required" class="error">Country is required</span>
							</span>
					</div>
						<div class="col-xs-3">
									<label for="country_id">Status<em class="asteriskRed">*</em>
									</label>
									<select class="form-control" name="state_status" ng-model="state_status.selectedOption" id="state_status" ng-required='true' ng-options="item.name for item in statusList track by item.id ">
									</select>
									<span ng-messages="stateForm.state_status.$error" ng-if='stateForm.state_status.$dirty  || stateForm.$submitted' role="alert">
											<span ng-message="required" class="error">Status is required</span>
									</span>
						</div>
						<div class="row">
									<div class="col-xs-2 pull-right">
										<input type="hidden" name="state_id" ng-model="state_id" ng-value="state_id">
										<button title="Update"  ng-disabled="editStateForm.$invalid" type='submit' class='mT26 btn btn-primary  btn-sm' ng-click='updateState()' > Update </button>
										<button title="Close"  type='button' class='mT26 btn btn-default  btn-sm' ng-click='showAddForm()' > Close </button>
									</div>
						</div>
				</div>
			</form>	
		</div>
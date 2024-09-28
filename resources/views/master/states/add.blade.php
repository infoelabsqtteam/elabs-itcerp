<div ng-model="addStateFormDiv" ng-hide="addStateFormDiv">
    <div class="row header1">
        <strong class="pull-left headerText">Add State</strong>
    </div>
    <form name='stateForm' id="add_state_form" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <div class="row">
            <div class="col-xs-3">
                <label for="state_code">State Code<em class="asteriskRed">*</em>
                </label>
                <input type="text" class="form-control" ng-model="state.state_code" name="state_code" id="state_code" ng-required='true' placeholder="State Code" />
                <span ng-messages="stateForm.state_code.$error" ng-if='stateForm.state_code.$dirty  || stateForm.$submitted' role="alert">
		    <span ng-message="required" class="error">State code is required</span>
                </span>
            </div>
            <div class="col-xs-3">
                <label for="state_name">State Name<em class="asteriskRed">*</em>
                </label>
                <input type="text" class="form-control" ng-model="state.state_name" name="state_name" id="state_name" ng-required='true' placeholder="State Name" />
                <span ng-messages="stateForm.state_name.$error" ng-if='stateForm.state_name.$dirty  || stateForm.$submitted' role="alert">
		    <span ng-message="required" class="error">State name is required</span>
                </span>
            </div>
            <div class="col-xs-3">
                <label for="country_id">Country<em class="asteriskRed">*</em>
                </label>
                <select class="form-control" name="country_id" ng-model="state.country_id" id="country_id" ng-required='true' ng-options="item.name for item in countryCodeList track by item.id ">
                    <option value="">Select Country</option>
                </select>
                <span ng-messages="stateForm.country_id.$error" ng-if='stateForm.country_id.$dirty  || stateForm.$submitted' role="alert">
		    <span ng-message="required" class="error">Country is required</span>
                </span>
            </div>
            <div class="col-xs-3">
                <label for="country_id">Status<em class="asteriskRed">*</em>
                </label>
                <select class="form-control" name="state_status" ng-model="state.state_status.selectedOption" id="state_status" ng-required='true' ng-options="item.name for item in statusList track by item.id ">
                </select>
                <span ng-messages="stateForm.state_status.$error" ng-if='stateForm.state_status.$dirty  || stateForm.$submitted' role="alert">
		                <span ng-message="required" class="error">Status is required</span>
                </span>
            </div>
				
				<div class="row">
					 <div class="col-xs-2 pull-right">
								<button title="Save" ng-disabled="stateForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='addState()'> Save </button>
								<button type='button' id='reset_button' class=' mT26 btn btn-default' ng-click='resetState()' title="Reset"> Reset </button>
					 </div>
				</div>
        </div>
    </form>
</div>

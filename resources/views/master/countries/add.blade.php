<div ng-model="addStateFormDiv" ng-show="addCountryFormDiv">
    <div class="row header1">
        <strong class="pull-left headerText">Add Country</strong>
    </div>
    <form name='addCountryForm' id="add_country_form" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <div class="row">
            <div class="col-xs-3">
                <label for="state_code">Country Code<em class="asteriskRed">*</em>
                </label>
                <input type="text" class="form-control" ng-model="addCountry.country_code" name="country_code" id="country_code" ng-required='true' placeholder="Country Code" />
                <span ng-messages="addCountryForm.country_code.$error" ng-if='addCountryForm.country_code.$dirty  || addCountryForm.$submitted' role="alert">
						  <span ng-message="required" class="error">Country Code is required</span>
                </span>
            </div>
            <div class="col-xs-3">
                <label for="state_name">Country Name<em class="asteriskRed">*</em>
                </label>
                <input type="text" class="form-control" ng-model="addCountry.country_name" name="country_name" id="state_name" ng-required='true' placeholder="Country Name" />
                <span ng-messages="addCountryForm.country_name.$error" ng-if='addCountryForm.country_name.$dirty  || addCountryForm.$submitted' role="alert">
						  <span ng-message="required" class="error">Country name is required</span>
                </span>
            </div>
           
            <div class="col-xs-3">
                <label for="country_id">Status<em class="asteriskRed">*</em>
                </label>
                <select class="form-control" name="country_status" ng-model="country_status.selectedOption" id="country_status" ng-required='true' ng-options="item.name for item in statusList track by item.id ">
                </select>
                <span ng-messages="addCountryForm.country_status.$error" ng-if='addCountryForm.country_status.$dirty  || addCountryForm.$submitted' role="alert">
		                <span ng-message="required" class="error">Status is required</span>
                </span>
            </div>
				
				
				<div class="col-xs-3 pull-right">
						  <button title="Save" ng-disabled="addCountryForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funAddCountryData()'> Save </button>
						  <button type='button' id='reset_button' class=' mT26 btn btn-default' ng-click='resetCountry()' title="Reset"> Reset </button>
				</div>
				
        </div>
    </form>
</div>

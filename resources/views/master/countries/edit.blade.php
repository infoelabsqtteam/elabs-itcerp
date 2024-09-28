
<div ng-model="editStateFormDiv" ng-show="editCountryFormDiv">
    <div class="row header1">
        <strong class="pull-left headerText">Edit Country</strong>
    </div>
    <form name='editCountryForm' id="edit_country_form" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <div class="row">
            <div class="col-xs-3">
                <label for="country_code">Country Code<em class="asteriskRed">*</em>
                </label>
                <input type="text" class="form-control" ng-model="edit_country.country_code" name="country_code" id="country_code" ng-required='true' placeholder="Country Code" readonly/>
                <span ng-messages="editCountryForm.country_code.$error" ng-if='editCountryForm.country_code.$dirty  || editCountryForm.$submitted' role="alert">
						  <span ng-message="required" class="error">Country Code is required</span>
                </span>
            </div>
            <div class="col-xs-3">
                <label for="state_name">Country Name<em class="asteriskRed">*</em>
                </label>
                <input type="text" class="form-control" ng-model="edit_country.country_name" name="country_name" id="state_name" ng-required='true' placeholder="Country Name" />
                <span ng-messages="editCountryForm.country_name.$error" ng-if='editCountryForm.country_name.$dirty  || editCountryForm.$submitted' role="alert">
						  <span ng-message="required" class="error">Country name is required</span>
                </span>
            </div>
           
            <div class="col-xs-3">
                <label for="country_id">Status<em class="asteriskRed">*</em>
                </label>
                <select class="form-control" name="country_status" ng-model="edit_country_status.selectedOption" id="country_status" ng-required='true' ng-options="item.name for item in statusList track by item.id ">
                </select>
                <span ng-messages="editCountryForm.country_status.$error" ng-if='editCountryForm.country_status.$dirty  || editCountryForm.$submitted' role="alert">
		                <span ng-message="required" class="error">Status is required</span>
                </span>
            </div>
				
				
				<div class="col-xs-3 pull-right">
														<input type="hidden" name="country_id" ng-model="country_id" ng-value="country_id">

						  <button title="Save" ng-disabled="editCountryForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funUpdateCountryData()'> Update </button>
						  <button title="Close"  type='button' class='mT26 btn btn-default  btn-sm' ng-click='showAddForm()' > Close </button>
				</div>
				
        </div>
    </form>
</div>

<div ng-model="addCityFormDiv" ng-hide="addCityFormDiv" >
<div class="row header1">
<strong class="pull-left headerText">Add City</strong>
</div>
<form name='cityForm' id="add_city_form" novalidate>	
<label for="submit">{{ csrf_field() }}</label>					
<div class="row">						
<div class="col-xs-3">
<label for="city_code">City Code<em class="asteriskRed">*</em></label>						   
	<input type="text" class="form-control" 
			ng-model="city.city_code"
			name="city_code" 
			id="city_code"
			ng-required='true'
			placeholder="City Code" />
	<span ng-messages="cityForm.city_code.$error" 
	ng-if='cityForm.city_code.$dirty  || cityForm.$submitted' role="alert">
		<span ng-message="required" class="error">City code is required</span>
	</span>
</div>
<div class="col-xs-3">
<label for="city_name">City Name<em class="asteriskRed">*</em></label>						   
	<input type="text" class="form-control" 
			ng-model="city.city_name"
			name="city_name" 
			id="city_name"
			ng-required='true'
			placeholder="City Name" />
	<span ng-messages="cityForm.city_name.$error" 
	ng-if='cityForm.city_name.$dirty  || cityForm.$submitted' role="alert">
		<span ng-message="required" class="error">City name is required</span>
	</span>
</div>
<div class="col-xs-3">
                <label for="country_id">Country<em class="asteriskRed">*</em>
                </label>
                <select
			class="form-control"
			ng-model="city.country_id"
			id="country_id"
			ng-required='true'
			ng-options="item.name for item in countryCodeList track by item.id"
			ng-change="funGetStateOnCountryChange(city.country_id.id)">
                    <option value="">Select Country</option>
                </select>
                <span ng-messages="stateForm.country_id.$error" ng-if='stateForm.country_id.$dirty  || stateForm.$submitted' role="alert">
		    <span ng-message="required" class="error">Country is required</span>
                </span>
            </div>
<div class="col-xs-3" ng-if="statesList.length">
<label for="state_id">State<em class="asteriskRed">*</em></label>
	<select class="form-control" name="state_id"
			ng-model="city.state_id"
			id="state_id" ng-required='true'
			ng-options="item.name for item in statesList track by item.id ">
			<option value="">Select State</option>
	</select>
	<span ng-messages="cityForm.state_id.$error" 
	 ng-if='cityForm.state_id.$dirty  || cityForm.$submitted' role="alert">
		<span ng-message="required" class="error">State is required</span>
	</span>
</div>
<div class="col-xs-3">
<div class="">
<button title="Save"   ng-disabled="cityForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='addCity()' > Save </button>
	<button  type='button' id='reset_button' class=' mT26 btn btn-default' ng-click='resetCity()' title="Reset"> Reset </button>

</div>
</div>
</div>
</form>	
</div>
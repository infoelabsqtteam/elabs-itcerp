<div ng-model="addFormDiv" ng-hide="addFormDiv">
    <div class="row header1">
        <strong class="pull-left headerText">Add Stability Type Master</strong>
    </div>
    <form name='addForm' id="add_form" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <div class="row">
				
            <div class="col-xs-4">
                <label for="state_name">Stability Type<em class="asteriskRed">*</em>
                </label>
                <input type="text" class="form-control" ng-model="stabilityType.stb_stability_type_name" name="stb_stability_type_name" id="stb_stability_type_name" ng-required='true' placeholder="Stability Type" />
                <span ng-messages="addForm.stb_stability_type_name.$error" ng-if='addForm.stb_stability_type_name.$dirty  || addForm.$submitted' role="alert">
					<span ng-message="required" class="error">Stability type is required</span>
                </span>
            </div>
           
				<div class="col-xs-4">
                <label for="status">Status<em class="asteriskRed">*</em></label>
					 <select class="form-control"
						 name="stb_stability_type_status"
						 id="stb_stability_type_status"
						 ng-model="stabilityType.stb_stability_type_status.selectedOption"
						 ng-options="status.name for status in statusList track by status.id"
						 ng-required="true">
						 <option value="">Select Status</option>
					 </select>
				
                <span ng-messages="addForm.stb_stability_type_status.$error" ng-if='addForm.stb_stability_type_status.$dirty  || addForm.$submitted' role="alert">
						  <span ng-message="required" class="error">Status is required</span>
                </span>
            </div>
					 
            <div class="col-xs-4">
                <div class="">
                    <button title="Save" ng-disabled="addForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='add()'> Save </button>
                    <button type='button' id='reset_button' class=' mT26 btn btn-default' ng-click='resetForm()' title="Reset"> Reset </button>
                </div>
            </div>
        </div>
    </form>
</div>
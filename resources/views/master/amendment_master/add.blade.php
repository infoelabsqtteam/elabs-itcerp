<div ng-model="addAmendmentFormDiv" ng-hide="addAmendmentFormDiv">
    <div class="row header1">
        <strong class="pull-left headerText">Add Amendment Master</strong>
    </div>
    <form name='amendmentForm' id="add_amendment_form" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <div class="row">
				<div class="col-xs-3">
				<span class="generate"><a href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
                <label for="state_name">Amendment Code<em class="asteriskRed">*</em> </label>
						  <input readonly type="text" class="form-control" ng-model="amendment.oam_code" name="oam_code" id="amendment_code" ng-required='true' placeholder="Amendment Code" />
                <span ng-messages="amendmentForm.oam_code.$error" ng-if='amendmentForm.oam_code.$dirty  || amendmentForm.$submitted' role="alert">
						  <span ng-message="required" class="error">Amendment code is required</span>
                </span>
            </div>
            <div class="col-xs-3">
                <label for="state_name">Amendment Name<em class="asteriskRed">*</em>
                </label>
                <input type="text" class="form-control" ng-model="amendment.oam_name" name="oam_name" id="amendment_name" ng-required='true' placeholder="Amendment Name" />
                <span ng-messages="amendmentForm.oam_name.$error" ng-if='amendmentForm.oam_name.$dirty  || amendmentForm.$submitted' role="alert">
					<span ng-message="required" class="error">Amendment name is required</span>
                </span>
            </div>
           
				<div class="col-xs-3">
                <label for="country_id">Status<em class="asteriskRed">*</em></label>
					 <select class="form-control"
						 name="oam_status"
						 id="oam_status"
						 ng-model="amendment.oam_status.selectedOption"
						 ng-options="status.name for status in statusList track by status.id"
						 ng-required="true">
						 <option value="">Select Status</option>
						
					 </select>
				
                <span ng-messages="amendmentForm.oam_status.$error" ng-if='amendmentForm.oam_status.$dirty  || amendmentForm.$submitted' role="alert">
						  <span ng-message="required" class="error">Status is required</span>
                </span>
            </div>
					 
            <div class="col-xs-3">
                <div class="">
                    <button title="Save" ng-disabled="amendmentForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='addAmendment()'> Save </button>
                    <button type='button' id='reset_button' class=' mT26 btn btn-default' ng-click='resetAmendmentForm()' title="Reset"> Reset </button>
                </div>
            </div>
        </div>
    </form>
</div>
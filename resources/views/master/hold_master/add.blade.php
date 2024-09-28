<div ng-model="addHoldFormDiv" ng-hide="addHoldFormDiv">
    <div class="row header1">
        <strong class="pull-left headerText">Add Hold Master</strong>
    </div>
    <form name='holdForm' id="add_hold_form" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <div class="row">
				<div class="col-xs-3">
				<span class="generate"><a href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
                <label for="state_name">Hold Code<em class="asteriskRed">*</em> </label>
						  <input readonly type="text" class="form-control" ng-model="hold.oh_code" name="oh_code" id="hold_code" ng-required='true' placeholder="Hold Code" />
                <span ng-messages="holdForm.oh_code.$error" ng-if='holdForm.oh_code.$dirty  || holdForm.$submitted' role="alert">
						  <span ng-message="required" class="error">Hold code is required</span>
                </span>
            </div>
            <div class="col-xs-3">
                <label for="state_name">Hold Name<em class="asteriskRed">*</em>
                </label>
                <input type="text" class="form-control" ng-model="hold.oh_name" name="oh_name" id="hold_name" ng-required='true' placeholder="Hold Name" />
                <span ng-messages="holdForm.oh_name.$error" ng-if='holdForm.oh_name.$dirty  || holdForm.$submitted' role="alert">
					<span ng-message="required" class="error">Hold name is required</span>
                </span>
            </div>
           
				<div class="col-xs-3">
                <label for="country_id">Status<em class="asteriskRed">*</em></label>
					 <select class="form-control"
						 name="oh_status"
						 id="oh_status"
						 ng-model="hold.oh_status.selectedOption"
						 ng-options="status.name for status in statusList track by status.id"
						 ng-required="true">
						 <option value="">Select Status</option>
					 </select>
				
                <span ng-messages="holdForm.oam_status.$error" ng-if='holdForm.oam_status.$dirty  || holdForm.$submitted' role="alert">
						  <span ng-message="required" class="error">Status is required</span>
                </span>
            </div>
					 
            <div class="col-xs-3">
                <div class="">
                    <button title="Save" ng-disabled="holdForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='add()'> Save </button>
                    <button type='button' id='reset_button' class=' mT26 btn btn-default' ng-click='resetForm()' title="Reset"> Reset </button>
                </div>
            </div>
        </div>
    </form>
</div>
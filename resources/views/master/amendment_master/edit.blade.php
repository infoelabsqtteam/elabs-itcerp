<div ng-model="editAmendmentFormDiv" id="editAmendmentDiv" ng-hide="editAmendmentFormDiv" >
   <div class="row header1">
      <strong class="pull-left headerText">Edit Amendment Master</strong>
   </div>
   <form name='editAmendmentForm' id="edit_amendment_form" novalidate>
      <label for="submit">{{ csrf_field() }}</label>	
      <div class="row">
         <div class="col-xs-3">
            <label for="state_name">Amendment Code<em class="asteriskRed">*</em> </label>
            <input readonly type="text" class="form-control" ng-model="editData.oam_code" name="oam_code" id="amendment_code" ng-required='true' placeholder="Amendment Code" />
            <span ng-messages="editAmendmentForm.oam_code.$error" ng-if='editAmendmentForm.oam_code.$dirty  || editAmendmentForm.$submitted' role="alert">
            <span ng-message="required" class="error">Amendment code is required</span>
            </span>
         </div>
         <div class="col-xs-3">
            <label for="amendment_name">Amendment Name<em class="asteriskRed">*</em>
            </label>
            <input type="text" class="form-control" ng-model="editData.oam_name" name="oam_name" id="oam_name" ng-required='true' placeholder="Amendment Name" />
            <span ng-messages="editAmendmentForm.oam_name.$error" ng-if='editAmendmentForm.oam_name.$dirty  || editAmendmentForm.$submitted' role="alert">
            <span ng-message="required" class="error">Amendment name is required</span>
            </span>
         </div>
         
         <div class="col-xs-3">
            <label for="country_id1">Status<em class="asteriskRed">*</em></label>
            <select class="form-control"
               name="oam_status"
               id="oam_status"
               ng-model="status.selectedOption"
               ng-options="status.name for status in statusList track by status.id"
               ng-required="true">
               <option value="">Select Status</option>
            </select>
            <span ng-messages="editAmendmentForm.oam_status.$error" 
               ng-if='editAmendmentForm.oam_status.$dirty  || editAmendmentForm.$submitted' role="alert">
            <span ng-message="required" class="error">Status is required</span>
            </span>
         </div>
         <div class="col-xs-3">
            <input type="hidden" name="oam_id" ng-model="oam_id" ng-value="oam_id">
            <button title="Update"  ng-disabled="editAmendmentForm.$invalid" type='submit' class='mT26 btn btn-primary  btn-sm' ng-click='updateAmendment()' > Update </button>
            <button title="Close"  type='button' class='mT26 btn btn-default  btn-sm' ng-click='showAddForm()' > Close </button>
         </div>
      </div>
   </form>
</div>
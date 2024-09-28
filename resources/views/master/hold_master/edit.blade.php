<div ng-model="editHoldFormDiv" id="editHoldDiv" ng-hide="editHoldFormDiv" >
   <div class="row header1">
      <strong class="pull-left headerText">Edit Hold Master</strong>
   </div>
   <form name='editHoldForm' id="edit_hold_form" novalidate>
      <label for="submit">{{ csrf_field() }}</label>	
      <div class="row">
         <div class="col-xs-3">
            <label for="hold_code">Hold Code<em class="asteriskRed">*</em> </label>
            <input readonly type="text" class="form-control" ng-model="editData.oh_code" name="oh_code" id="hold_code" ng-required='true' placeholder="Hold Code" />
            <span ng-messages="editHoldForm.oh_code.$error" ng-if='editHoldForm.oh_code.$dirty  || editHoldForm.$submitted' role="alert">
            <span ng-message="required" class="error">Hold code is required</span>
            </span>
         </div>
         <div class="col-xs-3">
            <label for="hold_name">Hold Name<em class="asteriskRed">*</em>
            </label>
            <input type="text" class="form-control" ng-model="editData.oh_name" name="oh_name" id="oh_name" ng-required='true' placeholder="Hold Name" />
            <span ng-messages="editHoldForm.oh_name.$error" ng-if='editHoldForm.oh_name.$dirty  || editHoldForm.$submitted' role="alert">
            <span ng-message="required" class="error">Hold name is required</span>
            </span>
         </div>
         
         <div class="col-xs-3">
            <label for="hold_status">Status<em class="asteriskRed">*</em></label>
            <select class="form-control"
               name="oh_status"
               id="oh_status"
               ng-model="status.selectedOption"
               ng-options="status.name for status in statusList track by status.id"
               ng-required="true">
               <option value="">Select Status</option>
            </select>
            <span ng-messages="editHoldForm.oam_status.$error" 
               ng-if='editHoldForm.oam_status.$dirty  || editHoldForm.$submitted' role="alert">
            <span ng-message="required" class="error">Status is required</span>
            </span>
         </div>
         <div class="col-xs-3">
            <input type="hidden" name="oh_id" ng-model="oh_id" ng-value="oh_id">
            <button title="Update"  ng-disabled="editHoldForm.$invalid" type='submit' class='mT26 btn btn-primary  btn-sm' ng-click='update()' > Update </button>
            <button title="Close"  type='button' class='mT26 btn btn-default  btn-sm' ng-click='showAddForm()' > Close </button>
         </div>
      </div>
   </form>
</div>
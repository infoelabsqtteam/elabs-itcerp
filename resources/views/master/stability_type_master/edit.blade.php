<div ng-model="editFormDiv" id="editDiv" ng-hide="editFormDiv" >
   <div class="row header1">
      <strong class="pull-left headerText">Edit Stability Type Master</strong>
   </div>
   <form name='editForm' id="edit_form" novalidate>
      <label for="submit">{{ csrf_field() }}</label>	
      <div class="row">
         
         <div class="col-xs-4">
            <label for="hold_name">Stability Type<em class="asteriskRed">*</em>
            </label>
            <input type="text" class="form-control" ng-model="editData.stb_stability_type_name" name="stb_stability_type_name" id="stb_stability_type_name" ng-required='true' placeholder="Hold Name" />
            <span ng-messages="editForm.stb_stability_type_name.$error" ng-if='editForm.stb_stability_type_name.$dirty  || editForm.$submitted' role="alert">
            <span ng-message="required" class="error">Stability type is required</span>
            </span>
         </div>
         
         <div class="col-xs-4">
            <label for="status">Status<em class="asteriskRed">*</em></label>
            <select class="form-control"
               name="stb_stability_type_status"
               id="stb_stability_type_status"
               ng-model="status.selectedOption"
               ng-options="status.name for status in statusList track by status.id"
               ng-required="true">
               <option value="">Select Status</option>
            </select>
            <span ng-messages="editForm.stb_stability_type_status.$error" 
               ng-if='editForm.stb_stability_type_status.$dirty  || editForm.$submitted' role="alert">
            <span ng-message="required" class="error">Status is required</span>
            </span>
         </div>
         <div class="col-xs-4">
            <input type="hidden" name="stb_stability_type_id" ng-model="stb_stability_type_id" ng-value="stb_stability_type_id">
            <button title="Update"  ng-disabled="editHoldForm.$invalid" type='submit' class='mT26 btn btn-primary  btn-sm' ng-click='update()' > Update </button>
            <button title="Close"  type='button' class='mT26 btn btn-default  btn-sm' ng-click='showAddForm()' > Close </button>
         </div>
      </div>
   </form>
</div>
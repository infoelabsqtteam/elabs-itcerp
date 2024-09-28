<div ng-model="editHeaderNoteFormDiv" id="editHeaderNoteDiv" ng-hide="editHeaderNoteFormDiv" >
   <div class="row header1">
      <strong class="pull-left headerText">Edit Header Note</strong>
   </div>
   <form name='editHeaderNoteForm' id="editHeaderNoteform" novalidate>
      <label for="submit">{{ csrf_field() }}</label>	
      <div class="row">
         <div class="col-xs-3">
            <label for="header_name">Header Name<em class="asteriskRed">*</em> </label>
            <input  type="text" class="form-control" ng-model="editData.header_name" name="header_name" id="header_name" ng-required='true' placeholder="Header Name" />
            <span ng-messages="editHeaderNoteForm.header_name.$error" ng-if='editHeaderNoteForm.header_name.$dirty  || editHeaderNoteForm.$submitted' role="alert">
            <span ng-message="required" class="error">Header Name is required</span>
            </span>
         </div>
         <div class="col-xs-3">
            <label for="header_limit">Header Limit </label>
            <input type="text" class="form-control" ng-model="editData.header_limit" name="header_limit" id="header_limit" placeholder="Header Limit" />
            
         </div>
         
         <div class="col-xs-3">
            <label for="header_status">Status<em class="asteriskRed">*</em></label>
            <select class="form-control"
               name="header_status"
               id="header_status"
               ng-model="status.selectedOption"
               ng-options="status.name for status in statusList track by status.id"
               ng-required="true">
               <option value="">Select Status</option>
            </select>
            <span ng-messages="editHeaderNoteForm.header_status.$error" 
               ng-if='editHeaderNoteForm.header_status.$dirty  || editHeaderNoteForm.$submitted' role="alert">
            <span ng-message="required" class="error">Status is required</span>
            </span>
         </div>
         <div class="col-xs-3">
            <input type="hidden" name="header_id" ng-model="header_id" ng-value="header_id">
            <button title="Update"  ng-disabled="editHeaderNoteForm.$invalid" type='submit' class='mT26 btn btn-primary  btn-sm' ng-click='update()' > Update </button>
            <button title="Close"  type='button' class='mT26 btn btn-default  btn-sm' ng-click='showAddForm()' > Close </button>
         </div>
      </div>
   </form>
</div>
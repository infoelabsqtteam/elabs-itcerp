<div ng-model="addHeaderNoteFormDiv" ng-hide="addHeaderNoteFormDiv">
    <div class="row header1">
        <strong class="pull-left headerText">Add Header Note</strong>
    </div>
    <form name='headerNoteForm' id="addHeaderNoteForm" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <div class="row">
				<div class="col-xs-3">
                <label for="header name">Header Name<em class="asteriskRed">*</em> </label>
						  <input  type="text" class="form-control" ng-model="headerNote.header_name" name="header_name" id="header_name" ng-required='true' placeholder="Header Name" />
                <span ng-messages="headerNoteForm.header_name.$error" ng-if='headerNoteForm.header_name.$dirty  || headerNoteForm.$submitted' role="alert">
						  <span ng-message="required" class="error"> Header Name is required</span>
                </span>
            </div>
            <div class="col-xs-3">
                <label for="header limit">Header Limit </label>
                <input type="text" class="form-control" ng-model="headerNote.header_limit" name="header_limit" id="header_limit"  placeholder="Header Limit" />
                
            </div>
           
				<div class="col-xs-3">
                <label for="header_status">Status<em class="asteriskRed">*</em></label>
					 <select class="form-control"
						 name="header_status"
						 id="header_status"
						 ng-model="headerNote.header_status.selectedOption"
						 ng-options="status.name for status in statusList track by status.id"
						 ng-required="true">
						 <option value="">Select Status</option>
						 
					 </select>
				
                <span ng-messages="headerNoteForm.oam_status.$error" ng-if='headerNoteForm.oam_status.$dirty  || headerNoteForm.$submitted' role="alert">
						  <span ng-message="required" class="error">Status is required</span>
                </span>
            </div>
					 
            <div class="col-xs-3">
                <div class="">
                    <button title="Save" ng-disabled="headerNoteForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='add()'> Save </button>
                    <button type='button' id='reset_button' class=' mT26 btn btn-default' ng-click='resetForm()' title="Reset"> Reset </button>
                </div>
            </div>
        </div>
    </form>
</div>
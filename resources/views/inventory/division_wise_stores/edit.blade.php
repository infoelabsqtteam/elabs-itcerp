<div class="row" ng-hide="editStoreFormDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Edit Store : <span ng-bind="editBranchWiseStore.store_name"></span></strong></span>
                </div>            
                <div role="new" class="navbar-form navbar-right">
                    <div style="margin: -4px; padding-right: 9px;">
                        <button ng-click="navigateStorePage();" class="btn btn-primary btn-sm">Back</button>
                    </div>
                </div>
            </div>               
            <form name="erpEditBranchWiseStoreForm" id="erpEditBranchWiseStoreForm" method="POST" novalidate>
                <div class="row">
                    <!--Store Code-->
                    <div class="col-xs-3 form-group">
                        <label for="store_code">Store Code<em class="asteriskRed">*</em></label>						   
                        <input type="text"
                            class="form-control" 
                            ng-model="editBranchWiseStore.store_code"
                            name="store_code" 
                            id="store_code"
                            ng-required='true'
                            placeholder="Store Code"
                            readonly />
                        <span ng-messages="erpEditBranchWiseStoreForm.store_code.$error" ng-if='erpEditBranchWiseStoreForm.store_code.$dirty  || erpEditBranchWiseStoreForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Store code is required</span>
                        </span>
                    </div>
                    <!--/Store Code-->
                    
                    <!--Store Name-->
                    <div class="col-xs-3 form-group">
                        <label for="store_name">Store Name<em class="asteriskRed">*</em></label>						   
                        <input type="text" class="form-control" 
                            ng-model="editBranchWiseStore.store_name"
                            name="store_name" 
                            id="store_name"
                            ng-required='true'
                            placeholder="Store Name" />
                        <span ng-messages="erpEditBranchWiseStoreForm.store_name.$error" ng-if='erpEditBranchWiseStoreForm.store_name.$dirty  || erpEditBranchWiseStoreForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Store name is required</span>
                        </span>
                    </div>
                    <!--/Store Name-->
                    
                    <!--Store Division -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">
                        <label for="division_id">Store Division<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="division_id"
                            id="division_id"
                            ng-model="editBranchWiseStore.division_id.selectedOption"
                            ng-options="division.name for division in divisionsCodeList track by division.division_id">
                            <option value="">Select Branche</option>
                        </select>
                        <span ng-messages="erpEditBranchWiseStoreForm.item_unit.$error" ng-if='erpEditBranchWiseStoreForm.item_unit.$dirty  || erpEditBranchWiseStoreForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Branche is required</span>
                        </span>
                    </div>
                    <div ng-if="{{$division_id}} > 0">
                        <input type="hidden" value="{{$division_id}}" name="division_id" ng-model="editBranchWiseStore.division_id" id="division_id">
                    </div>
                    <!--/Store Division -->
					                    
                    <!--Update Button-->
                    <div class="col-xs-2 form-group text-right mT20">
						<label for="submit">{{ csrf_field() }}</label>
                        <input type="hidden" ng-model="editBranchWiseStore.store_id" name="store_id" ng-value="editBranchWiseStore.store_id" id="store_id">
						<button type="submit" ng-disabled="erpEditBranchWiseStoreForm.$invalid" class="btn btn-primary" ng-click="funUpdateBranchWiseStore(divisionID)">Update</button>						
						<button type="button" class="btn btn-default" ng-click="backButton()">Back</button>
                    </div>
                    <!--Update Button-->
                        
                </div>                    
            </form>
        </div>
    </div>
</div>
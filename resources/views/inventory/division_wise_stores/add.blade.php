<div class="row" ng-hide="addStoreFormDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Add New Store</strong></span>
                </div>            
                <div role="new" class="navbar-form navbar-right"></div>
            </div>               
            <form name="erpAddBranchWiseStoreForm" id="erpAddBranchWiseStoreForm" method="POST" novalidate>
                <div class="row">
                    
                    <!--Store Code-->
                    <div class="col-xs-3 form-group">
						<span class="generate"><a href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>  
                        <label for="store_code">Store Code<em class="asteriskRed">*</em></label>						   
                        <input type="text"
                            class="form-control" readonly
							ng-model="store_code"
							ng-bind="store_code" 
                            name="store_code" 
                            id="store_code"
                            ng-required='true'
                            placeholder="Store Code" />
                        <span ng-messages="erpAddBranchWiseStoreForm.store_code.$error" ng-if='erpAddBranchWiseStoreForm.store_code.$dirty  || erpAddBranchWiseStoreForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Store code is required</span>
                        </span>
                    </div>
                    <!--/Store Code-->
                    
                    <!--Store Name-->
                    <div class="col-xs-3 form-group">
                        <label for="store_name">Store Name<em class="asteriskRed">*</em></label>						   
                        <input type="text" class="form-control" 
                            ng-model="addBranchWiseStore.store_name"
                            name="store_name" 
                            id="store_name"
                            ng-required='true'
                            placeholder="Store Name" />
                        <span ng-messages="erpAddBranchWiseStoreForm.store_name.$error" ng-if='erpAddBranchWiseStoreForm.store_name.$dirty  || erpAddBranchWiseStoreForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Store name is required</span>
                        </span>
                    </div>
                    <!--/Store Name-->
                    
                    <!--Store Branch -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">
                        <label for="division_id">Store Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="division_id"
                            id="division_id"
                            ng-model="addBranchWiseStore.division_id"
                            ng-options="division.name for division in divisionsCodeList track by division.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpAddBranchWiseStoreForm.division_id.$error" ng-if='erpAddBranchWiseStoreForm.division_id.$dirty  || erpAddBranchWiseStoreForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <div ng-if="{{$division_id}} > 0">
                        <input type="hidden" value="{{$division_id}}" name="division_id" ng-model="addBranchWiseStore.division_id" id="division_id">
                    </div>
                    <!--/Store Branch -->
					                    
                    <!--Save Button-->
                    <div class="col-xs-2 form-group text-right mT20">
						<label for="submit">{{ csrf_field() }}</label>
						<button type="submit" ng-disabled="erpAddBranchWiseStoreForm.$invalid" class="btn btn-primary" ng-click="funAddBranchWiseStore(divisionID)">Save</button>
						<button type="reset" class="btn btn-default">Reset</button>
                    </div>
                    <!--Save Button-->
                        
                </div>                    
            </form>
        </div>
    </div>
</div>
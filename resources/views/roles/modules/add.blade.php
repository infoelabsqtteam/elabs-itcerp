<div class="row" ng-hide="addFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Add New Module</strong></span>
                </div>            
                <div role="new" class="navbar-form navbar-right"></div>
            </div>               
            <form name="erpAddModuleForm" id="erpAddModuleForm" method="POST" novalidate>
                <div class="row">
                    
                    <!--Module Category-->
                    <div class="col-xs-3 form-group view-record">
                        <label for="parent_id">Module Category</label>						   
                        <select class="form-control"
                            name="parent_id"
                            id="parent_id"
                            ng-model="addModule.parent_id"
                            ng-options="moduleCategory.module_name for moduleCategory in moduleCategoryList track by moduleCategory.id">
                            <option value="">Select Module category</option>
                        </select>                        
                    </div>
                    <!--/Module Category-->
                    
                    <!--Module Name-->
                    <div class="col-xs-3 form-group">
                        <label for="module_name">Module Name<em class="asteriskRed">*</em></label>						   
                        <input type="text"
                            class="form-control" 
                            ng-model="addModule.module_name"
                            name="module_name" 
                            id="module_name"
                            ng-required='true'
                            placeholder="Module Name" />
                        <span ng-messages="erpAddModuleForm.module_name.$error" ng-if='erpAddModuleForm.module_name.$dirty  || erpAddModuleForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Module Name is required</span>
                        </span>
                    </div>
                    <!--/Module Name-->
					
					<!--Module Link-->
                    <div class="col-xs-3 form-group">
                        <label for="module_link">Module Link</label>						   
                        <input type="text"
                            class="form-control" 
                            ng-model="addModule.module_link"
                            name="module_link" 
                            id="module_link"                            
                            placeholder="Module Link" />
                    </div>
                    <!--/Module Link-->
                    					
					<!--Module Level-->
                    <div class="col-xs-3 form-group">
                        <label for="module_link">Module Level<em class="asteriskRed">*</em></label>
						<select class="form-control"
                            name="module_level"
                            id="module_level"
                            ng-model="addModule.module_level"
							ng-required='true'
                            ng-options="moduleLevels.module_level_name for moduleLevels in moduleLevelList track by moduleLevels.module_level">
                            <option value="">Select Module Level</option>
                        </select>
                        <span ng-messages="erpAddModuleForm.module_level.$error" ng-if='erpAddModuleForm.module_level.$dirty  || erpAddModuleForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Module Level is required</span>
                        </span>
                    </div>
                    <!--/Module Level-->
                    
                </div>
					
                <div class="row">
					
                    <!--Module Status-->
                    <div class="col-xs-3 form-group">
                        <label for="module_status">Module Status<em class="asteriskRed">*</em></label>
						<select class="form-control"
                            name="module_status"
                            id="module_status"
                            ng-model="addModule.module_status"
							ng-required='true'
                            ng-options="moduleStatus.module_status_name for moduleStatus in moduleStatusList track by moduleStatus.module_status">
                            <option value="">Select Module Status</option>
                        </select>
                        <span ng-messages="erpAddModuleForm.module_status.$error" ng-if='erpAddModuleForm.module_status.$dirty  || erpAddModuleForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Module Status is required</span>
                        </span>
                    </div>
                    <!--/Module Status-->
					                    
                    <!--Save Button-->
                    <div class="col-xs-3 form-group mT20">
						<label for="submit">{{ csrf_field() }}</label>
						<a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" ng-disabled="erpAddModuleForm.$invalid" class="btn btn-primary" ng-click="funAddModule(currentModuleID)">Save</a>
						<button type="reset" class="btn btn-default">Reset</button>
                    </div>
                    <!--Save Button-->
                        
                </div>                    
            </form>
        </div>
    </div>
</div>
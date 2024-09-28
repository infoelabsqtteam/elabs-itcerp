<div class="row" ng-hide="editFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Edit Module</strong></span>
                </div>            
                <div role="new" class="navbar-form navbar-right"></div>
            </div>               
            <form name="erpEditModuleForm" id="erpEditModuleForm" method="POST" novalidate>
                <div class="row">
                    
                    <!--Module Category-->
                    <div class="col-xs-3 form-group view-record">
                        <label for="parent_id">Module Category<em class="asteriskRed">*</em></label>						   
                        <select class="form-control"
                            name="parent_id"
                            id="parent_id"
                            ng-model="editModule.parent_id.selectedOption"
                            ng-options="moduleCategory.module_name for moduleCategory in moduleCategoryList track by moduleCategory.id">
                            <option value="">Select Module category</option>
                        </select>
                        <span ng-messages="erpEditModuleForm.parent_id.$error" ng-if="erpEditModuleForm.parent_id.$dirty || erpEditModuleForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Module Category is required</span>
                        </span>
                    </div>
                    <!--/Module Category-->
                    
                    <!--Module Name-->
                    <div class="col-xs-3 form-group">
                        <label for="module_name">Module Name<em class="asteriskRed">*</em></label>						   
                        <input type="text"
                            class="form-control" 
                            ng-model="editModule.module_name"
                            name="module_name" 
                            id="module_name"
                            ng-required='true'
                            placeholder="Module Name" />
                        <span ng-messages="erpEditModuleForm.module_name.$error" ng-if='erpEditModuleForm.module_name.$dirty  || erpEditModuleForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Module Name is required</span>
                        </span>
                    </div>
                    <!--/Module Name-->		
                    
                    <!--Module Link-->
                    <div class="col-xs-3 form-group">
                        <label for="module_link">Module Link</label>						   
                        <input type="text"
                            class="form-control" 
                            ng-model="editModule.module_link"
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
                            ng-model="editModule.module_level.selectedOption"
							ng-required='true'
                            ng-options="moduleLevels.module_level_name for moduleLevels in moduleLevelList track by moduleLevels.module_level">
                            <option value="">Select Module Level</option>
                        </select>
                        <span ng-messages="erpEditModuleForm.module_level.$error" ng-if="erpEditModuleForm.module_level.$dirty || erpEditModuleForm.$submitted" role="alert">
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
                            ng-model="editModule.module_status.selectedOption"
							ng-required='true'
                            ng-options="moduleStatus.module_status_name for moduleStatus in moduleStatusList track by moduleStatus.module_status">
                            <option value="">Select Module Status</option>
                        </select>
                        <span ng-messages="erpEditModuleForm.module_status.$error" ng-if="erpEditModuleForm.module_status.$dirty || erpEditModuleForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Module Status is required</span>
                        </span>
                    </div>
                    <!--/Module Status-->
					                    
                    <!--Save Button-->
                    <div class="col-xs-3 form-group mT20">
						<label for="submit">{{ csrf_field() }}</label>
                        <input type="hidden" ng-model="editModule.id" name="id" ng-value="editModule.id" id="id">
						<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" ng-disabled="erpEditModuleForm.$invalid" class="btn btn-primary" ng-click="funUpdateModule(currentModuleID)">Update</a>
						<button type="button" class="btn btn-default" ng-click="backButton()">Back</button>
                    </div>
                    <!--Save Button-->
                        
                </div>                    
            </form>
        </div>
    </div>
</div>
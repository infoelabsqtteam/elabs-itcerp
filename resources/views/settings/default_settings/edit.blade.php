<div class="row" ng-if="isVisibleEditSettingDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Edit Sample : <span>[[editForm.setting_key]]</span></strong></span>
                </div>            
                <div role="new" class="navbar-form navbar-right"></div>
            </div>               
            <form name="erpEditSettingForm" id="erpEditSettingForm" method="POST" novalidate>
                <div class="row">
                    
                       <!--setting group -->
                    <div class="col-xs-3 form-group">
                        <label for="setting_group_id">Setting Groups<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="setting_group_id"
                            id="setting_group_id"
                            ng-model="editForm.setting_group_id.selectedOption"
                            ng-options="settings.setting_group_name for settings in settingGroupList track by settings.setting_group_id">
                            <option value="">Select Setting Groups</option>
                        </select>
                    </div>
                    <!--/setting group -->
                    <!-- Setting Key-->
                    <div class="col-xs-3 form-group">
                        <label for="Setting Key">Setting Key<em class="asteriskRed">*</em></label>
                        <input
                            type="text"
                            class="form-control"
                            name="setting_key"
                            id="setting_key"
                            ng-required="true"
                            ng-model="editForm.setting_key"
                            placeholder="Setting Key">
                    </div>
                    <!-- /Setting Key-->
                    <!-- Setting Value-->   
                    <div class="col-xs-3 form-group">
                        <label for="customer_id">Setting Value<em class="asteriskRed">*</em></label>
                        <input
                            type="text"
                            class="form-control"
                            name="setting_value"
                            id="setting_value"
                            ng-required="true"
                            ng-model="editForm.setting_value"
                            placeholder="Setting Value">
                    </div>  
                    <!--Update Button-->
                    <div class="col-xs-2 form-group text-right mT20">
						<label for="submit">{{ csrf_field() }}</label>
                        <input type="hidden" name = "setting_id" value="[[setting_id]]">
						<button type="submit" class="btn btn-primary" ng-click="funUpdateDefaultSetting()">Update</button>	
                        <button type="button" class="btn btn-default" ng-click="backButton()">Back</button>
                    </div>
                    <!--Update Button-->
                        
                </div>                    
            </form>
        </div>
    </div>
</div>
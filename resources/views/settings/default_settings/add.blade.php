
<div class="row" ng-if = "isVisibleAddSettingDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Add New Setting</strong></span>
                </div>            
                <div role="new" class="navbar-form navbar-right"></div>
            </div>               
            <form name="erpAddSettingForm" id="erpAddSettingForm" method="POST" novalidate>
                <div class="row">
                    
                    <!--setting group -->
                    <div class="col-xs-3 form-group">
                        <label for="setting_group_id">Setting Groups<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="setting_group_id"
                            id="setting_group_id"
                            ng-model="addSettings.setting_group_id"
                            ng-options="settings.setting_group_name for settings in settingGroupList track by settings.setting_group_id">
                            <option value="">Select Setting Groups</option>
                        </select>
                    </div>
                    <!--/setting group -->
					
					<!-- Setting Key-->
					<div class="col-xs-3 form-group">
						<label for="customer_id">Setting Key<em class="asteriskRed">*</em></label>
						<input
							type="text"
							class="form-control"
							name="setting_key"
                            id="setting_key"
							ng-required="true"
                            ng-model="addSettings.setting_key"
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
                            ng-model="addSettings.setting_value"
							placeholder="Setting Value">
					</div>	
                    <!--Save Button-->
                    <div class="col-xs-3 form-group text-left mT26">
						<label for="submit">{{ csrf_field() }}</label>
						<button type="submit"  class="btn btn-primary" ng-disabled="erpAddSettingForm.$invalid" ng-click="funAddDefaultSettings()">Save</button>
						<button type="button" class="btn btn-default" ng-click="resetButton()">Reset</button>
                    </div>
                    <!--Save Button "-->
                </div>                    
            </form>
        </div>
    </div>
</div>
<div class="row header" ng-if ="isVisibleListSettingDiv">
    <div role="new" class="navbar-form navbar-left">            
        <span class="pull-left"><strong id="form_title" ng-click="funGetBranchWiseSamples(divisionID)">Settings<span ng-if="settingList.length > 0">( [[settingList.length]] )</span></strong></span>
    </div>   
    <div role="new" class="navbar-form navbar-right">
        <div style="margin: -5px; padding-right: 9px;">
            <input type="text" placeholder="Search" ng-model="searchSetting" class="form-control ng-pristine ng-untouched ng-valid">
           
            <select class="form-control"
                name="setting_group_id"
                id="setting_group_id"
                ng-model="addSettings.setting_group"
                ng-options="settings.setting_group_name for settings in settingGroupList track by settings.setting_group_id" ng-change="funGetGroupWiseSetting(addSettings.setting_group.setting_group_id)">
                <option value="">All Setting Groups</option>
            </select>
        </div>
    </div>

    <div id="no-more-tables">
        <table class="col-sm-12 table-striped table-condensed cf">
            <thead class="cf">
                <tr>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('setting_group_name')">Setting Group</label>
                        <span class="sortorder" ng-show="predicate === 'setting_group_name'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th ng-if="{{$division_id}} == 0">
                        <label class="sortlabel" ng-click="sortBy('setting_key')">Setting Key</label>
                        <span class="sortorder" ng-show="predicate === 'setting_key'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('setting_value')">Setting Value</label>
                        <span class="sortorder" ng-show="predicate === 'setting_value'" ng-class="{reverse:reverse}"></span>						
                    </th>                                            
                    <th>
                        <label class="sortlabel" ng-click="sortBy('setting_group_status')">Status</label>
                        <span class="sortorder" ng-show="predicate === 'setting_group_status'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th class="width10">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="settingListObj in settingList| filter:searchSetting | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                    <td data-title="Setting Group">[[settingListObj.setting_group_name | capitalize]] </td>
                    <td data-title="Setting Key">[[settingListObj.setting_key | capitalizeAll ]]</td>                    
                    <td data-title="Setting Value">[[settingListObj.setting_value ]]</td>                    
                    <td data-title="Setting Status">
						<span class="po-open" ng-if="settingListObj.setting_group_status == 0">Deactive</span>
						<span class="po-closed" ng-if="settingListObj.setting_group_status == 1">Active</span>
					</td>
                    <td class="width10">
                        <a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record" class="btn btn-info btn-sm" ng-click="funEditDefaultSettings(settingListObj.setting_id, settingListObj.setting_group_id)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete Record" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(settingListObj.setting_id)"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                </tr>

                <tr ng-if="!settingList.length" class="noRecord"><td colspan="10">No Record Found!</td></tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="10">
                        <div class="box-footer clearfix">
                            <dir-pagination-controls></dir-pagination-controls>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>	  
    </div>
</div>
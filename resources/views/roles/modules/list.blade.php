<div class="row header" ng-hide="listFormBladeDiv">
    <div role="new" class="navbar-form navbar-left">            
        <span class="pull-left"><strong id="form_title">Module Listing<span ng-if="[[moduleDataList.length]] > 0">([[moduleDataList.length]])</span></strong></span>
    </div>            
    <div role="new" class="navbar-form navbar-right">
        <div style="margin: -5px; padding-right: 9px;">
            <input type="text" placeholder="Search" ng-model="filterModules" class="form-control ng-pristine ng-untouched ng-valid">
            <select class="form-control" ng-model="moduleCategories.moduleId" ng-options="moduleCategory.module_name for moduleCategory in moduleCategoryList track by moduleCategory.id" ng-change="funGetModuleList(moduleCategories.moduleId.id)">
                <option value="">All Modules</option>
            </select>
        </div>
    </div>

    <div id="no-more-tables">
        <table class="col-sm-12 table-striped table-condensed cf">
            <thead class="cf">
                <tr>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('module_name')">Module Name</label>
                        <span class="sortorder" ng-show="predicate === 'module_name'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('module_link')">Module Link</label>
                        <span class="sortorder" ng-show="predicate === 'module_link'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('module_level')">Module Level</label>
                        <span class="sortorder" ng-show="predicate === 'module_level'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('module_status')">Module Status</label>
                        <span class="sortorder" ng-show="predicate === 'module_status'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('createdByName')">Created By</label>
                        <span class="sortorder" ng-show="predicate === 'createdByName'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('created_at')">Added On</label>
                        <span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('updated_at')">Modified On</label>
                        <span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th class="width10">Action
						<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
					</th>
                </tr>
            </thead>
            <tbody>
				<tr ng-hide="multiSearchTr">
					<td><input type="text" ng-change="getMultiSearch()" name="search_module_name" ng-model="searchModules.search_module_name" class="multiSearch form-control width80" placeholder="Module Name"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_module_link" ng-model="searchModules.search_module_link" class="multiSearch form-control width80" placeholder="Module Link"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_module_level" ng-model="searchModules.search_module_level" class="multiSearch form-control width80" placeholder="Module Level"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_status"  ng-model="searchModules.search_status" class="multiSearch form-control width80" placeholder="Module Status"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" ng-model="searchModules.search_created_by" class="multiSearch form-control width80" placeholder="Created By"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_at" ng-model="searchModules.search_created_at" class="multiSearch form-control width80 visibility" placeholder="Created On"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" ng-model="searchModules.search_updated_at" class="multiSearch form-control width80 visibility" placeholder="Updated On"></td>
					<td class="width10">
						<button ng-click="refreshMultisearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<button ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
				</tr>
                <tr dir-paginate="moduleDataObj in moduleDataList | filter : filterModules | itemsPerPage : {{ defined('PERPAGE') ? PERPAGE : 10 }} | orderBy:predicate:reverse" >
                    <td data-title="module_name">[[moduleDataObj.module_name]]</td>
                    <td data-title="module_link">[[moduleDataObj.module_link ? moduleDataObj.module_link : '-']]</td>					
                    <td data-title="module_level">[[moduleDataObj.module_level]]</td>
                    <td data-title="module_status">
                        <span ng-if="moduleDataObj.module_status == 0" class="po-open">Inactive</span>
						<span ng-if="moduleDataObj.module_status == 1" class="po-closed">Active</span>
                    </td>
                    <td data-title="Created By">[[moduleDataObj.createdByName]]</td>
                    <td data-title="Added On">[[moduleDataObj.created_at]]</td>
                    <td data-title="Modified On">[[moduleDataObj.updated_at]]</td>
                    <td class="width10">                        
                        <a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record" class="btn btn-info btn-sm" ng-click="funEditModule(moduleDataObj.id)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="javascript:;" ng-if="{{defined('DELETE') && DELETE }} && moduleDataObj.module_level != 0" title="Delete Record" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(moduleDataObj.id,currentModuleID);"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                </tr>					
                <tr ng-if="!moduleDataList.length" class="noRecord"><td colspan="8">No Record Found!</td></tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="box-footer clearfix">
                            <dir-pagination-controls></dir-pagination-controls>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>	  
    </div>
</div>
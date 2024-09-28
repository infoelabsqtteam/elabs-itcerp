<div class="row">

    <div class="header">
        <strong class="pull-left headerText" ng-click="funListMaster()" title="Refresh">Instance Listing <span ng-if="masterDataList.length">([[masterDataList.length]])</span></strong>
        <div class="navbar-form navbar-right" role="search">
            <div class="nav-custom">
                <input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="filterMaster">
            </div>
        </div>
    </div>

    <div id="no-more-tables">
        <form method="POST" role="form" id="erpListMasterForm" name="erpListMasterForm" novalidate>
            <table class="col-sm-12 table-striped table-condensed cf">
                <thead class="cf">
                    <tr>
                        <th class="width10">
                            <label class="sortlabel" ng-click="sortBy('instance_code')">Instance Code </label>
                            <span class="sortorder" ng-show="predicate === 'instance_code'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width10">
                            <label class="sortlabel" ng-click="sortBy('instance_name')">Instance Name </label>
                            <span class="sortorder" ng-show="predicate === 'instance_name'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th>
                            <label class="sortlabel" ng-click="sortBy('instance_desc')">Instance Description </label>
                            <span class="sortorder" ng-show="predicate === 'instance_desc'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width10">
                            <label class="sortlabel" ng-click="sortBy('equipment_name')">Equipment Type </label>
                            <span class="sortorder" ng-show="predicate === 'equipment_name'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th>
                            <label class="sortlabel" ng-click="sortBy('p_category_name')">Parent Category</label>
                            <span class="sortorder" ng-show="predicate === 'p_category_name'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width10">
                            <label class="sortlabel" ng-click="sortBy('created_by')">Created By </label>
                            <span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width8">
                            <label class="sortlabel" ng-click="sortBy('created_at')">Created On </label>
                            <span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width8">
                            <label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
                            <span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width10">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr dir-paginate="masterObj in masterDataList| filter : filterMaster| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                        <td data-title="Instance Code">[[masterObj.instance_code]]</td>
                        <td data-title="Instance Name ">[[masterObj.instance_name]]</td>
                        <td data-title="Instance Description">
                            <span id="instancelimitedText-[[masterObj.instance_id]]">
                                [[ masterObj.instance_desc | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
                                <a href="javascript:;" ng-click="toggleDescription('instance',masterObj.instance_id)" ng-show="masterObj.instance_desc.length > 150" class="readMore">read more...</a>
                            </span>
                            <span id="instancefullText-[[masterObj.instance_id]]" style="display:none;">
                                [[ masterObj.instance_desc]]
                                <a href="javascript:;" ng-click="toggleDescription('instance',masterObj.instance_id)" class="readMore">read less...</a>
                            </span>
                        </td>
                        <td data-title="Equipment Type">[[masterObj.equipment_name]]</td>
                        <td data-title="Parent Category Name ">[[masterObj.p_category_name]]</td>
                        <td data-title="Created By">[[masterObj.createdBy]]</td>
                        <td data-title="Created On">[[masterObj.created_at]]</td>
                        <td data-title="Updated On">[[masterObj.updated_at]]</td>
                        <td class="width10" ng-if="{{ (defined('EDIT') && EDIT) || (defined('DELETE') && DELETE) }}">
                            <a ng-if="{{ defined('EDIT') && EDIT }}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='funEditMaster(masterObj.instance_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a ng-if="{{ defined('DELETE') && DELETE }}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(masterObj.instance_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                    <tr ng-hide="masterDataList.length" class="noRecord">
                        <td colspan="9">No Record Found!</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr ng-hide="masterDataList.length" class="noRecord">
                        <td colspan="9">
                            <div class="box-footer clearfix">
                                <dir-pagination-controls></dir-pagination-controls>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>
</div>

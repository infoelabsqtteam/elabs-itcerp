<div class="row" ng-hide="listMasterFormBladeDiv">
    <form method="POST" role="form" id="erpMasterListingForm" name="erpMasterListingForm" novalidate>

        <div class="header">
            <strong class="pull-left headerText" ng-click="funListMaster();" title="Refresh">Analyst Window Settings <span ng-if="masterDataList.length">([[masterDataList.length]])</span></strong>
            <div class="navbar-form navbar-right" role="search">
                <div class="nav-custom">
                    <input type="text" class="form-control" name="searchKeyword" placeholder="Search" ng-model="filterListModel">
                    <a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" title="Add Defined Test Standard" class="btn btn-primary" ng-click="navigatePage()">Add New</a>
                </div>
            </div>
        </div>

        <div id="no-more-tables">
            <table class="col-sm-12 table table-striped table-condensed cf">
                <thead class="cf">
                    <tr>
                        <th class="width10">
                            <label class="sortlabel" ng-click="sortBy('oaws_division_name')">Division Name</label>
                            <span class="sortorder" ng-show="predicate === 'oaws_division_name'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width10">
                            <label class="sortlabel" ng-click="sortBy('oaws_product_category_name')">Department Name</label>
                            <span class="sortorder" ng-show="predicate === 'oaws_product_category_name'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width10">
                            <label class="sortlabel" ng-click="sortBy('oaws_equipment_type_name ')"> Equipment Name</label>
                            <span class="sortorder" ng-show="predicate === 'oaws_equipment_type_name '" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width10">
                            <label class="sortlabel" ng-click="sortBy('oaws_created_name')">Created By</label>
                            <span class="sortorder" ng-show="predicate === 'oaws_created_name'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th class="width8">
                            <label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
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
                    <tr dir-paginate="masterDataobj in masterDataList| filter:filterListModel | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                        <td data-title="Division Name">[[masterDataobj.oaws_division_name]]</td>
                        <td data-title="Department Name">[[masterDataobj.oaws_product_category_name]]</td>
                        <td data-title="Equipment Name">
                            <span ng-if="masterDataobj.equipment_list_saved">
                                <a href="javascript:;" title="[[equipmentAnchorTitleList]]" ng-mouseover="funArrayToString(masterDataobj.equipment_list_saved,masterDataobj.equipment_list_saved.oaws_equipment_type_name)" class="text-center" data-toggle="modal" data-target="#myModalEquipment_[[masterDataobj.oaws_id]]"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <div class="modal fade" id="myModalEquipment_[[masterDataobj.oaws_id]]" role="dialog">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header text-left">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><span class="poptitle"> Equipments </span></h4>
                                            </div>
                                            <div class="modal-body">
                                                <ul>
                                                    <li id="[[masterDataobj.oaws_id]]" ng-repeat="equipmentObj in masterDataobj.equipment_list_saved">[[equipmentObj]]</li>
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                            <span ng-if="!masterDataobj.equipment_list_saved">-</span>
                        </td>
                        <td data-title="Created By">[[masterDataobj.oaws_created_name]]</td>
                        <td data-title="Created On">[[masterDataobj.created_at]]</td>
                        <td data-title="Updated On">[[masterDataobj.updated_at]]</td>
                        <td class="width10">
                            <a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='funEditMaster(masterDataobj.oaws_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(masterDataobj.oaws_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                    <tr ng-if="!masterDataList.length" class="noRecord">
                        <td colspan="8">No Record Found!</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr ng-if="masterDataList.length">
                        <td colspan="[[masterDataList.length]]">
                            <div class="box-footer clearfix">
                                <dir-pagination-controls></dir-pagination-controls>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </form>
</div>
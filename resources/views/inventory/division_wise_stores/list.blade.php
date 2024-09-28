<div class="row header" ng-hide="listStoreFormDiv">

    <div role="new" class="navbar-form navbar-left">            
        <span class="pull-left"><strong id="form_title">Branch Wise Store Listing<span ng-if="[[storeDataList.length]] > 0">([[storeDataList.length]])</span></strong></span>
    </div>            
    <div role="new" class="navbar-form navbar-right">
        <div style="margin: -5px; padding-right: 9px;">
            <input type="text" placeholder="Search" ng-model="searchStores" class="form-control ng-pristine ng-untouched ng-valid">
            <select ng-if="{{$division_id}} == 0" class="form-control" ng-model="division" ng-options="division.name for division in divisionsCodeList track by division.division_id" ng-change="funGetBranchWiseStores(division.division_id)">
                <option value="">All Branch</option>
            </select>
        </div>
    </div>

    <div id="no-more-tables">
        <table class="col-sm-12 table-striped table-condensed cf">
            <thead class="cf">
                <tr>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('store_code')">Store Code</label>
                        <span class="sortorder" ng-show="predicate === 'store_code'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('store_name')">Store Name</label>
                        <span class="sortorder" ng-show="predicate === 'store_name'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th ng-if="{{$division_id}} == 0">
                        <label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
                        <span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('created_by')">Created By</label>
                        <span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('created_at')">Added On</label>
                        <span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('updated_at')">Modified On</label>
                        <span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th class="width10">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="storeDataListObj in storeDataList| filter:searchStores | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE-5 : 10 }} | orderBy:predicate:reverse" >
                    <td data-title="Store Code">[[storeDataListObj.store_code]]</td>						
                    <td data-title="Store Name ">[[storeDataListObj.store_name]]</td>
                    <td ng-if="{{$division_id}} == 0" data-title="Division Name ">[[storeDataListObj.division_name]]</td>
                    <td data-title="Created By">[[storeDataListObj.createdBy]]</td>
                    <td data-title="Added On">[[storeDataListObj.created_at]]</td>
                    <td data-title="Added On">[[storeDataListObj.updated_at]]</td>
                    <td class="width10">
                        <a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record" class="btn btn-info btn-sm" ng-click="funEditStore(storeDataListObj.store_id, divisionID)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete Record" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(storeDataListObj.store_id, divisionID)"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                </tr>					
                <tr ng-if="!storeDataList.length" class="noRecord"><td colspan="6">No Record Found!</td></tr>
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
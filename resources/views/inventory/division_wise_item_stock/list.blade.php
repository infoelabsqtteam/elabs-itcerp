<div class="row header" ng-hide="listItemStockFormDiv">

    <div role="new" class="navbar-form navbar-left">            
        <span class="pull-left"><strong id="form_title">Branch Wise Stock Listing<span ng-if="[[itemStockDataList.length]] > 0">([[itemStockDataList.length]])</span></strong></span>
    </div>            
    <div role="new" class="navbar-form navbar-right">
        <div style="margin: -5px; padding-right: 9px;">
            <input type="text" placeholder="Search" ng-model="searchItemStock" class="form-control ng-pristine ng-untouched ng-valid">
            <select ng-if="{{$division_id}} == 0" ng-init="funGetDivisions()" class="form-control" ng-model="division" ng-options="division.name for division in divisionsCodeList track by division.division_id" ng-change="funGetBranchWiseItemStocks(division.division_id)">
                <option value="">All Branch</option>
            </select>
        </div>
    </div>

    <div id="no-more-tables">
        <table class="col-sm-12 table-striped table-condensed cf">
            <thead class="cf">
                <tr>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('division_wise_item_stock_id')">Stock Id</label>
                        <span class="sortorder" ng-show="predicate === 'division_wise_item_stock_id'" ng-class="{reverse:reverse}"></span>
                    </th>
                    <th ng-if="{{$division_id}} == 0">
                        <label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
                        <span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('store_name')">Store Name</label>
                        <span class="sortorder" ng-show="predicate === 'store_name'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('item_name')">Item Name</label>
                        <span class="sortorder" ng-show="predicate === 'item_name'" ng-class="{reverse:reverse}"></span>						
                    </th>						
                    <th>
                        <label class="sortlabel" ng-click="sortBy('openning_balance')">Openning Balance</label>
                        <span class="sortorder" ng-show="predicate === 'openning_balance'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('openning_balance_date')">Openning Balance Date</label>
                        <span class="sortorder" ng-show="predicate === 'openning_balance_date'" ng-class="{reverse:reverse}"></span>						
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
                <tr dir-paginate="itemStockDataObj in itemStockDataList| filter:searchItemStock | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE-5 : 10 }} | orderBy:predicate:reverse">
                    <td data-title="Store Code">[[itemStockDataObj.division_wise_item_stock_id]]</td>
                    <td ng-if="{{$division_id}} == 0" data-title="Division Name ">[[itemStockDataObj.division_name]]</td>
                    <td data-title="Store Code">[[itemStockDataObj.store_name]]</td>						
                    <td data-title="Store Name ">[[itemStockDataObj.item_name]]</td>						
                    <td data-title="Openning Balance ">[[itemStockDataObj.openning_balance]]</td>
                    <td data-title="Openning Balance Date">[[itemStockDataObj.openning_balance_date | date : 'dd/MM/yyyy']]</td>
                    <td data-title="Created By">[[itemStockDataObj.createdBy]]</td>
                    <td data-title="Added On">[[itemStockDataObj.created_at]]</td>
                    <td data-title="Added On">[[itemStockDataObj.updated_at]]</td>
                    <td class="width10">
                        <a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update" class="btn btn-info btn-sm" ng-click="funEditItemStock(itemStockDataObj.division_wise_item_stock_id, divisionID)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(itemStockDataObj.division_wise_item_stock_id, divisionID)"><i class="fa fa-trash-o" aria-hidden="true"></i></a>							
                    </td>
                </tr>					
                <tr ng-if="!itemStockDataList.length" class="noRecord"><td colspan="9">No Record Found!</td></tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9">
                        <div class="box-footer clearfix">
                            <dir-pagination-controls></dir-pagination-controls>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>		  
    </div>
</div>
<div id="no-more-tables" ng-hide="listBranchItemFormDivAll">
    <form name="erpAddBranchWiseAllItemForm" id="erpAddBranchWiseAllItemForm" method="POST" novalidate>
        <table class="col-sm-12 table-striped table-condensed cf">
            <thead class="cf">
                <tr>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('item_code')">Item Code</label>
                        <span class="sortorder" ng-show="predicate === 'item_code'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('item_name')">Item Name</label>
                        <span class="sortorder" ng-show="predicate === 'item_name'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
                        <span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('created_at')">Added On</label>
                        <span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('updated_at')">Modified On</label>
                        <span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('msl')">MSL  </label>
                        <span class="sortorder" ng-show="predicate === 'msl'" ng-class="{reverse:reverse}"></span>						
                    </th>							
                    <th>
                        <label class="sortlabel" ng-click="sortBy('rol')">ROL</label>
                        <span class="sortorder" ng-show="predicate === 'rol'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th colspan="2">
                        <button title="Update All" class="btn btn-primary btn-sm" ng-click="navigateItemPage(divisionID)">Back</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="itemDataListAllObj in itemDataDivisionList | filter:searchBranchItems | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                    <td data-title="Item Code">[[itemDataListAllObj.item_code]]</td>						
                    <td data-title="Item Name ">[[itemDataListAllObj.item_name]]</td>
                    <td data-title="Item Name ">[[itemDataListAllObj.division_name]]</td>
                    <td data-title="Added On">[[itemDataListAllObj.created_at]]</td>
                    <td data-title="Updated">[[itemDataListAllObj.updated_at]]</td>
                    <td data-title="MSL">
                        <input type="hidden" ng-model="addEditBranchWiseAllItem.division_wise_item_id" ng-value="itemDataListAllObj.division_wise_item_id" name="division_wise_item_id[]">
                        <input type="text" class="form-control" ng-model="addEditBranchWiseAllItem.msl" ng-value="itemDataListAllObj.msl" name="msl[]" placeholder="MSL">
                    </td>					
                    <td data-title="ROL">
                        <input type="text" class="form-control" ng-model="addEditBranchWiseAllItem.rol" ng-value="itemDataListAllObj.rol" name="rol[]" placeholder="ROL">                        
                    </td>
                </tr>                
                <tr ng-hide="itemDataDivisionList.length" class="noRecord"><td colspan="7">No Record Found!</td></tr>
            </tbody>
            <tfoot>
                <tr>
                    <td ng-if="listBranchItemFormDivAllPaginate" colspan="2">
                        <div class="box-footer clearfix">
                            <dir-pagination-controls></dir-pagination-controls>
                        </div>
                    </td>
                    <td colspan="5">
                        <div class="box-footer clearfix pull-right">
                            <label for="submit">{{ csrf_field() }}</label>
                            <button type="submit" class="btn btn-primary" ng-click="funSaveAllBranchItem(divisionID)">Save All</button>
                            <button title="Update All" class="btn btn-primary btn-sm" ng-click="navigateItemPage(divisionID)">Back</button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
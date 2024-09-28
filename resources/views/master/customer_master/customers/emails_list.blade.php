<div class="row header" ng-if ="isVisibleEmailListDiv">
    <div role="new" class="navbar-form navbar-left">            
        <span class="pull-left"><strong id="form_title" ng-click="fungetCustomerEmailAddresses(customerID)">Customer Emails<span ng-if="customerEmailLists.length > 0">( [[customerEmailLists.length]] )</span></strong></span>
    </div>   
    <div role="new" class="navbar-form navbar-right">
        <div style="margin: -5px; padding-right: 9px;">
            <input type="text" placeholder="Search" ng-model="search" class="form-control ng-pristine ng-untouched ng-valid">
        </div>
    </div>

    <div id="no-more-tables">
        <table class="col-sm-12 table-striped table-condensed cf">
            <thead class="cf">
                <tr>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('customer_email')">Customer Email</label>
                        <span class="sortorder" ng-show="predicate === 'customer_email'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th ng-if="{{$division_id}} == 0">
                        <label class="sortlabel" ng-click="sortBy('customer_email_type')">Email Type</label>
                        <span class="sortorder" ng-show="predicate === 'customer_email_type'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('customer_email_status')">Email Status</label>
                        <span class="sortorder" ng-show="predicate === 'customer_email_status'" ng-class="{reverse:reverse}"></span>						
                    </th>                                            
                    <th>
                        <label class="sortlabel" ng-click="sortBy('setting_group_status')">Action</label>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="emailListObj in customerEmailLists| filter:search | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                    <td data-title="customer_email">[[emailListObj.customer_email]] </td>
                    <td data-title="customer_email_type">
					<span class="po-closed" ng-if="emailListObj.customer_email_type == 'P'">Primary</span>
						<span class="po-open" ng-if="emailListObj.customer_email_type == 'S'">Secondary</span></td>                    
                    <td data-title="customer_email_status">
						<span class="po-closed" ng-if="emailListObj.customer_email_status == 1">Active</span>
						<span class="po-open" ng-if="emailListObj.customer_email_status == 2">Inactive</span>
					</td>
                    <td class="width10">
                        <a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record" class="btn btn-info btn-sm" ng-click="funShowEditCustomerEmailAddresses(emailListObj.customer_id,emailListObj.customer_email_id)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete Record" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(emailListObj.customer_email_id,'deleteCustomerEmailRecord')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                </tr>

                <tr ng-if="!customerEmailLists.length" class="noRecord"><td colspan="10">No Record Found!</td></tr>
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
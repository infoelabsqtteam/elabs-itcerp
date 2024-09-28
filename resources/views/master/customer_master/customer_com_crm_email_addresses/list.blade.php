<div class="row" ng-hide="listCustomerComCrmFormBladeDiv">

    <!--search-->
    <div class="row header">
        <div role="new" class="navbar-form navbar-left">
            <div>
                <strong id="form_title"><span ng-click="funGetBranchWiseCustomerComCrms()">Customer CRM Listing<span ng-if="customerComCrmsList.length"></span>([[customerComCrmsList.length]])</strong>
            </div>
        </div>
        <div role="new" class="navbar-form navbar-right">
            <div class="nav-custom custom-display">
                <input type="text" class="form-control" placeholder="Search" ng-model="searchCustomerComCrm"">
                </div>
            </div>
        </div>
        <!--/search-->
	</div>

	<!--display record-->
	<div class=" row" id="no-more-tables">
			<table class="col-sm-12 table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('cccea_division_name')">Branch</label>
							<span class="sortorder" ng-show="predicate === 'cccea_division_name'"
								ng-class="{reverse:reverse}"></span>
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('cccea_product_category_name')">Department</label>
							<span class="sortorder" ng-show="predicate === 'cccea_product_category_name'"
								ng-class="{reverse:reverse}"></span>
						</th>
						<th>
							<label ng-click="sortBy('cccea_name')" class="sortlabel">CRM Name</label>
							<span ng-class="{reverse:reverse}" ng-show="predicate === 'cccea_name'"
								class="sortorder reverse ng-hide"></span>
						</th>
						<th>
							<label ng-click="sortBy('cccea_email')" class="sortlabel">CRM Email</label>
							<span ng-class="{reverse:reverse}" ng-show="predicate === 'cccea_email'"
								class="sortorder reverse ng-hide"></span>
						</th>
						<th>
							<label ng-click="sortBy('cccea_status')" class="sortlabel">CRM Status</label>
							<span ng-class="{reverse:reverse}" ng-show="predicate === 'cccea_status'"
								class="sortorder reverse ng-hide"></span>
						</th>
						<th class="width8">
							<label ng-click="sortBy('cccea_created_by_name')" class="sortlabel">Created By</label>
							<span ng-class="{reverse:reverse}" ng-show="predicate === 'cccea_created_by_name'"
								class="sortorder reverse ng-hide"></span>
						</th>
						<th class="width10">Action</th>
					</tr>
				</thead>
				<tbody>
					<tr
						dir-paginate="customerComCrmsObj in customerComCrmsList | filter:searchCustomerComCrm | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
						<td data-title="Division Name">[[customerComCrmsObj.cccea_division_name]]</td>
						<td data-title="Department Name">[[customerComCrmsObj.cccea_product_category_name]]</td>
						<td data-title="CRM Name">[[customerComCrmsObj.cccea_name]]</td>
						<td data-title="CRM Email">[[customerComCrmsObj.cccea_email]]</td>
						<td data-title="CRM Status" ng-class="customerComCrmsObj.cccea_status == '1' ? 'text-green' : 'text-danger'">[[customerComCrmsObj.cccea_status == '1' ? 'Active' : 'Inactive']]</td>
						<td data-title="Created By">[[customerComCrmsObj.cccea_created_by_name]]</td>
						<td class="width10">
							<span ng-if="{{ defined('EDIT') && EDIT }}">
								<button type="button" ng-click="funEditCustomerComCrm(customerComCrmsObj.cccea_id)"
									title="Edit CRM" class="btn btn-primary btn-sm">
									<i aria-hidden="true" class="fa fa-pencil-square-o"></i>
								</button>
							</span>
							<span ng-if="{{ defined('DELETE') && DELETE }}">
								<button type="button"
									ng-click="funConfirmDeleteMessage(customerComCrmsObj.cccea_id,divisionID)"
									title="Delete Credit Note" class="btn btn-danger btn-sm">
									<i class="fa fa-trash-o" aria-hidden="true"></i>
								</button>
							</span>
						</td>
					</tr>
					<tr ng-if="!customerComCrmsList.length">
						<td colspan="7">No record found.</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="7">
							<div class="box-footer clearfix">
								<dir-pagination-controls></dir-pagination-controls>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

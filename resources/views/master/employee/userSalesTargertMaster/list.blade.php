<div class="row" ng-hide="isViewListDiv">
	<div class="header">
		<strong class="pull-left headerText" ng-click="funGetUserSalesTargetListing()" title="Refresh">Employee Sales Target Listing <span ng-if="userSalesTargetList.length">([[userSalesTargetList.length]])</span></strong>	
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
				<input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="filterUserSalesTarget">
			</div>
		</div>
	</div>
	<div id="no-more-tables">
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
						<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('dept_name')">Department</label>
						<span class="sortorder" ng-show="predicate === 'dept_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('employee_name')">Employee</label>
						<span class="sortorder" ng-show="predicate === 'employee_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('customer_name')">Customer</label>
						<span class="sortorder" ng-show="predicate === 'customer_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('ust_month')">Target Month</label>
						<span class="sortorder" ng-show="predicate === 'ust_month'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('ust_year')">Target Year</label>
						<span class="sortorder" ng-show="predicate === 'ust_year'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('usty_name')">Type</label>
						<span class="sortorder" ng-show="predicate === 'usty_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('ust_amount')">Target Amount(&#8377;)</label>
						<span class="sortorder" ng-show="predicate === 'ust_amount'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('ust_status')">Status</label>
						<span class="sortorder" ng-show="predicate === 'ust_status'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('created_at')">Created Date</label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
						<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">Action</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="userSalesTargetObj in userSalesTargetList| filter : filterUserSalesTarget | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="Branch Name">[[userSalesTargetObj.division_name]]</td>
					<td data-title="Branch Name">[[userSalesTargetObj.dept_name]]</td>
					<td data-title="Employee Name">[[userSalesTargetObj.employee_name]]</td>
					<td data-title="Employee Name">[[userSalesTargetObj.customer_name]]</td>
					<td data-title="Target Month">[[userSalesTargetObj.ust_month]]</td>
					<td data-title="Target Year">[[userSalesTargetObj.ust_year]]</td>
					<td data-title="Target Type Name">[[userSalesTargetObj.usty_name]]</td>
					<td data-title="Target Amount">[[userSalesTargetObj.ust_amount]]</td>
					<td data-title="Target Status"><span ng-if="userSalesTargetObj.ust_status == 1">Active</span><span ng-if="userSalesTargetObj.ust_status == 2">Inactive</span></td>
					<td data-title="Created Date">[[userSalesTargetObj.created_at]]</td>
					<td data-title="Updated On">[[userSalesTargetObj.updated_at]]</td>
					<td class="width10">
						@if(defined('EDIT') && EDIT)
							<button title="Edit" class="btn btn-primary btn-sm" ng-click="funEditUserSalesTarget(userSalesTargetObj.ust_id)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
						@endif
						@if(defined('DELETE') && DELETE)	
							<button title="Delete" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(userSalesTargetObj.ust_id)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
						@endif
					</td>
				</tr>
				<tr ng-if="!userSalesTargetList.length" class="noRecord"><td align="left" colspan="12">No Record Found!</td></tr>
			</tbody>
			<tfoot>
				<tr ng-if="userSalesTargetList.length">
					<td colspan="12"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
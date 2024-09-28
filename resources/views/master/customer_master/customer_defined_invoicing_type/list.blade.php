<div class="row">
	<div class="header">
		<strong class="pull-left headerText" ng-click="funGetCustomersDefinedInvoicings();" title="Refresh">Customer Defined Invoicing Type <span ng-if="custDataList.length">([[custDataList.length]])</span></strong>
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
				<input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="filterCustomerDefinedInvoicing">

			</div>
		</div>
	</div>

	<div id="no-more-tables">
	    <form  method="POST" role="form" id="erpFilterMultiSearchDetectorForm" name="erpFilterMultiSearchDetectorForm" novalidate>
			<table class="col-sm-12 table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th class="">
							<label class="sortlabel" ng-click="sortBy('division_name')">Division Name  </label>
							<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>
						</th>
						<th class="">
							<label class="sortlabel" ng-click="sortBy('customer_name')">Customer Name  </label>
							<span class="sortorder" ng-show="predicate === 'customer_name'" ng-class="{reverse:reverse}"></span>
						</th>
						<th   class="">
							<label class="sortlabel" ng-click="sortBy('p_category_name')">Department Name </label>
							<span class="sortorder" ng-show="predicate === 'p_category_name'" ng-class="{reverse:reverse}"></span>
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('invoicing_type')">Invoicing Type </label>
							<span class="sortorder" ng-show="predicate === 'invoicing_type'" ng-class="{reverse:reverse}"></span>
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('billing_type')">Billing Type </label>
							<span class="sortorder" ng-show="predicate === 'billing_type'" ng-class="{reverse:reverse}"></span>
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('discount_type')">Discount Type </label>
							<span class="sortorder" ng-show="predicate === 'discount_type'" ng-class="{reverse:reverse}"></span>
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('discount_value')">Discount Value </label>
							<span class="sortorder" ng-show="predicate === 'discount_value'" ng-class="{reverse:reverse}"></span>
						</th>
						<th class="">
							<label class="sortlabel" ng-click="sortBy('customer_invoicing_type_status')">Status </label>
							<span class="sortorder" ng-show="predicate === 'customer_invoicing_type_status'" ng-class="{reverse:reverse}"></span>
						</th>

						<th  class="">
							<label class="sortlabel" ng-click="sortBy('created_at')">Created On </label>
							<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>
						</th>
						<th class="" >
							<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
							<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>
						</th>
						<th class="">Action	</th>

					</tr>
				</thead>
				<tbody>
					<tr dir-paginate="obj in custDataList| filter:filterCustomerDefinedInvoicing| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Division Name">[[obj.division_name]]</td>
						<td data-title="Customer Name">[[obj.customer_name]]</td>
						<td data-title="Parent Category Name ">[[obj.p_category_name]]</td>
						<td data-title="Invoicing Type ">[[obj.invoicing_type]]</td>
						<td data-title="Invoicing Type ">[[obj.billing_type]]</td>
						<td data-title="Invoicing Type ">[[obj.discount_type]]</td>
						<td data-title="Invoicing Type ">[[obj.discount_value]]</td>
						<td data-title="Status ">[[obj.customer_invoicing_type_status=='1' ? 'Active' : 'Deactive']]</td>
						<td data-title="Created On">[[obj.created_at]]</td>
						<td data-title="Updated On">[[obj.updated_at]]</td>
						<td  ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">
							<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='funEditCustomerDefinedInvoice(obj.cdit_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.cdit_id,"deleteCustomerRecord")'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>
					<tr ng-hide="custDataList.length" class="noRecord"><td colspan="5">No Record Found!</td></tr>
				</tbody>
			</table>
		</form>
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>
	</div>
</div>

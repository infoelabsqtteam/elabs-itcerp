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
							<label class="sortlabel" ng-click="sortBy('division_name')">Division </label>
							<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>
						</th>
						<th class="">
							<label class="sortlabel" ng-click="sortBy('customer_name')">Customer  </label>
							<span class="sortorder" ng-show="predicate === 'customer_name'" ng-class="{reverse:reverse}"></span>
						</th>
						<th   class="">
							<label class="sortlabel" ng-click="sortBy('p_category_name')">Department </label>
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
						<th>
							<label class="sortlabel" ng-click="sortBy('tat_editable')">TAT Editable </label>
							<span class="sortorder" ng-show="predicate === 'tat_editable'" ng-class="{reverse:reverse}"></span>
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('customer_invoicing_type_status')">Status </label>
							<span class="sortorder" ng-show="predicate === 'customer_invoicing_type_status'" ng-class="{reverse:reverse}"></span>
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('created_at')">Created On </label>
							<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
							<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>
						</th>
						<th>Action
							<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary mL10"><i class="fa fa-filter"></i></button>
						</th>

					</tr>
				</thead>
				<tbody>
					<tr ng-hide="multiSearchTr">
						<td>
							<select  class="form-control multiSearch" name="division_id"	ng-model="searchCustomer.division_id" id="division_id"		ng-options="item.name for item in divisionsCodeList track by item.id">
								<option value="">Division</option>
							</select>
						</td>
						<td>
							<input type="text"  name="customer_name"   ng-model="searchCustomer.search_customer_name" class="multiSearch form-control" placeholder="Customer Name">
						</td>
						<td>
							<select class="form-control multiSearch"
								name="product_category_id"
								id="product_category_id"
								ng-model="searchCustomer.product_category_id"
								ng-options="item.name for item in parentCategoryList track by item.id">
							<option value="">Department</option>
							</select>
						</td>
						<td>
							<select class="form-control multiSearch"
									name="invoicing_type_id"
									ng-model="searchCustomer.invoicing_type_id"
									id="invoicing_type_id"
									ng-options="item.name for item in invoicingTypes track by item.id">
								<option value="">Invoicing Type</option>
							</select>
						</td>
						<td>
							<select class="form-control multiSearch"
									name="billing_type_id"
									ng-model="searchCustomer.billing_type_id"
									id="billing_type_id"
									ng-options="item.name for item in billingTypes track by item.id">
								<option value="">Billing Type</option>
							</select>
						</td>
						<td>
							<select
								class="form-control multiSearch"
								name="discount_type_id"
								ng-model="searchCustomer.discount_type_id"
								id="discount_type_id" ng-options="item.name for item in discountTypes track by item.id">
							<option value="">Discount Type</option>
							</select>
						</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="width10">
							<button ng-click="getMultiSearch()" class="btn btn-primary btn-sm" title="Refresh"><i class="fa fa-search" aria-hidden="true"></i></button>
							<button ng-click="refreshMultisearch()" class="btn btn-primary btn-sm" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></button>
							<button ng-click="closeMultisearch()" class="btn btn-default btn-sm" title="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
						</td>
					</tr>
					<tr dir-paginate="obj in custDataList| filter:filterCustomerDefinedInvoicing| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Division Name">[[obj.division_name]]</td>
						<td data-title="Customer Name">[[obj.customer_name]]</td>
						<td data-title="Parent Category Name ">[[obj.p_category_name]]</td>
						<td data-title="Invoicing Type">[[obj.invoicing_type]]</td>
						<td data-title="Invoicing Type">[[obj.billing_type]]</td>
						<td data-title="Invoicing Type">[[obj.discount_type]]</td>
						<td data-title="Invoicing Type">[[obj.discount_value]]</td>
						<td data-title="TAT Editable">[[obj.tat_editable == '1' ? 'Editable' : '']]</td>
						<td data-title="Status">[[obj.customer_invoicing_type_status=='1' ? 'Active' : 'Deactive']]</td>
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

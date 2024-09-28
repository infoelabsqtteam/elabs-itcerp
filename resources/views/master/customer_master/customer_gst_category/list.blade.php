<div class="row">
	<div class="header">
		<strong class="pull-left headerText" ng-click="funGetCustomerGstCategory()" title="Refresh">Customer GST Categories <span ng-if="customerGstCategoryList.length">([[customerGstCategoryList.length]])</span></strong>	
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
			 <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="filterCustomerGstCategory">
			</div>
		</div>
	</div>
	<div id="no-more-tables">
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('cgc_name')">GST Category Name</label>
						<span class="sortorder" ng-show="predicate === 'cgc_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cgc_status')">GST Category Status</label>
						<span class="sortorder" ng-show="predicate === 'cgc_status'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
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
				<tr dir-paginate="customerGstCategoryObj in customerGstCategoryList| filter : filterCustomerGstCategory | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="GST Category Name">[[customerGstCategoryObj.cgc_name]]</td>
					<td data-title="GST Category Status"><span ng-if="customerGstCategoryObj.cgc_status == 1">Active</span><span ng-if="customerGstCategoryObj.cgc_status == 2">Inactive</span></td>
					<td data-title="Created On">[[customerGstCategoryObj.created_at]]</td>
					<td data-title="Updated On">[[customerGstCategoryObj.updated_at]]</td>
					<td class="width10">
						@if(defined('EDIT') && EDIT)
							<button title="Update" class="btn btn-primary btn-sm"  ng-click="funEditCgCategory(customerGstCategoryObj.cgc_id)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>						
						@endif
						@if(defined('DELETE') && DELETE)	
							<button title="Delete" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(customerGstCategoryObj.cgc_id)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
						@endif
					</td>
				</tr>
				<tr ng-if="!customerGstCategoryList.length" class="noRecord"><td colspan="5">No Record Found!</td></tr>
			</tbody>
			<tfoot>
				<tr ng-if="customerGstCategoryList.length">
					<td colspan="5"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
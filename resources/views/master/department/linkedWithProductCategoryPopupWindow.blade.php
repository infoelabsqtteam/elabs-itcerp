<div class="modal fade" data-backdrop="static" data-keyboard="false" id="linkedWithProductCategoryPopupWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">
		<form method="POST" role="form" id="linkedWithProductCategoryPopupForm" name="linkedWithProductCategoryPopupForm" novalidate>	
			<div class="modal-content" ng-init="funGetParentCategory();">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-left">Department Linked Detail : <span ng-if="dispatchOrder.order_no" ng-bind="dispatchOrder.order_no"></span></h4>
				</div>
				<div id="no-more-tables" class="modal-body custom-pt-scroll">
					<table class="col-sm-12 table-striped table-condensed cf">
						<thead class="cf">
							<tr>                            
								<th>S. No.</th>
								<th>Department</th>
								<th>Product Category</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="linkedWithProductCateObj in linkedWithProductCateList |orderBy:predicate:reverse track by $index">
								<td data-title="S. No." class="ng-binding">[[$index + 1]]</td>
								<td data-title="Department" class="ng-binding">[[linkedWithProductCateObj.department_name]]</td>
								<td data-title="Product Category" class="ng-binding">
									<select
										class="form-control"
										name="product_category_id['[[linkedWithProductCateObj.department_id]]']"
										id="product_category_id"
										ng-model="linkedWithProductCategory.product_category_id[[linkedWithProductCateObj.department_id]]"
										ng-required='true'>
									<option value="">Select Product Category</option>
									<option ng-repeat="parentCategory in parentCategoryList" ng-selected="parentCategory.id == linkedWithProductCateObj.product_category_id" ng-value="[[parentCategory.id]]" ng-bind="parentCategory.name"></option>
								</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>
					<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
						<input type="button" name="linked_with_product_category" value="Save" ng-click="funUpdateLinkedWithProductCategory()" class="btn btn-primary">
					</span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
				</div>
			</div>
		</form>
	</div>
</div>
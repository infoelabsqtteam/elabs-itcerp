<div class="row header">
		<strong class="pull-left headerText" ng-click="getProductCategory()" title="Refresh">Test Product Categories <span ng-if="prodata.length">([[prodata.length]])</span></strong>	
		<form class="form-inline ng-pristine ng-valid" method="POST" role="form" id="erpFilterProductCateforiesForm" name="erpFilterProductCateforiesForm" action="{{url('master/test-product-categories/download')}}">
			{{ csrf_field() }}
		<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
						<button type="button" title="Select Product Category" ng-click="showProductCatTreeViewPopUp(4)" class="btn btn-default">Tree View</button>
						<select class="seachBox form-control hidden" 
								name="search_category_id"
								id="seach_category_id" 
								ng-model="seach_category_id.selectedOption"
								ng-change="getProductCategory(seach_category_id.selectedOption.id)"
								ng-options="item.name for item in categoryCodeList track by item.id">
							<option value="">Select Parent Category</option>
						</select>
						<button ng-disabled="!prodata.length" type="button"  class="form-control btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download">
							Download</button>
							<div class="dropdown-menu" style="top:20px !important;margin-top: 17.5%; right: 16%;">
								<input type="submit"   formtarget="_blank" name="generate_product_categories_documents" value="Excel"
								class="dropdown-item">
							</div>				
						<input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="filterProductCategory">
						<a class=" btn btn-primary" title="Add Product" href="{{url('master/products')}}">Add Product</a>
				</div>
		</div>
		</form>
</div>
	<div class="row">
	<div id="no-more-tables">
		 <!-- show error message -->
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('p_category_code')">Test Product Category Code  </label>
						<span class="sortorder" ng-show="predicate === 'p_category_code'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('p_category_name')">Test Product Category Name </label>
						<span class="sortorder" ng-show="predicate === 'p_category_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('parent_id')">Test Product Parent Category</label>
						<span class="sortorder" ng-show="predicate === 'parent_id'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">
						<label class="sortlabel" ng-click="sortBy('level')">Level  </label>
						<span class="sortorder" ng-show="predicate === 'level'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">
						<label class="sortlabel" ng-click="sortBy('created_by')">Created By  </label>
						<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('created_at')">Created On  </label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On </label>
						<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
					</th>				
					<th class="width10">Action
						<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
					</th>     			
				</tr>
			</thead>
			<tbody>
				<tr ng-hide="multiSearchTr">
					<td><input type="text" ng-change="getMultiSearch()" name="search_p_category_code" ng-model="searchProductCat.search_p_category_code" class="multiSearch form-control width80" placeholder="Product Category "></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_p_category_name" ng-model="searchProductCat.search_p_category_name" class="multiSearch form-control width80" placeholder="Product Name"></td>
					<td class="width10"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_level" ng-model="searchProductCat.search_level" class="multiSearch form-control width80" placeholder="Level"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" ng-model="searchProductCat.search_created_by" class="multiSearch form-control width80" placeholder="Created By"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_at" ng-model="searchProductCat.search_created_at" class="multiSearch form-control visibility" placeholder="Created On"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" ng-model="searchProductCat.search_updated_at" class="multiSearch form-control visibility" placeholder="Updated On"></td>
					<td class="width10">
						<button ng-click="refreshMultisearch()" title="Refresh"  class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<button ng-click="closeMultisearch()" title="Close"  class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
				</tr>
				<tr dir-paginate="obj in prodata| filter:filterProductCategory| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="Product Category Code">[[obj.p_category_code]]</td>
					<td data-title="Product Category Name ">[[obj.p_category_name]]</td>
					<td data-title="Product Parent Category">[[obj.parent_cat?obj.parent_cat:'-']]</td>
					<td data-title="Created By">[[obj.level]]</td>
					<td data-title="Created By">[[obj.createdBy]]</td>
					<td data-title="Created On">[[obj.created_at]]</td>
					<td data-title="Updated On">[[obj.updated_at]]</td>
					<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">
						<a ng-if="{{defined('EDIT') && EDIT}} && obj.parent_id" href="javascript:;" title="Update"  class="btn btn-primary btn-sm" ng-click='editProductCategory(obj.p_category_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>		
						<a ng-if="{{defined('DELETE') && DELETE}} && obj.parent_id" href="javascript:;" title="Delete"  class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.p_category_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>					
				<tr ng-hide="prodata.length"  class="noRecord"><td colspan="5">No Record Found!</td></tr>

			</tbody>
		</table>
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>
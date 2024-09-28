<div class="row header">
		<strong class="pull-left headerText" ng-click="funRefreshProductTest(0,null)" title="Refresh">Test Products <span ng-if="prodata.length">([[prodata.length]])</span></strong>	
		<form class="form-inline ng-pristine ng-valid" method="POST" role="form" id="erpFilterProductsForm" name="erpFilterProductsForm" action="{{url('master/test-products/download')}}">
			<label>{{ csrf_field() }}</label>
		<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
						<button type="button" title="Select Product Category" ng-click="showProductCatTreeViewPopUp(5)" class="btn btn-default">Tree View</button>
						<select class="seachBox form-control hidden" 
								id="seach_category_id" ng-model="seach_category_id.selectedOption"
								ng-change="getProducts(seach_category_id.selectedOption.id)"
								ng-options="item.name+' ('+item.parent_category_name+')' for item in productCategories track by item.id">
							<option value="">Select product category</option>
						</select>
						<input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-change="funFilterProductTest(seachCategoryId,seach_category_id.searchProducthdr)"
						ng-model="seach_category_id.searchProducthdr">
						
						<button ng-disabled="!prodata.length" type="button"  class="form-control btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download">
						Download</button>
								
						<div class="dropdown-menu" style="top:106px !important;margin-top: 17.5%;">
							<input type="submit"   formtarget="_blank" name="generate_products_documents" value="Excel"
							class="dropdown-item">
						</div>		
				</div>	
				<input type="hidden" value="[[seachCategoryId]]" name="product_category_id">
		</div>
		</form>
</div>
<div class="row" style="margin-bottom: 39px;">
	<div id="no-more-tables">
		 <!-- show error message -->
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('product_code')">Product Code  </label>
						<span class="sortorder" ng-show="predicate === 'product_code'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('product_barcode')">Product Barcode </label>
						<span class="sortorder" ng-show="predicate === 'product_barcode'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('product_name')">Product Name </label>
						<span class="sortorder" ng-show="predicate === 'product_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('product_description')">Product Description </label>
						<span class="sortorder" ng-show="predicate === 'product_description'" ng-class="{reverse:reverse}"></span>						
					</th>					
					<th>
						<label class="sortlabel" ng-click="sortBy('p_category_name')">Product Category  </label>
						<span class="sortorder" ng-show="predicate === 'p_category_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8">
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
					<td><input type="text" ng-change="getMultiSearch()" name="search_product_code"    ng-model="searchProduct.search_product_code" 	class="multiSearch form-control width80" placeholder="Product Category"></td>				
					<td><input type="text" ng-change="getMultiSearch()" name="search_product_barcode" ng-model="searchProduct.search_product_barcode" class="multiSearch form-control width80" placeholder="Product Barcode"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_product_name"    ng-model="searchProduct.search_product_name"  class="multiSearch form-control width80" placeholder="Product Name"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_product_desc" 	  ng-model="searchProduct.search_product_desc"  class="multiSearch form-control width80" placeholder="Product Description"></td>
					<td class="width10"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" 	  ng-model="searchProduct.search_created_by" 	class="multiSearch form-control " placeholder="Created By"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_at"  	  ng-model="searchProduct.search_created_at" 	class="multiSearch form-control visibility" placeholder="Created On"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" 	  ng-model="searchProduct.search_updated_at" 	class="multiSearch form-control visibility" placeholder="Updated On"></td>
					<td class="width10">
						<button ng-click="refreshMultisearch()" title="Refresh" value="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<button ng-click="closeMultisearch()" title="Close" value="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
				</tr>
				<tr dir-paginate="obj in prodata| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="Product Code">[[obj.product_code]]</td>
					<td data-title="Product Barcode ">[[obj.product_barcode]]</td>
					<td data-title="Product Name ">[[obj.product_name]]</td>
					<td data-title="Product Description">
						<span id="productlimitedText-[[obj.product_id]]">
							[[ obj.product_description | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
							<a href="javascript:;" ng-click="toggleDescription('product',obj.product_id)" ng-show="obj.product_description.length > 150" class="readMore">read more...</a>
						</span>
						<span style="display:none;" id="productfullText-[[obj.product_id]]">
							[[ obj.product_description]] 
							<a href="javascript:;" ng-click="toggleDescription('product',obj.product_id)" class="readMore">read less...</a>
						</span>
					</td>
					<td data-title="Product Category">[[obj.p_category_name]] ([[obj.parent_category_name]])</td>
					<td data-title="Created By">[[obj.createdBy]]</td>
					<td data-title="Created At">[[obj.created_at]]</td>
					<td data-title="Updated At">[[obj.updated_at]]</td>
					<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">
						<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update"  class="btn btn-primary btn-sm" ng-click='editProductFun(obj.product_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>						
						<a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete"  class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(seachCategoryId,obj.product_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>					
				<tr ng-hide="prodata.length"  class="noRecord"><td colspan="5">No Record Found!</td></tr>

			</tbody>
		</table>
		<div ng-if="allListPaginate" class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>
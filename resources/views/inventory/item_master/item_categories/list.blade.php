<div class="row header">
		<strong class="pull-left headerText" ng-click = "getItemCategory();categoryFun()" title="Refresh">Item Categories([[itemdata.length]])</strong>	
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
			  <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchItemCategory">
			 <a class=" btn btn-primary" title="Add Product" href="{{url('/inventory/items')}}">Add New Item</a>
		</div></div>
</div>

<div class="row">
	<div id="no-more-tables">
		 <!-- show error message -->
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('item_cat_code')"> Item Category Code  </label>
						<span class="sortorder" ng-show="predicate === 'item_cat_code'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('item_cat_name')"> Item Category Name </label>
						<span class="sortorder" ng-show="predicate === 'item_cat_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('item_parent_cat')"> Item Parent Category</label>
						<span class="sortorder" ng-show="predicate === 'item_parent_cat'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('created_by')"> Created By</label>
						<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">
						<label class="sortlabel" ng-click="sortBy('created_at')"> Created On</label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">
						<label class="sortlabel" ng-click="sortBy('updated_at')"> Updated On</label>
						<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">Action
						<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-hide="multiSearchTr">
					<td><input type="text" ng-change="getMultiSearch()" name="search_item_cat_code"   ng-model="searchItemCat.search_item_cat_code" class="multiSearch form-control width80" placeholder="Item Category Code"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_item_cat_name"   ng-model="searchItemCat.search_item_cat_name" class="multiSearch form-control width80" placeholder="Item Category Name"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_parent_cat"      ng-model="searchItemCat.search_parent_cat"  class="multiSearch form-control width80"   placeholder="Item Parent Category	"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" 	  ng-model="searchItemCat.search_created_by"  class="multiSearch form-control width80"   placeholder="Created By"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_at"  	  ng-model="searchItemCat.search_created_at"  class="multiSearch form-control visibility" placeholder="Created On"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" 	  ng-model="searchItemCat.search_updated_at"  class="multiSearch form-control visibility" placeholder="Updated On"></td>
					<td class="width10">
						<button ng-click="refreshMultisearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<button ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
				</tr>
				<tr dir-paginate="obj in itemdata| filter:searchItemCategory| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="Item Category Code">[[obj.item_cat_code]]</td>
					<td data-title="Item Category Name ">[[obj.item_cat_name]]</td>
					<td data-title="Item Parent Category">[[obj.parent_cat?obj.parent_cat:'-']]</td>
					<td data-title="Item Created By">[[obj.createdBy?obj.createdBy:'-']]</td>
					<td data-title="Item Created On">[[obj.created_at?obj.created_at:'-']]</td>
					<td data-title="Item Updated On">[[obj.updated_at?obj.updated_at:'-']]</td>
					<td class="width10">
						<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update"  class="btn btn-primary btn-sm" ng-click='editItemCategory(obj.item_cat_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete"  class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.item_cat_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>					
				<tr ng-hide="itemdata.length"  class="noRecord"><td colspan="6">No Record Found!</td></tr>

			</tbody>
		</table>
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>
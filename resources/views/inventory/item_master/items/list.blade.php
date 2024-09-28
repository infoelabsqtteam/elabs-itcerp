<div class="row header" ng-hide="listItemFormDiv">
	<div role="new" class="navbar-form navbar-left">            
		<span class="pull-left"><strong id="form_title" ng-click="funGetItems()" title="Refresh" >Item Listing([[itemDataList.length]])</strong></span>
	</div>            
	<div role="new" class="navbar-form navbar-right">
		<div class="nav-custom">
			<input type="text" placeholder="Search" ng-model="filterItems" class="form-control ng-pristine ng-untouched ng-valid">
			<a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" ng-click="navigateItemPage();" class="btn btn-primary">Add New</a>
		</div>
	</div>

	<div id="no-more-tables">
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>						
					<th class="width10">
						<label class="sortlabel" ng-click="sortBy(item_image)">Item Image</label>
						<span class="sortorder" ng-show="predicate === 'item_image'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('item_code')">Item Code  </label>
						<span class="sortorder" ng-show="predicate === 'item_code'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('item_name')">Item Name </label>
						<span class="sortorder" ng-show="predicate === 'item_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy(item_cat_name)">Item Category  </label>
						<span class="sortorder" ng-show="predicate === 'item_cat_name'" ng-class="{reverse:reverse}"></span>						
					</th>							
					<th>
						<label class="sortlabel" ng-click="sortBy('item_barcode')">Item Barcode </label>
						<span class="sortorder" ng-show="predicate === 'item_barcode'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('created_by')">Created By</label>
						<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
						<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">Action
						<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-hide="multiSearchTr">
					<td ></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_item_code"  ng-model="searchItem.search_item_code" 	class="multiSearch form-control width80" placeholder="Item Code"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_item_name"  ng-model="searchItem.search_item_name" 	class="multiSearch form-control width80" placeholder="Item Name"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_item_cat_name"  ng-model="searchItem.search_item_cat_name" 	class="multiSearch form-control width80" placeholder="Item Category"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_item_barcode"  ng-model="searchItem.search_item_barcode"  class="multiSearch form-control width80" placeholder="Item Barcode"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" 	  ng-model="searchItem.search_created_by" 	    class="multiSearch form-control width80" placeholder="Created By"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_at"  	  ng-model="searchItem.search_created_at" 	    class="multiSearch form-control visibility"  placeholder="Created On"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" 	  ng-model="searchItem.search_updated_at 	" 	    class="multiSearch form-control visibility" placeholder="Updated On"></td>
					<td class="width10">
						<button ng-click="refreshMultisearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<button ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
				</tr>
				<tr dir-paginate="itemDataListObj in itemDataList| filter:filterItems | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >				
					<td ng-switch="itemDataListObj" data-title="Item Image">
						<span ng-if="itemDataListObj.item_image" id="removeItemImage-[[itemDataListObj.item_id]]" ng-click="funRemoveItemImage([[itemDataListObj.item_id]])" title="Remove Image"><i class="fa fa-times-rectangle-o imgDelIcon" aria-hidden="true"></i></span>
						<span ng-if="itemDataListObj.item_image" style="width:100%;">
							<img id="item_image_[[itemDataListObj.item_id]]" height="50%" width="50%" class="item_images img-thumbnail" title="[[itemDataListObj.item_name]]" alt="[[itemDataListObj.item_name]]" ng-src="{{url('/public/images/items')}}/[[itemDataListObj.item_id]]/[[itemDataListObj.item_image]]">
						</span>							
						<span ng-if="!itemDataListObj.item_image" class="btn btn-default btn-file " style="margin-left: 23px !important;">
							Upload <input type="file" id="[[itemDataListObj.item_id]]" class="uploadItemImage">
						</span>							
					</td>
					<td data-title="Item Code">[[itemDataListObj.item_code]]</td>
					<td data-title="Item Name ">[[itemDataListObj.item_name]]</td>
					<td data-title="Item Category">[[itemDataListObj.item_cat_name]]</td>						
					<td data-title="Item Barcode ">[[itemDataListObj.item_barcode]]</td>
					<td data-title="Created By">[[itemDataListObj.createdBy]]</td>
					<td class="width10" data-title="Created On">[[itemDataListObj.created_at]]</td>
					<td class="width10" data-title="Updated On">[[itemDataListObj.updated_at]]</td>
					<td class="width10">						
						<a href="javascript:;" ng-if="{{defined('VIEW') && VIEW}}" title="View" class="btn btn-primary btn-sm" ng-click="funViewItem(itemDataListObj.item_id)"><i class="fa fa-eye" aria-hidden="true"></i></a>
						<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update" class="btn btn-primary btn-sm" ng-click="funEditItem(itemDataListObj.item_id)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(itemDataListObj.item_id)"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>					
				<tr ng-if="!itemDataList.length" class="noRecord"><td colspan="8">No Record Found!</td></tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="8">
						<div class="box-footer clearfix">
							<dir-pagination-controls></dir-pagination-controls>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>					  
	</div>
</div>
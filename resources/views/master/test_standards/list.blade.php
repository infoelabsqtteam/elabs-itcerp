<div class="row header">
	<form class="form-inline ng-pristine ng-valid" method="POST" role="form" id="erpFilterTestStandardForm" name="erpFilterTestStandardForm" action="{{url('master/test-standard/download')}}">
		{{ csrf_field() }}	
		<div class=" header">
			<strong class="pull-left headerText" ng-click="funGetTestStandard(GlobalProductCategoryId)" title="Refresh">Test Standards <span ng-if="testStdData.length">([[testStdData.length]])</span></strong>
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
					<select
						class="form-control"
						name="product_category_id"
						id="product_category_id"
						ng-change="funGetTestStandard(addTestParaCategory.product_category_id.id)"
						ng-model="addTestParaCategory.product_category_id"
						ng-options="item.name for item in parentCategoryList track by item.id">
						<option value="">Select Parent Category</option>
					</select>	
					<input type="text" class="seachBox width35 form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="filterTestStandards">
					<button ng-disabled="!testStdData.length" style="width:16%;" type="button"  class="form-control btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download">Download</button>
					<div class="dropdown-menu" style="top:34px !important;margin-top:16.5%;">
						<input type="submit" formtarget="_blank" name="generate_test_standard_documents" value="Excel" class="dropdown-item">
					</div>
				</div>
				
			</div>			
		</div>
	</form>	
	<div id="no-more-tables">
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('test_std_code')">Test Standard Code</label>
						<span class="sortorder" ng-show="predicate === 'test_std_code'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('test_std_name')">Test Standard Name</label>
						<span class="sortorder" ng-show="predicate === 'test_std_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('test_std_desc')">Test Standard Desc </label>
						<span class="sortorder" ng-show="predicate === 'test_std_desc'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('p_category_name')">Parent Product Category</label>
						<span class="sortorder" ng-show="predicate === 'p_category_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">
						<label class="sortlabel" ng-click="sortBy('created_by')">Created By</label>
						<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">
						<label class="sortlabel" ng-click="sortBy('status')">Status</label>
						<span class="sortorder" ng-show="predicate === 'status'" ng-class="{reverse:reverse}"></span>						
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
					<td><input type="text" ng-change="getMultiSearch()" name="search_test_std_code" ng-model="searchStandard.search_test_std_code" class="multiSearch form-control width80" placeholder="Test Standard Code"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_test_std_name" ng-model="searchStandard.search_test_std_name" class="multiSearch form-control width80" placeholder="Test Standard Name"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_test_std_desc" ng-model="searchStandard.search_test_std_desc" class="multiSearch form-control width80" placeholder="Test Standard Desc"></td>
					<td></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" ng-model="searchStandard.search_created_by" class="multiSearch form-control width80" placeholder="Created By"></td>
					<td><select ng-change="getMultiSearch()" name="search_status" class="form-control multiSearch" ng-model="searchStandard.search_status" ng-options="status.name for status in statusList track by status.id"><option value="">All Status</option></select></td>

					<td><input type="text" ng-change="getMultiSearch()" name="search_created_at" ng-model="searchStandard.search_created_at" class="multiSearch form-control visibility" placeholder="Created On"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" ng-model="searchStandard.search_updated_at" class="multiSearch form-control visibility" placeholder="Updated On"></td>
					<td class="width10">
						<button ng-click="refreshMultisearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<button ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
				</tr>
				<tr dir-paginate="obj in testStdData | filter:filterTestStandards | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="Test Standard Code">[[obj.test_std_code]]</td>
					<td data-title="Test Standard Name">[[obj.test_std_name]]</td>
					<td data-title="Test Standard Desc">[[obj.test_std_desc]]</td>
					<td data-title="Parent Category Name">[[obj.p_category_name]]</td>
					<td data-title="Created By">[[obj.createdBy]]</td>
					<td data-title="Created By">[[obj.status | activeOrInactiveUsers]]</td>
					<td data-title="Created On">[[obj.created_at]]</td>
					<td data-title="Updated On">[[obj.updated_at]]</td>
					<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">							
						<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update" class="btn btn-primary btn-sm" ng-click='editTestStd(obj.test_std_id)'> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>					
						<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.test_std_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
				<tr ng-hide="testStdData.length" class="noRecord"><td colspan="6">No Record Found!</td></tr>
			</tbody>
			<tfood>
				<tr>
					<td colspan="6">
						<div class="box-footer clearfix">
							<dir-pagination-controls></dir-pagination-controls>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
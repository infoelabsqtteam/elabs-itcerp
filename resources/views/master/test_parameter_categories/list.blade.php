<div class="row">
	<form class="form-inline ng-pristine ng-valid" method="POST" role="form" id="erpFilterTestStandardForm" name="erpFilterTestStandardForm" action="{{url('master/test-parameter-categories/download')}}">
				{{ csrf_field() }}	
		<div class="header">
			<strong class="pull-left headerText" ng-click="funGetParameterCategoryList();" title="Refresh">Test Parameter Categories <span ng-if="testParameterCategories.length">([[testParameterCategories.length]])</span></strong>	
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
				  <button type="button" title="Select Product Category" ng-click="showParameterCatTreeViewPopUp(10)" class="btn btn-default">Tree View</button>
				  <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="filterParametersCategories">
					<button ng-disabled="!testParameterCategories.length" style="width:16%;" type="button"  class="form-control btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download">
							Download</button>
							<div class="dropdown-menu" style="top:117px !important;margin-top:16.5%;margin-right: 68px;">
								<input type="submit"   formtarget="_blank" name="generate_parameter_categories_documents" value="Excel"
								class="dropdown-item">
							</div>
					<input type="hidden" value="[[GlobalPrameterParentId]]" ng-model="GlobalPrameterParentId" name="parent_id">		
					<a ng-if="{{defined('ADD') && ADD}}" title="Add Parameter" href="{{url('master/test-parameters')}}" class="btn btn-primary btn-md" ng-show="testParameterCategories.length">Add Parameter</a>
				</div>
			</div>
		</div>
	</form>	
	<div id="no-more-tables">
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('test_para_cat_code')">Parameter Category Code  </label>
						<span class="sortorder" ng-show="predicate === 'test_para_cat_code'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('test_para_cat_name')">Parameter Category Name  </label>
						<span class="sortorder" ng-show="predicate === 'test_para_cat_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('test_para_cat_print_desc')">Parameter Category Description </label>
						<span class="sortorder" ng-show="predicate === 'test_para_cat_print_desc'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('parent_id')"> Parent Category  </label>
						<span class="sortorder" ng-show="predicate === 'parent_category'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('p_category_name')">Product Section</label>
						<span class="sortorder" ng-show="predicate === 'p_category_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('level')">Level</label>
						<span class="sortorder" ng-show="predicate === 'level'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('created_by')">Created By </label>
						<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('status')">Status </label>
						<span class="sortorder" ng-show="predicate === 'status'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('created_at')">Created On </label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On </label>
						<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8"><label>Action</label>
						<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary mL10"><i class="fa fa-filter"></i></button>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-hide="multiSearchTr">
					<td><input type="text" ng-change="getMultiSearch()" name="search_para_cat_code"   ng-model="searchParaCat.search_para_cat_code" class="multiSearch form-control" placeholder="Test Parameter Category Code"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_para_cat_name"   ng-model="searchParaCat.search_para_cat_name" class="multiSearch form-control" placeholder="Test Parameter Category Name"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_para_cat_desc"   ng-model="searchParaCat.search_para_cat_desc" class="multiSearch form-control" placeholder="Test Parameter Category Print Description"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_parameter_category" ng-model="searchParaCat.search_parameter_category" class="multiSearch form-control" placeholder="Parameter Category"></td>
					
					<td><input type="text" ng-change="getMultiSearch()" name="search_parent_category" ng-model="searchParaCat.search_parent_category" class="multiSearch form-control" placeholder="Parent Category"></td>
					<td class="width10"><input type="text" ng-change="getMultiSearch()" name="search_level" ng-model="searchParaCat.search_level" class="multiSearch form-control" placeholder="Level"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" 	  ng-model="searchParaCat.search_created_by" class="multiSearch form-control" placeholder="Created By"></td>
					<td><select ng-change="getMultiSearch()" name="search_status" class="form-control multiSearch" ng-model="searchParaCat.search_status" ng-options="status.name for status in statusList track by status.id"><option value="">All Status</option></select></td>

					<td><input type="text" ng-change="getMultiSearch()" name="search_created_at"  	  ng-model="searchParaCat.search_created_at" class="multiSearch form-control visibility" placeholder="Created On"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" 	  ng-model="searchParaCat.search_updated_at" class="multiSearch form-control visibility" placeholder="Updated On"></td>
					<td class="width10">
						<button ng-click="refreshMultisearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<button ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
				</tr>
				<tr dir-paginate="obj in testParameterCategories | filter:filterParametersCategories | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="Category Code">[[obj.test_para_cat_code]]</td>
					<td data-title="Category Name">[[obj.test_para_cat_name]]</td>
					<td data-title="Category Print Description">
						<span id="testCatlimitedText-[[obj.test_para_cat_id]]">
							[[ obj.test_para_cat_print_desc | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
							<a href="javascript:;" ng-click="toggleDescription('testCat',obj.test_para_cat_id)" ng-show="obj.test_para_cat_print_desc.length > 150" class="readMore">read more...</a>
						</span>
						<span id="testCatfullText-[[obj.test_para_cat_id]]" style="display:none;" >
							[[ obj.test_para_cat_print_desc]] 
							<a href="javascript:;" ng-click="toggleDescription('testCat',obj.test_para_cat_id)" class="readMore">read less...</a>
						</span>
					</td>
					<td data-title="Parent Category">[[obj.parent_cat?obj.parent_cat:'-']]</td>					
					<td data-title="Parent Category Name ">[[obj.p_category_name]]</td>				
					<td data-title="Parent Category Name ">[[obj.level]]</td>				
					<td data-title="Created By">[[obj.createdBy]]</td>
					<td data-title="Created By">[[obj.status | activeOrInactiveUsers]]</td>

					<td data-title="Created On">[[obj.created_at?obj.created_at:'-']]</td>
					<td data-title="Updated On ">[[obj.updated_at?obj.updated_at:'-']]</td>
					<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">							
						<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update"  class="btn btn-primary btn-sm" ng-click='funEditTestParameter(obj.test_para_cat_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete"  class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.test_para_cat_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
				<tr ng-hide="testParameterCategories.length"  class="noRecord"><td colspan="8">No Record Found!</td></tr>
			</tbody>
		</table>
		
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>
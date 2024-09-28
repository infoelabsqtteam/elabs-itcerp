<div class="row" ng-hide="listDivisions">
	<div class=" header">
		<strong class="pull-left headerText" ng-click="getDynamicFields()" title="Refresh">Dynamic Fields<span ng-if="dynamicFieldsData.length">([[dynamicFieldsData.length]])</span></strong>	
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
				<input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="filterDivisions">
			</div>
		</div>
	</div>
		<div id="no-more-tables">
			  <table class="col-sm-12 table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('dynamic_field_code')">Dynamic Field Code </label>
							<span class="sortorder" ng-show="predicate === 'dynamic_field_code'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('dynamic_field_name')"> Dynamic Field Name</label>
							<span class="sortorder" ng-show="predicate === 'dynamic_field_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('dynamic_field_status')"> Dynamic Field Status</label>
							<span class="sortorder" ng-show="predicate === 'dynamic_field_status'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('created_by')">Created By</label>
							<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
						</th>			
						<th  class="width10">
							<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
							<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th  class="width10">
							<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
							<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">Action 					
							<!-- <button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary mL10"><i class="fa fa-filter"></i></button>     			 -->
						</th>
					</tr>
				</thead>
				<tbody>		
					<!-- <tr ng-hide="multiSearchTr">
						<td><input type="text" ng-change="getMultiSearch()" name="search_dynamic_field_code" ng-model="searchDynamicField.search_dynamic_field_code" class="multiSearch form-control width80" placeholder="Code"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_dynamic_field_name" ng-model="searchDynamicField.search_dynamic_field_name" class="multiSearch form-control width80" placeholder="Name"></td>
						<td></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" ng-model="searchDynamicField.search_created_by" class="multiSearch form-control width80" placeholder="Created By"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_created_at" ng-model="searchDynamicField.search_created_at" class="multiSearch form-control width80 visibility" placeholder="Created On"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" ng-model="searchDynamicField.search_updated_at" class="multiSearch form-control width80 visibility" placeholder="Updated On"></td>
						<td class="width10">
							<button ng-click="refreshMultisearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
							<button ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
						</td>
					</tr> -->
					<tr dir-paginate="obj in dynamicFieldsData| filter:filterDivisions | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Dynamic Field Code ">[[obj.dynamic_field_code]]</td>
						<td data-title="Dynamic Field Name ">[[obj.dynamic_field_name]]</td>
						<td data-title="Dynamic Field Status">[[obj.dynamic_field_status == 1 ? 'Active' : 'Inactive']]</td>
						<td  data-title="Created By ">[[obj.createdBy]]</td>
						<td  data-title="Created On ">[[obj.created_at]]</td>
						<td  data-title="Updated On">[[obj.updated_at]]</td>
						<td class="width10">
							<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" class="btn btn-primary btn-sm" ng-click='editDynamicField(obj.odfs_id)' title="Update"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.odfs_id)' title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>
						
					<tr ng-hide="dynamicFieldsData.length"  class="noRecord"><td colspan="5">No Record Found!</td></tr>
				</tbody>
			</table>
				
			<div class="box-footer clearfix">
				<dir-pagination-controls></dir-pagination-controls>
			</div>
		</div>
</div>

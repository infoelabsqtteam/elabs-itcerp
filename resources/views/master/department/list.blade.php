<div class="row">
	<div class="header">
		<strong class="pull-left headerText"ng-click="getDepartments()" title="Refresh">Departments <span ng-if="deptdata.length">([[deptdata.length]])</span></strong>	
			<form class="form-inline" method="POST" role="form" name="erpDepartmentListForm" action="{{url('master/departments/download-excel')}}"  target= "blank" novalidate>
			<label for="submit">{{ csrf_field() }}</label>
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom custom-display">
					<input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchDepartments">		
					@if(defined('ADD') && ADD)<a title="Upload CSV Records" title="Upload CSV" class="btn btn-primary" href="{{ url('/departments/upload') }}">Upload</a>@endif
					
					<button type="button" ng-disabled="!deptdata.length" class="form-control btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download">
					Download</button>
					<div class="dropdown-menu">
					    <input type="submit" formtarget="_blank" name="generate_department_documents" value="excel"
					    class="dropdown-item">
					</div>
				</div>
			</div>	
		</form>
		
	</div>
	<div id="no-more-tables">
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>						
					<th>
						<label class="sortlabel" ng-click="sortBy('department_code')">Department Code  </label>
						<span class="sortorder" ng-show="predicate === 'department_code'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('department_name')">Department Name  </label>
						<span class="sortorder" ng-show="predicate === 'department_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('department_type')">Department Type  </label>
						<span class="sortorder" ng-show="predicate === 'department_type'" ng-class="{reverse:reverse}"></span>						
					</th>
					@if(defined('ROLEID') && ROLEID==1)
					<th>
						<label class="sortlabel" ng-click="sortBy('created_by')">Created By  </label>
						<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
					</th>
					@endif
					<th  class="width10">
						<label class="sortlabel" ng-click="sortBy('created_on')">Created On  </label>
						<span class="sortorder" ng-show="predicate === 'created_on'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th  class="width10">
						<label class="sortlabel" ng-click="sortBy('updated_on')">Updated On  </label>
						<span class="sortorder" ng-show="predicate === 'updated_on'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">Action
						<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-hide="multiSearchTr">						
					<td><input type="text" ng-change="getMultiSearch()" name="search_department_code" ng-model="searchDept.search_department_code" 	class="multiSearch form-control width80" placeholder="Department Code"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_department_name" ng-model="searchDept.search_department_name" 	class="multiSearch form-control width80" placeholder="Department Name"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_department_type" ng-model="searchDept.search_department_type"  class="multiSearch form-control width80" placeholder="Department Type"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" 	  ng-model="searchDept.search_created_by" 	    class="multiSearch form-control width80" placeholder="Created By"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_created_at"  	  ng-model="searchDept.search_created_at" 	    class="multiSearch form-control width80 visibility" placeholder="Created On"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" 	  ng-model="searchDept.search_updated_at" 	    class="multiSearch form-control width80 visibility" placeholder="Updated On"></td>
					<td class="width10">
						<button ng-click="refreshMultisearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<button ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
				</tr>
				<tr dir-paginate="obj in deptdata | filter:searchDepartments | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">						
					<td data-title="Department Code">[[obj.department_code]]</td>
					<td data-title="Department Name ">[[obj.department_name]]</td>
					<td data-title="Department Type ">[[obj.department_type_name]]</td>
					
					@if(defined('ROLEID') && ROLEID==1)
						<td data-title="Created By">[[obj.createdBy]]</td>
					@endif
					
					<td data-title="Created at">[[obj.department_created_at]]</td>
					<td data-title="Updated at">[[obj.department_updated_at]]</td>
					@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
					<td class="width10">
					  @if(defined('EDIT') && EDIT)
						<button title="Update" class="btn btn-primary btn-sm"  ng-click='editDepartment(obj.department_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>						
					  @endif
					  @if(defined('DELETE') && DELETE)	
						<button title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.department_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
					  @endif
					</td>
					@endif
				</tr>
				<tr ng-hide="deptdata.length"  class="noRecord"><td colspan="5">No Record Found!</td></tr>
			</tbody>
		</table>
		
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>
<div class="row">	
	<div class="header">
		<strong class="pull-left headerText" ng-click="getBranchWiseDefaultRemarks(division_id,department_id)" title="Refresh">Remarks <span ng-if="defaultRemarkData.length">([[defaultRemarkData.length]])</span></strong>
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
				<input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="filterDefaultRemarksList">
				<!--<select
					ng-if="{{$division_id}} == 0"
					class="form-control" 
					ng-model="filterDefaultRemarkNotes.department_id.selectedOption" 
					ng-options="item.name for item in parentCategoryList track by item.id"
					ng-change="getBranchWiseDefaultRemarks(divisionID,filterDefaultRemarkNotes.department_id.selectedOption.id)">
					<option value="">Select Department</option>
				</select>
				<select
					ng-if="{{$division_id}} == 0"
					class="form-control"
					ng-model="filterDefaultRemarkNotes.division"
					ng-options="division.name for division in divisionsCodeList track by division.id"
					ng-change="getBranchWiseDefaultRemarks(filterDefaultRemarkNotes.division.id,departmentID)">
					<option value="">All Branch</option>
				</select>-->
			</div>
		</div>
	</div>
	
	<div id="no-more-tables">
	    <form  method="POST" role="form" id="erpFilterMultiSearchDefaultRemarksForm" name="erpFilterMultiSearchDefaultRemarksForm">
			<table class="col-sm-12 table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
							<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('p_category_name')">Department</label>
							<span class="sortorder" ng-show="predicate === 'p_category_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('remark_name')">Description</label>
							<span class="sortorder" ng-show="predicate === 'remark_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('type')">Type</label>
							<span class="sortorder" ng-show="predicate === 'type'" ng-class="{reverse:reverse}"></span>						
						</th>
						
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('remark_status')">Status</label>
							<span class="sortorder" ng-show="predicate === 'remark_status'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width8">
							<label class="sortlabel" ng-click="sortBy('created_by')">Created By</label>
							<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th  class="width8">
							<label class="sortlabel" ng-click="sortBy('created_at')">Created On </label>
							<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width8" >
							<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
							<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">Action
						<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary mL10" aria-hidden="false">
						<i class="fa fa-filter"></i></button>
						</th>					
					</tr>
					<tr ng-hide="multiSearchTr">
					<td>
				<select class="form-control multiSearch" name="division_id"
					ng-model="filterDefaultRemarkNotes.division"
					ng-options="division.name for division in divisionsCodeList track by division.id"
					>
					<option value="">All Branch</option>
				</select></td>
					<td><select class="form-control multiSearch" name="product_category_id" 
					ng-model="filterDefaultRemarkNotes.department_id" 
					ng-options="item.name for item in parentCategoryList track by item.id"
					>
					<option value="">Select Department</option>
				</select></td>
					<td></td>
					<td><select class="form-control multiSearch" name="remark_type" id="remark_type" ng-model="filterDefaultRemarkNotes.remark_type" 
							ng-options="remarkTypes.remark_type_name for remarkTypes in remarkTypeList track by remarkTypes.remark_type">
							<option value="">Select Remark Type</option>
						</select>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="width10">
						<button ng-click="getMultiSearch()" class="btn btn-primary btn-sm" title="Refresh"><i aria-hidden="true" class="fa fa-search"></i></button>
						<button ng-click="refreshMultisearch()" class="btn btn-primary btn-sm" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<button ng-click="closeMultisearch()" class="btn btn-default btn-sm" title="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
					</tr>
				</thead>
				<tbody>
		                          <tr dir-paginate="defaultRemarkObj in defaultRemarkData  | filter : filterDefaultRemarksList | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">				
						<td data-title="Parent Category Name">[[defaultRemarkObj.division_name]]</td>
						<td data-title="Parent Category Name">[[defaultRemarkObj.p_category_name]]</td>
						<td data-title="remark Remark Usage Time">[[defaultRemarkObj.remark_name]]</td>
						<td data-title="Remark Type">
							<span ng-if="defaultRemarkObj.type == 1">Notes</span>
							<span ng-if="defaultRemarkObj.type == 2">Remarks</span>
						</td>
						<td data-title="Remark Status">
							<span ng-if="defaultRemarkObj.remark_status == 1">Active</span>
							<span ng-if="defaultRemarkObj.remark_status == 2">Inactive</span>
						</td>
                                                <td data-title="Created By">[[defaultRemarkObj.createdBy]]</td>
						<td data-title="Created On">[[defaultRemarkObj.created_at]]</td>
						<td data-title="Updated On">[[defaultRemarkObj.updated_at]]</td>
						<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">
							<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='funEditDefaultRemarks(defaultRemarkObj.remark_id,divisionID,departmentID)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(defaultRemarkObj.remark_id,divisionID,departmentID)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>
					<tr ng-if="!defaultRemarkData.length" class="noRecord"><td colspan="5">No Record Found!</td></tr>
				</tbody>
			</table>
		</form>	
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>
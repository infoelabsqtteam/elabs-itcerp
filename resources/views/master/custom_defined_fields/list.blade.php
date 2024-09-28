<div class="row">	
	<div class="header">
		<strong class="pull-left headerText" ng-click="getCustomDefinedList()" title="Refresh">Custom Defined Fields <span ng-if="customFieldData.length">([[customFieldData.length]])</span></strong>
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
				<input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="filterCustomDefinedList">
			</div>
		</div>
	</div>
	
	<div id="no-more-tables">
	    <form  method="POST" role="form" novalidate>
			<table class="col-sm-12 table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('label_name')">Label Name</label>
							<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('label_value')">Label Value</label>
							<span class="sortorder" ng-show="predicate === 'p_category_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('label_status')">Label Status</label>
							<span class="sortorder" ng-show="predicate === 'remark_name'" ng-class="{reverse:reverse}"></span>						
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
						<th class="width10">Action</th>					
					</tr>
				</thead>
				<tbody>
		                          <tr dir-paginate="customFieldObj in customFieldData |filter:filterCustomDefinedList| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">				
						<td data-title="Label Name">[[customFieldObj.label_name]]</td>
						<td data-title="Label Value">[[customFieldObj.label_value]]</td>
						<td data-title="Label Status">
							<span ng-if="customFieldObj.label_status == 1">Active</span>
							<span ng-if="customFieldObj.label_status == 2">Inactive</span>
						</td>
                                                <td data-title="Created By">[[customFieldObj.createdBy]]</td>
						<td data-title="Created On">[[customFieldObj.created_at]]</td>
						<td data-title="Updated On">[[customFieldObj.updated_at]]</td>
						<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">
							<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='funEditCustomDefinedFields(customFieldObj.label_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(customFieldObj.label_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>
					<tr ng-hide="customFieldData.length" class="noRecord"><td colspan="5">No Record Found!</td></tr>
				</tbody>
			</table>
		</form>	
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>
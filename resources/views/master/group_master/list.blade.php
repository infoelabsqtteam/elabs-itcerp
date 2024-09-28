<div class="row">	
	<form method="POST" role="form" id="erpMasterListingForm" name="erpMasterListingForm" novalidate>
		
		<div class="header">
			<strong class="pull-left headerText" ng-click="funListMaster();" title="Refresh">Groups <span ng-if="masterDataList.length">([[masterDataList.length]])</span></strong>
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
					<input type="text" class="form-control" name="searchKeyword" placeholder="Search" ng-model="filterGroupModel">
				</div>
			</div>
		</div>
		
		<div id="no-more-tables">			
			<table class="col-sm-12 table table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('org_group_code')">Group Code</label>
							<span class="sortorder" ng-show="predicate === 'org_group_code'" ng-class="{reverse:reverse}"></span>						
						</th>			
						<th   class="width10">
							<label class="sortlabel" ng-click="sortBy('org_group_name')">Group Name</label>
							<span class="sortorder" ng-show="predicate === 'org_group_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th   class="width10">
							<label class="sortlabel" ng-click="sortBy('org_division_name')">Division Name</label>
							<span class="sortorder" ng-show="predicate === 'org_division_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th   class="width10">
							<label class="sortlabel" ng-click="sortBy('org_product_category_name')">Department Name</label>
							<span class="sortorder" ng-show="predicate === 'org_product_category_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('org_group_status ')">Group Status</label>
							<span class="sortorder" ng-show="predicate === 'org_group_status '" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('org_group_created_name')">Created By</label>
							<span class="sortorder" ng-show="predicate === 'org_group_created_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th  class="width8">
							<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
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
					<tr dir-paginate="masterDataobj in masterDataList| filter:filterGroupModel | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Group Code">[[masterDataobj.org_group_code]]</td>
						<td data-title="Group Name">[[masterDataobj.org_group_name]]</td>
						<td data-title="Division Name">[[masterDataobj.org_division_name]]</td>
						<td data-title="Department Name">[[masterDataobj.org_product_category_name]]</td>
						<td data-title="Equipment Type">[[masterDataobj.org_group_status | activeOrInactiveUsers]]</td>
						<td data-title="Created By">[[masterDataobj.org_group_created_name]]</td>
						<td data-title="Created On">[[masterDataobj.created_at]]</td>
						<td data-title="Updated On">[[masterDataobj.updated_at]]</td>
						<td class="width10">
							<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='funEditMaster(masterDataobj.org_group_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(masterDataobj.org_group_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>
					<tr ng-if="!masterDataList.length" class="noRecord"><td colspan="9">No Record Found!</td></tr>
				</tbody>
				<tfoot>
					<tr ng-if="masterDataList.length">
						<td colspan="[[masterDataList.length]]">
							<div class="box-footer clearfix">
								<dir-pagination-controls></dir-pagination-controls>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</form>	
</div>
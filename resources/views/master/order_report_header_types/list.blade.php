<div ng-if="showOrderReportHdrTypeList">
	<div class="header">
		<strong class="pull-left headerText" ng-click="getOrderReportHdrTypeList();" title="Refresh">Total<span ng-if="orderRptHdrTypeData.length">([[orderRptHdrTypeData.length]])</span></strong>
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
				<input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="filterOrderReportHdrType">
			</div>
		</div>
	</div>
	
	<div id="no-more-tables">
		<form  method="POST" role="form" id="erpFilterMultiSearchTemplateForm" name="erpFilterMultiSearchTemplateForm" novalidate>
			<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th class="width10">
						<label class="sortlabel" ng-click="sortBy('orht_branch')">Branch</label>
						<span class="sortorder" ng-show="predicate === 'orht_branch'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('orht_dept')">Department</label>
						<span class="sortorder" ng-show="predicate === 'orht_dept'" ng-class="{reverse:reverse}"></span>						
					</th>
				    <th>
						<label class="sortlabel" ng-click="sortBy('orht_customer_type')">Customer Type</label>
						<span class="sortorder" ng-show="predicate === 'orht_customer_type'" ng-class="{reverse:reverse}"></span>						
					</th>	
					<th>
						<label class="sortlabel" ng-click="sortBy('orht_report_hdr_type')">Report Header Type</label>
						<span class="sortorder" ng-show="predicate === 'orht_report_hdr_type '" ng-class="{reverse:reverse}"></span>					
					<th  class="width8">
						<label class="sortlabel" ng-click="sortBy('created_at')">Created On </label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8" >
						<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
						<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">Action					
						{{--  <button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>  --}}
					</th>
			
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="obj in orderRptHdrTypeData | filter:filterOrderReportHdrType | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="Division Name">[[obj.division_name]]</td>
					<td data-title="Department Name">[[obj.department_name]]</td>
					<td data-title="Customer Type">[[obj.customer_type]]</td>
					
					<td data-title="Report header type default">[[obj.rhtd_name]]</td>
					<td data-title="Created On">[[obj.created_at]]</td>
					<td data-title="Updated On">[[obj.updated_at]]</td>
					<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">
						<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='funEditDetails(obj.orht_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.orht_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
				<tr ng-hide="orderRptHdrTypeData.length" class="noRecord"><td colspan="5">No Record Found!</td></tr>
				</tbody>
				<tfoot>
					<tr class="noRecord">
						<td colspan="5">
							<div class="box-footer clearfix">
								<dir-pagination-controls></dir-pagination-controls>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>

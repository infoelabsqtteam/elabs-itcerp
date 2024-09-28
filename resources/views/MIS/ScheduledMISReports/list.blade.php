<div class="row">
	<div class="header">
		<strong class="pull-left headerText" ng-click="funGetScheduledMisReport()" title="Refresh">Scheduled Mis Reports<span ng-if="scheduledMisReportList.length">([[scheduledMisReportList.length]])</span></strong>	
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
			 <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="filterScheduledMisReport">
			</div>
		</div>
	</div>
	<div id="no-more-tables">
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('smrd_division_name')">Division</label>
						<span class="sortorder" ng-show="predicate === 'smrd_division_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('smrd_product_category_name')">Department</label>
						<span class="sortorder" ng-show="predicate === 'smrd_product_category_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cgc_status')">MIS-Report Type</label>
						<span class="sortorder" ng-show="predicate === 'cgc_status'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label>Primary Email</label>
						<span class="sortorder" ng-show="predicate === 'cgc_status'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label>Seconday Email</label>
						<span class="sortorder" ng-show="predicate === 'cgc_status'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
						<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">Action</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="scheduledMisReportObj in scheduledMisReportList| filter : filterScheduledMisReport | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="Division">[[scheduledMisReportObj.smrd_division_name]]</td>
					<td data-title="Department">[[scheduledMisReportObj.smrd_product_category_name]]</td>
					<td data-title="MIS-Report Type">[[scheduledMisReportObj.smrd_mis_report_name]]</td>
					<td data-title="Primary Email">
						<span ng-if="scheduledMisReportObj.smrd_to_email_address.length">
							<a href="javascript:;" title="View all primary email" class="text-center" data-toggle="modal" data-target="#myModalPriEmail_[[scheduledMisReportObj.smrd_id]]"><i class="fa fa-eye" aria-hidden="true"></i></a>
							<div class="modal fade" id="myModalPriEmail_[[scheduledMisReportObj.smrd_id]]" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header text-left">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title"><span class="poptitle">Primary Emails</span></h4>
										</div>
										<div class="modal-body">											
											<ul>
												<li id="[[key]]" ng-repeat="(key,value) in scheduledMisReportObj.smrd_to_email_address">[[value]]</li>
											</ul>									
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div>
						</span>
						<span ng-if="!scheduledMisReportObj.smrd_to_email_address.length">-</span>
					</td>
					<td data-title="Seconday Email">
						<span ng-if="scheduledMisReportObj.smrd_from_email_address.length">
							<a href="javascript:;" title="View all seconday email" class="text-center" data-toggle="modal" data-target="#myModalSecEmail_[[scheduledMisReportObj.smrd_id]]"><i class="fa fa-eye" aria-hidden="true"></i></a>
							<div class="modal fade" id="myModalSecEmail_[[scheduledMisReportObj.smrd_id]]" role="dialog">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header text-left">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title"><span class="poptitle">Secondary Emails</span></h4>
										</div>
										<div class="modal-body">											
											<ul>
												<li id="[[key]]" ng-repeat="(key,value) in scheduledMisReportObj.smrd_from_email_address">[[value]]</li>
											</ul>									
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div>
						</span>
						<span ng-if="!scheduledMisReportObj.smrd_to_email_address.length">-</span>
					</td>
					<td data-title="Created On">[[scheduledMisReportObj.created_at]]</td>
					<td data-title="Updated On">[[scheduledMisReportObj.updated_at]]</td>
					<td class="width10">
						@if(defined('EDIT') && EDIT)
							<button title="Update" class="btn btn-primary btn-sm" ng-click="funEditScheduledMisReport(scheduledMisReportObj.smrd_id)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>						
						@endif
						@if(defined('DELETE') && DELETE)	
							<button title="Delete" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(scheduledMisReportObj.smrd_id)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
						@endif
					</td>
				</tr>
				<tr ng-if="!scheduledMisReportList.length" class="noRecord"><td colspan="8">No Record Found!</td></tr>
			</tbody>
			<tfoot>
				<tr ng-if="scheduledMisReportList.length">
					<td colspan="8"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
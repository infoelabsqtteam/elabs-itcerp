<div id="isViewInvoiceReport" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" style="padding: 3px;">&times;</button>
				<div class="col-xs-12 report-header">
					    <div class="pull-left"><strong id="form_title">[[MISReportDetailData.mis_report_name]]<span ng-if="MISReportDetailData.tableBody.length">([[MISReportDetailData.tableBody.length]])</span></strong></div>
				</div>
			</div>
			<div class="modal-body custom-nr-scroll">
				<div class="row">       
					<div class="panel panel-default mT20 custom-rt-scroll">
					    <div class="panel-body">
						<div id="no-more-tables" class="row col-xs-12">
						    <table class="col-sm-12 table-striped table-condensed cf">
							<thead class="cf" ng-if="MISReportData.tableHead.length">
							    <tr>
								<th ng-if="$index == 0" class="text-left" ng-repeat="tableHeadName in MISReportDetailData.tableHead track by $index">
								    <label ng-click="sortBy('[[tableHeadName]]')" class="sortlabel capitalizeAll">[[tableHeadName | removeUnderscores]]</label>
								    <span ng-class="{reverse:reverse}" ng-show="predicate === '[[tableHeadName]]'" class="sortorder reverse ng-hide"></span>
								</th>
								<th ng-if="$index > 0" class="text-left" ng-repeat="tableHeadName in MISReportDetailData.tableHead track by $index">
								    <label ng-click="sortBy('[[tableHeadName]]')" class="sortlabel capitalizeAll">[[tableHeadName | removeUnderscores]]</label>
								    <span ng-class="{reverse:reverse}" ng-show="predicate === '[[tableHeadName]]'" class="sortorder reverse ng-hide"></span>
								</th>
							    </tr>
							</thead>
							<tbody>
							    <tr ng-repeat="MISReportTdData in MISReportDetailData.tableBody | orderBy:predicate:reverse">
								<td ng-repeat="MISReportTdObj in MISReportTdData track by $index" data-title="[[MISReportTdObj]]" class="ng-binding">[[MISReportTdObj ? MISReportTdObj : '-']]</td>
							    </tr>                        
							    <tr ng-if="!MISReportDetailData.tableBody.length" class="text-center"><td colspan="8">No record found.</td></tr>
							</tbody>
						</table>	  
						</div>        
					    </div>
					</div>
				</div> 	
			</div>	
		</div>
	</div>
</div>
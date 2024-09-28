<div class="modal fade" data-backdrop="static" data-keyboard="false" id="sectionInchargeOrderDetailPopupWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}" class="modal-title text-left" title="Refresh" ng-click="funViewRefreshedAllocatedSectionIncharges(viewSInchargeDataList.order_id,viewSInchargeDataList.order_no)">Allocated Incharge Detail :<b>[[viewSInchargeDataList.order_no]]</b></h4>
				<h4 ng-if="{{!defined('IS_ADMIN')}}" class="modal-title text-left">Allocated Incharge Detail :<b>[[viewSInchargeDataList.order_no]]</b></h4>
			</div>
			<div id="no-more-tables" class="modal-body custom-scroll">
				<table class="col-sm-12 table-striped table-condensed cf">
					<thead class="cf">
						<tr>                            
							<th>S.No.</th>
							<th>Incharge Name</th>
							<th>Equipment Type</th>
							<th>Assigned On</th>
							<th>Confirmed Date</th>
							<th>Confirm By</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="viewSInchargeDataObj in viewSInchargeDataList |orderBy:predicate:reverse track by $index">
							<td data-title="S. No." class="ng-binding">[[$index+1]]</td>
							<td data-title="Incharge Name" class="ng-binding">[[viewSInchargeDataObj.incharge_name]]</td>
							<td data-title="Equipment Type" class="ng-binding">[[viewSInchargeDataObj.equipment_name]]</td>
							<td data-title="Assigned On" class="ng-binding">[[viewSInchargeDataObj.oid_assign_date]]</td>
							<td data-title="Confirmed Date" class="ng-binding">[[viewSInchargeDataObj.oid_confirm_date]]</td>
							<td data-title="Confirm By" class="ng-binding">[[viewSInchargeDataObj.incharge_confirm_by]]</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
			</div>
		</div>		
	</div>
</div>
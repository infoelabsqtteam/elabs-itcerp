<!-- need notification : add note modal -->
<div class="modal fade" id="needModificationModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Note : </h4>
			</div>
			<div class="modal-body">		
				 <text-angular name="note" ng-model="noteHtmlContent" required></text-angular>		
			</div>
			<div class="modal-footer">
				<input ng-disabled="!noteHtmlContent.length" type="submit" ng-click="funConfirmReportStatus(viewOrderReport.order_id,'NeedModification','Are you sure you want need modification in this record?')" value="Need Modification" class="btn btn-primary">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
			</div>
		</div>
		
	</div>
</div>
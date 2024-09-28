<div class="modal fade" id="needModificationBySectionInchargeModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Note :</h4>
			</div>
			<div class="modal-body">
				<text-angular name="note_by_reporter" ng-model="reporterNoteHtmlContent" required></text-angular>	
			</div>
			
			<div class="modal-footer">
				<input ng-disabled="!reporterNoteHtmlContent.length" type="submit" ng-click="funConfirmReportStatus(viewOrderReport.order_id,'NeedModificationBySectionIncharge','Are you sure you want need modification in this record?')" value="Need Modification" class="btn btn-primary">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
			</div>	
		</div>
	</div>
</div>
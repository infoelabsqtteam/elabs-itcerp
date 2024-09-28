<div class="modal fade" data-backdrop="static" data-keyboard="false" id="generateDebitNoteCriteriaId" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">
		<form name="generateDebitNotePdf" action="{{ url('payments/debit-notes/generate-debit-note-pdf') }}" method="POST">
			<label for="submit">{{ csrf_field() }}</label>	
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-left">Select Debit Note Type :</h4>
				</div>
				<div class="modal-body">		
					<div class="radio text-left">
						<label><input type="radio" ng-model="downloadType" name="downloadType" value="1">With Header</label>
					</div>
					<div class="radio text-left">
						<label><input type="radio" ng-model="downloadType" name="downloadType" value="2">Without Header</label>
					</div>
									
				</div>
				<div class="modal-footer">
					<span ng-if="{{(defined('IS_ADMIN') && IS_ADMIN) || (defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR)}}">
						<input type="hidden" ng-value="[[debit_note_id]]" name="debit_note_id" ng-model="debit_note_id">
						<input type="submit" formtarget="_blank" name="generate_debit_note_pdf" value="Generate PDF" class="btn btn-primary">
					</span>

					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
				</div>
			</div>
		</form>
	</div>
</div>
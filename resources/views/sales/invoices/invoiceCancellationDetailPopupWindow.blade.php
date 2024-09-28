<!-- Modal -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="invoiceCancellationDetailPopupWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">	
		<div class="modal-content">
			<form method="POST" role="form" id="erpInvoiceCancellationDetailPopupWindowForm" name="erpInvoiceCancellationDetailPopupWindowForm" novalidate>
			
				<!--modal-header-->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Cancellation Detail : Invoice No : <span ng-bind="viewInvoiceCancelledData.invoice_no"></span></h4>
				</div>
				<!--/modal-header-->
				
				<!--modal-body-->
				<div class="modal-body custom-pt-scroll">
					
					<!--Description-->
					<div class="col-xs-12 form-group">
						<label class="col-xs-4" for="invoice_cancellation_description">Cancellation Reason:</label>
						<span>[[viewInvoiceCancelledData.invoice_cancellation_description ? viewInvoiceCancelledData.invoice_cancellation_description : '']]</span>
					</div>
					<!--/Description-->
					
					<!--Cancelled Date-->
					<div class="col-xs-12 form-group">
						<label class="col-xs-4" for="invoice_cancelled_date">Cancelled Date:</label>
						<span>[[viewInvoiceCancelledData.invoice_cancelled_date ? viewInvoiceCancelledData.invoice_cancelled_date : '']]</span>
					</div>
					<!--/Cancelled Date-->
					
					<!--Cancelled By-->
					<div class="col-xs-12 form-group">
						<label class="col-xs-4" for="invoice_cancelled_date">Cancelled By:</label>
						<span>[[viewInvoiceCancelledData.invoiceCancelledBy ? viewInvoiceCancelledData.invoiceCancelledBy : '']]</span>
					</div>
					<!--/Cancelled By-->
					
					<!--Approved By-->
					<div class="col-xs-12 form-group">
						<label class="col-xs-4" for="invoice_cancelled_date">Approved By:</label>
						<span>[[viewInvoiceCancelledData.invoice_canc_approved_by ? viewInvoiceCancelledData.invoice_canc_approved_by : '-']]</span>
					</div>
					<!--/Approved By-->
					
					<!--Approved Date-->
					<div class="col-xs-12 form-group">
						<label class="col-xs-4" for="invoice_cancelled_date">Approved Date:</label>
						<span>[[viewInvoiceCancelledData.invoice_canc_approved_date ? viewInvoiceCancelledData.invoice_canc_approved_date : '-']]</span>
					</div>
					<!--/Approved Date-->
					
				</div>					
				<div class="modal-footer">
					<button type="button" class="btn btn-default" class="close" data-dismiss="modal">Close</button>
				</div>					
			</form>
		</div>
	</div>
</div>
<!-- Modal -->
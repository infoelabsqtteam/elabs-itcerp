<!-- Modal -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="invoiceCancellationInputPopupWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">	
		<div class="row modal-content">
			<form method="POST" role="form" id="erpInvoiceCancellationPopupWindowForm" name="erpInvoiceCancellationPopupWindowForm" novalidate>
			
				<!--modal-header-->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Invoice No : <span ng-bind="invoiceCancellation.invoice_no"></span></h4>
				</div>
				<!--/modal-header-->
				
				<!--modal-body-->
				<div class="row modal-body">
				
					<!--Cancellation Type-->
					<div class="col-xs-12 form-group">
						<label for="cancellation_description" style="float: left; width: 100%;">Select Cancellation Type<em class="asteriskRed">*</em></label>
						<div class="col-xs-6 radio text-left">
							<label><input type="radio" ng-required="true" ng-model="cancelledWithRelatedOrders" name="cancelledWithRelatedOrders" value="1">Fully Cancelled</label>
						</div>
						<div class="col-xs-6 radio text-left mT10">
							<label><input type="radio" ng-required="true" ng-model="cancelledWithRelatedOrders" name="cancelledWithRelatedOrders" value="2">Re-Generation</label>
						</div>
					</div>
					<!--/Cancellation Type-->
					
					<!--Reason of Cancellation -->
					<div class="col-xs-12 form-group">
						<label for="cancellation_description">Reason of Cancellation<em class="asteriskRed">*</em></label>
						<textarea
							row="5"
							class="form-control"
							id="cancellation_description"
							ng-model="invoiceCancellation.cancellation_description"
							name="cancellation_description"
							ng-required="true"
							placeholder="Reason of Cancellation"></textarea>
						<span ng-messages="erpInvoiceCancellationPopupWindowForm.cancellation_description.$error" ng-if="erpInvoiceCancellationPopupWindowForm.cancellation_description.$dirty || erpInvoiceCancellationPopupWindowForm.$submitted" role="alert">
							<span ng-message="required" class="error">Reason of Cancellation is required</span>
						</span>
					</div>
					<!--Reason of Cancellation -->
					
					<!--Approved by-->
					<div class="col-xs-6 form-group">
						<label for="invoice_canc_approved_by">Approved by<em class="asteriskRed">*</em></label>
						<input type="text"
							class="form-control"
							id="invoice_canc_approved_by"
							ng-model="invoiceCancellation.invoice_canc_approved_by"
							name="invoice_canc_approved_by"
							ng-required="true"
							placeholder="Approved by">
						<span ng-messages="erpInvoiceCancellationPopupWindowForm.invoice_canc_approved_by.$error" ng-if="erpInvoiceCancellationPopupWindowForm.invoice_canc_approved_by.$dirty || erpInvoiceCancellationPopupWindowForm.$submitted" role="alert">
							<span ng-message="required" class="error">Approved by is required</span>
						</span>
					</div>
					<!--Approved by-->
					
					<!-- Approved date-->
					<div class="col-xs-6 form-group">
						<label for="invoice_canc_approved_date">Approved Date<em class="asteriskRed">*</em></label>
						<div class="input-group date" data-provide="datepicker">
							<input readonly
								type="text"
								id="invoice_canc_approved_date"
								ng-model="invoiceCancellation.invoice_canc_approved_date"
								ng-required="true"
								name="invoice_canc_approved_date"
								class="form-control bgWhite"
								placeholder="Approved Date">
							<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
						</div>   
						<span ng-messages="erpInvoiceCancellationPopupWindowForm.invoice_canc_approved_date.$error" ng-if="erpInvoiceCancellationPopupWindowForm.invoice_canc_approved_date.$dirty  || erpInvoiceCancellationPopupWindowForm.$submitted" role="alert">
							<span ng-message="required" class="error">Approved date is required</span>
						</span>
					</div>
					<!-- /Approved date-->
				</div>					
				<div class="modal-footer">
					<input type="hidden" ng-value="invoiceCancellation.invoice_id" name="invoice_id" ng-model="invoiceCancellation.invoice_id">
					<button type="button" class="btn btn-primary" ng-disabled="erpInvoiceCancellationPopupWindowForm.$invalid" ng-click="funProcessInvoiceCancellation(invoiceCancellation.invoice_id);">Submit</button>
					<button type="button" class="btn btn-default" class="close" data-dismiss="modal">Close</button>
				</div>					
			</form>
		</div>
	</div>
</div>
<!-- Modal -->
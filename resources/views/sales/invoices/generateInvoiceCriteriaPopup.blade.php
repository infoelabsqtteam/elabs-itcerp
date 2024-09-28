<?php
/*****************************************************
*Common Function Configuration File
*Created By  : Praveen-Singh
*Created On  : 15-Dec-2017
*Modified On : 10-Oct-2018,12-July-2019
*Package     : ITC-ERP-PKL
******************************************************/
?>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="generateInvoiceCriteriaId" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">
		<form name="generateInvoicePdf" action="{{ url('sales/invoices/generate-invoice-pdf') }}" method="POST">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-left">Select Invoice Type : </h4>
				</div>
				<div class="modal-body">		
					<div class="radio text-left">
						<label><input type="radio" ng-model="downloadType" name="downloadType" value="1">With Header</label>
					</div>
					<div class="radio text-left">
						<label><input type="radio" ng-model="downloadType" name="downloadType" value="2">Without Header</label>
					</div>
					<div class="checkbox invoice-footer-p1">
						<label><input type="checkbox" ng-checked="invoiceTemplateTypeDiv==2" ng-model="invoiceTemplateType" name="invoiceTemplateType" value="4">State Wise</label>
					</div>
					<div ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}" class="checkbox invoice-footer-p2">
						<label><input type="checkbox" checked="checked" ng-model="sendMailType" name="sendMailType" value="3">Send Mail to Party</label>
					</div>	
					<div ng-if="{{defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR}}" class="checkbox invoice-footer-p2">
						<label><input type="checkbox" checked="checked" ng-model="sendMailType" name="sendMailType" value="3">Send Mail to Party</label>
					</div>
					<div ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}" class="checkbox invoice-footer-p2">
						<label><input type="checkbox" checked="checked" ng-model="sendMailType" name="sendMailType" value="3">Send Mail to Party</label>
					</div>					
				</div>
				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>	
					<span ng-if="{{(defined('IS_ADMIN') && IS_ADMIN) || (defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR) || (defined('IS_DISPATCHER') && IS_DISPATCHER) || (defined('IS_CRM') && IS_CRM)}}">
						<input type="hidden" ng-value="invoiceId" name="invoice_id" ng-model="invoiceId">
						<input type="submit" formtarget="_blank" name="generate_invoice_pdf" value="Generate PDF" class="btn btn-primary">
					</span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
				</div>
			</div>
		</form>
	</div>
</div>
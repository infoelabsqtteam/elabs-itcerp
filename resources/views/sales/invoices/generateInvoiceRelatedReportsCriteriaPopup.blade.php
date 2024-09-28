<?php
/*****************************************************
*Common Function Configuration File
*Created By  : Praveen-Singh
*Created On  : 15-Dec-2017
*Modified On : 10-Oct-2018
*Package     : ITC-ERP-PKL
******************************************************/
?>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="generateRelatedReportsCriteriaId" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" >
	<div class="modal-dialog">
		<form name="generateInvoiceRelatedReportsPdf" id="generateInvoiceRelatedReportsPdf" method="POST" action="{{ url('sales/invoices/generate-invoice-related-reports-pdf') }}">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-left">Select Customer : </h4>
				</div>				
				<div ng-if="viewInvoiceReportsData" class="radio text-left" ng-repeat="(key, value) in viewInvoiceReportsData track by $index">
					<label class="font14">
						<strong><input type="radio" ng-model="downloadType" ng-checked="invoiceReportToContentDiv == $index" name="downloadType" value="[[$index + 1]]" ng-click="funHideShowInvoiceReportToContent($index)"> [[key | removeUnderscores]]</strong>
					</label>
					<div ng-if="invoiceReportToContentDiv == $index" class="pT10 pB10">
						<table>
							<tr ng-repeat="reportingToObj in value">
								<td>
									<span class="pL30 font12"><input type="hidden" ng-value="reportingToObj.order_id" ng-model="reportingToObj.order_id" name="order_id[]"><i class="fa fa-check-square-o"></i>[[reportingToObj.order_no]]</span>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>	
					<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
						<button type="submit" class="btn btn-primary" formtarget="_blank" name="generate_invoice_related_reports_pdf" value="generate_invoice_related_reports_pdf">Generate Related Reports</button>
					</span>
					<span ng-if="{{defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR}}">
						<button type="submit" class="btn btn-primary" formtarget="_blank" name="generate_invoice_related_reports_pdf" value="generate_invoice_related_reports_pdf">Generate Related Reports</button>
					</span>
					<span ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}">
						<button type="submit" class="btn btn-primary" formtarget="_blank" name="generate_invoice_related_reports_pdf" value="generate_invoice_related_reports_pdf">Generate Related Reports</button>
					</span>
					<span>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</span>
				</div>
			</div>
		</form>
			
	</div>
</div>
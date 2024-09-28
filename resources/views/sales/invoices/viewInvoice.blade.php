<div class="row" ng-hide="IsViewInvoiceDetail">

	<!--Invoice Header-->	
	<div class="col-xs-12 pdng-20">
		<div class="text-right"><button type="button" class="btn btn-primary" ng-click="backButton([[backTypeValue]])">Back</button></div>
		<div class="col-sm-12 col-xs-12 header-content" ng-bind-html="headerContent"></div>
	</div>
	<!--/Invoice Header-->
		
	<!--Invoice Body-->	
	<div class="col-xs-12 mT10">
		<div class="col-md-12" ng-if="invoiceTemplateTypeDiv == '1'">
			@include('sales.invoices.viewInvoiceWithoutStateWise')
		</div>
		<div class="col-md-12" ng-if="invoiceTemplateTypeDiv == '2'">
			@include('sales.invoices.viewInvoiceWithStateWise')
		</div>
	</div>
	<!--/Invoice Body-->
	
	<!--Invoice Footer-->
	<div class="col-xs-7 form-group text-center mT30 col-md-offset-3">
		<div class="col-xs-3 form-group">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generateInvoiceCriteriaId">Generate Invoice PDF</button>
		</div>
		<div class="col-xs-4 form-group">
			<button type="submit" class="btn btn-primary" data-toggle="modal" ng-click="funGetReportingToReports('1',[[invoiceId]])" formtarget="_blank" name="generate_invoice_related_reports_pdf" value="generate_invoice_related_reports_pdf">Generate Related Reports</button>
			
		</div>
		<div class="col-xs-1 form-group">
			<button type="button" class="btn btn-primary" ng-click="backButton([[backTypeValue]])">Back</button>
		</div>		
	</div>
	<!--/Invoice Footer-->
	
	<!--generate Invoice Criteria Popup--> 
	@include('sales.invoices.generateInvoiceCriteriaPopup')
	<!--/generate Invoice Criteria Popup-->
	
	<!--generate Invoice Criteria Popup--> 
	@include('sales.invoices.generateInvoiceRelatedReportsCriteriaPopup')
	<!--/generate Invoice Criteria Popup-->
</div>
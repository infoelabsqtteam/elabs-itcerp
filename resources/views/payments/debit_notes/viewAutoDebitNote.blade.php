<div class="row" ng-if="IsViewAutoDebitNoteDetail">

	<!--Invoice Header-->	
	<div class="col-xs-12 pdng-20">
		<div class="text-right"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></div>
		<div class="col-sm-12 col-xs-12 header-content" ng-bind-html="debitNoteHeaders.header_content"></div>
	</div>
	<div class="col-xs-12 pdng-20" style ="margin-top: 10px;">
		<div class="col-sm-12 col-xs-12 text-center"><h2>Debit Note</h2></div>
	</div>
	<!--/Invoice Header-->
	
	<!--Invoice Body-->	
	<div class="col-xs-12 mT10">
		<div class="table-responsive">
			<table class="table table-bordered" style="margin:0;">
				<thead>
					<tr>
						<th colspan="4" rowspan="2">
							<p class="col-md-12"><b>Organisation Name</b></p>
							<p class="col-md-12" style="font-weight:500">[[debitNoteHeaders.customer_name]]</p>
							<p class="col-md-12"><b>Billing Address</b></p>
							<p class="col-md-12" style="font-weight:500">[[debitNoteHeaders.customer_address]]</p>
							<p class="col-md-12" style="font-weight:500">[[debitNoteHeaders.customer_city_name]]<span style="float:right">[[debitNoteHeaders.customer_state_name]]</span></p>
							<p class="col-md-12" style="font-weight:500">{{defined('GSTIN') && !empty(GSTIN) ? GSTIN : 'GSTIN:'}}[[debitNoteHeaders.customer_gst_no]]</p>
						</th>
						<th colspan="2">
							<h4 ng-if="debitNoteHeaders.debit_note_no" class="col-md-12 text-left font16"><strong>Debit Note No.:&nbsp;</strong>[[debitNoteHeaders.debit_note_no]]</h4>
							<h4 ng-if="debitNoteHeaders.debit_note_date" class="col-md-12 text-left font16"><strong>Debit Note Date:&nbsp;</strong>[[debitNoteHeaders.debit_note_date | date : 'dd-MM-yyyy']]</h4>
						</th>
					</tr>
					
					<tr>
						<th colspan="2">
							<h4 class="col-md-12 text-left font16"><strong>Invoice No.:&nbsp;</strong>[[debitNoteHeaders.invoice_no]]</h4>
							<h4 class="col-md-12 text-left font16"><strong>Dated:&nbsp;</strong>[[debitNoteHeaders.invoice_date | date : 'dd-MM-yyyy']]</h4>
						</th>
					</tr>
					
					<tr>
						<th class="text-center" style="width:5%">S.No.</th>
						<th class="text-left" style="width:45%">Sample Name</th>
						<th class="text-center">Batch No.</th>
						<th class="text-center">Report No.</th>
						<th class="text-center">Amount</th>
					 </tr>
				</thead>
			
				<!--If Billing Type is Daily,Monthly,Weekly and Regular-->
				<tbody ng-if="debitNoteHeaders.billing_type != 5" style="min-height: 500px;">
					<tr ng-repeat="debitNoteObj in debitNoteBody track by $index">
						<td class="text-center">[[$index+1]].</td>
						<td class="text-left">[[debitNoteObj.name_of_product]]</td>
						<td class="text-center">[[debitNoteObj.batch_no]]</td>
						<td class="text-center">[[debitNoteObj.order_no]]</td>
						<td class="text-right">[[debitNoteObj.amount]]</td>
					</tr>
				</tbody>
				<!--If Billing Type is Daily,Monthly,Weekly and Regular-->
			
				<!--If Billing Type is PO_Wise-->
				<tbody ng-repeat="(key,debitNoteBodyObj) in debitNoteBody" ng-if="debitNoteHeaders.billing_type == 5" style="min-height: 500px;">
					<tr>
						<td></td>
						<td colspan="4"><strong>[[key]]</strong></td>
					</tr>
					<tr ng-repeat="debitNoteObj in debitNoteBodyObj track by $index">
						<td class="text-center">[[$index+1]].</td>
						<td class="text-left">[[debitNoteObj.name_of_product]]</td>
						<td class="text-center">[[debitNoteObj.batch_no]]</td>
						<td class="text-center">[[debitNoteObj.order_no]]</td>
						<td class="text-right">[[debitNoteObj.amount]]</td>
					</tr>
				</tbody>
				<!--If Billing Type is PO_Wise-->
			
				<tfoot>
					<tr>
						<td colspan="2"><h4>Total Amount (In Words) Rs.</h4><p class="font14">[[debitNoteFooters.net_total_in_words]]</p></td>
						<td colspan="2" class="text-right">
							<h5 class="font14" ng-if="debitNoteFooters.total"><b>Total</b></h5>
							<h5 class="font14" ng-if="debitNoteFooters.discount != 0.00"><b>Discount&nbsp;</b><b ng-if="discountText != 0">[[debitNoteFooters.discount_text]]</b></h5>
							<h5 class="font14" ng-if="debitNoteFooters.net_amount"><b>Net Total</b></h5>
							<h5 class="font14" ng-if="debitNoteFooters.surcharge_amount != 0.00"><b>Surcharge Amount</b></h5>
							<h5 class="font14" ng-if="debitNoteFooters.extra_amount != 0.00"><b>Convenience & Sampling Amount</b></h5>
							<h5 class="font14" ng-if="debitNoteFooters.sgst_rate && debitNoteFooters.sgst_amount"><b>State Goods & Service Tax ([[debitNoteFooters.sgst_rate]]%)</b></h5>
							<h5 class="font14" ng-if="debitNoteFooters.cgst_rate && debitNoteFooters.cgst_amount"><b>Central Goods & Service Tax ([[debitNoteFooters.cgst_rate]]%)</b></h5>
							<h5 class="font14" ng-if="debitNoteFooters.igst_rate && debitNoteFooters.igst_amount"><b>Integrated Goods & Service Tax ([[debitNoteFooters.igst_rate]]%)</b></h5>
						</td>
						<td>
							<h5 class="text-right font14" ng-if="debitNoteFooters.total"><b>[[debitNoteFooters.total]]</b></h5>
							<h5 class="text-right font14" ng-if="debitNoteFooters.discount != 0.00"><b>[[debitNoteFooters.discount]]</b></h5>
							<h5 class="text-right font14" ng-if="debitNoteFooters.net_amount"><b>[[debitNoteFooters.net_amount]]</b></h5>
							<h5 class="text-right font14" ng-if="debitNoteFooters.surcharge_amount != 0.00"><b>[[debitNoteFooters.surcharge_amount]]</b></h5>
							<h5 class="text-right font14" ng-if="debitNoteFooters.extra_amount != 0.00"><b>[[debitNoteFooters.extra_amount]]</b></h5>
							<h5 class="text-right font14" ng-if="debitNoteFooters.sgst_rate && debitNoteFooters.sgst_amount"><b>[[debitNoteFooters.sgst_amount]]</b></h5>
							<h5 class="text-right font14" ng-if="debitNoteFooters.cgst_rate && debitNoteFooters.cgst_amount"><b>[[debitNoteFooters.cgst_amount]]</b></h5>
							<h5 class="text-right font14" ng-if="debitNoteFooters.igst_rate && debitNoteFooters.igst_amount"><b>[[debitNoteFooters.igst_amount]]</b></h5>
						</td>
					</tr>
					
					<tr>
						<td colspan="2">
							<h5 ng-if="debitNoteHeaders.debit_note_remark" class="text-left font16"><b>Remarks for Debit Note: </b></h5>
							<div ng-if="debitNoteHeaders.debit_note_remark" class="col-md-12 font14 break-word mT5">[[debitNoteHeaders.debit_note_remark]]</div>
						</td>	
						<td colspan="2">
							<h5 class="text-right font14"><b>GRAND TOTAL</b></h5>
						</td>
						<td>
							<h5 class="text-right font14"><b>[[debitNoteFooters.net_total]]</b></h5>
						</td>
					</tr>
					
					<tr>
						<td colspan="2" class="font11">
							<p class="col-md-12"><b>HSB Code-998346(Technical Testing & Analysis Services)</b></p>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><b class="small">Bank Details</b></div>
								<div class="col-md-9 small"><b>(for all services mentioned above)</b></div>
							</div>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><b class="small">Account Name</b></div>
								<div class="col-md-9 small">:- {{defined('ACCOUNT_NAME') && !empty(ACCOUNT_NAME) ? ucwords(ACCOUNT_NAME) : '-'}}</div>
							</div>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><b class="small">Bank Name</b></div>
								<div class="col-md-9 small">:- {{defined('BANK_NAME') && !empty(BANK_NAME) ? ucwords(BANK_NAME) : '-'}}</div>
							</div>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><b class="small">Bank A/C</b></div>
								<div class="col-md-9 small">:- {{defined('BANK_ACCOUNT_NUMBER') && !empty(BANK_ACCOUNT_NUMBER) ? ucwords(BANK_ACCOUNT_NUMBER) : '-'}}</div>
							</div>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><b class="small">IFSC Code</b></div>
								<div class="col-md-9 small">:- {{defined('IFSC_CODE') && !empty(IFSC_CODE) ? ucwords(IFSC_CODE) : '-'}}</div>
							</div>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><b class="small">Branch Address</b></div>
								<div class="col-md-9 small">:- {{defined('BRANCH_ADDRESS') && !empty(BRANCH_ADDRESS) ? ucwords(BRANCH_ADDRESS) : '-'}}</div>
							</div>
						</td>
						
						<td rowspan="2" colspan="3">
							<h5 class="text-right"><b>For INTERSTELLAR TESTING CENTRE PVT. LTD.</b></h5>
							<div style="font-size:10px;margin-top: 150px;float:right!important;">
							     <p ng-if="debitNoteHeader.user_signature" style="float: right; width: 100%; padding-left: 21px;"><img style="height: 30px; width: 100px;" alt="[[debitNoteHeader.invoice_by]]" ng-src="[[debitNoteHeader.user_sign_path]][[debitNoteHeader.user_signature]]"/></p>
							     <p style="margin-top:30px!important;vertical-align: bottom;font-size:13px!important;"><b>Authorised Signatory</b></p>
							</div> 
						</td>
					</tr>
					
					<tr>
						<td colspan="2" class="font11">
							<h5 class="small" style="padding-left: 23px;"><b>Terms & Conditions</b></h5>
							<ul class="unstyled-list t_c_invoice">
								<li class="small">Interest @ 2 % p.m. will be charged if the bill is not paid within 30 days.</li>
								<li class="small">All payments to be made through at par cheques/Drafts.</li>
								<li class="small">Unless otherwise stated. tax on this invoice is not payee under reverse charge</li>
							</ul>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<!--/Invoice Body-->
	
	<!--Invoice Footer-->
	<div class="col-xs-12 form-group text-center mT30 col-md-offset-3">
		
		<div class="col-xs-6 form-group">
			<button type="button" class="btn btn-primary"   data-toggle="modal" data-target="#generateDebitNoteCriteriaId">Generate</button>
			<button type="button" class="btn btn-primary" ng-click="backButton()">Back</button>
		</div>
			
		<!--generate Invoice Criteria Popup--> 
		@include('payments.debit_notes.generateDebitNoteCriteriaPopup')
		<!--/generate Invoice Criteria Popup-->
	</div>
	<!--/Invoice Footer-->
</div>
<div class="row" ng-if="IsViewManualCreditNoteDetail">

	<!--Invoice Header-->	
	<div class="col-xs-12 pdng-20">
		<div class="text-right"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></div>
		<div class="col-sm-12 col-xs-12 header-content" ng-bind-html="creditNoteHeaders.header_content"></div>
	</div>
	<div class="col-xs-12 pdng-20" style ="margin-top: 10px;">
		<div class="col-sm-12 col-xs-12 text-center"><h2>Credit Note</h2></div>
	</div>
	<!--/Invoice Header-->
	
	<!--Invoice Body-->	
	<div class="col-xs-12 mT10">
		<div class="table-responsive">
			<table class="table table-bordered" style="margin:0;">
				<thead>
					<tr>
						<th colspan="4" rowspan="2">
							<p class="col-md-12"><strong>Organisation Name</strong></p>
							<p class="col-md-12" style="font-weight:500">[[creditNoteHeaders.customer_name]]</p>
							<p class="col-md-12"><strong>Billing Address</strong></p>
							<p class="col-md-12" style="font-weight:500">[[creditNoteHeaders.customer_address]]</p>
							<p class="col-md-12" style="font-weight:500">[[creditNoteHeaders.customer_city_name]]<span style="float:right">[[creditNoteHeaders.customer_state_name]]</span></p>
							<p class="col-md-12" style="font-weight:500">{{defined('GSTIN') && !empty(GSTIN) ? GSTIN : 'GSTIN:'}}[[creditNoteHeaders.customer_gst_no]]</p>
						</th>
						<th colspan="2">
							<h4 ng-if="creditNoteHeaders.credit_note_no" class="col-md-12 text-left font16"><strong>Credit Note No.:&nbsp;</strong>[[creditNoteHeaders.credit_note_no]]</h4>
							<h4 ng-if="creditNoteHeaders.credit_note_date" class="col-md-12 text-left font16"><strong>Credit Note Date:&nbsp;</strong>[[creditNoteHeaders.credit_note_date | date : 'dd-MM-yyyy']]</h4>
						</th>
					</tr>
					<tr>
						<th colspan="2">
							<p class="col-md-12 text-left font16"><strong>Reference No./Invoice No.</strong></p>
							<h4 ng-if="creditNoteHeaders.invoice_no" class="col-md-12 text-left font16">[[creditNoteHeaders.invoice_no]]</h4>
							<h4 ng-if="creditNoteHeaders.invoice_date" class="col-md-12 text-left font16"><strong>Dated:&nbsp;</strong>[[creditNoteHeaders.invoice_date | date : 'dd-MM-yyyy']]</h4>
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
				<tbody style="min-height: 500px;">
					<tr ng-repeat="creditNoteObj in creditNoteBody track by $index">
						<td class="text-center">[[$index+1]].</td>
						<td class="text-left">[[creditNoteObj.name_of_product]]</td>
						<td class="text-center">[[creditNoteObj.batch_no]]</td>
						<td class="text-center">[[creditNoteObj.order_no]]</td>
						<td class="text-right">[[creditNoteObj.amount]]</td>
					</tr>
				</tbody>
				<!--If Billing Type is Daily,Monthly,Weekly and Regular-->
				
				<tfoot>					
					<tr>
						<td colspan="2"><h4>Total Amount (In Words) Rs.</h4><p class="font14">[[creditNoteFooters.net_total_in_words]]</p></td>
						<td colspan="2" class="text-right">
							<h5 class="font14" ng-if="creditNoteFooters.total"><strong>Total</strong></h5>
							<h5 class="font14" ng-if="creditNoteFooters.sgst_rate && creditNoteFooters.sgst_amount"><strong>State Goods & Service Tax ([[creditNoteFooters.sgst_rate]]%)</strong></h5>
							<h5 class="font14" ng-if="creditNoteFooters.cgst_rate && creditNoteFooters.cgst_amount"><strong>Central Goods & Service Tax ([[creditNoteFooters.cgst_rate]]%)</strong></h5>
							<h5 class="font14" ng-if="creditNoteFooters.igst_rate && creditNoteFooters.igst_amount"><strong>Integrated Goods & Service Tax ([[creditNoteFooters.igst_rate]]%)</strong></h5>
						</td>
						<td>
							<h5 class="text-right font14" ng-if="creditNoteFooters.total"><strong>[[creditNoteFooters.total]]</strong></h5>
							<h5 class="text-right font14" ng-if="creditNoteFooters.sgst_rate && creditNoteFooters.sgst_amount"><strong>[[creditNoteFooters.sgst_amount]]</strong></h5>
							<h5 class="text-right font14" ng-if="creditNoteFooters.cgst_rate && creditNoteFooters.cgst_amount"><strong>[[creditNoteFooters.cgst_amount]]</strong></h5>
							<h5 class="text-right font14" ng-if="creditNoteFooters.igst_rate && creditNoteFooters.igst_amount"><strong>[[creditNoteFooters.igst_amount]]</strong></h5>
						</td>
					</tr>
					
					<tr>
						<td colspan="2" align="left">
							<h5 ng-if="creditNoteHeaders.credit_note_remark" class="text-left font16"><strong>Remarks for Credit Note: </strong></h5>
							<div ng-if="creditNoteHeaders.credit_note_remark" class="col-md-12 font14 break-word mT5">[[creditNoteHeaders.credit_note_remark]]</div>
						</td>						
						<td colspan="2">
							<h5 class="text-right font14"><strong>GRAND TOTAL</strong></h5>
						</td>						
						<td>
							<h5 class="text-right font14"><strong>[[creditNoteFooters.net_total]]</strong></h5>
						</td>
					</tr>
					
					<tr>
						<td colspan="2" class="font11">
							<p class="col-md-12"><strong>HSB Code-998346(Technical Testing & Analysis Services)</strong></p>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><strong class="small">Bank Details</strong></div>
								<div class="col-md-9 small"><strong>(for all services mentioned above)</strong></div>
							</div>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><strong class="small">Account Name</strong></div>
								<div class="col-md-9 small">:- {{defined('ACCOUNT_NAME') && !empty(ACCOUNT_NAME) ? ucwords(ACCOUNT_NAME) : '-'}}</div>
							</div>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><strong class="small">Bank Name</strong></div>
								<div class="col-md-9 small">:- {{defined('BANK_NAME') && !empty(BANK_NAME) ? ucwords(BANK_NAME) : '-'}}</div>
							</div>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><strong class="small">Bank A/C</strong></div>
								<div class="col-md-9 small">:- {{defined('BANK_ACCOUNT_NUMBER') && !empty(BANK_ACCOUNT_NUMBER) ? ucwords(BANK_ACCOUNT_NUMBER) : '-'}}</div>
							</div>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><strong class="small">IFSC Code</strong></div>
								<div class="col-md-9 small">:- {{defined('IFSC_CODE') && !empty(IFSC_CODE) ? ucwords(IFSC_CODE) : '-'}}</div>
							</div>
							<div class="col-md-12" style="padding:0">
								<div class="col-md-3"><strong class="small">Branch Address</strong></div>
								<div class="col-md-9 small">:- {{defined('BRANCH_ADDRESS') && !empty(BRANCH_ADDRESS) ? ucwords(BRANCH_ADDRESS) : '-'}}</div>
							</div>
						</td>
							
						<td rowspan="2" colspan="3">
							<h5 class="text-right"><strong>For INTERSTELLAR TESTING CENTRE PVT. LTD.</strong></h5>
							<div style="font-size:10px;margin-top: 130px;float:right!important;">
							     <p ng-if="creditNoteHeaders.user_signature && creditNoteHeaders.user_sign_path" style="float: right; width: 100%; padding-left: 21px;"><img style="height: 30px; width: 100px;" alt="[[creditNoteHeaders.invoice_by]]" ng-src="[[creditNoteHeaders.user_sign_path]][[creditNoteHeaders.user_signature]]"/></p>
							     <p style="margin-top:30px!important;vertical-align: bottom;font-size:13px!important;"><strong>Authorised Signatory</strong></p>
							</div> 
						</td>
					</tr>
					
					<tr>
						<td colspan="2" class="font11">
							<h5 class="small" style="padding-left: 13px;"><strong>Terms & Conditions</strong></h5>
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
		
		<div class="col-xs-6 form-group text-center">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generateCreditNoteCriteriaId">Generate</button>
			<button type="button" class="btn btn-primary" ng-click="backButton()">Back</button>
		</div>
			
		<!--generate Invoice Criteria Popup--> 
		@include('payments.credit_notes.generateCreditNoteCriteriaPopup')
		<!--/generate Invoice Criteria Popup-->
	</div>
	<!--/Invoice Footer-->
</div>
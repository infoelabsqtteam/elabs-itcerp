<div class="table-responsive">
	<table class="table table-bordered" style="margin:0;">
		<thead>
			<tr>
			      <th colspan="3" rowspan="2">
				       <p class="col-md-12"><b>Organisation Name</b></p>
				       <p class="col-md-12" style="font-weight:500;">[[customerName]]</p>
				       <p class="col-md-12"><b>Billing Address</b></p>
				       <p class="col-md-12" style="font-weight:500;">[[customerAddress]]</p>
				       <p class="col-md-12" style="font-weight:500;">[[customerCityName]]<span style="padding-left:20px!important;">[[customerStateName]]</span></p>
				       <p class="col-md-12" style="font-weight:500;">{{defined('GSTIN') && !empty(GSTIN) ? GSTIN : 'GSTIN:'}}[[customerGstNo]]</p>
			      </th>
			      <th colspan="2">
				      <p class="col-md-12 text-center"><b>Invoice No.</b></p>
				      <h4  class="col-md-12 text-center" title="[[divisionName]]/[[pCategoryName]]">[[invoiceNo]]</h4>
			      </th>
			</tr>
			<tr>
			      <th colspan="2">
				      <p class="col-md-12 text-center"><b>Dated</b></p>
				      <h4  class="col-md-12 text-center">[[invoiceDate | date : 'dd-MM-yyyy']]</h4>
			      </th>
			</tr>
			<tr>
			      <th class="text-center" style="width:5%">S.No.</th>
			      <th class="text-left" style="width:45%">Name of the Sample</th>
			      <th class="text-center">Batch No.</th>
			      <th class="text-center">Report No.</th>
			      <th class="text-center">Amount</th>
		       </tr>
		</thead>
		
		<!--If Billing Type is Daily,Monthly,Weekly and Regular-->
		<tbody ng-if="billingType != 5" style="min-height: 500px;">
			<tr ng-repeat="invoiceBodyObj in invoiceBody track by $index">
				<td class="text-center">[[$index+1]].</td>
				<td class="text-left">[[invoiceBodyObj.name_of_product]]</td>
				<td class="text-center">[[invoiceBodyObj.batch_no]]</td>
				<td class="text-center" title="[[invoiceBodyObj.order_no]]">[[invoiceBodyObj.report_no]]</td>
				<td class="text-right">[[invoiceBodyObj.amount]]</td>
			</tr>
		</tbody>
		<!--If Billing Type is Daily,Monthly,Weekly and Regular-->
		
		<!--If Billing Type is PO_Wise-->
		<tbody ng-repeat="(key,invoiceObj) in invoiceBody" ng-if="billingType == 5" style="min-height: 500px;">
			<tr>
				<td></td>
				<td colspan="4"><strong>[[key]]</strong></td>
			</tr>
			<tr ng-repeat="invoiceBodyObj in invoiceObj track by $index">
				<td class="text-center">[[$index+1]].</td>
				<td class="text-left">[[invoiceBodyObj.name_of_product]]</td>
				<td class="text-center">[[invoiceBodyObj.batch_no]]</td>
				<td class="text-center" title="[[invoiceBodyObj.order_no]]">[[invoiceBodyObj.report_no]]</td>
				<td class="text-right">[[invoiceBodyObj.amount]]</td>	
			</tr>
		</tbody>
		<!--If Billing Type is PO_Wise-->
		
		<tfoot>
			<tr style="height:100px">
				<td class="text-center"></td>
				<td class="text-left"></td>
				<td class="text-center"></td>
				<td class="text-center"></td>
				<td class="text-right"></td>
			</tr>
				
			<tr>
				<td colspan="2"><h4>Total Amount (In Words) Rs.</h4><p>[[netTotalInWord]]</p></td>
				<td colspan="2" class="text-right">
					<h5><b>Total</b></h5>
					<h5 ng-if="discount != 0.00"><b>Discount&nbsp;</b><b ng-if="discountText != 0">[[discountText]]</b></h5>
					<h5 ng-if="netAmount"><b>Net Total</b></h5>
					<h5 ng-if="surchargeAmount != 0.00"><b>Surcharge Amount</b></h5>
					<h5 ng-if="extraAmount != 0.00"><b>Convenience & Sampling Amount</b></h5>
					<h5 ng-if="sgstRate && sgstAmount"><b>State Goods & Service Tax ([[sgstRate]]%)</b></h5>
					<h5 ng-if="cgstRate && cgstAmount"><b>Central Goods & Service Tax ([[cgstRate]]%)</b></h5>
					<h5 ng-if="igstRate && igstAmount"><b>Integrated Goods & Service Tax ([[igstRate]]%)</b></h5>
				</td>
				<td>
					<h5 class="text-right"><b>[[total]]</b></h5>
					<h5 class="text-right" ng-if="discount != 0.00"><b>[[discount]]</b></h5>
					<h5 class="text-right" ng-if="netAmount"><b>[[netAmount]]</b></h5>
					<h5 class="text-right" ng-if="surchargeAmount != 0.00"><b>[[surchargeAmount]]</b></h5>
					<h5 class="text-right" ng-if="extraAmount != 0.00"><b>[[extraAmount]]</b></h5>
					<h5 class="text-right" ng-if="sgstRate && sgstAmount"><b>[[sgstAmount]]</b></h5>
					<h5 class="text-right" ng-if="cgstRate && cgstAmount"><b>[[cgstAmount]]</b></h5>
					<h5 class="text-right" ng-if="igstRate && igstAmount"><b>[[igstAmount]]</b></h5>
				</td>
			</tr>
			
			<tr>
				<td rowspan="2" colspan="2" style="font-size:13px!important;">
					<p class="col-md-12"><b>SAC Code-998346(Technical Testing & Analysis Services)</b></p>
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
				<td colspan="2">
					<h5 class="text-right"><b>GRAND TOTAL</b></h5>
				</td>
				<td>
					<h5 class="text-right"><b>[[netTotal]]</b></h5>
				</td>
			</tr>
			<tr>
			   <td rowspan="2" colspan="3">
				   <h5 class="text-right"><b>For INTERSTELLAR TESTING CENTRE PVT. LTD.</b></h5>
				   <div style="font-size:10px;margin-top: 150px;float:right!important;">
					<p ng-if="InvoicerSign" style="float: right; width: 100%; padding-left: 21px;"><img style="height: 30px; width: 100px;" alt="[[invoiceBy]]" title="[[invoiceBy]]" ng-src="[[userSignPath]][[InvoicerSign]]"/></p>
					<p style="margin-top:30px!important;vertical-align: bottom;font-size:13px!important;"><b>Authorised Signatory</b></p>
				   </div> 
			   </td>
			</tr>
			 <tr>
			   <td colspan="2">
				<h5 class="small" style="padding-left: 23px;"><b>Terms & Conditions</b></h5>
				<ul class="unstyled-list t_c_invoice">
					<li class="small">Interest @ 2 % p.m. will be charged if the bill is not paid within 30 days.</li>
					<li class="small">All payments to be made through at par cheques/Drafts.</li>
					<li class="small">Unless otherwise stated. tax on this invoice is not payee under reverse charge</li>
					<li class="small">We are falling under MSMED Act. Please release the payment within 45 days or else Compound interest @12.75% P.A will be chargeable</li>
				</ul>
			   </td>
			</tr>
		</tfoot>
	</table>
</div>
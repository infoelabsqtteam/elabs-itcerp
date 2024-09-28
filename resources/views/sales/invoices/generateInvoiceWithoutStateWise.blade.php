<?php
/****************************************
* Invoice Generation Blade without State Wise
* CreatedOn : 30-June-2018
* ModifiedOn : 30-June-2018
* Author : Praveen Singh
*******************************************/
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
@page{margin: 200px 20px 20px;font-size:13px;}
.page-break-always{ page-break-after: always;}
.page-break-auto{ page-break-after:auto;}
#header {left: 0;position: fixed;right: 0;text-align: center;top: -190px;width: 100%;height:auto;}
#footer {left: 0;position: fixed;right: 0;bottom: 0px;width: 100%;height:170px;padding:0px!important;margin:0px!important;}
#content {bottom: 0;height: auto;left: 0;right: 0;width: 100%;}
#footer .page:after { content: counter(page, upper-roman); }
.page-break {page-break-before: always;}
td table td {border-bottom: 0px!important;}
p{padding:2!important;margin:0!important;}
h5{font-size:13px!important;overflow-wrap: break-word;word-wrap: break-word;}
.pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
.pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:3px 2px!important;vertical-align: middle;}
.header-inv-content table,footer-inv-content table{margin:0 auto;border-collapse:collapse;}
.left-inv-content{border:0px none!important;vertical-align:top;}
.middle-inv-content{font-size:13px;margin:3px;font-weight:700;color:#4d64a1;border:0px noneimportant;text-align:center!important;padding:0px;margin:0px;}
.middle-inv-content > h3 {font-size:24px!important;font-family:times of romen;font-weight:600;color:#4d64a1;margin:0}
.middle-inv-content > h5 {margin: 2px !important;padding: 0 !important;}
.right-inv-content{border:0!important;width: 20%;border: 0px!important;font-size: 13px!important;color: #4d64a1;padding-left:60px!important;}
.bottom-inv-content{border:0px none!important;margin:0px!important;}
.bottom-inv-content td span{width:auto;border:1px solid;padding:9px!important;font-weight:bold;}
</style>
<!--- header-->
<div id="header">
	
	<table width="100%" style="margin:0 auto;border-collapse:collapse;">
		<thead>
			<tr>
				<td width="10%"></td>
				<td width="70%"></td>
				<td width="20%" align="center" style="border:0!important;border:0px!important;font-size:12px!important;color:#4d64a1;">
					<p style="text-align:center !important;color: #4d64a1;">
					<script type='text/php'>
					if(isset($pdf)){ 
						$font  = $fontMetrics->get_font('serif','bold');
						$text  = "Page {PAGE_NUM} of {PAGE_COUNT}";
						$size  = 11;
						$color = array(0,0,0);
						$y     = $pdf->get_height() - 835;
						$x     = $pdf->get_width() - 60 - $fontMetrics->get_text_width('1/1', $font, $size);
						$pdf->page_text($x, $y, $text, $font, $size, $color);
					}
					</script>
					</p>
				</td>
			</tr>
		</thead>
	</table>
	
	<div class="header-inv-content"@if(!empty($hasContainHeaderFooter) && $hasContainHeaderFooter == '2')style="visibility: hidden;"@endif>
		<?php echo !empty($viewData['invoiceHeader']['header_content']) ? htmlspecialchars_decode($viewData['invoiceHeader']['header_content']) : '';?>
	</div>
</div>
<!--- /header-->

<!--- footer-->
<div id="footer" class="footer-inv-content">
	<?php echo !empty($viewData['invoiceHeader']['footer_content']) ? htmlspecialchars_decode($viewData['invoiceHeader']['footer_content']) : '';?>
</div>
<!--- /footer-->

<body>	
	<!--content-->
	<div id="content">
		<div class="page-break-auto">			
			<table border="1" class="pdftable" width="100%" style="margin:0;border-collapse:collapse">
				<tbody>
					<tr>
						<td colspan="3" rowspan="2">
							<p style="margin-left:15px;"><b>Organisation Name</b></p>
							<p style="font-weight:500;margin-left: 15px;">{{$viewData['invoiceHeader']['customer_name']}}</p>
							<p style="margin-left:15px;"><b>Billing Address</b></p>
							<p style="margin-left:15px;">{{$viewData['invoiceHeader']['customer_address']}}</p>
							<p style="font-weight:500;margin-left: 15px;">{{$viewData['invoiceHeader']['customer_city_name']}}<span style="padding-left:20px!important;">{{$viewData['invoiceHeader']['customer_state_name']}}</span></p>
							<p style="font-weight:500;margin-left: 15px;">{{ defined('GSTIN') && !empty(GSTIN) ? GSTIN : 'GSTIN:'}}{{$viewData['invoiceHeader']['customer_gst_no']}}</p>
						</td>
						<th colspan="2" style="text-align:center!important">
							<p><b>Invoice No.</b></p>
							<h4 style="margin: 0px;">{{$viewData['invoiceHeader']['invoice_no']}}</h4>
						</th>
					</tr>
					<tr>
						<th colspan="2" style="text-align:center!important">
							<p><b>Dated</b></p>
							<h4 style="margin: 0px;">{{date('d-m-Y',strtotime($viewData['invoiceHeader']['invoice_date']))}}</h4>
						</th>
					</tr>
					<tr>
						<th width="3%!important;" align="center">S.No.</th>
						<th width="30%!important;" align="left">Sample Name</th>
						<th width="20%!important;" align="left">Batch No.</th>
						<th width="8%!important;" align="left">Report No.</th>
						<th width="10%!important;" align="right">Amount</th>
					</tr>
					@if(!empty($viewData['invoiceBody']))
						@if(!empty($viewData['invoiceHeader']['billing_type']) && $viewData['invoiceHeader']['billing_type'] == '5')
							@foreach($viewData['invoiceBody'] as $PONumberKey => $invoiceBodyPoAll)
								<tr>
									<td align="center" style="font-size:11px!important;overflow-wrap: break-word;word-wrap: break-word;"></td>
									<td align="left" colspan="4" style="font-size:11px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;">{{$PONumberKey}}</td>
								</tr>
								@if(!empty($invoiceBodyPoAll) && is_array($invoiceBodyPoAll))
									@foreach($invoiceBodyPoAll as $key => $invoiceBody)
										<tr>
											<td align="center" style="font-size:12px!important;overflow-wrap:break-word;word-wrap: break-word;">{{$key+1}}</td>
											<td align="left" style="font-size:12px!important;overflow-wrap:break-word;word-wrap: break-word;">{{$invoiceBody['name_of_product']}}</td>
											<td align="left" style="font-size:12px!important;overflow-wrap:break-word;word-wrap: break-word;">{{$invoiceBody['batch_no']}}</td>
											<td align="left" style="font-size:11px!important;overflow-wrap:break-word;word-wrap: break-word;">{{$invoiceBody['report_no']}}</td>
											<td align="right" style="font-size:12px!important;overflow-wrap:break-word;word-wrap: break-word;">{{$invoiceBody['amount']}}</td>
										</tr>
									@endforeach
								@endif
							@endforeach
						@else
							@foreach($viewData['invoiceBody'] as $key => $invoiceBody)
								<tr>
								      <td align="center" style="font-size:12px!important;overflow-wrap:break-word;word-wrap: break-word;">{{$key+1}}</td>
								      <td align="left" style="font-size:12px!important;overflow-wrap:break-word;word-wrap: break-word;">{{$invoiceBody['name_of_product']}}</td>
								      <td align="left" style="font-size:12px!important;overflow-wrap:break-word;word-wrap: break-word;">{{$invoiceBody['batch_no']}}</td>
								      <td align="left" style="font-size:11px!important;overflow-wrap:break-word;word-wrap: break-word;">{{$invoiceBody['report_no']}}</td>
								      <td align="right" style="font-size:12px!important;overflow-wrap:break-word;word-wrap: break-word;">{{$invoiceBody['amount']}}</td>
								</tr>						    
							@endforeach
						@endif
					@endif                         
					<tr>
						<td colspan="2" style="vertical-align: top;">
							<h4 style="padding: 5px; margin: 0px;">Total Amount (In Words) Rs.</h4>
							<p>{{ucwords($viewData['invoiceFooter']['net_total_in_words'])}}</p>
						</td>
						<td colspan="2" style="text-align:right!important">
							<h5 style="padding: 5px; margin: 0px ! important;"><b>Total</b></h5>
							@if(!empty(round($viewData['invoiceFooter']['discount'])))
							<h5 style="padding: 5px; margin: 0px ! important;">
							    <b>Discount</b>
							    @if(!empty($viewData['invoiceFooter']['discount_text']))
							    <b>{{$viewData['invoiceFooter']['discount_text']}}</b>
							    @endif
							</h5>
							@endif
							@if(!empty(round($viewData['invoiceFooter']['net_amount'])))
							<h5 style="padding: 5px; margin: 0px ! important;"><b>Net Total</b></h5>
							@endif
							@if(!empty(round($viewData['invoiceFooter']['surcharge_amount'])))
							<h5 style="padding: 5px; margin: 0px ! important;"><b>Surcharge Amount</b></h5>
							@endif
							@if(!empty(round($viewData['invoiceFooter']['extra_amount'])))
							<h5 style="padding: 5px; margin: 0px ! important;"><b>Convenience & Sampling Amount</b></h5>
							@endif
							@if(!empty($viewData['invoiceFooter']['sgst_rate']) && !empty($viewData['invoiceFooter']['sgst_amount']))
							<h5 style="padding: 5px; margin: 0px ! important;"><b>State Goods & Service Tax ({{$viewData['invoiceFooter']['sgst_rate']}}%)</b></h5>
							@endif
							@if(!empty($viewData['invoiceFooter']['cgst_rate']) && !empty($viewData['invoiceFooter']['cgst_amount']))
							<h5 style="padding: 5px; margin: 0px ! important;"><b>Central Goods & Service Tax ({{$viewData['invoiceFooter']['cgst_rate']}}%)</b></h5>
							@endif
							@if(!empty($viewData['invoiceFooter']['igst_rate']) && !empty($viewData['invoiceFooter']['igst_amount']))
							<h5 style="padding: 5px; margin: 0px ! important;"><b>Integrated Goods & Service Tax ({{$viewData['invoiceFooter']['igst_rate']}}%)</b></h5>
							@endif
						</td>
						<td>
							<h5 style="padding: 5px; margin: 0px ! important;text-align:right!important;"><b>{{$viewData['invoiceFooter']['total']}}</b></h5>
							@if(!empty(round($viewData['invoiceFooter']['discount'])))
							<h5 style="padding: 5px; margin: 0px ! important;text-align:right!important;"><b>{{$viewData['invoiceFooter']['discount']}}</b></h5>
							@endif
							@if(!empty(round($viewData['invoiceFooter']['net_amount'])))
							<h5 style="padding: 5px; margin: 0px ! important;text-align:right!important;"><b>{{$viewData['invoiceFooter']['net_amount']}}</b></h5>
							@endif
							@if(!empty(round($viewData['invoiceFooter']['surcharge_amount'])))
							<h5 style="padding: 5px; margin: 0px ! important;text-align:right!important;"><b>{{$viewData['invoiceFooter']['surcharge_amount']}}</b></h5>
							@endif
							@if(!empty(round($viewData['invoiceFooter']['extra_amount'])))
							<h5 style="padding: 5px; margin: 0px ! important;text-align:right!important;"><b>{{$viewData['invoiceFooter']['extra_amount']}}</b></h5>
							@endif
							@if(!empty($viewData['invoiceFooter']['sgst_rate']) && !empty($viewData['invoiceFooter']['sgst_amount']))
							<h5 style="padding: 5px; margin: 0px ! important;text-align:right!important;"><b>{{$viewData['invoiceFooter']['sgst_amount']}}</b></h5>
							@endif
							@if(!empty($viewData['invoiceFooter']['cgst_rate']) && !empty($viewData['invoiceFooter']['cgst_amount']))
							<h5 style="padding: 5px; margin: 0px ! important;text-align:right!important;"><b>{{$viewData['invoiceFooter']['cgst_amount']}}</b></h5>
							@endif
							@if(!empty($viewData['invoiceFooter']['igst_rate']) && !empty($viewData['invoiceFooter']['igst_amount']))
							<h5 style="padding: 5px; margin: 0px ! important;text-align:right!important;"><b>{{$viewData['invoiceFooter']['igst_amount']}}</b></h5>
							@endif
						</td>
					</tr>
					<tr>
						<td colspan="2"></td>
						<td align="right" colspan="2">
							<b style="text-align:right!important">GRAND TOTAL</b>
						</td>
						<td align="right" colspan="1">
							<b style="text-align:right!important;"><b>{{$viewData['invoiceFooter']['net_total']}}</b>
						</td>
					</tr>
					
					<tr>
						<td colspan="2" style="border:1px solid">
							<table class="pdftable" width="100%" style="margin:0;">
								<tr>
									<td width="100%" align="left" colspan="2"><b style="vertical-align: middle; font-size: 12px!important;padding:0px!important;margin:0px!important;">SAC Code-998346(Technical Testing & Analysis Services)</b></td>
								</tr> 
								<tr>
									<td colspan="2" width="100%" align="left"><b style="vertical-align: middle; font-size: 10px!important;padding:0px!important;margin:0px!important;">Bank Details (for all services mentioned above)</b></td>
								</tr>
								<tr>
									<td style="float: left; width: 60px;" align="left"><b style="vertical-align: middle; font-size: 10px!important;padding:0px!important;margin:0px!important;">Account Name</b></td>
									<td style="float: left; width: 74%;" align="left"><span style="vertical-align: middle; font-size: 10px!important;padding:0px!important;margin:0px!important;">: <span style="margin-left: 5px;">{{defined('ACCOUNT_NAME') && !empty(ACCOUNT_NAME) ? ucwords(ACCOUNT_NAME) : '-'}}</span> </span></td>
								</tr>
								<tr>
									<td style="float: left; width: 60px;" align="left"><b style="vertical-align: middle; font-size: 10px!important;padding:0px!important;margin:0px!important;">Bank Name</b></td>
									<td style="float: left; width: 74%;" align="left"><span style="vertical-align: middle; font-size: 10px!important;padding:0px!important;margin:0px!important;">: <span style="margin-left: 5px;">{{defined('BANK_NAME') && !empty(BANK_NAME) ? ucwords(BANK_NAME) : '-'}}</span> </span></td>
								</tr>
								<tr>
									<td style="float: left; width: 60px;" align="left"><b style="vertical-align: middle; font-size: 10px!important;padding:0px!important;margin:0px!important;">Bank A/C</b></td>
									<td style="float: left; width: 74%;" align="left"><span style="vertical-align: middle; font-size: 10px!important;padding:0px!important;margin:0px!important;">: <span style="margin-left: 5px;">{{defined('BANK_ACCOUNT_NUMBER') && !empty(BANK_ACCOUNT_NUMBER) ? ucwords(BANK_ACCOUNT_NUMBER) : '-'}}</span></span></td>
								</tr>
								<tr>
									<td style="float: left; width: 60px;" align="left"><b style="vertical-align: middle; font-size: 10px!important;padding:0px!important;margin:0px!important;">IFSC Code</b></td>
									<td style="float: left; width: 74%;" align="left"><span style="vertical-align: middle; font-size: 10px!important;padding:0px!important;margin:0px!important;">: <span style="margin-left: 5px;">{{defined('IFSC_CODE') && !empty(IFSC_CODE) ? ucwords(IFSC_CODE) : '-'}}</span></span></td>
								</tr>
								<tr>
									<td style="float: left; width:  60px;" align="left"><b style="vertical-align: middle; font-size: 10px!important;padding:0px!important;margin:0px!important;">Branch Address</b></td>
									<td style="float: left; width: 74%;" align="left"><span style="vertical-align: middle; font-size: 10px!important;padding:0px!important;margin:0px!important;">: <span style="margin-left: 5px;">{{defined('BRANCH_ADDRESS') && !empty(BRANCH_ADDRESS) ? ucwords(BRANCH_ADDRESS) : '-'}}</span></span></td>
								</tr>
							</table>
						</td>                 
						<td colspan="3" style="vertical-align: top;border:1px solid;border-bottom:0px!important">
							<h5 style="text-align:right!important;margin-top:10px !important;"><b>For INTERSTELLAR TESTING CENTRE PVT. LTD.</b></h5>
						</td>
					</tr>
					<tr>   
						<td colspan="2" style="font-size: 10px;border:1px solid">
							<span style="font-size: 10px ! important;"><b>Terms &amp; Conditions</b></span>
							<ul style="margin:0 0 0 5px;padding:0px!important;">
								<li style="list-style-position: inherit;padding-left:5px!important;">Interest @ 2 % p.m. will be charged if the bill is not paid within 30 days.</li>
								<li style="list-style-position: inherit;padding-left:5px!important;">All payments to be made through at par cheques/Drafts.</li>
								<li style="list-style-position: inherit;padding-left:5px!important;">Unless otherwise stated. tax on this invoice is not payee under reverse charge</li>
								<li style="list-style-position: inherit;padding-left:5px!important;">We are falling under MSMED Act. Please release the payment within 45 days or else Compound interest @12.75% P.A will be chargeable</li>
							</ul>
						</td>
						<td colspan="3" style="vertical-align: bottom;border:1px solid;border-top:0px!important" valign="bottom">

							<div style="font-size:10px;padding-top:10px!important;float:right!important;">
								@if(!empty($viewData['invoiceHeader']['user_signature_file_url']) )
								
								<p style="float:right;width:100%;@if(!empty($hasContainHeaderFooter) && $hasContainHeaderFooter == '3');@endif">
									<img style="width: 70px; height: 30px; padding-left:30px;" alt="{{$viewData['invoiceHeader']['invoice_by']}}" src="{{$viewData['invoiceHeader']['user_signature_file_url']}}"/></p>
								@endif
                                
								<p style="margin-top:40px!important;vertical-align: bottom;font-size:13px!important;"><b>Authorised Signatory</b></p>
							</div>                        
						</td>
					</tr>
				</tbody>							
			</table>
		</div>
	</div>
	<!---/content-->
</body>
</html>
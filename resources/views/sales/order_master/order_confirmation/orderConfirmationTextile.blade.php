
<html class="gr__localhost">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
    .page-break-always{ page-break-after: always;}
    .page-break-auto{ page-break-after:auto;}
    @page { margin: 285px 20px 110px 20px;font-size:13px;}	
    #header {left: 0;position: fixed;right: 0;text-align: center;top: -280px;width: 100%;height:auto;}
    #footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
    #content {bottom: 0;height:auto;left: 0;right: 0;width: 100%;}
    td table td {border-bottom: 0px!important;}
    p{padding:2!important;margin:0!important;}
    .sno{width:6%!important;font-size:12px!important;padding:5px 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .category{font-size:13px!important;padding:0 5px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;}
    .parameter{width:34%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .methodName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .equipmentName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .requirementName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .testResult{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
    .pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:3px 2px!important;vertical-align: middle;}
    .header-content table,footer-content table{border-collapse:collapse;}
    .left-content{border: 0px none ! important; vertical-align: top;}
    .middle-content{color:#4d64a1;border:0px !important;text-align:center!important;}
    .middle-content h3{font-family: times of romen; font-weight: 600; color: rgb(77, 100, 161); margin: 0px; font-size: 30px !important;}
    .right-content{border: 0px none ! important; color: #d7563a; vertical-align: top; padding: 5px; text-align: center ! important; font-size: 12px ! important;}
    .footer-content h3 {font-family: times of romen; font-weight: 600; color:#4d64a1;margin: 0px; font-size: 15px !important;padding: 0 !important;}
    .footer-content p,h5 {font-size:11px !important;margin:0 !important;padding: 0 !important;}
    .footer-content ul {margin: 0 !important;padding: 0 !important;}
    .footer-content ul li {font-size: 11px !important;padding: 0 !important;}
    .rightSection{display:none;}
    table.dash {border: 1px dashed #000;border-collapse: collapse;}
    table.dash td {border: 1px dashed #000;}
    </style>

<body data-gr-c-s-loaded="true">
	      <!----header content-->
	      <div id="header">
			    <div class="header-content" @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('2','3')))style="visibility: hidden;"@endif>
			    <?php echo !empty($viewData['order']['header_content']) ? htmlspecialchars_decode($viewData['order']['header_content']) : '';?>
			    </div>
			    <div width="100%">
					  <table width="100%" style="border-collapse:collapse;">
							<tr>
								      <td colspan="6">
										    <table width="100%" style="padding: 0px !important;">
												  <tr>
														<td width="33.3%" align="left"></td>
														<td width="43.3%" align="center"><b style="padding: 5px;font-size: 16px; width: 150px;">TEST CONFIRMATION DOCUMENT</b></td>
														<td width="23.3%" align="right">&nbsp;&nbsp;&nbsp;</td>
												  </tr>
										    </table>
								      </td>
							</tr>
					  </table>
					  <table width="100%" border="1" style="padding: 0px !important;border-collapse:collapse;">
							<tbody>
								      <tr>
										    <td width="50%" stlye="vertical-align:top" >
												  <p style="margin-bottom: 27px;display: table;vertical-align: top;">
														<b style="width:105px!important;float:left;display: table-cell;">Applicant</b>
														<b style="display: table-cell;">:</b>
														<b style="display: table-cell;">{{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}} <br/>
														{{!empty($viewData['order']['customer_address']) ? $viewData['order']['customer_address'] : ''}}<br/>
														{{!empty($viewData['order']['city_name']) ? $viewData['order']['city_name'] : ''}}
														</b>
												  </p>
												  <p>
														<b style="width:105px!important;float:left;">ATTN</b>
														<b style="">:</b>
														<b style="float:right;margin-right:50px;">{{!empty($viewData['order']['contact_name1']) ? $viewData['order']['contact_name1'] : ''}}</b>
												  </p>
										    </td>
										    <td width="50%">
												  <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Sample Reg. No.</b><b>:</b>{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}</p>
												  <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Date In</b><b>:</b>{{!empty($viewData['order']['booking_date']) ? date(DATEFORMAT,strtotime($viewData['order']['booking_date'])) : ''}}</p>
												  <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Date Out</b><b>:</b></p>
												  <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Revised Date</b><b>:</b></p>
												  <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Buyer</b><b>:</b></p>
												  <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">working Days</b><b>:</b>{{!empty($viewData['order']['tat_days']) ? $viewData['order']['tat_days'] : '0'}}</p>
												  <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Page</b><b>:</b>
														<script type="text/php">
														if(isset($pdf)){ 
															$font = $fontMetrics->get_font('serif','Normal');
															$size = 10;
															$y    = $pdf->get_height() - @if(!empty($viewData['order']['nabl_no'])) '646' @else '660' @endif;
															$x    = $pdf->get_width() - 200 - $fontMetrics->get_text_width('1/1', $font, $size);
															$pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
															if('{PAGE_NUM}'>1){
															   $pdf->page_text($x, $y, '{PAGE_NUM}', $font, $size);
															}
														}
														</script>
												  </p>
										    </td>
								      </tr>
							  </tbody>
					  </table>
			    </div>
	      </div>
	      <!----/header content-->
		  
	      <!--- footer start-->
	      <div id="footer" class="footer-content">
			    <div @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('2','3')))style="visibility: hidden;"@endif>
					<?php echo !empty($viewData['order']['footer_content']) ? htmlspecialchars_decode($viewData['order']['footer_content']) : '';?>
			    </div>
	      </div>
	      <!--- /footer start-->
	      
	      <div id="content">
			    <div class="page-break-auto">
					  <table style="width:100%;margin:-10px 0;" cellpadding="10px" >
							<tr>
								      <td style="width: 50%;"></td>
								      <td style="width: 50%;border:1px double">
										    <h3 style="text-align:center;margin:0;">OVERALL RATING</h3>
										    <table style="width:100%">
												  <tr>
													   <td width="50%">Pass</td>
													   <td width="50%"><p style="width: 50%;border-bottom:1px solid;text-align:center;float: right;min-height:50px;">X</p></td>
												  </tr>
												  <tr>
													   <td width="50%">Fail</td>
													   <td width="50%"><p style="width:50%;border-bottom:1px solid;text-align:center;float: right;min-height:50px;">&nbsp;</p></td>
												  </tr>
												  <tr>
													   <td width="50%">Data</td>
													   <td width="50%"><p style="width: 50%;border-bottom:1px solid;text-align:center;float: right;min-height:50px;">&nbsp;</p></td>
												  </tr>
										    </table>
								      </td>
							</tr>
					  </table>
				 
					  <table border="1" style="width:100%;border-collapse:collapse;margin-top:30px;">
							<tr>
								<th align="left" width="25%" style="padding:0 10px">Vendor</th>
								<td width="25%" style="padding:0 10px">{{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}</td>
								<th align="left" width="25%" style="padding:0 10px">Agent</th>
								<td width="25%" style="padding:0 10px">/</td>
							</tr>
							<tr>
								<th align="left" width="25%" style="padding:0 10px">Sample Description</th>
								<td width="25%" style="padding:0 10px">{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : ''}}</td>
								<th align="left" width="25%" style="padding:0 10px">Style No</th>
								<td width="25%" style="padding:0 10px">IMPSW1007</td>
							</tr>
							<tr>
								<th align="left" width="25%" style="padding:0 10px">SKU No.</th>
								<td width="25%" style="padding:0 10px">/</td>
								<th align="left" width="25%" style="padding:0 10px">Season</th>
								<td width="25%" style="padding:0 10px">AW18</td>
							</tr>
							<tr>
								<th align="left" width="25%" style="padding:0 10px">P.O. No.</th>
								<td width="25%" style="padding:0 10px">/</td>
								<th align="left" width="25%" style="padding:0 10px">Color</th>
								<td width="25%" style="padding:0 10px">Black(Black/Grey)</td>
							</tr>
							<tr>
								<th align="left" width="25%" style="padding:0 10px">Country of Origin</th>
								<td width="25%" style="padding:0 10px">Made in {{!empty($viewData['order']['country_name']) ? $viewData['order']['country_name'] : ''}}</td>
								<th align="left" width="25%" style="padding:0 10px">End Use</th>
								<td width="25%" style="padding:0 10px">Garment</td>
							</tr>
							<tr>
								<th align="left" width="25%" style="padding:0 10px">Claimed Fabric Weight</th>
								<td width="25%" style="padding:0 10px">/</td>
								<th align="left" width="25%" style="padding:0 10px">Submitted Size</th>
								<td width="25%" style="padding:0 10px">M</td>
							</tr>
							<tr>
								<th align="left" width="25%" style="padding:0 10px">Production Stage</th>
								<td width="25%" style="padding:0 10px">/</td>
								<th align="left" width="25%" style="padding:0 10px">Type of Print</th>
								<td width="25%" style="padding:0 10px">Pigment</td>
							</tr>
					  </table>
					  <table border="1" style="width:100%;border-collapse:collapse;margin:20px 0;">
							<tr>
								<th align="left" width="25%" style="padding:0 10px">Product Category</th>
								<td width="75%" style="padding:0 10px">{{!empty($viewData['order']['p_category_name']) ? $viewData['order']['p_category_name'] : ''}}</td>
							</tr>
							<tr>
								<th align="left" width="25%" style="padding:0 10px">Test Requested</th>
								<td width="75%" style="padding:0 10px">{{!empty($viewData['order']['test_code']) ? $viewData['order']['test_code'] : ''}}</td>
							</tr>
							<tr>
								<th align="left" width="25%" style="padding:0 10px">Previous Report No.</th>
								<td width="75%" style="padding:0 10px">nA</td>
							</tr>
							<tr>
								      <th align="left" width="25%" style="padding:0 10px">Revised Report</th>
								      <td width="75%" style="padding:0 10px">
										    <table>
												  <tr>
												  <td style="min-width:70px;border-right:1px solid;text-align:center">yes</td>
														<td style="min-width:70px;border-right:1px solid;text-align:center">&nbsp;</td>
														<td style="min-width:70px;border-right:1px solid;text-align:center">No</td>
														<td style="min-width:70px;border-right:1px solid;text-align:center">X</td>
												  </tr>
										    </table>
								      </td>
							</tr>
							<tr>
								<th align="left" width="25%" style="padding:0 10px">Reason for Revision</th>
								<td width="75%" style="padding:0 10px">/</td>
							</tr>
					  </table>
					  <table border="1" style="width:100%;border-collapse:collapse;margin:10px 0;">
							<tr>
								      <th align="left" width="25%" style="padding:0 10px">Submitted Fiber Content</th>
								      <td width="75%" style="padding:0 10px">/</td>
							</tr>
							<tr>
								      <th align="left" width="25%" style="padding:0 10px">Submitted Care Instruction(s)</th>
								      <td width="75%" style="padding:0 10px">
										    <table>
												  <tr>
													  <td style="min-width:70px;"><img src="{{url('/public/img/1.png')}}" style="max-width:60px;"/></td>
													  <td style="min-width:70px;"><img src="{{url('/public/img/2.png')}}" style="max-width:60px;"/></td>
													  <td style="min-width:70px;"><img src="{{url('/public/img/3.png')}}" style="max-width:60px;"/></td>
													  <td style="min-width:70px;"><img src="{{url('/public/img/4.png')}}" style="max-width:60px;"/></td>
													  <td style="min-width:70px;"><img src="{{url('/public/img/5.png')}}" style="max-width:60px;"/></td>
												  </tr>
										    </table>
										    <p>Wash and Dry Inside Out Machine Wash Cold Dark Colors Separately</p>
										    <p>Do Not Bleach Do Not Wring, Flat Dry In Shade Warm Iron When Needed Do Not Iron Directly On Prints (As claimed on label)</p>
								      </td>
							</tr>
					  </table>
					  <table style="width:100%;margin-top:10px !important;">
							<tr>
								      <td colspan="2">
								      <h3>EXECUTIVE SUMMARY</h3>
								      <p>The submitted sample exhibits Satisfactory performance in the test performed.</p>
								      </td>
							</tr>
					  </table>
					  
					  <table border="1" style="border-collapse:collapse;width:100%;text-align:center;margin:20px 0" cellpadding="5px" >
							<thead>
								      <tr bgcolor="#ccc">
										    <th>SNo.</th>
										    <th>Test Property</th>
										    <th>Test Method</th>
										    <th>Requirements</th>
										    <th>Test Results</th>
										    <th>Rating</th>
								      </tr>
							</thead>
							<tbody>
							<?php $count = '1';?>
							@if(!empty($viewData['orderParameters']))
								      @foreach($viewData['orderParameters'] as $key => $orderParameterCategoryName)
										    @if(!empty($orderParameterCategoryName['categoryParams']))
												  @foreach($orderParameterCategoryName['categoryParams'] as $orderParaKey => $orderParameterCategoryParameters)
														<tr>
															<td>{{$count++}}.</td>
															<td><?php echo trim($orderParameterCategoryParameters['test_parameter_name']);
																echo !empty($orderParameterCategoryParameters['non_nabl_parameter_symbol']) ? '<sup>'. $orderParameterCategoryParameters['non_nabl_parameter_symbol'] .'</sup>': '' ; ?>
															</td>
															<td>{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
															<td>{{!empty($orderParameterCategoryParameters['requirement_from_to']) ? trim($orderParameterCategoryParameters['requirement_from_to']) : ''}}</td>
															<td></td>
															<td></td> 
														</tr>
												  @endforeach
										    @endif    
								      @endforeach
							@endif
							</tbody>
					  </table>
					  <table style="width: 100%;margin:-30px 0 !important;padding: 10px;display:none;">
							<tr>
								      <td>
									      <h3 style="text-align:center">Sample Digital</h3>
								      </td>
							</tr>
							<tr>
								      <td><center><img style="max-width:230px;"/></center></td>
							</tr>
					  </table>
					  <table style="width: 100%;margin: 10px 0;padding: 10px;display:none;">
							<tr>
								      <td colspan="2">
									       <h4 style="text-align:center;margin-bottom:0">Label</h4>
								      </td>
							</tr>
							<tr>
								      <td width="50%">
									       <center><img style="border: 2px solid;max-width:230;margin-top:2px !important;"/></center>
								      </td>
								      <td width="50%">
									       <center><img style="border: 2px solid;max-width:230;margin-top:2px !important;"/></center>
								      </td>
							</tr>
					  </table>
					  <table style="width: 100%;">
							<tr>
								      <td colspan="2">
									       <h4 style="text-align:center">*****End Of Document*****</h4>
								      </td>
							</tr>
					  </table>
			    </div>
	      </div>
</body>
</htm>

<html>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <style>
   .page-break-always{ page-break-after: always;}
   .page-break-auto{ page-break-after:auto;}
   @if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus))
   @page { margin: 170px 20px 265px 20px;font-size:13px;}
   #header {left: 0;position: fixed;right: 0;text-align: center;top: -160px;width: 100%;height:auto;}
   @else
  	@if(!empty($hasContainEquipment) && empty($notContainLimit))
	  	@page { margin: 170px 20px 265px 20px;font-size:13px;}
   		#header {left: 0;position: fixed;right: 0;text-align: center;top: -160px;width: 100%;height:auto;}
	@elseif(empty($hasContainEquipment) && !empty($notContainLimit))
		@page { margin: 170px 20px 265px 20px;font-size:13px;}
   		#header {left: 0;position: fixed;right: 0;text-align: center;top: -160px;width: 100%;height:auto;}
	@else
		@page { margin: 170px 20px 265px 20px;font-size:13px;}
   		#header {left: 0;position: fixed;right: 0;text-align: center;top: -160px;width: 100%;height:auto;}
	@endif
   @endif
   #footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
   #content {bottom: 0;height: auto;left: 0;right: 0;width: 100%;}
   #footer .page:after { content: counter(page, upper-roman); }
   td table td {padding:1px!important;border-bottom: 0px!important;}
   p{padding:2px 0px !important;margin:0!important;}
   .sno{width:6%!important;font-size:12px!important;padding:5px 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .category{font-size:14px!important;padding:0 5px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;}
   .parameter{width:40%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .parameter p{padding:0px!important;margin:0!important;display: inline-block!important;}
   .methodName{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .equipmentName{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .requirementName{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .testResult{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
   .pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:3px 2px!important;}   
   .header-content table,footer-content table{border-collapse:collapse;}   
   .left-content{border: 0px none ! important; vertical-align: middle;}
   .left-content .cetificate-section{color:#4d64a1;margin-top:-2px;font-size: 11px!important; border: 1px solid; display: block; vertical-align: middle; padding: 2px; width: 200px;}
   .left-content .validity-section{color:#4d64a1;font-size:11px!important; border: 1px solid; display: block; vertical-align: middle; padding: 2px; width: 111px;}   
   .middle-content{font-family:times of romen;border:0px!important;text-align:center!important;vertical-align:top;}
   .middle-content .form-section{color: rgb(138, 47, 48);margin:0px!important;font-weight: bold;border: 2px solid rgb(138, 47, 48)!important;font-size: 15px;padding:5px!important;}
   .middle-content .lic-no-section{color:#4d64a1;font-size:11px!important;padding:5px!important;margin:0px!important;}
   .middle-content .title-section{color:#4d64a1;font-weight: 600; color: rgb(77, 100, 161);padding:0px!important;font-size: 28px!important;line-height:28px!important;}
   .middle-content .sub-title-section{color:#4d64a1;font-size:11px!important;padding:0px!important;margin:-200px!important;}
   .right-content{border: 0px none ! important; color: #d7563a; vertical-align: top; padding: 5px; text-align: center ! important; font-size: 12px ! important;vertical-align: top;}
   .footer-content h3 {font-family: times of romen; font-weight: 600; color:#4d64a1;margin: 0px; font-size: 15px !important;padding: 0 !important;}
   .footer-content p,h5 {font-size:11px !important;margin:0 !important;padding: 0 !important;}
   .footer-content ul {margin: 0 !important;padding: 0 !important;}
   .footer-content ul li {font-size: 11px !important;margin-top: 0px !important;padding: 0 !important;}
   .rightSection{display:none!important;}
   .middle-content > p {margin: 0 !important;padding: 0 !important;}
   td.middle-content>p:nth-child(2) {padding: 5px 0 0 0!important;}
   .reportText{font-style: italic;font-weight:bold;color: #fff;background: #8A2F30;font-size: 16px;padding:8px!important;}
   </style>

<!--- header-->
<div id="header">

	<div class="header-content" @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3'))) style="visibility: hidden;" @endif>
		<?php echo !empty($viewData['order']['header_content']) ? htmlspecialchars_decode($viewData['order']['header_content']) : '';?>
	</div>

	<table width="100%" style="border-collapse:collapse;margin-top:-15px!important;@if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3')))visibility: hidden;@endif">
		<tr>
			<td align="left" width="25%">&nbsp;</td>
			<td width="60%" align="center">
				<div style="display:inline-flex!important;text-align:center!important;">
					<span class="reportText">
						TEST REPORT
						@if(!empty($hasContainHeaderFooter) && $hasContainHeaderFooter == '2')
						(DRAFT)
						@endif
					</span>
				</div>
			</td>
			<td align="left" width="15%">&nbsp;</td>
		</tr>
	</table>
</div>
<!--- header end-->

<!--- footer start-->
<div id="footer" class="footer-content">

	<div>
		<table valign="bottom" width="100%" style="margin:0 auto;border-collapse:collapse;">
			<tr>
				<td align="center" colspan="6" style="margin-top: 0px!important;">
					<b style="text-align:center;">
						@if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus)) 
						<script type='text/php'>
							if(isset($pdf)){
								$font = $fontMetrics->get_font('serif','Normal');
								$size = 10;
								$y    = $pdf->get_height() - 728;
								$x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
								$pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
							}
						</script>
						@else
						<script type='text/php'>
							if(isset($pdf)){
								$font = $fontMetrics->get_font('serif','Normal');
								$size = 10;
								$y    = $pdf->get_height() - 730;
								$x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
								$pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
							}
						</script>
						@endif
					</b>
				</td>
			</tr>
		</table>
	</div>

	<div>
		<?php
		if(!empty($viewData['orderTrackRecord']['finalizing']['user_id']) && $viewData['orderTrackRecord']['finalizing']['user_id'] == '128'){
			$stampStyleCss     = 'margin:35px -20px;visibility:hidden;';
			$finalizingNameCss = 'font-size:13px;padding-top:-5px!important;';
			$finalizingSignCss = 'margin-bottom:-5px!important;width:70px;height:70px;';
		}else{
			$stampStyleCss     = 'margin:10px -20px;visibility:hidden;';
			$finalizingNameCss = 'font-size:13px;padding-top:-1px!important;margin-right:40px!important;';
			$finalizingSignCss = 'margin-bottom:-5px!important;width:70px;height:30px;';
		}
		?>
		<table class="pdftable" width="100%" style="border-collapse:collapse;border-bottom: 1px solid;">
			<tr>
				<td width="33.3%" align="left" valign="bottom">
					@if(!empty($viewData['order']['report_date']))
					<p style="padding: 5px 0;font-size:13px;display: inline-block;vertical-align: middle;"><b> Report
							Date </b><b>:
						</b>{{!empty($viewData['order']['report_date']) ? date(DATEFORMAT,strtotime($viewData['order']['report_date'])) : ''}}
						@endif
				</td>
				<td width="33.3%" align="center" valign="bottom">
					<p style="font-size:10px;padding-top:-5px!important;">
						@if(!empty($viewData['orderTrackRecord']['reviewing']['user_signature']) &&
						file_exists(DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['reviewing']['user_signature']))
						<span
							style="display:inline-block;width:100%;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img
								style="width:70px;height:30px;padding-top:-5px!important;"
								alt="{{$viewData['orderTrackRecord']['reviewing']['user_signature']}}"
								src="{{DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['reviewing']['user_signature']}}" /></span></br>
						@endif
						<span
							style="font-size:13px;padding-top:-1px!important;">{{!empty($viewData['orderTrackRecord']['reviewing']['username']) ? $viewData['orderTrackRecord']['reviewing']['username'] : ''}}</span>
					</p>
					<p style="font-size:13px;padding-top:-1px!important;"><b>Reviewer</b></p>
				</td>
				<td width="33.3%" align="right" valign="bottom">
					<p style="width:100%!important;margin-right:30px!important;">
						@if(!empty($viewData['quality']['is_display_stamp']))
						@if($viewData['quality']['is_display_stamp'] == '1' && !empty($viewData['quality']['stampType'])
						&& defined('GREEN_STANDARD_QUALITY'))
						<span
							style="width: 50%; float: left;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img
								style="margin: 35px -20px;"
								src="{{DOC_ROOT.'/public/images/quality/'.GREEN_STANDARD_QUALITY}}" /></span>
						@elseif($viewData['quality']['is_display_stamp'] == '1' &&
						empty($viewData['quality']['stampType']) && defined('RED_STANDARD_QUALITY') &&
						defined('NOT_STANDARD_QUALITY'))
						<span
							style="width: 50%; float: left;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img
								style="margin: 35px -20px;"
								src="{{DOC_ROOT.'/public/images/quality/'.RED_STANDARD_QUALITY}}" /></span>
						@else($viewData['quality']['is_display_stamp'] == '2' && defined('NOT_STANDARD_QUALITY'))
						<span
							style="width: 50%; float: left;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img
								style="margin: 35px -20px;"
								src="{{DOC_ROOT.'/public/images/quality/'.NOT_STANDARD_QUALITY}}" /></span>
						@endif
						@else
						<span
							style="width:50%;float:left;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img
								style="{{$stampStyleCss}}"
								src="{{DOC_ROOT.'/public/images/quality/'.RED_STANDARD_QUALITY}}" /></span>
						@endif
						@if(!empty($viewData['orderTrackRecord']['finalizing']['user_signature']) &&
						file_exists(DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['finalizing']['user_signature']))
						<span style="width:50%;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img
								style="{{$finalizingSignCss}}"
								alt="{{$viewData['orderTrackRecord']['finalizing']['user_signature']}}"
								src="{{DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['finalizing']['user_signature']}}" /></span>
						@endif
					</p>
					<p style="{{$finalizingNameCss}}">
						<b>{{!empty($viewData['orderTrackRecord']['finalizing']['username']) ? $viewData['orderTrackRecord']['finalizing']['username'] : ''}}</b>
					</p>
					<p style="font-size:13px;padding-top:-1px!important;"><b>[Person Incharge / DGM Pharma]</b></p>
				</td>
			</tr>
		</table>
	</div>

	<div @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3')))style="visibility:hidden;"
		@endif>
		<?php echo !empty($viewData['order']['footer_content']) ? htmlspecialchars_decode($viewData['order']['footer_content']) : '';?>
	</div>
</div>
<!--- footer start-->

<body>
   <!--content-->
   <div id="content">
      <div class="page-break-auto">
	 <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;">
	    <tbody>       
	       <tr>
		  <td style="padding:0px;" width="100%" align="left" colspan="4"><b style="display: inline-block;vertical-align: middle;width: 110px;">Sample Name<?php echo Helper::getCustomerDefinedFieldSymbol('sample_description',$viewData['order']['customer_defined_fields']);?></b><b>:</b><b>{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : ''}}</b></td>
	       </tr>				       
	       <tr>
		  <td style="padding:0px;" align="left" colspan="3"><b style="display: inline-block;vertical-align: middle;vertical-align: middle;width: 110px;">Supplied By<?php echo Helper::getCustomerDefinedFieldSymbol('supplied_by',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['supplied_by']) ? $viewData['order']['supplied_by'] : '' }}</td>
		  <td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Report No.<?php echo Helper::getCustomerDefinedFieldSymbol('report_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b>
		     {{!empty($viewData['order']['report_no']) ?  $viewData['order']['report_no'] : ''}}{{ !empty($viewData['order']['is_amended_no']) ? '-'.$viewData['order']['is_amended_no'] : '' }}
		     @if(!empty($reportWithRightLogo) && in_array($reportWithRightLogo,array('16','17'))) 
			{{ $reportWithRightLogo == '16' ? '&nbsp;(1)' : '&nbsp;(2)' }}
		     @endif
		  </td>
	       </tr>				       
	       @if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus)) 
	       <tr>
		  <td style="padding:0px;" align="left" colspan="3"><b style="display: inline-block;vertical-align: middle;width: 110px;">Manufactured By<?php echo Helper::getCustomerDefinedFieldSymbol('manufactured_by',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['manufactured_by']) ?  $viewData['order']['manufactured_by'] : ''}}</td>
		  <td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Booking Code<?php echo Helper::getCustomerDefinedFieldSymbol('order_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}</td>
	       </tr>
	       <tr>
		  <td style="padding:0px;" align="left" colspan="3"><b style="display: inline-block;vertical-align: middle;width: 110px;">Party Ref. Date<?php echo Helper::getCustomerDefinedFieldSymbol('letter_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['letter_no']) ?  $viewData['order']['letter_no'] : ''}}</td>
		  <td style="padding:0px;">
		     @if(!empty($reportWithRightLogo) && in_array($reportWithRightLogo,array('7','16'))) 
			<b style="display:inline-block;vertical-align: middle;width: 95px;font-size:12px!important;">NABL ULR No.<?php echo Helper::getCustomerDefinedFieldSymbol('nabl_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b><span style="font-size:12.5px;">{{!empty($viewData['order']['nabl_no']) ? $viewData['order']['nabl_no'] : ''}}</span>
		     @else
			<b style="display:inline-block;vertical-align: middle;width: 95px;font-size:12px!important;"></b><span style="font-size:12.5px;"></span>
		     @endif
		  </td>
	       </tr>					  
	       <tr>
		  <td style="padding:0px;" align="left" colspan="4"><b style="display: inline-block;vertical-align: middle;width: 110px;">Submitted By<?php echo Helper::getCustomerDefinedFieldSymbol('customer_name',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}{{!empty($viewData['order']['city_name']) ? '-'.$viewData['order']['city_name'] : ''}} ( {{!empty($viewData['order']['state_name']) ? $viewData['order']['state_name'] : ''}} ) </td>
	       </tr>
	       @else
		  <tr>
		     <td style="padding:0px;" align="left" colspan="3"><b style="display: inline-block;vertical-align: middle;width: 110px;">Manufactured By<?php echo Helper::getCustomerDefinedFieldSymbol('manufactured_by',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['manufactured_by']) ?  $viewData['order']['manufactured_by'] : ''}}</td>
		     <td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Booking Code<?php echo Helper::getCustomerDefinedFieldSymbol('order_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}</td>
		  </tr>					     
		  <tr>
		     <td style="padding:0px;" align="left" colspan="3"><b style="display: inline-block;vertical-align: middle;width: 110px;">Submitted By<?php echo Helper::getCustomerDefinedFieldSymbol('customer_name',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}{{!empty($viewData['order']['city_name']) ? '-'.$viewData['order']['city_name'] : ''}} ( {{!empty($viewData['order']['state_name']) ? $viewData['order']['state_name'] : ''}} ) </td>
		     <td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Party Ref. Date<?php echo Helper::getCustomerDefinedFieldSymbol('letter_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['letter_no']) ?  $viewData['order']['letter_no'] : ''}}</td>
		  </tr>
	       @endif				       
	       <tr>
		  <td align="left" style="padding:0px;" colspan="2" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 110px;">Mfg. Lic. No.<?php echo Helper::getCustomerDefinedFieldSymbol('mfg_lic_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['mfg_lic_no']) ? $viewData['order']['mfg_lic_no'] : ''}}</td>
		  <td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 65px;">Batch No.<?php echo Helper::getCustomerDefinedFieldSymbol('batch_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['batch_no']) ?  $viewData['order']['batch_no'] : ''}} </td>
		  <td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 95px;">Booking Date<?php echo Helper::getCustomerDefinedFieldSymbol('order_date',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['order_date']) ?  date(DATEFORMAT,strtotime($viewData['order']['order_date'])) :''}}</td>
	       </tr>				       
	       <tr>
		  <td align="left" style="padding:0px;" width="20%"><b style="display: inline-block;vertical-align: middle;width: 40px;">D/M<?php echo Helper::getCustomerDefinedFieldSymbol('mfg_date',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['mfg_date']) ? $viewData['order']['mfg_date'] : '-'}}</td>
		  <td align="left" style="padding:0px;" width="20%"><b style="display: inline-block;vertical-align: middle;width: 40px;">D/E<?php echo Helper::getCustomerDefinedFieldSymbol('expiry_date',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['expiry_date']) ? $viewData['order']['expiry_date'] : '-'}}</td>
		  <td align="left" style="padding:0px;" width="30%"><b style="display: inline-block;vertical-align: middle;width: 70px;">Batch Size<?php echo Helper::getCustomerDefinedFieldSymbol('batch_size',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['batch_size']) ? $viewData['order']['batch_size'] :'' }}</td>
		  <td align="left" style="padding:0px;" width="30%"><b style="display: inline-block;vertical-align: middle;width: 95px;">Sample Qty.<?php echo Helper::getCustomerDefinedFieldSymbol('sample_qty',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : ''}}</td>
	       </tr>
		   <tr><td align="left" colspan="4" style="padding:0px;" width="100%" style="padding:0px;margin:0px;font-weight:bold;">&nbsp;</td></tr>	
			<tr>
				<?php
				if(!empty($viewData['orderTrackRecord']['reviewing']['report_view_date'])){
					$viewDate = date('d-m-Y',strtotime($viewData['orderTrackRecord']['reviewing']['report_view_date']));
				}else if(!empty($viewData['orderTrackRecord']['finalizing']['report_view_date'])){
					$viewDate = date('d-m-Y',strtotime($viewData['orderTrackRecord']['finalizing']['report_view_date']));
				}
				?>
				<td align="left" colspan="2" style="padding:0px;" width="100%" style="padding:0px;margin:0px;font-weight:bold;"><b>Date of start of analysis : {{!empty($viewData['order']['analysis_start_date']) ?  date(DATEFORMAT,strtotime($viewData['order']['analysis_start_date'])) : 'N/A'}}</b></td>
				<td align="right" colspan="2" style="padding:0px;" width="100%" style="padding:0px;margin:0px;font-weight:bold;"><b>Date of completion of analysis : {{!empty($viewData['order']['orderAmendStatus'])  && !empty($viewData['order']['is_amended_no']) ? $viewDate :  date(DATEFORMAT,strtotime($viewData['order']['analysis_completion_date']))}}</b></td>
			</tr>				       
	       @if(!empty($viewData['order']['stability_note']))
	       <tr>
		  <td align="center" colspan="4" width="100%"><b>{{!empty($viewData['order']['stability_note']) ? $viewData['order']['stability_note'] : '-'}}</b></td>
	       </tr>		     
	       @endif
	    </tbody>
	 </table>
	 
	 <!--Description Parameters Category-->
	 <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">
	    @if(!empty($viewData['descriptionParameters']))
	       @foreach($viewData['descriptionParameters'] as $key => $descriptionParaCategoryName)
		  <thead style="display:none;">
		     <tr>
			<th align="left" colspan="5" style="padding:4px 2px!important;">{{strtoupper($descriptionParaCategoryName['categoryName'])}}{{!empty($descriptionParaCategoryName['categoryNameSymbol']) ? trim($descriptionParaCategoryName['categoryNameSymbol']) : ''}}</th>
		     </tr>
		  </thead>
		  @if(!empty($descriptionParaCategoryName['categoryParams']))
		     <tbody>
			@foreach($descriptionParaCategoryName['categoryParams'] as $orderParaKey => $descriptionParaCategoryParameters)
			   <tr>
			      <td colspan="1" align="left"><?php echo trim($descriptionParaCategoryParameters['test_parameter_name']);?><?php echo !empty($descriptionParaCategoryParameters['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($descriptionParaCategoryParameters['non_nabl_parameter_symbol']).'</sup>' : '';?></td>
			      <td colspan="4" class="text-justify" align="left" style="padding:0 15px;">{{!empty($descriptionParaCategoryParameters['description']) ? trim($descriptionParaCategoryParameters['description']) : ''}} </td>
			   </tr>
			@endforeach
		     </tbody>
		  @endif
	       @endforeach
	    @endif
	 </table>
	 <!--/Description Parameters Category-->
	 
	 <!--Test Parameters Category-->
	 @if(!empty($viewData['orderParameters']))
	 <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">
	    <tbody>	       
	       @foreach($viewData['orderParameters'] as $paramKey => $orderParametersCategoryName)
		  @if(strtolower($orderParametersCategoryName['categoryName']) == strtolower($viewData['order']['testParametersWithSpace']))  
		     <tr>
			<th class="parameter" align="left">{{strtoupper($orderParametersCategoryName['categoryName'])}}{{!empty($orderParametersCategoryName['categoryNameSymbol']) ? trim($orderParametersCategoryName['categoryNameSymbol']) : ''}}</th>
			<th class="equipmentName" align="center"@if(empty($hasContainEquipment)) style="display:none;"@endif>EQUIPMENT</th>
			<th class="testResult" align="center">RESULT</th>
			<th class="requirementName" align="center"@if(!empty($notContainLimit)) style="display:none;"@endif>LIMIT</th>
		     </tr>
		     @foreach($orderParametersCategoryName['categoryParams'] as $key => $orderParametersCategoryParams)
			<tr>
			   <td class="parameter" width="40%" align="left">
			      <?php echo trim($orderParametersCategoryParams['test_parameter_name']);?><?php echo !empty($orderParametersCategoryParams['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($orderParametersCategoryParams['non_nabl_parameter_symbol']).'</sup>' : '';?>
			   </td>
			   @if(empty($orderParametersCategoryParams['description']))
			      <td class="equipmentName" align="center"@if(empty($hasContainEquipment)) style="display:none;"@endif>{{!empty($orderParametersCategoryParams['equipment_name']) ? $orderParametersCategoryParams['equipment_name'] : ''}}</td>
			      <td class="testResult" align="left">{{!empty($orderParametersCategoryParams['test_result']) ? $orderParametersCategoryParams['test_result'] : ''}}</td>
			      <td class="requirementName" align="center"@if(!empty($notContainLimit)) style="display:none;"@endif>
				 {{!empty($orderParametersCategoryParams['requirement_from_to']) ? trim($orderParametersCategoryParams['requirement_from_to']) : ''}}
				 &nbsp;
				 {{!empty($orderParametersCategoryParams['claim_value_unit']) ? strtoupper($orderParametersCategoryParams['claim_value_unit']) : ''}}
			      </td>			      
			   @else
			      <td class="testResult"
				 @if(empty($hasContainEquipment) && empty($notContainLimit))
				    colspan="2"
				 @elseif(!empty($hasContainEquipment) && empty($notContainLimit))
				    colspan="3"
				 @elseif(empty($hasContainEquipment) && !empty($notContainLimit))
				    colspan="1"
				 @elseif(!empty($hasContainEquipment) && !empty($notContainLimit))
				    colspan="2"
				 @endif
				 align="left">
				 {{!empty($orderParametersCategoryParams['test_result']) ? $orderParametersCategoryParams['test_result'] : ''}}
			      </td>
			   @endif
			</tr>
		     @endforeach
		  @endif
	       @endforeach	              
	    </tbody>
	 </table>
	 @endif	
	 <!--/Test Parameters Category-->
	 
	 <!--Assay Parameters Category-->
	 @if(!empty($viewData['orderParameters']))
	    <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">
	       <tbody>		  
		  @foreach($viewData['orderParameters'] as $paramKey => $orderParametersCategoryName)
		     @if(strtolower($orderParametersCategoryName['categoryName']) == strtolower($viewData['order']['assayParametersWithSpace']))
			@if(!empty($viewData['order']['header_note']))
			<tr>
			   <td align="center"
			   @if(!empty($viewData['order']['hasClaimValue']))
			      @if(empty($hasContainEquipment) && empty($notContainLimit))
				 colspan="5"
			      @elseif(!empty($hasContainEquipment) && empty($notContainLimit))
				 colspan="6"
			      @elseif(empty($hasContainEquipment) && !empty($notContainLimit))
				 colspan="4"
			      @elseif(!empty($hasContainEquipment) && !empty($notContainLimit))
				 colspan="5"
			      @endif
			   @else
			      @if(empty($hasContainEquipment) && empty($notContainLimit))
				 colspan="4"
			      @elseif(!empty($hasContainEquipment) && empty($notContainLimit))
				 colspan="5"
			      @elseif(empty($hasContainEquipment) && !empty($notContainLimit))
				 colspan="3"
			      @elseif(!empty($hasContainEquipment) && !empty($notContainLimit))
				 colspan="4"
			      @endif
			   @endif
			   style="padding:0px;"><b style="margin:0px;padding:0px;">{{!empty($viewData['order']['header_note']) ? $viewData['order']['header_note'] : '&nbsp;'}}</b></td>
			</tr>
			@endif
			
			<tr>
			   <th class="parameter" align="left">{{strtoupper($orderParametersCategoryName['categoryName'])}}{{!empty($orderParametersCategoryName['categoryNameSymbol']) ? trim($orderParametersCategoryName['categoryNameSymbol']) : ''}}</th>
			   <th class="equipmentName" align="center"@if(empty($hasContainEquipment)) style="display:none;"@endif>EQUIPMENT</th>
			   <th class="testResult" align="center">RESULT</th>
			   <th class="requirementName" align="center"@if(empty($viewData['order']['hasClaimValue'])) style="display:none;"@endif>CLAIM</th>
			   <th class="requirementName" align="center"@if(!empty($notContainLimit)) style="display:none;"@endif>LIMIT</th>
			   <th class="methodName" align="center">METHOD</th>
			</tr>
			
			@foreach($orderParametersCategoryName['categoryParams'] as $key => $orderParametersCategoryParams)
			   <tr>
			      <td class="parameter" align="left">
				 <?php echo trim($orderParametersCategoryParams['test_parameter_name']);?><?php echo !empty($orderParametersCategoryParams['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($orderParametersCategoryParams['non_nabl_parameter_symbol']).'</sup>' : '';?>
			      </td>
			      @if(empty($orderParametersCategoryParams['description']))
				 <td class="equipmentName" align="center"@if(empty($hasContainEquipment)) style="display:none;"@endif>{{!empty($orderParametersCategoryParams['equipment_name']) ? $orderParametersCategoryParams['equipment_name'] : ''}}</td>
				 <td class="testResult" align="left">{{!empty($orderParametersCategoryParams['test_result']) ? $orderParametersCategoryParams['test_result'] : '-'}}</td>
				 <td class="requirementName" align="center"@if(empty($viewData['order']['hasClaimValue'])) style="display:none;"@endif>
				    @if(!empty($orderParametersCategoryParams['claim_value']))
				       {{$orderParametersCategoryParams['claim_value']}}
				       &nbsp;
				       {{!empty($orderParametersCategoryParams['claim_value_unit']) ? strtoupper($orderParametersCategoryParams['claim_value_unit']) : ''}}
				    @endif
				 </td>
				 <td class="requirementName" align="center"@if(!empty($notContainLimit)) style="display:none;"@endif>
				    {{!empty($orderParametersCategoryParams['requirement_from_to']) ? trim($orderParametersCategoryParams['requirement_from_to']) : ''}}
				    &nbsp;
				    {{!empty($orderParametersCategoryParams['claim_value_unit']) ? strtoupper($orderParametersCategoryParams['claim_value_unit']) : ''}}
				 </td>
				 <td class="methodName" align="center">{{!empty($orderParametersCategoryParams['method_name']) ? $orderParametersCategoryParams['method_name'] : ''}}</td>
			      @else				 
				 <td class="testResult"
				    @if(!empty($viewData['order']['hasClaimValue']))
				       @if(empty($hasContainEquipment) && empty($notContainLimit))
					  colspan="4"
				       @elseif(!empty($hasContainEquipment) && empty($notContainLimit))
					  colspan="5"
				       @elseif(empty($hasContainEquipment) && !empty($notContainLimit))
					  colspan="3"
				       @elseif(!empty($hasContainEquipment) && !empty($notContainLimit))
					  colspan="5"
				       @endif
				    @else
				       @if(empty($hasContainEquipment) && empty($notContainLimit))
					  colspan="3"
				       @elseif(!empty($hasContainEquipment) && empty($notContainLimit))
					  colspan="4"
				       @elseif(empty($hasContainEquipment) && !empty($notContainLimit))
					  colspan="2"
				       @elseif(!empty($hasContainEquipment) && !empty($notContainLimit))
					  colspan="4"
				       @endif
				    @endif
				 align="left">{{!empty($orderParametersCategoryParams['test_result']) ? $orderParametersCategoryParams['test_result'] : ''}}</td>
			      @endif
			   </tr>
			@endforeach
		     @endif
		  @endforeach			 
	       </tbody>
	    </table>
	 @endif	
	 <!--/Assay Parameters Category-->
	 
	 <!--Neither Test Nor Assay Parameters Category-->
	 @if(!empty($viewData['orderParameters']))
	    <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">
	       <tbody>	       
		  @foreach($viewData['orderParameters'] as $paramKey => $orderParametersCategoryName)
		     @if(strtolower($orderParametersCategoryName['categoryName']) != strtolower($viewData['order']['testParametersWithSpace']) && strtolower($orderParametersCategoryName['categoryName']) != strtolower($viewData['order']['assayParametersWithSpace']))			
			<tr>
			   <th class="parameter" align="left">{{strtoupper($orderParametersCategoryName['categoryName'])}}{{!empty($orderParametersCategoryName['categoryNameSymbol']) ? trim($orderParametersCategoryName['categoryNameSymbol']) : ''}}</th>
			   <th class="equipmentName" align="center"@if(empty($hasContainEquipment)) style="display:none;"@endif>EQUIPMENT</th>
			   <th class="testResult" align="center">RESULT</th>
			   <th class="requirementName" align="center"@if(!empty($notContainLimit)) style="display:none;"@endif>LIMIT</th>
			</tr>		     		     
			@foreach($orderParametersCategoryName['categoryParams'] as $key => $orderParametersCategoryParams)
			   <tr>
			      <td class="parameter" align="left">
				 <?php echo trim($orderParametersCategoryParams['test_parameter_name']);?><?php echo !empty($orderParametersCategoryParams['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($orderParametersCategoryParams['non_nabl_parameter_symbol']).'</sup>' : '';?>
			      </td>			   
			      @if(empty($orderParametersCategoryParams['description']))
				 <td class="equipmentName" align="center"@if(empty($hasContainEquipment)) style="display:none;"@endif>{{!empty($orderParametersCategoryParams['equipment_name']) ? $orderParametersCategoryParams['equipment_name'] : ''}}</td>
				 <td class="testResult" align="left">{{!empty($orderParametersCategoryParams['test_result']) ? $orderParametersCategoryParams['test_result'] : '-'}}</td>
				 <td class="requirementName" align="center"@if(!empty($notContainLimit)) style="display:none;"@endif>
				    {{!empty($orderParametersCategoryParams['requirement_from_to']) ? trim($orderParametersCategoryParams['requirement_from_to']) : ''}}
				    &nbsp;
				    {{!empty($orderParametersCategoryParams['claim_value_unit']) ? strtoupper($orderParametersCategoryParams['claim_value_unit']) : ''}}
				 </td>
			      @else
				 <td class="testResult"
				 @if(empty($hasContainEquipment) && empty($notContainLimit))
				    colspan="2"
				 @elseif(!empty($hasContainEquipment) && empty($notContainLimit))
				    colspan="3"
				 @elseif(empty($hasContainEquipment) && !empty($notContainLimit))
				    colspan="1"
				 @elseif(!empty($hasContainEquipment) && !empty($notContainLimit))
				    colspan="2"
				 @endif
				 align="left">{{!empty($orderParametersCategoryParams['test_result']) ? $orderParametersCategoryParams['test_result'] : ''}}</td>
			      @endif
			   </tr>
			@endforeach
		     @endif
		  @endforeach			  
	       </tbody>
	    </table>
	 @endif
	 <!--/Neither Test Nor Assay Parameters Category-->
	 
	 <!--Notes and Remarks Section-->
	 <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">
	    <tr>
	       <td width="100%" align="left" style="border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align:middle;font-size:11px!important;margin-top:-12px!important;" colspan="3">
		  @if(!empty($viewData['order']['order_nabl_remark_scope']))
		     '&#x2A;' represents categories/test parameters not covered under NABL
		  @endif
		  @if(!empty($viewData['order']['order_outsource_remark_scope']))
		     &nbsp;&#124;&nbsp;'&#x2A;&#x2A;' represents outsource sample
		  @endif
		  @if(!empty(Helper::getCustomerDefinedFieldSymbol('sample_description',$viewData['order']['customer_defined_fields'])))
		     &nbsp;'&#x23;' represents Customer Defined Fields
		  @endif
	       </td>
	    </tr>
	    @if(!empty($viewData['order']['note_value']))
	    <tr>
	       <td width="100%" align="left" style="border: 1px solid #fff;visibility:hidden;" colspan="3">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="100%" align="left" style="border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="3"><b style="display: inline-block;font-size:14px!important;display: inline-block;vertical-align: middle;">NOTE :</b> <span style="vertical-align: middle;">{{!empty($viewData['order']['note_value']) ? $viewData['order']['note_value'] : ''}}</span></td>
	    </tr>			 
	    @endif
	    @if(!empty($viewData['order']['remark_value']))
	    <?php $testStdValue = !empty($viewData['order']['test_standard_value'])? $viewData['order']['test_standard_value']:''; ?>
	    <tr>
	       <td width="100%" align="left" style="visibility:hidden;" colspan="3">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="100%" align="left" style="padding: 5px 0px !important; border-right: 1px solid #fff;border-left: 1px solid #fff;border-top:1px solid #000!important;border-bottom:1px solid #000!important;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="3">{{!empty($viewData['order']['remark_value']) ? $viewData['order']['remark_value'].$testStdValue : ''}}</td>
	    </tr>
	    @endif
	    <tr>
	       <td width="100%" align="center" style="margin-top:10px!important;border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="3"><b style="font-size:14px!important;vertical-align: middle;">*****End Of Report*****</td>
	    </tr>
	 </table>
	 <!--/Notes and Remarks Section-->      
      </div>
   </div>
   <!--/content-->
</body>
</html>
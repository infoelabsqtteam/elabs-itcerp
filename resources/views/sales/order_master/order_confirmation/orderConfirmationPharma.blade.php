<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
	.page-break-always{ page-break-after: always;}
	.page-break-auto{ page-break-after:auto;}
	@page { margin: 130px 20px 110px 20px;font-size:13px;}
	#header {left: 0;position: fixed;right: 0;text-align: center;top: -125px;width: 100%;height:auto;}
	#footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
	#content {bottom: 0;height: auto;left: 0;right: 0;width: 100%;}
	#footer .page:after { content: counter(page, upper-roman); }
	td table td {padding:1px!important;border-bottom: 0px!important;}
	p{padding:2px 0px !important;margin:0!important;}
	.sno{width:6%!important;font-size:12px!important;padding:5px 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.category{font-size:14px!important;padding:0 5px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;}
	.parameter{width:50%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.methodName{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.equipmentName{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.requirementName{width:10%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.testResult{width:10%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
	.pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:3px 2px!important;}   
	.header-content table,footer-content table{border-collapse:collapse;}   
	.left-content{border: 0px none ! important; vertical-align: top;}
	.left-content .cetificate-section{color:#4d64a1;margin-top:-2px;font-size: 11px!important; border: 1px solid; display: block; vertical-align: middle; padding: 2px; width: 200px;}
	.left-content .validity-section{color:#4d64a1;font-size:11px!important; border: 1px solid; display: block; vertical-align: middle; padding: 2px; width: 111px;}   
	.middle-content{color:#4d64a1;border:0px !important;text-align:center!important;}
	.middle-content h3{font-family: times of romen; font-weight: 600; color: rgb(77, 100, 161); margin: 0px; font-size: 30px !important;}
	.middle-content .form-section{color: rgb(138, 47, 48);margin:0px!important;font-weight: bold;border: 2px solid rgb(138, 47, 48);font-size: 15px;padding:2px!important;}
	.middle-content .lic-no-section{color:#4d64a1;font-size:11px!important;padding:5px!important;margin:0px!important;}
	.middle-content .title-section{color:#4d64a1;font-weight: 600; color: rgb(77, 100, 161);padding:0px!important;font-size: 28px!important;line-height:28px!important;}
	.middle-content .sub-title-section{color:#4d64a1;font-size:11px!important;padding:0px!important;margin:-200px!important;}
	.right-content{border: 0px none ! important; color: #d7563a; vertical-align: top; padding: 5px; text-align: center ! important; font-size: 12px ! important;vertical-align: top;}
	.footer-content h3 {font-family: times of romen; font-weight: 600; color:#4d64a1;margin: 0px; font-size: 15px !important;padding: 0 !important;}
	.footer-content p,h5 {font-size:11px !important;margin:0 !important;padding: 0 !important;}
	.footer-content ul {margin: 0 !important;padding: 0 !important;}
	.footer-content ul li {font-size: 11px !important;margin-top: 0px !important;padding: 0 !important;}
	.rightSection{display:none;}
	.middle-content > p {margin: 0 !important;padding: 0 !important;}
	td.middle-content>p:nth-child(2) {padding: 5px 0 0 0!important;}
	</style>

	<!--- header-->
	<div id="header">

		<div class="header-content"@if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3'))) style="visibility: hidden;"@endif>
			<?php echo !empty($viewData['order']['header_content']) ? htmlspecialchars_decode($viewData['order']['header_content']) : '';?>
		</div>

		<table width="100%" style="border-collapse:collapse;@if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3')))visibility: hidden;@endif">
			<tr>
			<td align="left" width="25%">&nbsp;</td>
			<td width="60%" align="center"><b style="padding: 5px;font-size: 16px; width: 200px;">TEST CONFIRMATION DOCUMENT</b></td>
			<td align="left" width="15%">&nbsp;</td>
			</tr>	    
		</table>
	</div>
	<!--- header end-->

	<!--- footer start-->
	<div id="footer" class="footer-content">
		<div>
			<table valign="bottom" width="100%" style="border-collapse:collapse;">
			<tr>
				<td align="center" colspan="6" style="margin-top: 0px!important;">
				<b style="text-align:center;">
					<script type='text/php'>
					if(isset($pdf)){
				$font = $fontMetrics->get_font('serif','bold');
				$size = 11;
				$y    = $pdf->get_height() - 765;
				$x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
				$pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
					}
					</script>
				</b>
				</td>
			</tr>
			</table>
		</div>      
		<div @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3')))style="visibility:hidden;"@endif>
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
						<td style="padding:0px;" width="100%" align="left" colspan="3"><b style="display: inline-block;vertical-align: middle;width: 100px;">Sample Name</b><b>:</b><b>{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : ''}}</b></td>
					</tr>				       
					<tr>
						<td style="padding:0px;" align="left" colspan="2" width="50%"><b style="display: inline-block;vertical-align: middle;vertical-align: middle;width: 100px;">Supplied By</b><b>:</b>{{!empty($viewData['order']['supplied_by']) ? $viewData['order']['supplied_by'] : '' }}</td>
						<td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Sample Reg. No.</b><b>:</b>{{!empty($viewData['order']['order_no']) ?  $viewData['order']['order_no'] : ''}}</td>
					</tr>
					<tr>
						<td style="padding:0px;" align="left" colspan="2" width="50%"><b style="display: inline-block;vertical-align: middle;width: 100px;">Manufactured By</b><b>:</b>{{!empty($viewData['order']['manufactured_by']) ?  $viewData['order']['manufactured_by'] : ''}}</td>
						<td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Party Ref. Date</b><b>:</b>{{!empty($viewData['order']['letter_no']) ?  $viewData['order']['letter_no'] : ''}}</td>
					</tr>					     
					<tr>
						<td style="padding:0px;" align="left" colspan="2" width="50%"><b style="display: inline-block;vertical-align: middle;width: 100px;">Submitted By</b><b>:</b>{{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}{{!empty($viewData['order']['city_name']) ? '-'.$viewData['order']['city_name'] : ''}} ( {{!empty($viewData['order']['state_name']) ? $viewData['order']['state_name'] : ''}} ) </td>
						<td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Booking Date</b><b>:</b>{{!empty($viewData['order']['order_date']) ?  date(DATEFORMAT,strtotime($viewData['order']['order_date'])) :''}}</td>
					</tr>
					<tr>
						<td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 100px;">Mfg. Lic. No.</b><b>:</b>{{!empty($viewData['order']['mfg_lic_no']) ? $viewData['order']['mfg_lic_no'] : ''}}</td>
						<td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 60px;">Batch No.</b><b>:</b>{{!empty($viewData['order']['batch_no']) ?  $viewData['order']['batch_no'] : ''}} </td>
						<td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 95px;">Batch Size</b><b>:</b>{{!empty($viewData['order']['batch_size']) ? $viewData['order']['batch_size'] :'' }}</td>
					</tr>				       
					<tr>
						<td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 100px;">D/M</b><b>:</b>{{!empty($viewData['order']['mfg_date']) ? $viewData['order']['mfg_date'] : '-'}}</td>
						<td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 60px;">D/E</b><b>:</b>{{!empty($viewData['order']['expiry_date']) ? $viewData['order']['expiry_date'] : '-'}}</td>
						<td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 95px;">Sample Qty</b><b>:</b>{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : ''}}</td>
					</tr>				       
					<tr>
						<td align="right" colspan="3" style="padding:5px!important;" width="100%" style="padding:0px;margin:0px;font-weight:bold;"><b>Date of completion of analysis : </b></td>
					</tr>				       
					@if(!empty($viewData['order']['stability_note']))
					<tr>
						<td align="center" colspan="3" width="100%"><b>{{!empty($viewData['order']['stability_note']) ? $viewData['order']['stability_note'] : '-'}}</b></td>
					</tr>		     
					@endif
				</tbody>
			</table>

			<!--Test Parameters Category-->
			@if(!empty($viewData['orderParameters']))
			<table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">
			<tbody>	       
				@foreach($viewData['orderParameters'] as $paramKey => $orderParametersCategoryName)
				@if(strtolower($orderParametersCategoryName['categoryName']) == strtolower($viewData['order']['testParametersWithSpace']))
					<tr>
				<th align="left" width="40%">{{strtoupper($orderParametersCategoryName['categoryName'])}}</th>
				@if(!empty($hasContainEquipment))
				<th align="center">Equipment</th>
				@endif
				<th align="center">RESULT</th>
				<th align="center">LIMIT</th>
					</tr>		     		     
					@foreach($orderParametersCategoryName['categoryParams'] as $key => $orderParametersCategoryParams)
				<tr>
					<td   class="parameter"  align="left"><?php echo trim($orderParametersCategoryParams['test_parameter_name']);?>
					<?php echo !empty($orderParametersCategoryParams['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'. $orderParametersCategoryParams['non_nabl_parameter_symbol'].'</sup>' : '' ?>
				</td>
					@if(empty($orderParametersCategoryParams['description']))
						@if(!empty($hasContainEquipment))
						<td class="equipmentName" align="center">{{!empty($orderParametersCategoryParams['equipment_name']) ? $orderParametersCategoryParams['equipment_name'] : ''}}</td>
						@endif
						<td class="testResult" align="left"></td>
						<td class="requirementName" align="center">
						{{!empty($orderParametersCategoryParams['requirement_from_to']) ? trim($orderParametersCategoryParams['requirement_from_to']) : ''}}
						&nbsp;
						{{!empty($orderParametersCategoryParams['claim_value_unit']) ? strtoupper($orderParametersCategoryParams['claim_value_unit']) : ''}}
						</td>
					@else
						<td class="testResult"
						@if(!empty($hasContainEquipment))
						colspan="3"
						@else
						colspan="2"
						@endif
						align="left"></td>
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
			@if(!empty($hasContainEquipment) && !empty($viewData['order']['hasClaimValue']))
				colspan="6"
			@elseif(!empty($hasContainEquipment) && empty($viewData['order']['hasClaimValue']))
				colspan="5"
			@elseif(empty($hasContainEquipment) && !empty($viewData['order']['hasClaimValue']))
				colspan="5"
			@elseif(empty($hasContainEquipment) && empty($viewData['order']['hasClaimValue']))
				colspan="4"
			@endif
			style="padding:0px;"><b style="margin:0px;padding:0px;">{{!empty($viewData['order']['header_note']) ? $viewData['order']['header_note'] : '&nbsp;'}}</b></td>
		</tr>
		@endif
		
		<tr>
			<th align="left" width="40%">{{strtoupper($orderParametersCategoryName['categoryName'])}}</th>
			@if(!empty($hasContainEquipment))
			<th align="center">Equipment</th>
			@endif
			<th align="center">RESULT</th>
			@if(!empty($viewData['order']['hasClaimValue']))
			<th align="center">CLAIM</th>
			@endif
			<th align="center">LIMIT</th>
			<th align="center">METHOD</th>
		</tr>
		
		@foreach($orderParametersCategoryName['categoryParams'] as $key => $orderParametersCategoryParams)
			<tr>
				<td class="parameter"  align="left"><?php echo trim($orderParametersCategoryParams['test_parameter_name']);?>
					<?php echo !empty($orderParametersCategoryParams['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'. $orderParametersCategoryParams['non_nabl_parameter_symbol'].'</sup>' : '' ;?>
                  
				</td>
				@if(empty($orderParametersCategoryParams['description']))
				@if(!empty($hasContainEquipment))
				<td class="equipmentName" align="center">{{!empty($orderParametersCategoryParams['equipment_name']) ? $orderParametersCategoryParams['equipment_name'] : ''}}</td>
				@endif
				<td class="testResult" align="left"></td>
				@if(!empty($viewData['order']['hasClaimValue']))
				<td class="requirementName" align="center">
					@if(!empty($orderParametersCategoryParams['claim_value']))
					{{$orderParametersCategoryParams['claim_value']}}
					&nbsp;
					{{!empty($orderParametersCategoryParams['claim_value_unit']) ? strtoupper($orderParametersCategoryParams['claim_value_unit']) : ''}}
					@endif
				</td>
				@endif
				<td class="requirementName" align="center">
				{{!empty($orderParametersCategoryParams['requirement_from_to']) ? trim($orderParametersCategoryParams['requirement_from_to']) : ''}}
				&nbsp;
				{{!empty($orderParametersCategoryParams['claim_value_unit']) ? strtoupper($orderParametersCategoryParams['claim_value_unit']) : ''}}
				</td>
				<td class="methodName" align="center">{{!empty($orderParametersCategoryParams['method_name']) ? $orderParametersCategoryParams['method_name'] : ''}}</td>
				@else				 
				<td class="testResult" 				 
				@if(!empty($hasContainEquipment) && !empty($viewData['order']['hasClaimValue']))
					colspan="5"
				@elseif(!empty($hasContainEquipment) && empty($viewData['order']['hasClaimValue']))
					colspan="4"
				@elseif(empty($hasContainEquipment) && !empty($viewData['order']['hasClaimValue']))
					colspan="4"
				@elseif(empty($hasContainEquipment) && empty($viewData['order']['hasClaimValue']))
					colspan="3"
				@endif
				align="left"></td>
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
			<th align="left" width="40%">{{strtoupper($orderParametersCategoryName['categoryName'])}}</th>
			@if(!empty($hasContainEquipment))
			<th align="center">Equipment</th>
			@endif
			<th align="center">RESULT</th>
			<th align="center">LIMIT</th>
		</tr>		     		     
		@foreach($orderParametersCategoryName['categoryParams'] as $key => $orderParametersCategoryParams)
			<tr>
				<td class="parameter" align="left"><?php echo trim($orderParametersCategoryParams['test_parameter_name']);?>
					<?php echo !empty($orderParametersCategoryParams['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($orderParametersCategoryParams['non_nabl_parameter_symbol']).'</sup>' : '';?>
				</td>
				@if(empty($orderParametersCategoryParams['description']))
				@if(!empty($hasContainEquipment))
				<td class="equipmentName" align="center">{{!empty($orderParametersCategoryParams['equipment_name']) ? $orderParametersCategoryParams['equipment_name'] : ''}}</td>
				@endif
				<td class="testResult" align="left"></td>
				<td class="requirementName" align="center">
				{{!empty($orderParametersCategoryParams['requirement_from_to']) ? trim($orderParametersCategoryParams['requirement_from_to']) : ''}}
				&nbsp;
				{{!empty($orderParametersCategoryParams['claim_value_unit']) ? strtoupper($orderParametersCategoryParams['claim_value_unit']) : ''}}
				</td>
				@else
				<td class="testResult"
				@if(!empty($hasContainEquipment))
					colspan="3"
				@else
					colspan="2"
				@endif
				align="left"></td>
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
	<table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;">
	<tr>
		<td colspan="3" style="border: 1px solid #fff;">
		<table class="pdftable" width="100%" style="border-collapse:collapse;">
			<tr>
		<td width="100%" align="center" style="margin-top:10px!important;border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="3"><b style="font-size:14px!important;vertical-align: middle;">*****End Of Document*****</td>
			</tr>
		</table>
		</td>
	</tr>
	</table>
	<!--/Notes and Remarks Section-->      
	</div>
	</div>
	<!--/content-->
	</body>
</html>
<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
    .page-break-always{ page-break-after: always;}
    .page-break-auto{ page-break-after:auto;}
    @page { margin: 60px 20px 90px 20px;font-size:13px;}	
    #header {left: 0;position: fixed;right: 0;text-align: center;top: -55px;width: 100%;height:auto;}
    #footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
    #content {bottom: 0;height:auto;left: 0;right: 0;width: 100%;}
    td table td {border-bottom: 0px!important;}
    p{padding:0!important;margin:0!important;}
    .sno{width:6%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .category{font-size:13px!important;padding:0 5px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;}
    .parameter{width:34%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .methodName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .equipmentName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .requirementName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .testResult{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
    .pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:2px 2px!important;vertical-align: middle;}
    .header-content table,footer-content table{border-collapse:collapse;}
    .left-content{border: 0px none ! important; vertical-align: top;}
    .middle-content{color:#4d64a1;border:0px !important;text-align:center!important;}
    .middle-content h3{font-family: times of romen; font-weight: 600; color: rgb(77, 100, 161); margin: 0px; font-size: 30px !important;}
    .right-content{border: 0px none ! important; color: #d7563a; vertical-align: top; padding: 5px; text-align: center ! important; font-size: 12px ! important;}
    .footer-content h3 {font-family: times of romen; font-weight: 600; color:#4d64a1;margin: 0px; font-size: 15px !important;padding: 0 !important;}
    .footer-content p,h5 {font-size:11px !important;margin:0 !important;padding: 0 !important;}
    .footer-content ul {margin: 0 !important;padding: 0 !important;}
    .footer-content ul li {font-size: 11px !important;margin-top: 0px !important;padding: 0 !important;}
    .rightSection{display:none;}
    </style>

<!--header-->
<div id="header">
    <table width="100%" style="margin:0 auto;border-collapse:collapse;">
	<tr>
	<td width="10%" style="border: 0px none ! important; vertical-align: top;"><img width="65px" src="{{url('/public/images/template_logo.png') }}"/></td>
	<td width="80%" style="border:0px!important;text-align:center!important;">
	    <h3 style="font-family: times of romen; color: rgb(77, 100, 161); padding: 0px; font-size: 20px!important; font-weight: 600; margin-top: 5px!important;">Interstellar Testing Centre Private Limited</h3>
	    <h2 style="font-family: times of romen; color: rgb(77, 100, 161); padding: 0px; font-size: 14px!important; font-weight: 600; padding-top: -20px!important;">{{ !empty($viewData['order']['department_name']) ? strtoupper($viewData['order']['department_name']) :  ''}} : ANALYTICAL SHEET</h2>
	</td>
	<td width="10%" valign="top" class="side_cntnt" style="border:0!important;width: 20%;border: 0px!important;font-size: 12px!important;color: #4d64a1;">
	    <script type='text/php'>
	    if(isset($pdf)){
		$font = $fontMetrics->get_font('serif','bold');
		$size = 11;
		$y    = $pdf->get_height() - 830;
		$x    = $pdf->get_width() - 60 - $fontMetrics->get_text_width('1/1', $font, $size);
		$pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
	    }
	    </script>
	</td>
	</tr>
    </table>
</div>
<!--/header-->

<!--footer-->
<div id="footer">
    <table valign="bottom" width="100%" style="margin:0 auto;border-collapse:collapse;">
	<tr>
	    <td colspan="3">
		<table width="100%">
		    <tr>
			<td width="33.33%" align="left" valign="bottom">
			    @if(defined('USERSIGNATURE') && !empty(USERSIGNATURE) && file_exists(DOC_ROOT.SIGN_PATH.USERSIGNATURE))
			    <p><img height="30px" width="70px" alt="{{USERSIGNATURE}}" src="{{DOC_ROOT.SIGN_PATH.USERSIGNATURE}}"/></p>
			    @endif
			    @if(defined('USERNAME') && !empty(USERNAME))
			    <p style="font-size:10px;">{{USERNAME}}</p>
			    @endif
			    <b>Schedular</b>
			</td>
			<td width="33.33%" valign="bottom" align="center"><b>Analyst</b><p></p></td>
			<td width="33.33%" valign="bottom" align="right" style=""><b>Reviewer</b><p></p></td>
		    </tr>
		</table>
	    </td>
	</tr>
    </table>
</div>
<!--/footer-->
    
<body>
    <!--content-->
    <div id="content">
	<div class="page-break-auto">
	    <table class="pdftable" border="1" width="100%" style="margin:0 auto;border-collapse:collapse;">
		
		<tr>
		    <td width="66.7%" colspan="2" align="left"><b style="display: inline-block;vertical-align: middle; width: 92px;">Sampling Name</b><b>:</b>{{!empty($viewData['order']['sample_description']) ? trim($viewData['order']['sample_description']) : '' }}</td>
		    <td width="33.3%" align="left"><span style="display: inline-block;vertical-align: middle;padding-left:2px!important;"><img height="18px" src="{{!empty($viewData['order']['barcode']) ? $viewData['order']['barcode'] : ''}}"></span></td>
		</tr>    
    
		<tr>
		    <td width="33.3%" align="left"><b style="display: inline-block;vertical-align: middle; width: 92px;">Batch No.</b><b>:</b>{{!empty($viewData['order']['batch_no']) ? $viewData['order']['batch_no'] : '' }}</td>
		    <td width="33.3%" align="left"><b style="display: inline-block;vertical-align: middle; width: 63px;">Batch Size</b><b>:</b>{{!empty($viewData['order']['batch_size']) ? $viewData['order']['batch_size'] : '' }}</td>
		    <td width="33.3%" align="left"><b style="display: inline-block;vertical-align: middle; width: 98px;">Sample Code</b><b>:</b>{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : '' }}</td>
		</tr>
	    
		<tr>
		    <td width="33.3%" align="left"><b style="display: inline-block;vertical-align: middle; width: 92px;">Sample Qty.</b><b>:</b>{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : '' }}</td>
		    <td width="33.3%" align="left"><b style="display: inline-block;vertical-align: middle; width: 63px;">Mfg. Date</b><b>:</b>{{!empty($viewData['order']['mfg_date']) ? $viewData['order']['mfg_date'] : '' }}</td>
		    <td width="33.3%" align="left"><b style="display: inline-block;vertical-align: middle; width: 98px;">Sealed/Unsealed</b><b>:</b>@if($viewData['order']['is_sealed']==0){{'Unsealed'}}@elseif($viewData['order']['is_sealed']==1){{'Sealed'}}@elseif($viewData['order']['is_sealed']==2){{'Intact'}}@elseif($viewData['order']['is_sealed']==3){{''}}@endif</td>
		</tr>
	    
		<tr>
		    <td width="33.3%" align="left"><b style="display: inline-block;vertical-align: middle; width: 92px;">Brand</b><b>:</b>{{!empty($viewData['order']['brand_type']) ? $viewData['order']['brand_type'] : '' }}</td>
		    <td width="33.3%" align="left"><b style="display: inline-block;vertical-align: middle; width: 63px;">Exp. Date</b><b>:</b>{{!empty($viewData['order']['expiry_date']) ? $viewData['order']['expiry_date'] : '' }}</td>
		    <td width="33.3%" align="left"><b style="display: inline-block;vertical-align: middle; width: 98px;">Sign/Unsign</b><b>:</b>@if($viewData['order']['is_signed']==0){{ 'Unsigned'}}@elseif($viewData['order']['is_signed']==1){{ 'Signed'}}@endif</td>
		</tr>
	    
		<tr>
		    <td width="66.7%" align="left" colspan="2"><b style="display: inline-block;vertical-align: middle; width: 92px;">Packing Mode</b><b>:</b>{{!empty($viewData['order']['packing_mode']) ? $viewData['order']['packing_mode'] : '' }}</td>
		    <td width="33.3%" align="left"><b style="display: inline-block;vertical-align: middle; width: 99px;">Party Code if any</b><b>:</b>{{!empty($viewData['order']['party_code']) ? $viewData['order']['party_code'] : '' }}</td>
		</tr>
	      
		<tr>
		    <td align="left" colspan="3"><b style="display: inline-block;vertical-align: middle; width: 92px;">Remarks</b><b>:</b>{{!empty($viewData['order']['remarks']) ? $viewData['order']['remarks'] : '' }}</td>							
		</tr>
		
	    </table>
	    
	    <table class="pdftable" border="1" width="100%" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">
		
		<tr>
		    <td align="center" colspan="6"><h3 style="text-align:center;margin:0px!important;padding:1px!important;">Sample Particulars</td>
		</tr>
		    
		<tr>
		    <td align="left" colspan="6"><b style="margin:0px 2px;display: inline-block;vertical-align: middle; width: 88px;">Testing As Per</b><b>:</b>{{!empty($viewData['order']['test_std_name']) ? $viewData['order']['test_std_name'] : '' }}</td>
		</tr>
	    
		<tr>
		    <th class="sno" align="center" style="padding:3px 0px!important;">S.No.</th>
		    <th class="parameter" align="left">Parameter</th>
		    <th class="equipmentName" align="center">Instruments</th>
		    <th class="methodName" align="center">Method</th>
		    <th class="requirementName" align="center">Claim</th>
		    <th class="testResult" align="center">Result</th>
		</tr>
		
		@if(!empty($viewData['orderParameters']))
		    @foreach($viewData['orderParameters'] as $key=>$orderParameterCategoryName)
			<tr>
			    <td class="sno" align="center"><b>{{++$key}}.</b></td>
			    @if(strtolower($orderParameterCategoryName['categoryName']) =='assay master')
				@if(!empty($viewData['order']['header_note']))
				<td class="category" align="left"><b>{{$orderParameterCategoryName['categoryName']}}</b></td>
				<td colspan="4" align="left"><b>{{ $viewData['order']['header_note'] }} </b></td>
				@else
				<td colspan="5" class="category" align="left"><b>{{$orderParameterCategoryName['categoryName']}}</b></td>
				@endif
			    @else
			    <td colspan="5" class="category" align="left"><b>{{$orderParameterCategoryName['categoryName']}}</b></td>
			    @endif
			</tr>
			@if(!empty($orderParameterCategoryName['categoryParams']))
			    <?php $charSNo = 'a';?>
			    @foreach($orderParameterCategoryName['categoryParams'] as $orderParameterCategoryParameters)
				@if(empty($orderParameterCategoryParameters['description']))
				    <tr>
					<td class="sno" align="center">{{ $charSNo++ }}.</td>
					<td class="parameter" align="left"><?php echo trim($orderParameterCategoryParameters['test_parameter_name']);?></td>
					<td class="equipmentName" align="center">{{!empty($orderParameterCategoryParameters['equipment_name']) && strtolower($orderParameterCategoryParameters['equipment_name']) != 'n/a' ? $orderParameterCategoryParameters['equipment_name'] : ''}}</td>
					<td class="methodName" align="center">{{!empty($orderParameterCategoryParameters['method_name']) && strtolower($orderParameterCategoryParameters['method_name']) != 'n/a' ? $orderParameterCategoryParameters['method_name'] : ''}}</td>
					<td class="requirementName" align="center">{{!empty($orderParameterCategoryParameters['claim_value']) && strtolower($orderParameterCategoryParameters['claim_value']) != 'n/a' ? $orderParameterCategoryParameters['claim_value'] : ''}}</td>
					<td class="testResult" align="left">{{!empty($orderParameterCategoryParameters['test_result']) && !empty($viewData['order']['status']) &&  $viewData['order']['status'] >= '8' ? $orderParameterCategoryParameters['test_result'] : ''}}</td>
				    </tr>
				@else
				    <tr>
					<td class="sno" align="center">{{ $charSNo++ }}.</td>
					<td class="parameter" align="left"><?php echo $orderParameterCategoryParameters['test_parameter_name'];?></td>
					<td class="testResult" align="center" colspan="4"><?php echo $orderParameterCategoryParameters['description'];?></td>
				    </tr>											
				@endif
			    @endforeach
			@endif
		    @endforeach
		@endif			    
	    </table>
	</div>
    </div>
    <!--/content-->	
</body>
</html>

<html>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
	.page-break-always{ page-break-after: always;}
	.page-break-auto{ page-break-after:auto;}
	@page { margin: 245px 20px 110px 20px;font-size:13px;}
	#header {left: 0;position: fixed;right: 0;text-align: center;top: -240px;width: 100%;height:auto;}
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
	.footer-content ul li {font-size: 11px !important;margin-top: 0px !important;padding: 0 !important;}
	.rightSection{display:none;}
	table.dash {border: 1px dashed #000;border-collapse: collapse;}
	table.dash td {border: 1px dashed #000;}
    </style>

<!--- header-->
<div id="header">
    
    <div class="header-content" @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('2','3')))style="visibility: hidden;"@endif>
	<?php echo !empty($viewData['order']['header_content']) ? htmlspecialchars_decode($viewData['order']['header_content']) : '';?>
    </div>
	
    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <td colspan="6">
                <table width="100%" style="padding: 0px !important;">
                    <tr>
                        <td width="33.3%" align="left"></td>
                        <td width="43.3%" align="center">
			    <b style="padding: 5px;font-size: 16px; width: 150px;">TEST CONFIRMATION DOCUMENT</b>
                        </td>
			<td width="23.3%" align="right">
			    <span style="font-weight:bold" align="right">
				<script type='text/php'>
				if(isset($pdf)){ 
				    $font = $fontMetrics->get_font('serif','Normal');
				    $size = 10;
				    $y    = $pdf->get_height() - 760;
				    $x    = $pdf->get_width() - 48 - $fontMetrics->get_text_width('1/1', $font, $size);
				    $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
				}
				</script>
			    </span><br />
			</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" border="1" style="padding: 0px !important;border-collapse:collapse">
        <tr>
            <td width="50%">
                <p style="margin-bottom: 27px;"><b>Issued To</b></p>
                <p style="margin: -25px 5px 30px 5px;font-size: 13px">
		    {{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}
		    <br/>{{!empty($viewData['order']['customer_address']) ? $viewData['order']['customer_address'] : ''}}
		    <br/>{{!empty($viewData['order']['city_name']) ? $viewData['order']['city_name'] : ''}}
                </p>
            </td>
            <td width="50%">
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Sample Reg. No.</b><b>:</b>{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}{{!empty($viewData['order']['is_amended_no']) ? '-'.$viewData['order']['is_amended_no'] :'' }}</p>
		<p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Sample Reg. Date </b><b>:</b>{{!empty($viewData['order']['order_date']) ? date(DATEFORMAT,strtotime($viewData['order']['order_date'])) : ''}}</p>
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Report Date</b><b>:</b></p>
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Customer Ref. No.</b><b>:</b>{{!empty($viewData['order']['reference_no']) ? $viewData['order']['reference_no'] : ''}}</p>
		<p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Letter Dated</b><b>:</b>{{!empty($viewData['order']['letter_no']) ? $viewData['order']['letter_no'] : ''}}</p>
            </td>
        </tr>
    </table>
</div>
<!--- /header end-->

<!--- footer start-->
<div id="footer" class="footer-content">    
    <div @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('2','3')))style="visibility: hidden;"@endif>
	<?php echo !empty($viewData['order']['footer_content']) ? htmlspecialchars_decode($viewData['order']['footer_content']) : '';?>
    </div>    
</div>
<!--- /footer start-->

<body>
    <div id="content">
	<div class="page-break-auto">
	    <?php
	    if(empty($notContainEquipment) && $viewData['order']['test_standard'] == '80'){
		$colspanCounter = 7 ;
	    }else if(!empty($notContainEquipment) && $viewData['order']['test_standard'] == '80'){
		$colspanCounter = 6 ;
	    }else if(empty($notContainEquipment) && $viewData['order']['test_standard'] != '80'){
		$colspanCounter = 6 ;
	    }else if(!empty($notContainEquipment) && $viewData['order']['test_standard'] != '80'){
		$colspanCounter = 5 ;
	    }else{
		$colspanCounter = 6;
	    }
	    ?>
	    <table class="pdftable" width="100%" border="1" style="border:1px solid #000;border-collapse:collapse;">
		<tr width="100%" style="border:1px solid #000;border-collapse:collapse;">
		    <td colspan="{{$colspanCounter}}">
			<table width="100%" style="border-collapse:collapse;">
			    <tr>
				<td align="left" width="49%" style="vertical-align: middle; padding-top: 5px ! important; padding-bottom: 5px ! important; border-top: 1px solid rgb(255, 255, 255);"><b>Test Report as per IS</b></td>
				<td align="left" width="50%" style="vertical-align: middle; padding-top: 5px ! important; padding-bottom: 5px ! important; border-top: 1px solid rgb(255, 255, 255);">{{!empty($viewData['order']['test_std_name']) ? ' : '.$viewData['order']['test_std_name'] : ''}}</td>
			    </tr>
			</table>
		    </td>
		</tr>			
		<tr width="100%" style="border:1px solid #000;border-collapse:collapse;">
		    <td colspan="{{$colspanCounter}}" style="border:0px !important">
			<table width="100%" style="border-collapse:collapse;">
			    @if(!empty($viewData['generalParameters']))
				@foreach($viewData['generalParameters'] as $generalParameters)
				    <tr>
					<td>
					    <table width="100%" style="border-collapse:collapse;">
						<tr>
						    <th align="left">{{strip_tags($generalParameters['categoryName'])}}</th>
						    <th align="left">&nbsp;</th>
						</tr>
						@foreach($generalParameters['categoryParams'] as $key=>$parameterValues)
						    <tr>
							<td style="padding:0px;" align="left" width="50%"><b style="display: inline-block;vertical-align: middle;"><?php echo $parameterValues['test_parameter_name'];
							
								echo !empty($parameterValues['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.$parameterValues['non_nabl_parameter_symbol'] .'</sup>' : ''; 
								?></b>
							</td>
							<td style="padding:0px;" align="left" width="50%"><span style="display: inline-block;vertical-align: middle;">:&nbsp;{{$parameterValues['test_result']}}</span></td>
						    </tr>
						@endforeach
					    </table>
					</td>
				    </tr>
				@endforeach
			    @endif
			</table>
		    </td>
		</tr>
	    </table>
	    
	    <table class="pdftable" width="100%" border="1" style="border:1px solid #000;border-collapse:collapse;margin-top:8px;">
		<tr>
		    <td align="center" colspan="{{$colspanCounter}}">
			<b style="padding-top:7px!important;padding-bottom:7px!important;font-size:14px!important;display: inline-block;vertical-align: middle;">TEST RESULTS</b>
		    </td>
		</tr>
		<tr>
		    <th align="center" class="sno">S.No.</th>
		    <th align="center" width="{{empty($notContainEquipment) ? "30%!important;" : "20%!important;" }}">Test Parameter</th>
		    @if(empty($notContainEquipment))
		    <th align="center" width="10%!important;">Inst.Used</th>
		    @endif
		    @if(!empty($viewData['order']['test_standard']) && $viewData['order']['test_standard'] == '80')
		    <th align="center" width="{{empty($notContainEquipment) ? "15%!important;" : "10%!important;" }}">Method</th>
		    <th align="center" width="{{empty($notContainEquipment) ? "15%!important;" : "10%!important;" }}">Acceptable Limit</th>
		    <th align="center" width="{{empty($notContainEquipment) ? "15%!important;" : "10%!important;" }}">Permissible Limit<span style="font-size:8px!important;">(Absence of Alternate Source)</span></th>
		    @else
		    <th align="center" width="20%!important;">Method</th>
		    <th align="center" width="{{!empty($notContainEquipment) ? "25%!important;" : "30%!important;" }}">Requirement</th>
		    @endif
		    <th align="center" width="{{empty($notContainEquipment) ? "20%!important;" : "10%!important;" }}">Result</th>
		</tr>
		<tr>
		    <td colspan="{{$colspanCounter}}" style="text-align:left;padding: 5px;"><b>Test Details :</b>&nbsp;
			<span>{{!empty($viewData['order']['header_note']) ? ucfirst($viewData['order']['header_note']) : '' }}</span>
		    </td>
		</tr>
		    
		@if(!empty($viewData['orderParameters']))
		    @foreach($viewData['orderParameters'] as $key => $orderParameterCategoryName)
			
			<tr>
			    <th class="sno" align="center">{{$key+1}}.</th>
			    <th class="category" align="left" colspan="{{$colspanCounter-1}}" style="padding:0 5px;">{{trim($orderParameterCategoryName['categoryName'])}}</th>
			</tr>
	    
			@if(!empty($orderParameterCategoryName['categoryParams']))
			    <?php $charNum = 'a';?>
			    @foreach($orderParameterCategoryName['categoryParams'] as $orderParaKey => $orderParameterCategoryParameters)
				<tr>
				    <td class="sno" align="center">{{ $charNum++ }}.</td>
					<td class="parameter"  width="{{empty($notContainEquipment) ? "30%" : "20%" }}" align="left"><?php 
						echo trim($orderParameterCategoryParameters['test_parameter_name']);
						echo !empty($orderParameterCategoryParameters['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.$orderParameterCategoryParameters['non_nabl_parameter_symbol'] .'</sup>' : ''; 
					?>
					
					</td>
				    @if(!empty($orderParameterCategoryParameters['description']))
					<td class="parameter" class="text-justify" colspan="{{$colspanCounter-2}}" align="left" style="padding:0 15px;">{{!empty($orderParameterCategoryParameters['description']) ? trim($orderParameterCategoryParameters['description']) : ''}} </td>
				    @else
					@if(empty($notContainEquipment))
					<td class="equipmentName" align="center" width="5%">{{!empty($orderParameterCategoryParameters['equipment_name']) ? $orderParameterCategoryParameters['equipment_name'] : ''}}</td>
					@endif
					@if(!empty($viewData['order']['test_standard']) && $viewData['order']['test_standard'] == '80')
					    <td class="methodName" align="center" width="{{empty($notContainEquipment) ? "15%" : "10%" }}"">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
					    <td class="requirementName" align="center" width="{{empty($notContainEquipment) ? "15%" : "10%" }}"">{{!empty($orderParameterCategoryParameters['standard_value_from']) ? trim($orderParameterCategoryParameters['standard_value_from']) : ''}}</td>
					    <td class="requirementName" align="center" width="{{empty($notContainEquipment) ? "15%" : "10%" }}"">{{!empty($orderParameterCategoryParameters['standard_value_to']) ? trim($orderParameterCategoryParameters['standard_value_to']) : ''}}</td>
					@else
					    <td class="methodName" align="center" width="20%">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
					    <td class="requirementName" align="center" width="25%">{{!empty($orderParameterCategoryParameters['requirement_from_to']) ? trim($orderParameterCategoryParameters['requirement_from_to']) : ''}}</td>
					@endif
					<td class="testResult" align="left" width="{{empty($notContainEquipment) ? "20%" : "10%" }}"></td>
				    @endif
				</tr>
			    @endforeach				    
			@endif
		    @endforeach
		@endif		    
		
		<tr>
		    <td colspan="{{$colspanCounter}}" style="padding-top:5px!important;border: 1px solid #fff;margin-top:10px!important;">
			<table class="pdftable" width="100%" style="border-collapse:collapse;">
			    <tr>
			       <td width="100%" align="center" style="border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="6"><b style="font-size:14px!important;vertical-align: middle;">*****End Of Document*****</td>
			    </tr>
			</table>
		    </td>
		</tr>

	    </table>	    
	</div>
    </div>
</body>


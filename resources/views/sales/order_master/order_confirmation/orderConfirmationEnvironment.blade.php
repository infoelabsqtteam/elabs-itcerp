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
			    <span style="font-weight:normal" align="right">
				<script type='text/php'>
				if(isset($pdf)){ 
				    $font = $fontMetrics->get_font('serif','Normal');
				    $size = 10;
				    $y    = $pdf->get_height() - 760;
				    $x    = $pdf->get_width() - 50 - $fontMetrics->get_text_width('1/1', $font, $size);
				    $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
				}
				</script>
			    </span>&nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" border="1" style="padding:0px!important;border-collapse:collapse">
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
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Sample Reg. No.</b><b>:</b>{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}</p>
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
	    <table class="pdftable" width="100%" border="1" style="border-collapse:collapse;">
    
		<tr>
		    <td align="left" width="50%" colspan="2" style="font-size:13px;padding-top:5px!important;padding-bottom:5px!important;"><b style="display: inline-block;vertical-align: middle;">Test Report as per IS </b><b style="display: inline-block;vertical-align: middle;">:</b>{{!empty($viewData['order']['test_std_name']) ? $viewData['order']['test_std_name'] : ''}}</td>
		    <td align="left" width="50%" style="font-size:13px;"><b style="display: inline-block;vertical-align: middle;">With Amendment No.(s) </b><b>:</b></td>
		</tr>
		<tr>
		    <td align="left" style="border-bottom: 1px solid rgb(255, 255, 255);padding-top:7px!important;padding-bottom:7px!important;" width="50%" colspan="3"><b style="display: inline-block;vertical-align: middle;font-size:14px;">PART A : PARTICULARS OF SAMPLE SUBMITTED</b></td>
		</tr>
		    
	    </table>
		
	    <?php $serialAlphabet = 'a';?>
		
	    <table class="pdftable" width="100%" border="1" style="border-collapse:collapse;">
		
		@if(!empty($viewData['order']['sample_description']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{$serialAlphabet}}))</td>
		    <td width="50%" align="left">Nature of Sample</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['grade_type']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Grade / Variety / Type / Class / Size etc.</td>
		    <td width="45%" align="left"></td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['brand_type']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Brand Name</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['brand_type']) ? $viewData['order']['brand_type'] : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['declared_values']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Declared Values,if any</td>
		    <td align="left">{{!empty($viewData['order']['declared_values']) ? $viewData['order']['declared_values'] : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['barcode']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Code No.</td>
		    <td width="45%" align="left"><img height="18px" src="{{!empty($viewData['order']['barcode']) ? $viewData['order']['barcode'] : ''}}">
		    </td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['batch_no']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Batch Number </td>
		    <td width="45%" align="left">{{!empty($viewData['order']['batch_no']) ? $viewData['order']['batch_no'] : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['mfg_date']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">D.O.M</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['mfg_date']) ? $viewData['order']['mfg_date'] : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['expiry_date']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Date of Expiry</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['expiry_date']) ? $viewData['order']['expiry_date'] : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['sample_qty']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Sample Quantity</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['batch_size']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Batch Size/Location</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['batch_size']) ? $viewData['order']['batch_size'] : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['packing_mode']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Mode of Packing</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['packing_mode']) ? $viewData['order']['packing_mode'] : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['order_date']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Date of Receipt</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['order_date']) ? date('d-m-Y',strtotime($viewData['order']['order_date'])) : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['order_date']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Date of Start</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['order_date']) ? date('d-m-Y',strtotime($viewData['order']['order_date'])) : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['report_date']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Date of Completion</td>
		    <td width="45%" align="left"></td>
		</tr>
		@endif
		
		@if(isset($viewData['order']['is_sealed']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Seal (Intact/Not Intact/Unsealed)</td>
		    <td width="45%" align="left">
			@if($viewData['order']['is_sealed'] == 0)
			    {{'Unsealed'}}
			@elseif($viewData['order']['is_sealed'] == 1)
			    {{ 'Sealed' }}
			@elseif($viewData['order']['is_sealed'] == 2)
			    {{'Intact'}}
			@elseif($viewData['order']['is_sealed'] == 3)
			    {{'NA'}}
			@elseif($viewData['order']['is_sealed'] == 4)
			    {{'Sealed by Officer Incharge'}}
			@elseif($viewData['order']['is_sealed'] == 5)
			    {{'Sealed by Department'}}
			@endif	
		    </td>
		</tr>
		@endif
		
		@if(isset($viewData['order']['is_signed']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">IO'S Signature (Signed/Unsigned)</td>
		    <td width="45%" align="left">
			@if(isset($viewData['order']['is_signed']))
			    @if($viewData['order']['is_signed'] == 0)
				{{'Unsigned'}}
			    @elseif($viewData['order']['is_signed'] == 1)
				{{'Signed'}}
			    @elseif($viewData['order']['is_sealed'] == 2)
				{{'NA'}}
			    @elseif($viewData['order']['is_sealed'] == 4)
				{{'Signed by Officer Incharge'}}
			    @elseif($viewData['order']['is_sealed'] == 5)
				{{'Signed by Department'}}
			    @endif
			@endif
		    </td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['remarks']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Any Other Information</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['remarks']) ? $viewData['order']['remarks'] : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['customer_name']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Test Request Submitted By</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}{{!empty($viewData['order']['city_name']) ? '-'.$viewData['order']['city_name'] : ''}} ( {{ !empty($viewData['order']['state_name']) ? $viewData['order']['state_name'] : ''}} )</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['manufactured_by']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Manufactured By</td>
		    <td align="left">{{!empty($viewData['order']['manufactured_by']) ? $viewData['order']['manufactured_by'] : ''}}</td>
		</tr>
		@endif
		
		@if(!empty($viewData['order']['supplied_by']))
		<tr>
		    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
		    <td width="50%" align="left">Supplied By</td>
		    <td width="45%" align="left">{{!empty($viewData['order']['supplied_by']) ? $viewData['order']['supplied_by'] : ''}}</td>
		</tr>
		@endif
		
	    </table>
		
	    <table width="100%" class="pdftable dash" style="border-collapse:collapse;text-align:center;">		
		<tr>
		    <td align="left" style="border-top: 1px solid #fff!important;border-right: 1px solid!important;border-bottom: 1px solid!important;border-left: 1px solid!important;padding-top:7px!important;padding-bottom:7px!important;" colspan="3"><b style="font-size:14px;display: inline-block;vertical-align: middle;">PART B : SUPPLIMENTARY INFORMATIONS</b></td>
		</tr>
		<tr>
		    <td align="center" width="5%" style="border-left: 1px solid!important;border-right: 1px solid #fff!important;padding-top:6px!important;padding-bottom:6px!important;">a.</td>
		    <td align="left" width="74%" style="border-right: 1px solid #fff!important;">Reference to sampling procedure, whenever applicable</td>
		    <td align="left" width="20%" style="border-right: 1px solid!important;">&nbsp;:&nbsp;</td>
		</tr>
		<tr>
		    <td align="center" width="5%" style="border-right: 1px solid #fff!important;border-left: 1px solid!important;padding-top:6px!important;padding-bottom:6px!important;">b.</td>
		    <td align="left" width="74%" style="border-right: 1px solid #fff!important;">Supporting documents for the measurement taken and results derived like graphs, tables, sketches and / or photographs as appropriate to test reports, if any</td>
		    <td align="left" width="20%" style="border-right: 1px solid!important;">&nbsp;:&nbsp;</td>
		</tr>
		<tr>
		    <td align="center" width="5%" style="border-right: 1px solid #fff!important;border-bottom: 1px solid!important;border-left: 1px solid!important;padding-top:6px!important;padding-bottom:6px!important;">c.</td>
		    <td align="left" width="74%" style="border-right: 1px solid #fff!important;border-bottom: 1px solid!important;">Deviation from the test methods as prescribed in relevant ISS/WORK Instruments, if any</td>
		    <td align="left" width="20%" style="border-bottom: 1px solid!important;border-right: 1px solid!important;">&nbsp;:&nbsp;<div class="page-break"></div></td>
		</tr>
	    </table>	    
	
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
	    
	    <table class="pdftable" border="1" width="100%" style="border-collapse:collapse;margin-top:5px!important;">
	       
		<tr>
		    <td align="left" colspan="{{$colspanCounter}}">
			<b style="padding-top:7px!important;padding-bottom:7px!important;font-size:14px!important;display: inline-block;vertical-align: middle;">PART C : TEST RESULTS</b>
		    </td>
		</tr>
		
		<tr>
		    <th align="center" class="sno">S.No.</th>
		    <th align="center" width="{{!empty($notContainEquipment) ? "20%!important;" : "30%!important;" }}">Test Parameter</th>
		    @if(empty($notContainEquipment))
		    <th align="center" width="15%!important;">Inst.Used</th>
		    @endif
		    @if(!empty($viewData['order']['test_standard']) && $viewData['order']['test_standard'] == '80')
		    <th align="center" width="{{!empty($notContainEquipment) ? "15%!important;" : "20%!important;" }}">Method</th>
		    <th align="center" width="{{!empty($notContainEquipment) ? "15%!important;" : "20%!important;" }}">Acceptable Limit</th>
		    <th align="center" width="{{!empty($notContainEquipment) ? "15%!important;" : "20%!important;" }}">Permissible Limit<span style="font-size:8px!important;">(Absence of Alternate Source)</span></th>
		    @else
		    <th align="center" width="20%!important;">Method</th>
		    <th align="center" width="{{!empty($notContainEquipment) ? "25%!important;" : "30%!important;" }}">Requirement</th>
		    @endif		    
		    <th align="center" width="15%" >Result</th>
		</tr>
		
		<tr>
		    <td colspan="{{$colspanCounter}}" style="text-align:left;padding: 5px;"><b>Test Details :</b>&nbsp;
			<span>{{!empty($viewData['order']['header_note']) ? ucfirst($viewData['order']['header_note']) : '' }}</span>
		    </td>
		</tr>
		
		@if(!empty($viewData['orderParameters']))
		    @foreach($viewData['orderParameters'] as $key => $orderParameterCategoryName)
				
			<tr>
			    <td class="sno" align="center">{{$key+1}}.</td>
			    <th class="category" align="left" colspan="{{$colspanCounter-1}}" style="padding:0 5px;">{{trim($orderParameterCategoryName['categoryName'])}}</th>
			</tr>
			
			@if(!empty($orderParameterCategoryName['categoryParams']))
			    <?php $charNum = 'a';?>
			    @foreach($orderParameterCategoryName['categoryParams'] as $orderParaKey => $orderParameterCategoryParameters)
				<tr>
				    <td class="sno" align="center">{{ $charNum++ }}.</td>
					<td class="parameter" width="{{empty($notContainEquipment) ? "20%" : "20%" }}" align="left"><?php echo trim($orderParameterCategoryParameters['test_parameter_name']);
					echo !empty($orderParameterCategoryParameters['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.$orderParameterCategoryParameters['non_nabl_parameter_symbol'] .'</sup>' : ''; 
					?>
					</td>
				    @if(!empty($orderParameterCategoryParameters['description']))
					<td class="text-justify" colspan="{{$colspanCounter-2}}" align="left" style="padding:0 15px;">{{!empty($orderParameterCategoryParameters['description']) ? trim($orderParameterCategoryParameters['description']) : ''}} </td>
				    @else
					@if(empty($notContainEquipment))
					<td class="equipmentName" align="center" width="5%">{{!empty($orderParameterCategoryParameters['equipment_name']) ? $orderParameterCategoryParameters['equipment_name'] : ''}}</td>
					@endif
					@if(!empty($viewData['order']['test_standard']) && $viewData['order']['test_standard'] == '80')
					<td class="methodName" align="center" width="{{empty($notContainEquipment) ? "10%" : "15%" }}">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
					<td class="requirementName" align="center" width="{{empty($notContainEquipment) ? "10%" : "15%" }}">{{!empty($orderParameterCategoryParameters['standard_value_from']) ? trim($orderParameterCategoryParameters['standard_value_from']) : ''}}</td>
					<td class="requirementName" align="center" width="{{empty($notContainEquipment) ? "10%" : "15%" }}">{{!empty($orderParameterCategoryParameters['standard_value_to']) ? trim($orderParameterCategoryParameters['standard_value_to']) : ''}}</td>
					@else
					<td class="methodName" align="center" width="20%">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
					<td class="requirementName" align="center" width="25%">{{!empty($orderParameterCategoryParameters['requirement_from_to']) ? trim($orderParameterCategoryParameters['requirement_from_to']) : ''}}</td>
					@endif
					<td class="testResult" align="left" width="{{empty($notContainEquipment) ? "10%" : "20%" }}"></td>
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

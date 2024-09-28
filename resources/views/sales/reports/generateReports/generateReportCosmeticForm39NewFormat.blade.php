<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
	.page-break-always{ page-break-after: always;}
	.page-break-auto{ page-break-after:auto;}
	@if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus))
	@page { margin: 260px 20px 265px 20px;font-size:13px;}
	#header {left: 0;position: fixed;right: 0;text-align: center;top: -250px;width: 100%;height:auto;}
	@else
	@page { margin: 220px 20px 265px 20px;font-size:13px;}
    #header {left: 0;position: fixed;right: 0;text-align: center;top: -210px;width: 100%;height:auto;}
	@endif
	#footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
	#content {bottom: 0;height: auto;left: 0;right: 0;width: 100%;}
	#footer .page:after { content: counter(page, upper-roman); }
	td table td {padding:1px!important;border-bottom: 0px!important;}
	p{padding:2px 0px !important;margin:0!important;}
	.sno{width:6%!important;font-size:12px!important;padding:5px 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.category{font-size:13px!important;padding:0 5px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;}
	.parameter{width:40%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.parameter p{padding:0px!important;margin:0!important;display: inline-block!important;}
	.methodName{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.equipmentName{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.requirementName{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.testResult{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
	.pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
	.pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:3px 2px!important;vertical-align: middle;}
	.header-content table,footer-content table{border-collapse:collapse;}   
	.left-content{border: 0px none ! important; vertical-align: middle;}
	.left-content .cetificate-section{color:#4d64a1;margin-top:-2px;font-size: 11px!important; border: 1px solid; display: block; vertical-align: middle; padding: 2px; width: 200px;}
	.left-content .validity-section{color:#4d64a1;font-size:11px!important; border: 1px solid; display: block; vertical-align: middle; padding: 2px; width: 111px;}   
	.middle-content{font-family:times of romen;border:0px!important;text-align:center!important;vertical-align:top;}
	.middle-content > p {margin: 0 !important;padding: 0 !important;}
	.middle-content .form-section{color: rgb(138, 47, 48);margin:0px!important;font-weight: bold;border: 2px solid rgb(138, 47, 48)!important;;font-size: 15px;padding:2px!important;}
	.middle-content .lic-no-section{color:#4d64a1;font-size:11px!important;padding:5px!important;margin:0px!important;}
	.middle-content .title-section{color:#4d64a1;font-weight: 600; color: rgb(77, 100, 161);font-size: 28px!important;line-height:28px!important;}
	.middle-content .sub-title-section{color:#4d64a1;margin:0px!important;font-size:11px!important;}
	.right-content{border: 0px none ! important; color: #d7563a; vertical-align: top; padding: 5px; text-align: center ! important; font-size: 12px ! important;vertical-align: top;}
	.footer-content h3 {font-family: times of romen; font-weight: 600; color:#4d64a1;margin: 0px; font-size: 15px !important;padding: 0 !important;}
	.footer-content p,h5 {font-size:11px !important;margin:0 !important;padding: 0 !important;}
	.footer-content ul {margin: 0 !important;padding: 0 !important;}
	.footer-content ul li {font-size: 11px !important;margin-top: 0px !important;padding: 0 !important;}
	.rightSection{display:none!important;}
	td.middle-content>p:nth-child(2) {padding: 5px 0 0 0!important;}
	.reportText{font-style: italic;font-weight:bold;color: #fff;background: #8A2F30;font-size: 16px;padding:8px!important;}
   </style>

<!--- header-->
<div id="header">

    <div class="header-content" @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3'))) style="visibility: hidden;" @endif>
        <table width="100%">
            <tr>
                <td align="left" width="25%" class="left-content">
                    <p><img class="image-section" src="../public/images/template_logo.png" style="width:120px" /></p>
                    <p style="display:none;"><b class="cetificate-section">NABL Certificate No.: TC-5926</b></p>
                    <p style="display:none;" class="hidden"><b class="validity-section">Valid till : 04-06-2021</b></p>
                </td>
                <td align="center" width="60%" class="middle-content">
                    <p><b class="form-section">COS-24</b></p>
                    <p><b class="lic-no-section ">[Lic. No. 1-COS-23]</b></p>
                    <div class="middleSection title-section">Interstellar Testing Centre Pvt. Ltd.</div>
                    <p><b class="sub-title-section">(The Drugs and Cosmetics Act 1940 and the Rules thereunder)</b></p>
                </td>
                <td align="right" width="15%" class="right-content" style="vertical-align: top;">
                    @if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus))
                    <img class="" src="../public/images/report_nabl_logo_01_v1.jpg" width="100px" />
                    @else
                    <img class="rightSection" src="../public/images/report_nabl_logo_01_v1.jpg" width="100px" />
                    @endif
                </td>
            </tr>
        </table>
        <?php //echo !empty($viewData['order']['header_content']) ? htmlspecialchars_decode($viewData['order']['header_content']) : '';?>
    </div>

    <table width="100%" style="border-collapse:collapse;@if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus)) margin-top:-8px!important; @else margin-top:-1px!important; @endif @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3')))visibility: hidden;@endif">
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
    <table width="100%" border="1" class="pdftable" style="margin:0 auto;border-collapse:collapse;">
        <tr>
            <td width="50%">
                <p style="margin-bottom: 27px;"><b>Issued To:</b></p>
                <p style="margin: -25px 5px 30px 5px;font-size: 13px">
                    {{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}
                    <br />{{!empty($viewData['order']['customer_address']) ? $viewData['order']['customer_address'] : ''}}
                    <br />{{!empty($viewData['order']['city_name']) ? $viewData['order']['city_name'] : ''}}
                </p>
            </td>
            <td width="50%">
                @if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus))
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:150px!important;float:left;">ULR No.</b><b>:</b>{{!empty($viewData['order']['nabl_no']) ? $viewData['order']['nabl_no'] : ''}}</p>
                @endif
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:150px!important;float:left;">Report No.</b><b>:</b>{{!empty($viewData['order']['report_no']) ? $viewData['order']['report_no'] : ''}} </p>
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:150px!important;float:left;">Sample Registration No.</b><b>:</b>{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}</p>
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:150px!important;float:left;">Date of Issue of Report</b><b>:</b>{{!empty($viewData['order']['report_date']) ? date(DATEFORMAT,strtotime($viewData['order']['report_date'])) : ''}}</p>
            </td>
        </tr>
    </table>
</div>
<!--- header end-->

<!--- footer start-->
<div id="footer" class="footer-content">

    <div>
        <table width="100%" style="margin:0 auto;border-collapse:collapse;">
            <tr>
                <td align="center" colspan="6">
                    <b style="text-align:center;">
                        @if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus))
                        <script type='text/php'>
                            if(isset($pdf)){
								$font = $fontMetrics->get_font('serif','Normal');
								$size = 10;
								$y    = $pdf->get_height() - 727;
								$x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
								$pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
							}
						</script>
                        @elseif(!empty($viewData['order']['reportWithRightLogoWithoutNabl']) && !empty($nablLogoHeaderMarginStatus))
                        <script type='text/php'>
                            if(isset($pdf)){
								$font = $fontMetrics->get_font('serif','Normal');
								$size = 10;
								$y    = $pdf->get_height() - 727;
								$x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
								$pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
							}
						</script>
                        @else
                        <script type='text/php'>
                            if(isset($pdf)){
								$font = $fontMetrics->get_font('serif','Normal');
								$size = 10;
								$y    = $pdf->get_height() - 758;
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
        <table class="pdftable" width="100%" style="border-collapse:collapse;border-bottom:1px solid;">
            <tr>
                <td width="33.3%" align="left" valign="bottom"></td>
                <td width="33.3%" align="center" valign="bottom">
                    <p style="font-size:10px;padding-top:-5px!important;">
                        @if(!empty($viewData['orderTrackRecord']['reviewing']['user_signature']) && file_exists(DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['reviewing']['user_signature']))
                        <span style="display:inline-block;width:100%;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img style="width:70px;height:30px;padding-top:-5px!important;" alt="{{$viewData['orderTrackRecord']['reviewing']['user_signature']}}" src="{{DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['reviewing']['user_signature']}}" /></span></br>
                        @endif
                        {{!empty($viewData['orderTrackRecord']['reviewing']['username']) ? $viewData['orderTrackRecord']['reviewing']['username'] : '-'}}
                    </p>
                    <p style="font-size:10px;padding-top:-1px!important;">{{!empty($viewData['order']['reviewing_date']) ? date(DATEFORMAT,strtotime($viewData['order']['reviewing_date'])) : ''}}</p>
                    <p style="font-size:13px;padding-top:-1px!important;"><b>Reviewer</b></p>
                </td>
                <td width="33.3%" align="right" valign="bottom">
                    <p style="font-size:10px;padding-top:-5px!important;">
                        @if(!empty($viewData['orderTrackRecord']['finalizing']['user_signature']) && file_exists(DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['finalizing']['user_signature']))
                        <span style="float:right;width:100%;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img style="width:70px;height:30px;padding-bottom:-6px!important;" alt="{{$viewData['orderTrackRecord']['finalizing']['user_signature']}}" src="{{DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['finalizing']['user_signature']}}" /></span>
                        @endif
                        <span style="display:inline-block;width:100%;padding-top:-1px!important;">{{!empty($viewData['order']['finalizing_date'] ) ? date(DATEFORMAT,strtotime($viewData['order']['finalizing_date'])) : ''}}</span>
                    </p>
                    <p style="font-size:13px;padding-top:-1px!important;"><b>{{!empty($viewData['orderTrackRecord']['finalizing']['username']) ? $viewData['orderTrackRecord']['finalizing']['username'] : ''}}</b></p>
                    <p style="font-size:13px;padding-top:-1px!important;"><b>[Person In charge of Testing/Authorized Signatory]</b></p>
                </td>
            </tr>
        </table>
    </div>

    <div @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3')))style="visibility:hidden;" @endif>
        <?php echo !empty($viewData['order']['footer_content']) ? htmlspecialchars_decode($viewData['order']['footer_content']) : '';?>
    </div>

</div>
<!--- /footer start-->

<body>
    <!--content-->
    <div id="content">
        <div class="page-break-auto">
            <table width="100%" border="1" class="pdftable" style="margin:0 auto;border-collapse:collapse;">
                <tbody>
                    <tr>
                        <td style="padding:0px;" width="100%" align="center" colspan="2"><b style="vertical-align: middle;font-size:14px;text-align:center;">Sample Particulars</b></td>
                    </tr>
                    <tr>
                        <td style="padding:0px;" width="100%" align="left" colspan="2"><b style="display: inline-block;vertical-align: middle;width: 130px;">Name of Sample<?php echo Helper::getCustomerDefinedFieldSymbol('sample_description',$viewData['order']['customer_defined_fields']);?></b><b>:</b><b>{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : 'N/A'}}</b></td>
                    </tr>
                    <tr>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 130px;">Batch No./Lot No.<?php echo Helper::getCustomerDefinedFieldSymbol('batch_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['batch_no']) ?  $viewData['order']['batch_no'] : 'N/A'}} </td>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 160px;">Batch Size/Lot Size<?php echo Helper::getCustomerDefinedFieldSymbol('batch_size',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['batch_size']) ? $viewData['order']['batch_size'] : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 130px;">Submitted By<?php echo Helper::getCustomerDefinedFieldSymbol('customer_name',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}{{!empty($viewData['order']['city_name']) ? '-'.$viewData['order']['city_name'] : 'N/A'}} ( {{!empty($viewData['order']['state_name']) ? $viewData['order']['state_name'] : ''}} ) </td>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 160px;">Supplied By<?php echo Helper::getCustomerDefinedFieldSymbol('supplied_by',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['supplied_by']) ? $viewData['order']['supplied_by'] : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 130px;">Manufactured By<?php echo Helper::getCustomerDefinedFieldSymbol('manufactured_by',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['manufactured_by']) ?  $viewData['order']['manufactured_by'] : 'N/A'}}</td>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 160px;">Manufacturer License No.<?php echo Helper::getCustomerDefinedFieldSymbol('mfg_lic_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['mfg_lic_no']) ? $viewData['order']['mfg_lic_no'] : 'N/A'}}</td>
                    </tr>
                    <tr>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 130px;">Date of Manufacture<?php echo Helper::getCustomerDefinedFieldSymbol('mfg_date',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['mfg_date']) ? $viewData['order']['mfg_date'] : 'N/A'}}</td>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 160px;">Date of Expiry<?php echo Helper::getCustomerDefinedFieldSymbol('expiry_date',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['expiry_date']) ? $viewData['order']['expiry_date'] : 'N/A'}}</td>
                    </tr>
                    <tr>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 130px;">Sample Quantity<?php echo Helper::getCustomerDefinedFieldSymbol('sample_qty',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : 'N/A'}}</td>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 160px;">Sample Condition<?php echo Helper::getCustomerDefinedFieldSymbol('sample_condition',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['sample_condition']) ? $viewData['order']['sample_condition'] : 'N/A'}}</td>
                    </tr>
                    <tr>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 130px;">Packaging Details<?php echo Helper::getCustomerDefinedFieldSymbol('packing_mode',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['packing_mode']) ? $viewData['order']['packing_mode'] : 'N/A'}}</td>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 160px;">Customer's Letter No.<?php echo Helper::getCustomerDefinedFieldSymbol('reference_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['reference_no']) ?  $viewData['order']['reference_no'] : 'N/A'}}</td>
                    </tr>
                    <tr>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 130px;">Sample receipt date<?php echo Helper::getCustomerDefinedFieldSymbol('order_date',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['order_date']) ?  date(DATEFORMAT,strtotime($viewData['order']['order_date'])) : 'N/A'}}</td>
                        <td align="left" style="padding:0px;" width="50%"><b style="display: inline-block;vertical-align: middle;width: 160px;">Customer's Letter Dated<?php echo Helper::getCustomerDefinedFieldSymbol('letter_no',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['letter_no']) ?  date(DATEFORMAT,strtotime($viewData['order']['letter_no'])) : 'N/A'}}</td>
                    </tr>
                    <tr>
                        <td style="padding:0px;" width="100%" align="left" colspan="2"><b style="display: inline-block;vertical-align: middle;width: 130px;">Remarks<?php echo Helper::getCustomerDefinedFieldSymbol('remarks',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['remarks']) ? $viewData['order']['remarks'] : 'N/A'}}</td>
                    </tr>
                    @if(!empty($viewData['order']['stability_note']))
                    <tr>
                        <td style="padding:0px;" width="100%" align="center" colspan="2"><b>{{!empty($viewData['order']['stability_note']) ? $viewData['order']['stability_note'] : '-'}}</b></td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <?php
			$hasEquipmentStyle = $hasLimitStyle = '';
			if(empty($notContainEquipment) && empty($notContainLimit)){
				$colspanCounter = 6;
				$colspanAnalysis = 2;
				$colspanCompleted = 4;
			}else if(!empty($notContainEquipment) && empty($notContainLimit)){
				$colspanCounter  = 5;
				$colspanAnalysis = 2;
				$colspanCompleted = 3;
				$hasEquipmentStyle = ' style="display:none;"';
			}else if(empty($notContainEquipment) && !empty($notContainLimit)){
				$colspanCounter = 5;
				$colspanAnalysis = 2;
				$colspanCompleted = 3;
				$hasLimitStyle  = ' style="display:none;"';
			}else if(!empty($notContainEquipment) && !empty($notContainLimit)){
				$colspanCounter  = 4;
				$colspanAnalysis = 2;
				$colspanCompleted = 2;
				$hasEquipmentStyle = ' style="display:none;"';
				$hasLimitStyle     = ' style="display:none;"';
			}
			?>

            <table width="100%" border="1" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:10px!important;">

                <tr>
                    <th align="center" colspan="{{$colspanCounter}}"><b style="vertical-align: middle;font-size:14px;text-align:center;">Test Results</b></th>
                </tr>

                <tr>
                    <th align="left" colspan="{{$colspanCounter}}"><b style="vertical-align: middle;">Date of performance of Lab activity</b></th>
                </tr>
                <tr>
                    <td align="left" style="padding:0px;" colspan="{{$colspanAnalysis}}"><b style="display: inline-block;vertical-align: middle;width: 110px;">Analysis started on<?php echo Helper::getCustomerDefinedFieldSymbol('analysis_start_date',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['analysis_start_date']) ?  date(DATEFORMAT,strtotime($viewData['order']['analysis_start_date'])) : 'N/A'}}</td>
                    <td align="left" style="padding:0px;" colspan="{{$colspanCompleted}}"><b style="display: inline-block;vertical-align: middle;width: 130px;">Analysis completed on<?php echo Helper::getCustomerDefinedFieldSymbol('analysis_completion_date',$viewData['order']['customer_defined_fields']);?></b><b>:</b>{{!empty($viewData['order']['analysis_completion_date']) ?  date(DATEFORMAT,strtotime($viewData['order']['analysis_completion_date'])) : 'N/A'}}</td>
                </tr>
                <tr>
                    <th align="center" colspan="{{$colspanCounter}}"><b style="vertical-align: middle;font-size:14px;text-align:center;">&nbsp;</b></th>
                </tr>

                <tr>
                    <th align="center" class="sno">S.No.</th>
                    <th align="left" class="parameter">Test Parameter</th>
                    <th align="center" class="equipmentName" <?php echo $hasEquipmentStyle;?>>Inst. Used</th>
                    <th align="center" class="methodName">Method</th>
                    <th align="center" class="requirementName" <?php echo $hasLimitStyle;?>>Requirement</th>
                    <th align="center" class="testResult">Result</th>
                </tr>

                @if(!empty($viewData['order']['header_note']))
                <tr>
                    <td colspan="{{$colspanCounter}}" style="text-align:left;padding: 5px;">
                        <span>{{!empty($viewData['order']['header_note']) ? ucfirst($viewData['order']['header_note']) : '' }}</span>
                    </td>
                </tr>
                @endif

                @if(!empty($viewData['orderParameters']))
					@foreach($viewData['orderParameters'] as $key => $orderParameterCategoryName)

						<tr>
							<td align="center" style="width:5%!important;">{{$key+1}}.</td>
							<td class="category" align="left" colspan="{{$colspanCounter-1}}" style="padding:0 5px;">{{trim($orderParameterCategoryName['categoryName'])}}</td>
						</tr>

						@if(!empty($orderParameterCategoryName['categoryParams']))
							<?php $charNum = 'a';?>
							@foreach($orderParameterCategoryName['categoryParams'] as $orderParaKey => $orderParameterCategoryParameters)
								<tr>
									<td align="center" style="width:5%!important;">{{ $charNum++ }}.</td>
									<td class="parameter" align="left"><?php echo trim($orderParameterCategoryParameters['test_parameter_name']);?><?php echo !empty($orderParameterCategoryParameters['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($orderParameterCategoryParameters['non_nabl_parameter_symbol']).'</sup>' : '';?></td>
									@if(!empty($orderParameterCategoryParameters['description']))
									<td class="text-justify parameter" colspan="{{$colspanCounter-2}}" align="left" style="padding:0 15px;">{{!empty($orderParameterCategoryParameters['description']) ? trim($orderParameterCategoryParameters['description']) : ''}} </td>
									@else
									<td class="equipmentName" align="center" <?php echo $hasEquipmentStyle;?>>{{!empty($orderParameterCategoryParameters['equipment_name']) ? trim($orderParameterCategoryParameters['equipment_name']) : ''}}</td>
									<td class="methodName" align="center">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
									<td class="requirementName" align="center" <?php echo $hasLimitStyle;?>>{{!empty($orderParameterCategoryParameters['requirement_from_to']) ? trim($orderParameterCategoryParameters['requirement_from_to']) : ''}}</td>
									<td class="testResult" align="left">{{!empty($orderParameterCategoryParameters['test_result']) ? trim($orderParameterCategoryParameters['test_result']) :''}}</td>
									@endif
								</tr>
							@endforeach
						@endif
					@endforeach

					<tr>
						<td colspan="{{$colspanCounter}}" style="border: 1px solid #fff;">
							<table class="pdftable" width="100%" style="border-collapse:collapse;">
								<tr>
									<td width="100%" align="left" style="border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align:middle;font-size:11px!important;" colspan="{{$colspanCounter}}">
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
							</table>
						</td>
					</tr>
                @endif

            </table>

            <table class="pdftable" width="100%" style="border-collapse:collapse;margin-top:5px!important;">
                <tr>
                    <td width="100%" align="left" style="visibility:hidden;" colspan="{{$colspanCounter}}">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" align="left" style="border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="{{$colspanCounter}}"><b style="display: inline-block;font-size:14px!important;display: inline-block;vertical-align: middle;">NOTE :</b> <span style="vertical-align: middle;">{{!empty($viewData['order']['note_value']) ? $viewData['order']['note_value'] : ''}}</span></td>
                </tr>
                <tr>
                    <td width="100%" align="left" style="visibility:hidden;" colspan="{{$colspanCounter}}">&nbsp;</td>
                </tr>
                <?php $testStdValue = !empty($viewData['order']['test_standard_value'])? $viewData['order']['test_standard_value']:''; ?>
                <tr>
                    <td width="100%" align="left" style="padding: 5px 0px !important; border-bottom: 1px solid ! important; border-top: 1px solid ! important;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="{{$colspanCounter}}">{{!empty($viewData['order']['remark_value']) ? $viewData['order']['remark_value'].$testStdValue : ''}}</td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="margin-top:10px!important;border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="6"><b style="font-size:14px!important;vertical-align: middle;">*****End of Report*****</td>
                </tr>
            </table>
        </div>
    </div>
    <!--/content-->

</body>
</html>

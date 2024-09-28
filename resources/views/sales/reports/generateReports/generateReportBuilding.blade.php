<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
    .page-break-always{ page-break-after: always;}
    .page-break-auto{ page-break-after:auto;}
    @if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus))
    @page { margin: 332px 20px 230px 20px;font-size:13px;}
    #header {left: 0;position: fixed;right: 0;text-align: center;top: -322px;width: 100%;height:auto;}
    @else
    @page { margin: 280px 20px 220px 20px;font-size:13px;}
    #header {left: 0;position: fixed;right: 0;text-align: center;top: -255px;width: 100%;height:auto;}
    @endif
    #footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
    #content {bottom: 0;height:auto;left: 0;right: 0;width: 100%;}
    td table td {border-bottom: 0px!important;}
    p{padding:2!important;margin:0!important;}
    .sno{width:6%!important;font-size:12px!important;padding:5px 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .category{font-size:13px!important;padding:0 5px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;}
    .parameter{width:34%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .parameter p{padding:0px!important;margin:0!important;display: inline-block!important;}
    .methodName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .equipmentName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .requirementName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .testResult{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
    .pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:3px 2px!important;vertical-align: middle;}
    .header-content table,footer-content table{border-collapse:collapse;}
    .left-content{border: 0px none ! important; vertical-align: middle;}
    .middle-content{color:#4d64a1;border:0px !important;text-align:center!important;}
    .middle-content h3{font-family: times of romen; font-weight: 600; color: rgb(77, 100, 161); margin: 0px; font-size: 30px !important;}
    .right-content{border: 0px none ! important; color: #d7563a; vertical-align: top; padding: 5px; text-align: center ! important; font-size: 12px ! important;}
    .footer-content h3 {font-family: times of romen; font-weight: 600; color:#4d64a1;margin: 0px; font-size: 15px !important;padding: 0 !important;}
    .footer-content p,h5 {font-size:11px !important;margin:0 !important;padding: 0 !important;}
    .footer-content ul {margin: 0 !important;padding: 0 !important;}
    .footer-content ul li {font-size: 11px !important;margin-top: 0px !important;padding: 0 !important;}
    .rightSection{display:none!important;}
    table.dash {border: 1px dashed #000;border-collapse: collapse;}
    table.dash td {border: 1px dashed #000;}
    </style>

<!--- header-->
<div id="header">

    <div class="header-content" @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('2','3')))style="visibility: hidden;" @endif>
        <?php echo !empty($viewData['order']['header_content']) ? htmlspecialchars_decode($viewData['order']['header_content']) : '';?>
    </div>

    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <td colspan="6">
                <table width="100%" style="padding: 0px !important;">
                    <tr>
                        <td width="33.3%" align="left"></td>
                        <td width="33.3%" align="center">
                            <b style="padding: 5px;font-size: 15px; width: 150px;">
                                Test Report
                                @if(!empty($hasContainHeaderFooter) && $hasContainHeaderFooter == '2')
                                (DRAFT)
                                @endif
                            </b>
                        </td>
                        <td width="33.3%" align="right">
                            <b style="vertical-align: middle;">Document QF : 2501<br> </b>
                            <span style="font-weight:normal" align="right">
                                @if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus))
								<script type='text/php'>
								if(isset($pdf)){ 
									$font = $fontMetrics->get_font('serif','Normal');
									$size = 10;
									$y    = $pdf->get_height() - 713;
									$x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
									$pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
								}
								</script>
								@else
								<script type='text/php'>
								if(isset($pdf)){ 
									$font = $fontMetrics->get_font('serif','Normal');
									$size = 10;
									$y    = $pdf->get_height() - 735;
									$x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
									$pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
								}
								</script>
								@endif
                            </span>&nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" border="1" style="padding:0px!important;border-collapse:collapse;">
        <tr>
            <td width="50%">
                <p style="margin-bottom: 27px;"><b>Issued To</b></p>
                <p style="margin: -25px 5px 30px 5px;font-size: 13px">
                    {{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}
                    <br />{{!empty($viewData['order']['customer_address']) ? $viewData['order']['customer_address'] : ''}}
                    <br />{{!empty($viewData['order']['city_name']) ? $viewData['order']['city_name'] : ''}} ( {{$viewData['order']['state_name']}} )
                </p>
            </td>
            <td width="50%">
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Sample Reg. No.</b><b>:</b>{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}</p>
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Sample Reg. Date </b><b>:</b>{{!empty($viewData['order']['order_date']) ? date(DATEFORMAT,strtotime($viewData['order']['order_date'])) : ''}}</p>
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Report Date</b><b>:</b>{{!empty($viewData['order']['report_date']) ? date(DATEFORMAT,strtotime($viewData['order']['report_date'])) : ''}}</p>
                <p style="font-size:13px;padding:1!important;margin:0!important;">
                    <b style="width:105px!important;float:left;">Report No.</b><b>:</b>{{!empty($viewData['order']['report_no']) ? $viewData['order']['report_no'] : ''}}{{!empty($viewData['order']['is_amended_no']) ? '-'.$viewData['order']['is_amended_no'] :'' }} @if(!empty($reportWithRightLogo) && in_array($reportWithRightLogo,array('16','17'))) {{ $reportWithRightLogo == '16' ? '&nbsp;(1)' : '&nbsp;(2)' }} @endif
                </p>
                <p style="font-size:13px;padding:1!important;margin:0!important; @if((empty($viewData['order']['nabl_no'])) || (!empty($reportWithRightLogo) && $reportWithRightLogo == '17')) display:none; @endif"><b style="width:105px!important;float:left;">NABL ULR No.</b><b>:</b>{{!empty($viewData['order']['nabl_no']) ? $viewData['order']['nabl_no'] : ''}}</p>
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Customer Ref. No.</b><b>:</b>{{!empty($viewData['order']['reference_no']) ? $viewData['order']['reference_no'] : '-'}}</p>
                <p style="font-size:13px;padding:1!important;margin:0!important;"><b style="width:105px!important;float:left;">Letter Dated</b><b>:</b>{{!empty($viewData['order']['letter_no']) ? $viewData['order']['letter_no'] : '-'}}</p>
            </td>
        </tr>
    </table>
</div>
<!--- /header end-->

<!--- footer start-->
<div id="footer" class="footer-content">

    <div>
        <table width="100%" style="border-collapse:collapse;border-bottom:1px solid;">
            <tr>
                <td width="33.3%" align="left" valign="bottom">
                    @if(!empty($viewData['order']['report_microbiological_name']))
                    <p style="font-size:10px;padding-top:-5px!important;">
                        <span style="float:right;width:100%;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img style="width:70px;height:30px;padding-bottom:-6px!important;" alt="{{$viewData['order']['report_microbiological_name']}}" src="{{$viewData['order']['report_microbiological_sign_dir_path']}}" /></span>
                    </p>
                    <p style="font-size:10px;padding-top:-1px!important;">{{!empty($viewData['order']['incharge_reviewing_date']) ? date(DATEFORMAT,strtotime($viewData['order']['incharge_reviewing_date'])) : ''}}</p>
                    <p style="font-size:13px;padding-top:-1px!important;"><b>{{$viewData['order']['report_microbiological_name']}}</b></p>
                    <p style="font-size:13px;padding-top:-1px!important;"><b>[{{defined('AUTHORIZED_SIGNATORY_TEXT') && AUTHORIZED_SIGNATORY_TEXT ? AUTHORIZED_SIGNATORY_TEXT  : 'Authorized Signatory'}}]</b></p>
                    @endif
                </td>
                <td width="33.3%" align="center" valign="bottom">
                    <p style="font-size:10px;padding-top:-5px!important;">
                        @if(!empty($viewData['orderTrackRecord']['reviewing']['user_signature']) && file_exists(DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['reviewing']['user_signature']))
                        <span style="display:inline-block;width:100%;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img style="width:70px;height:30px;padding-top:-5px!important;" alt="{{$viewData['orderTrackRecord']['reviewing']['user_signature']}}" src="{{DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['reviewing']['user_signature']}}" /></span></br>
                        @endif
                        {{!empty($viewData['orderTrackRecord']['reviewing']['username']) ? $viewData['orderTrackRecord']['reviewing']['username'] : ''}}
                    </p>
                    <p style="font-size:10px;padding-top:-1px!important;">{{!empty($viewData['order']['reviewing_date']) ? date(DATEFORMAT,strtotime($viewData['order']['reviewing_date'])) : ''}}</p>
                    <p style="font-size:13px;padding-top:-1px!important;"><b>Reviewer</b></p>
                </td>
                <td width="33.3%" align="right" valign="bottom">
                    <p style="font-size:10px;padding-top:-5px!important;">
                        @if(!empty($viewData['orderTrackRecord']['finalizing']['user_signature']) && file_exists(DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['finalizing']['user_signature']))
                        <span style="float:right;width:100%;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img style="width:70px;height:30px;padding-bottom:-6px!important;" alt="{{$viewData['orderTrackRecord']['finalizing']['user_signature']}}" src="{{DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['finalizing']['user_signature']}}" /></span>
                        @endif
                        <span style="display:inline-block;width:100%;padding-top:-5px!important;">{{!empty($viewData['order']['finalizing_date']) ? date(DATEFORMAT,strtotime($viewData['order']['finalizing_date'])) : ''}}</span>
                    </p>
                    <p style="font-size:13px;padding-top:-1px!important;"><b>{{!empty($viewData['orderTrackRecord']['finalizing']['username']) ? $viewData['orderTrackRecord']['finalizing']['username'] : ''}}</b></p>
                    <p style="font-size:13px;padding-top:-1px!important;"><b>[{{defined('AUTHORIZED_SIGNATORY_TEXT') && AUTHORIZED_SIGNATORY_TEXT ? AUTHORIZED_SIGNATORY_TEXT  : 'Authorized Signatory'}}]</b></p>
                </td>
            </tr>
        </table>
    </div>

    <div @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('2','3')))style="visibility: hidden;" @endif>
        <?php echo !empty($viewData['order']['footer_content']) ? htmlspecialchars_decode($viewData['order']['footer_content']) : '';?>
    </div>

</div>
<!--- /footer start-->

<body>
    <div id="content">
        <div class="page-break-auto">
            <table class="pdftable" width="100%" border="1" style="border-collapse:collapse;">
                <tr>
                    <td align="left" width="50%" colspan="2" style="font-size:13px;padding-top:5px!important;padding-bottom:5px!important;"><b>Test Report as per IS </b><b>:</b>{{!empty($viewData['order']['test_std_name']) ? $viewData['order']['test_std_name'] : ''}}</td>
                    <td align="left" width="50%" style="font-size:13px;"><b style="display: inline-block;vertical-align: middle;">With Amendment No.(s) </b><b>:</b>{{!empty($viewData['order']['with_amendment_no']) ? $viewData['order']['with_amendment_no'] : ''}}</td>
                </tr>
                <tr>
                    <td align="left" style="border-bottom: 1px solid rgb(255, 255, 255);padding-top:7px!important;padding-bottom:7px!important;" width="50%" colspan="3"><b style="display: inline-block;vertical-align: middle;font-size:14px;">PART A : PARTICULARS OF SAMPLE SUBMITTED</b></td>
                </tr>
            </table>

            <?php $serialAlphabet = 'a'; ?>

            <table class="pdftable" width="100%" border="1" style="border-collapse:collapse;">

                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{$serialAlphabet}})</td>
                    <td width="50%" align="left">Nature of Sample<?php echo Helper::getCustomerDefinedFieldSymbol('sample_description',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Sample Condition<?php echo Helper::getCustomerDefinedFieldSymbol('sample_condition',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['sample_condition']) ? $viewData['order']['sample_condition'] : 'N/A'}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Grade / Variety / Type / Class / Size etc.<?php echo Helper::getCustomerDefinedFieldSymbol('grade_type',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['grade_type']) ? $viewData['order']['grade_type'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Brand Name<?php echo Helper::getCustomerDefinedFieldSymbol('brand_type',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['brand_type']) ? $viewData['order']['brand_type'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Declared Values,if any<?php echo Helper::getCustomerDefinedFieldSymbol('declared_values',$viewData['order']['customer_defined_fields']);?></td>
                    <td align="left">{{!empty($viewData['order']['declared_values']) ? $viewData['order']['declared_values'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Code No.<?php echo Helper::getCustomerDefinedFieldSymbol('barcode',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left"><img height="18px" src="{{!empty($viewData['order']['barcode']) ? $viewData['order']['barcode'] : ''}}">
                    </td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Batch Number<?php echo Helper::getCustomerDefinedFieldSymbol('batch_no',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['batch_no']) ? $viewData['order']['batch_no'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">D.O.M<?php echo Helper::getCustomerDefinedFieldSymbol('mfg_date',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['mfg_date']) ? $viewData['order']['mfg_date'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Date of Expiry<?php echo Helper::getCustomerDefinedFieldSymbol('expiry_date',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['expiry_date']) ? $viewData['order']['expiry_date'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Sample Quantity<?php echo Helper::getCustomerDefinedFieldSymbol('sample_qty',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Batch Size<?php echo Helper::getCustomerDefinedFieldSymbol('batch_size',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['batch_size']) ? $viewData['order']['batch_size'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Mode of Packing<?php echo Helper::getCustomerDefinedFieldSymbol('packing_mode',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['packing_mode']) ? $viewData['order']['packing_mode'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Date of Receipt<?php echo Helper::getCustomerDefinedFieldSymbol('order_date',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['order_date']) ? date('d-m-Y',strtotime($viewData['order']['order_date'])) : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Date of Start<?php echo Helper::getCustomerDefinedFieldSymbol('analysis_start_date',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['analysis_start_date']) ? date('d-m-Y',strtotime($viewData['order']['analysis_start_date'])) : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Date of Completion<?php echo Helper::getCustomerDefinedFieldSymbol('analysis_completion_date',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left"> {{!empty($viewData['order']['analysis_completion_date']) ? date('d-m-Y',strtotime($viewData['order']['analysis_completion_date'])) : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">BIS Seal (Intact/Not Intact/Unsealed)<?php echo Helper::getCustomerDefinedFieldSymbol('is_sealed',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">
                        @if($viewData['order']['is_sealed'] == 0) {{'Unsealed'}} @elseif($viewData['order']['is_sealed'] == 1) {{ 'Sealed' }} @elseif($viewData['order']['is_sealed'] == 2) {{'Intact'}} @else{{ '' }} @endif
                    </td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">IO'S Signature (Signed/Unsigned)<?php echo Helper::getCustomerDefinedFieldSymbol('is_signed',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">
                        @if(isset($viewData['order']['is_signed']))
                        @if($viewData['order']['is_signed'] == 0) Unsigned @elseif($viewData['order']['is_signed'] == 1) Signed @else @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Any Other Information<?php echo Helper::getCustomerDefinedFieldSymbol('remarks',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['remarks']) ? $viewData['order']['remarks'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Test Request Submitted By<?php echo Helper::getCustomerDefinedFieldSymbol('customer_name',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}} {{!empty($viewData['order']['city_name']) ? '- '.$viewData['order']['city_name'] : ''}} ( {{!empty($viewData['order']['state_name']) ? $viewData['order']['state_name'] : ''}} )</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Manufactured By<?php echo Helper::getCustomerDefinedFieldSymbol('manufactured_by',$viewData['order']['customer_defined_fields']);?></td>
                    <td align="left">{{!empty($viewData['order']['manufactured_by']) ? $viewData['order']['manufactured_by'] : ''}}</td>
                </tr>
                <tr>
                    <td width="5%" style="padding-top:5px!important;padding-bottom:5px!important;" align="center">{{++$serialAlphabet}})</td>
                    <td width="50%" align="left">Supplied By<?php echo Helper::getCustomerDefinedFieldSymbol('supplied_by',$viewData['order']['customer_defined_fields']);?></td>
                    <td width="45%" align="left">{{!empty($viewData['order']['supplied_by']) ? $viewData['order']['supplied_by'] : ''}}</td>
                </tr>
            </table>

            <table width="100%" class="pdftable dash" style="border-collapse:collapse;text-align:center;">
                <tr>
                    <td align="left" style="border-top: 1px solid #fff!important;border-right: 1px solid!important;border-bottom: 1px solid!important;border-left: 1px solid!important;padding-top:7px!important;padding-bottom:7px!important;" colspan="3"><b style="font-size:14px;display: inline-block;vertical-align: middle;">PART B : SUPPLIMENTARY INFORMATIONS</b></td>
                </tr>
                <tr>
                    <td align="center" width="5%" style="border-left: 1px solid!important;border-right: 1px solid #fff!important;padding-top:6px!important;padding-bottom:6px!important;">a.</td>
                    <td align="left" width="74%" style="border-right: 1px solid #fff!important;">Reference to sampling procedure, whenever applicable</td>
                    <td align="left" width="20%" style="border-right: 1px solid!important;">&nbsp;:&nbsp;{{!empty($viewData['order']['ref_sample_value_name']) ? $viewData['order']['ref_sample_value_name']  : ''}}</td>
                </tr>
                <tr>
                    <td align="center" width="5%" style="border-right: 1px solid #fff!important;border-left: 1px solid!important;padding-top:6px!important;padding-bottom:6px!important;">b.</td>
                    <td align="left" width="74%" style="border-right: 1px solid #fff!important;">Supporting documents for the measurement taken and results derived like graphs, tables, sketches and / or photographs as appropriate to test reports, if any</td>
                    <td align="left" width="20%" style="border-right: 1px solid!important;">&nbsp;:&nbsp;{{!empty($viewData['order']['result_drived_value_name']) ? $viewData['order']['result_drived_value_name'] : ''}}</td>
                </tr>
                <tr>
                    <td align="center" width="5%" style="border-right: 1px solid #fff!important;border-bottom: 1px solid!important;border-left: 1px solid!important;padding-top:6px!important;padding-bottom:6px!important;">c.</td>
                    <td align="left" width="74%" style="border-right: 1px solid #fff!important;border-bottom: 1px solid!important;">Deviation from the test methods as prescribed in relevant ISS/WORK Instruments, if any</td>
                    <td align="left" width="20%" style="border-bottom: 1px solid!important;border-right: 1px solid!important;">&nbsp;:&nbsp;{{!empty($viewData['order']['deviation_value_name']) ? $viewData['order']['deviation_value_name'] : ''}}
                        <div class="page-break"></div>
                    </td>
                </tr>
            </table>

            <?php
            $hasEquipmentStyle = $hasLimitStyle = '';
            if(empty($notContainEquipment) && empty($notContainLimit)){
                $colspanCounter = 6;
            }else if(!empty($notContainEquipment) && empty($notContainLimit)){
                $colspanCounter    = 5;
                $hasEquipmentStyle = ' style="display:none;"';
            }else if(empty($notContainEquipment) && !empty($notContainLimit)){
                $colspanCounter = 5;
                $hasLimitStyle  = ' style="display:none;"';
            }else if(!empty($notContainEquipment) && !empty($notContainLimit)){
                $colspanCounter    = 4;
                $hasEquipmentStyle = ' style="display:none;"';
                $hasLimitStyle     = ' style="display:none;"';
            }
            ?>

            <!--Test Parameters Detail-->
            <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">

                <!--DESCRIPTION WISE PARAMETERLIST-->
                @if(!empty($viewData['orderParameterList']['descriptionWiseParameterList']))
                    @foreach($viewData['orderParameterList']['descriptionWiseParameterList'] as $key => $descriptionParaCategoryName)
                        <tr>
                            <th class="category" align="left" colspan="{{$colspanCounter}}" style="padding:4px 2px!important;">{{trim($descriptionParaCategoryName['categoryName'])}}{{!empty($descriptionParaCategoryName['categoryNameSymbol']) ? trim($descriptionParaCategoryName['categoryNameSymbol']) : ''}}</th>
                        </tr>
                        @if(!empty($descriptionParaCategoryName['categoryParams']))
                        <?php $charNum = 'a';?>
                            @foreach($descriptionParaCategoryName['categoryParams'] as $orderParaKey => $descriptionParaCategoryParameters)
                            <tr>
                                <td colspan="2" align="left"><?php echo trim($descriptionParaCategoryParameters['test_parameter_name']);?><?php echo !empty($descriptionParaCategoryParameters['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($descriptionParaCategoryParameters['non_nabl_parameter_symbol']).'</sup>' : '';?></td>
                                <td class="text-justify parameter" colspan="{{$colspanCounter-2}}" align="left" style="padding:0 15px;">{{!empty($descriptionParaCategoryParameters['description']) ? trim($descriptionParaCategoryParameters['description']) : ''}} </td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                    <tr>
                        <td style="padding:2px!important;" colspan="{{$colspanCounter}}">&nbsp;</td>
                    </tr>
                @endif
                <!--/DESCRIPTION WISE PARAMETERLIST-->

                <tr>
                    <th align="center" class="sno">S.No.</th>
                    <th align="center" class="parameter">Parameter</th>
                    <th align="center" class="equipmentName" <?php echo $hasEquipmentStyle;?>>Instrument</th>
                    <th align="center" class="methodName">Method</th>
                    <th align="center" class="requirementName" <?php echo $hasLimitStyle;?>>Specification</th>
                    <th align="center" class="testResult">Result</th>
                </tr>

                <!--CATEGORY WISE PARAMETER LIST-->
                @if(!empty($viewData['orderParameterList']['categoryWiseParameterList']))
                    <?php $categorySNo = '1';?>
                    @foreach($viewData['orderParameterList']['categoryWiseParameterList'] as $key => $orderParameterCategoryName)
                        <tr>
                            <th class="sno" align="center">{{$categorySNo++}}.</th>
                            <th class="category" align="left" colspan="{{$colspanCounter-1}}" style="padding:0 5px;">{{trim($orderParameterCategoryName['categoryName'])}}{{!empty($orderParameterCategoryName['categoryNameSymbol']) ? trim($orderParameterCategoryName['categoryNameSymbol']) : ''}}</th>
                        </tr>
                        @if(!empty($orderParameterCategoryName['categoryParams']))
                            @foreach($orderParameterCategoryName['categoryParams'] as $orderParaKey => $orderParameterCategoryParameters)
                            <tr>
                                <td align="center" class="sno">{{ $orderParameterCategoryParameters['charNumber'] }}.</td>
                                <td class="parameter" align="left"><?php echo trim($orderParameterCategoryParameters['test_parameter_name']);?><?php echo !empty($orderParameterCategoryParameters['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($orderParameterCategoryParameters['non_nabl_parameter_symbol']).'</sup>' : '';?></td>
                                @if(!empty($orderParameterCategoryParameters['description']))
                                    <td class="text-justify parameter" colspan="{{$colspanCounter-2}}" align="left" style="padding:0 15px;">{{!empty($orderParameterCategoryParameters['description']) ? trim($orderParameterCategoryParameters['description']) : ''}} </td>
                                @else
                                    <td align="center" class="equipmentName" <?php echo $hasEquipmentStyle;?>>{{!empty($orderParameterCategoryParameters['equipment_name']) ? trim($orderParameterCategoryParameters['equipment_name']) : ''}}</td>
                                    <td align="center" class="methodName">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
                                    <td align="center" class="requirementName" <?php echo $hasLimitStyle;?>>{{!empty($orderParameterCategoryParameters['requirement_from_to']) ? trim($orderParameterCategoryParameters['requirement_from_to']) : ''}}</td>
                                    <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['test_result']) ? trim($orderParameterCategoryParameters['test_result']) : ''}}</td>
                                @endif
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                @endif
                <!--/CATEGORY WISE PARAMETER LIST-->

                <!--DISCIPLINE WISE PARAMETERS LIST-->
                @if(!empty($viewData['orderParameterList']['disciplineWiseParametersList']))
                    @foreach($viewData['orderParameterList']['disciplineWiseParametersList'] as $key => $orderParameterCategoryName)

                        <!--DISCIPLINE NAME-->
                        @if(!empty($orderParameterCategoryName['disciplineHdr']['discipline_name']))
                        <tr>
                            <th class="sno" align="center"></th>
                            <th class="category" align="left" colspan="{{$colspanCounter-1}}" style="padding:3px 5px!important;">{{ !empty($orderParameterCategoryName['disciplineHdr']['discipline_name']) ? 'Discipline&nbsp;:&nbsp;'.trim($orderParameterCategoryName['disciplineHdr']['discipline_name']) : ''}}</th>
                        </tr>
                        @endif
                        <!--/DISCIPLINE NAME-->

                        <!--GROUP NAME-->
                        @if(!empty($orderParameterCategoryName['disciplineHdr']['group_name']))
                        <tr>
                            <th class="sno" align="center"></th>
                            <th class="category" align="left" colspan="{{$colspanCounter-1}}" style="padding:3px 5px!important;">{{ !empty($orderParameterCategoryName['disciplineHdr']['discipline_name']) ? 'Group&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.trim($orderParameterCategoryName['disciplineHdr']['group_name']) : ''}}</th>
                        </tr>
                        @endif
                        <!--/GROUP NAME-->

                        @if(!empty($orderParameterCategoryName['disciplineDtl']))
                            <?php $categorySNo = '1';?>
                            @foreach($orderParameterCategoryName['disciplineDtl'] as $key => $orderParameterCategoryName)
                                <tr>
                                    <th class="sno" align="center">{{ $categorySNo++ }}.</th>
                                    <th class="category" align="left" colspan="{{$colspanCounter-1}}" style="padding:0 5px;">{{trim($orderParameterCategoryName['categoryName'])}}{{!empty($orderParameterCategoryName['categoryNameSymbol']) ? trim($orderParameterCategoryName['categoryNameSymbol']) : ''}}</th>
                                </tr>
                                @if(!empty($orderParameterCategoryName['categoryParams']))
                                    <?php $charNum = 'a';?>
                                    @foreach($orderParameterCategoryName['categoryParams'] as $orderParaKey => $orderParameterCategoryParameters)
                                        <tr>
                                            <td align="center" class="sno">{{ $orderParameterCategoryParameters['charNumber'] }}.</td>
                                            <td class="parameter" align="left"><?php echo trim($orderParameterCategoryParameters['test_parameter_name']);?><?php echo !empty($orderParameterCategoryParameters['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($orderParameterCategoryParameters['non_nabl_parameter_symbol']).'</sup>' : '';?></td>
                                            @if(!empty($orderParameterCategoryParameters['description']))
                                                <td class="text-justify parameter" colspan="{{$colspanCounter-2}}" align="left" style="padding:0 15px;">{{!empty($orderParameterCategoryParameters['description']) ? trim($orderParameterCategoryParameters['description']) : ''}} </td>
                                            @else
                                                <td align="center" class="equipmentName" <?php echo $hasEquipmentStyle;?>>{{!empty($orderParameterCategoryParameters['equipment_name']) ? trim($orderParameterCategoryParameters['equipment_name']) : ''}}</td>
                                                <td align="center" class="methodName">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
                                                <td align="center" class="requirementName" <?php echo $hasLimitStyle;?>>{{!empty($orderParameterCategoryParameters['requirement_from_to']) ? trim($orderParameterCategoryParameters['requirement_from_to']) : ''}}</td>
                                                <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['test_result']) ? trim($orderParameterCategoryParameters['test_result']) : ''}}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
                <!--/DISCIPLINE WISE PARAMETERS LIST-->

            </table>
            <!--/Test Parameters Detail-->

            <!--Parameter Indexing-->
            @if(!empty($viewData['orderParameterList']))
            <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">
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
            </table>
            @endif
            <!--/Parameter Indexing-->

            <!--NOTE & REMARKS-->
            <table class="pdftable" width="100%" style="border-collapse:collapse;">
                <tr>
                    <td width="100%" align="left" style="visibility:hidden;" colspan="{{$colspanCounter}}">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" align="left" style="border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="{{$colspanCounter}}"><b style="font-size:14px!important;vertical-align: middle;">NOTE :</b> <span style="vertical-align: middle;">{{!empty($viewData['order']['note_value']) ? $viewData['order']['note_value'] : ''}}</span></td>
                </tr>
                <tr>
                    <td width="100%" align="left" style="visibility:hidden;" colspan="{{$colspanCounter}}">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" align="left" style="border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="{{$colspanCounter}}"><b style="font-size:14px!important;vertical-align: middle;">REMARKS :</b><span style="vertical-align: middle;">{{!empty($viewData['order']['remark_value']) ? $viewData['order']['remark_value'] : ''}}</span></td>
                </tr>
                <tr>
                    <td width="100%" align="left" style="visibility:hidden;" colspan="{{$colspanCounter}}">&nbsp;</td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="{{$colspanCounter}}"><b style="font-size:14px!important;vertical-align: middle;">*****End Of Report*****</td>
                </tr>
            </table>
            <!--/NOTE & REMARKS-->

        </div>
    </div>
</body>
</html>

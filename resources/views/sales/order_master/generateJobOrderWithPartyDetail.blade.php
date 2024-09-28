<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
    .page-break-always{ page-break-after: always;}
    .page-break-auto{ page-break-after:auto;}
    @page { margin: 120px 20px 80px 20px;font-size:13px;}	
    #header {left: 0;position: fixed;right: 0;text-align: center;top: -120px;width: 100%;height:auto;}
    #footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
    #content {bottom: 0;height:auto;left: 0;right: 0;width: 100%;}
    td table td {border-bottom: 0px!important;}
    p{padding:2!important;margin:0!important;}
    .sno{width:6%!important;font-size:12px!important;padding:5px 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .category{font-size:13px!important;padding:0 5px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;}
    .parameter{width:65%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
    .methodName{width:35%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
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
    .rightSection{display:none!important;}
    </style>
</head>
    
<!--- header-->     
<div id="header">
    
    <div class="header-content"@if(!empty($hasContainHeaderFooter) && $hasContainHeaderFooter == '3') style="visibility: hidden;"@endif>
        <?php echo !empty($viewData['order']['header_content']) ? htmlspecialchars_decode($viewData['order']['header_content']) : '';?>
    </div>
        
    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <td colspan="3">
                <table width="100%" style="padding: 0px !important;">
                    <tr>
                        <td width="33.3%" align="left"></td>
                        <td width="33.3%" align="center">
                            <b style="padding: 5px;font-size: 15px; width: 150px;">JOB ORDER</b>
                        </td>
                        <td width="33.3%" align="right"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<!--- /header-->

<!--- footer start-->
<div id="footer" class="footer-content">
    <table valign="bottom" width="100%" style="margin:0 auto;border-collapse:collapse;">
        <tr>
            <td colspan="3">
                <table width="100%" style="border-collapse:collapse;">
                    <tr>
                        <td width="33.3%" align="left" valign="bottom">
                            @if(!empty($viewData['order']['user_signature']) && file_exists(DOC_ROOT.SIGN_PATH.$viewData['order']['user_signature']))
                            <p><img height="30px" width="80px" alt="{{$viewData['order']['user_signature']}}" src="{{DOC_ROOT.SIGN_PATH.$viewData['order']['user_signature']}}"/></p>
                            @endif
                            @if(!empty($viewData['order']['createdByName']))
                            <p style="font-size:11px!important;">{{$viewData['order']['createdByName']}}</p>
                            @endif
                            <p style="font-size:11px!important;"><b>Booking Person</b></p>
                        </td>
                        <td width="33.3%" align="center" valign="bottom">
                            <script type='text/php'>
                            if(isset($pdf)){ 
                                $font = $fontMetrics->get_font('serif','bold');
                                $size = 11;
                                $y    = $pdf->get_height() - 28;
                                $x    = $pdf->get_width() - 305 - $fontMetrics->get_text_width('1/1', $font, $size);
                                $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
                            }
                            </script>
                        </td>
                        <td align="right" width="33.3%" valign="bottom">
                            <p style="font-size:11px!important;">{{!empty($viewData['order']['order_date']) ? date(DATEFORMAT,strtotime($viewData['order']['order_date'])) :  ''}}</p>
                            <p style="font-size:11px!important;"><b>Booking Date</b></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>                         
    </table>
</div>
<!--- /footer start-->

<body>
    <div id="content">
        <div class="page-break-auto">
            <table class="pdftable" border="1" width="100%" style="border-collapse:collapse;">
                <tr>
                    <td>
                        <!--Booking Detail-->
                        <table class="pdftable" width="100%" style="border-collapse:collapse;">	 
                            
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Booking Date & Time</b><b>:</b>{{!empty($viewData['order']['order_date']) ? date(DATETIMEFORMAT,strtotime($viewData['order']['order_date'])) :  ''}}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Security Code</b><b>:</b><span style="display: inline-block;vertical-align: middle;padding-top:3px;"><img width="150px" height="16px" src="{{!empty($viewData['order']['barcode']) ? $viewData['order']['barcode'] : ''}}"></span></td>
                            </tr>                            
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Booking Code</b><b>:</b>{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : '' }}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Due Date</b><b>:</b>{{!empty($viewData['order']['expected_due_date']) ? date(DATETIMEFORMAT,strtotime($viewData['order']['expected_due_date'])) : '' }}</td>
                            </tr>                            
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Department</b><b>:</b>{{ !empty($viewData['order']['department_name']) ? trim($viewData['order']['department_name']) :  '-'}}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Dept. Due Date</b><b>:</b>{{!empty($viewData['order']['order_dept_due_date']) ? date(DATETIMEFORMAT,strtotime($viewData['order']['order_dept_due_date'])) : '' }}</td>
                            </tr>                            
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Priority</b><b>:</b>{{!empty($viewData['order']['sample_priority_name']) ? $viewData['order']['sample_priority_name'] : '' }}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Report Due Date</b><b>:</b>{{!empty($viewData['order']['order_report_due_date']) ? date(DATETIMEFORMAT,strtotime($viewData['order']['order_report_due_date'])) : '' }}</td>
                            </tr>
                                
                        </table>
                        <!--/Booking Detail-->
                        
                        <!--Sample Detail-->
                        <table class="pdftable" width="100%" style="border-collapse:collapse;margin-top:10px!important;">
                            
                            <tr>
                                <td align="left" colspan="2"><b style="display: inline-block;vertical-align: middle; width: 130px;">Sample Name</b><b>:</b>{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : '' }}</td>
                            </tr>
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Batch No.</b><b>:</b>{{!empty($viewData['order']['batch_no']) ? $viewData['order']['batch_no'] : '' }}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Batch Size</b><b>:</b>{{!empty($viewData['order']['batch_size']) ? $viewData['order']['batch_size'] : '' }}</td>
                            </tr>
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Sample Quantity</b><b>:</b>{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : '' }}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Brand</b><b>:</b>{{!empty($viewData['order']['brand_type']) ? $viewData['order']['brand_type'] : '' }}</td>
                            </tr>
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">DOM</b><b>:</b>{{!empty($viewData['order']['mfg_date']) ? $viewData['order']['mfg_date'] : '' }}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Mfg. Lic. No.</b><b>:</b>{{!empty($viewData['order']['mfg_lic_no']) ? $viewData['order']['mfg_lic_no'] : '' }}</td>
                            </tr>
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">DOE</b><b>:</b>{{!empty($viewData['order']['expiry_date']) ? $viewData['order']['expiry_date'] : '' }}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Sealed/Unsealed</b><b>:</b>@if($viewData['order']['is_sealed']==0){{'Unsealed'}}@elseif($viewData['order']['is_sealed']==1){{'Sealed'}}@elseif($viewData['order']['is_sealed']==2){{'Intact'}}@elseif($viewData['order']['is_sealed']==3){{''}}@endif
                                </td>
                            </tr>
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Packing Mode</b><b>:</b>{{!empty($viewData['order']['packing_mode']) ? $viewData['order']['packing_mode'] : '' }}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Sign/Unsign</b><b>:</b>@if($viewData['order']['is_signed']==0){{'Unsigned'}}@elseif($viewData['order']['is_signed']==1){{'Signed'}}@endif
                                </td>
                            </tr>
                            
                        </table>
                        <!--/Sample Detail-->
                        
                        <!--Party Detail-->
                        <table class="pdftable" width="100%" style="border-collapse:collapse;margin-top:10px!important;">                                          
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Submitter</b><b>:</b>{{!empty($viewData['order']['customer_name']) ? trim($viewData['order']['customer_name']) : '' }}</td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Quotation No.</b><b>:</b>{{!empty($viewData['order']['quotation_no']) ? $viewData['order']['quotation_no'] : '' }}</td>
                            </tr>            
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Submission Type</b><b>:</b>@if($viewData['order']['submission_type']==1){{'Direct'}}@elseif($viewData['order']['submission_type']==2){{'Courier'}}@elseif($viewData['order']['submission_type']==3){{'Marketing Executive'}}@else {{ ''}}@endif
                                </td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">PO No. Ref.</b><b>:</b>{{!empty($viewData['order']['po_no']) ? $viewData['order']['po_no'] : '' }}</td>
                            </tr>
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Client Contact</b><b>:</b>{{!empty($viewData['order']['contact_name1']) ? $viewData['order']['contact_name1'] : '' }}</b></td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Sample Receiving No.</b><b>:</b>{{!empty($viewData['order']['sample_no']) ? $viewData['order']['sample_no'] : '' }}</b></td>
                            </tr>
                            <tr>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Sampled by</b><b>:</b>
                                    @if($viewData['order']['submission_type'] == '3')
                                        {{'Interstellar Testing Centre Private Limited'}}
                                    @endif
                                </td>
                                <td align="left" width="50%"><b style="display: inline-block;vertical-align: middle; width: 130px;">Sampling Date & Time</b><b>:</b>{{!empty($viewData['order']['sampling_date']) &&($viewData['order']['sampling_date']!=NULL) ? date(DATETIMEFORMAT,strtotime($viewData['order']['sampling_date'])) : '' }}</b></td>
                            </tr>                            
                        </table>
                        <!--/Party Detail-->
                    </td>
                </tr>
            </table>
                    
            <table class="pdftable" width="100%" border="1" style="border-collapse:collapse;margin-top:10px!important;">	    
                <tr>
                    <th class="parameter" align="left" valign="middle" style="padding-top:3px!important;padding-bottom:3px!important;"><b style="text-decoration: underline;font-size:14px!important;">Test Parameters</b></th>
                    <th class="methodName" align="left" valign="middle" style="padding-top:3px!important;padding-bottom:3px!important;"><b style="text-decoration: underline;font-size:14px!important;">Test Method</b><b style="float: right; padding-right: 1px;font-size:11px!important;vertical-align: middle!important;">[ANALYSIS TAT (IN DAYS) : {{ !empty($viewData['maxTatInDayNumber']) ? $viewData['maxTatInDayNumber'] : '-' }}]</b></th>
                </tr>
                @if(!empty($viewData['orderParameters']))
                    @foreach($viewData['orderParameters'] as  $categoryParameters)
                        @foreach($categoryParameters['categoryParams'] as $key=> $subCategoryParameters)
                            <tr>
                                <td class="parameter" align="left" valign="middle"><?php echo trim($subCategoryParameters['test_parameter_name']);?></td>
                                <td class="methodName" align="left" valign="middle">{{!empty($subCategoryParameters['method_name']) ? trim($subCategoryParameters['method_name']) : ''}}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endif                    
            </table>
        </div>
    </div>
</body>
</html>
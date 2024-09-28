<html>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <style>
      .page-break-always{ page-break-after: always;}
      .page-break-auto{ page-break-after:auto;}
      @if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus))
      @page { margin: 205px 20px 230px 20px;font-size:13px;}
      #header {left: 0;position: fixed;right: 0;text-align: center;top: -200px;width: 100%;height:auto;}
      @else
      @page { margin: 135px 20px 220px 20px;font-size:13px;}
      #header {left: 0;position: fixed;right: 0;text-align: center;top: -130px;width: 100%;height:auto;}
      @endif
      #footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
      #content {bottom: 0;height:auto;left: 0;right: 0;width: 100%;}
      td table td {border-bottom: 0px!important;}
      p{padding:2!important;margin:0!important;}
      .sno{width:6%!important;font-size:12px!important;padding:5px 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
      .category{font-size:13px!important;padding:0 5px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;}
      .parameter{width:34%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
      .parameter p{padding:0px!important;margin:0!important;display: inline-block!important;overflow-wrap: break-word;word-wrap: break-word;}
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
      .rightSection{display:none!important;}
      table.dash {border: 1px dashed #000;border-collapse: collapse;}
      table.dash td {border: 1px dashed #000;}
      .spanContent{width:40% !important;}
      .pdftable-general tr td p{float:left!important;}
   </style>
   
<body>
   <!--- Header Start-->
   <div id="header">
      <div class="header-content" @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('2','3')))style="visibility: hidden;"@endif>
         <?php echo !empty($viewData['order']['header_content']) ? htmlspecialchars_decode($viewData['order']['header_content']) : '';?>
      </div>
      <table width="100%" style="border-collapse: collapse;margin:-20px 0 auto;">
         <tr>
            <td colspan="5">
               <table width="100%" style="padding: 0px !important;">
                  <tr>
                     <td width="33.3%" align="left"></td>
                     <td width="33.3%" align="center">
                        <div style="padding:5px;font-size: 13px;font-weight:bold;">
                           <p>
                              Test Report No. :
                              <span style="border-bottom:2px dotted #000 !important;">
                                 {{!empty($viewData['order']['report_no']) ? $viewData['order']['report_no'] : ''}}
                                 @if(!empty($reportWithRightLogo) && in_array($reportWithRightLogo,array('16','17'))) 
                                    {{ $reportWithRightLogo == '16' ? '&nbsp;(1)' : '&nbsp;(2)' }}
                                 @endif
                              </span>
                           </p>
                           @if(!empty($viewData['order']['nabl_no']) && !empty($reportWithRightLogo) && in_array($reportWithRightLogo,array('7','16')))
                              <p>NABL ULR No. : <span style="border-bottom:2px dotted #000 !important;">{{!empty($viewData['order']['nabl_no']) ? $viewData['order']['nabl_no'] : ''}}</span></p>
                           @endif
                           @if(!empty($hasContainHeaderFooter) && $hasContainHeaderFooter == '2')
                              (DRAFT)
                           @endif
                        </div>
                     </td>
                     <td width="33.3%" align="right">
                        <b style="vertical-align: middle;">ORIGINAL<br> </b>
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
                                 $y    = $pdf->get_height() - 765;
                                 $x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
                                 $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
                              }
                           </script>
                           @endif
                        </span>
                        &nbsp;&nbsp;
                     </td>
                  </tr>
               </table>
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
                  <p style="font-size:10px;padding-top:-5px!important;">
                     @if(!empty($viewData['orderTrackRecord']['reviewing']['user_signature']) && file_exists(DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['reviewing']['user_signature']))
                     <span style="float:right;width:100%;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img style="width:70px;height:30px;padding-bottom:-6px!important;" alt="{{$viewData['orderTrackRecord']['reviewing']['user_signature']}}" src="{{DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['reviewing']['user_signature']}}"/></span>
                     @endif
                     <span style="display:inline-block;width:100%;padding-top:-1px!important;">{{!empty($viewData['order']['reviewing_date']) ? date(DATEFORMAT,strtotime($viewData['order']['reviewing_date'])) : '-'}}</span>
                  </p>
                  <p style="font-size:13px;padding-top:-1px!important;"><b>{{!empty($viewData['orderTrackRecord']['reviewing']['username']) ? $viewData['orderTrackRecord']['reviewing']['username'] : '-'}}</b></p>
                  <p style="font-size:13px;padding-top:-1px!important;"><b>Verified by</b></p>
               </td>
               <td width="33.3%" align="center" valign="bottom">
                  @if(!empty($viewData['order']['report_microbiological_name']))
                     <p style="font-size:10px;padding-top:-5px!important;">
                         <span style="float:right;width:100%;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img style="width:70px;height:30px;padding-bottom:-6px!important;" alt="{{$viewData['order']['report_microbiological_name']}}" src="{{$viewData['order']['report_microbiological_sign_path']}}"/></span>
                     </p>
                     <p style="font-size:10px;padding-top:-1px!important;">{{!empty($viewData['order']['incharge_reviewing_date']) ? date(DATEFORMAT,strtotime($viewData['order']['incharge_reviewing_date'])) : ''}}</p>
                     <p style="font-size:13px;padding-top:-1px!important;"><b>{{$viewData['order']['report_microbiological_name']}}</b></p>
                     <p style="font-size:13px;padding-top:-1px!important;"><b>[{{defined('AUTHORIZED_SIGNATORY_TEXT') && AUTHORIZED_SIGNATORY_TEXT ? AUTHORIZED_SIGNATORY_TEXT  : 'Authorized Signatory'}}]</b></p>
                  @endif
               </td>
               <td width="33.3%" align="right" valign="bottom">
                  <p style="font-size:10px;padding-top:-5px!important;">
                     @if(!empty($viewData['orderTrackRecord']['finalizing']['user_signature']) && file_exists(DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['finalizing']['user_signature']))
                     <span style="float:right;width:100%;@if(empty($withOrWithoutSignature))visibility:hidden;@endif"><img style="width:70px;height:30px;padding-bottom:-6px!important;" alt="{{$viewData['orderTrackRecord']['finalizing']['user_signature']}}" src="{{DOC_ROOT.SIGN_PATH.$viewData['orderTrackRecord']['finalizing']['user_signature']}}"/></span>
                     @endif
                     <span style="display:inline-block;width:100%;padding-top:-1px!important;">{{!empty($viewData['order']['finalizing_date']) ? date(DATEFORMAT,strtotime($viewData['order']['finalizing_date'])) : '-'}}</span>
                  </p>
                  <p style="font-size:13px;padding-top:-1px!important;"><b>{{!empty($viewData['orderTrackRecord']['finalizing']['username']) ? $viewData['orderTrackRecord']['finalizing']['username'] : '-'}}</b></p>
                  <p style="font-size:13px;padding-top:-1px!important;"><b>Authorised by</b></p>
               </td>
            </tr>
         </table>
      </div>
      <div @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('2','3')))style="visibility: hidden;"@endif>
         <?php echo !empty($viewData['order']['footer_content']) ? htmlspecialchars_decode($viewData['order']['footer_content']) : '';?>
      </div>
   </div>
   <!--- /footer start-->

   <!--content-->
   <div id="content">
      <div class="page-break-auto">         
         <table width="100%" class="pdftable" style="border-collapse:collapse;">
            <tr>
               <td style="padding:0px;" width="100%" align="left" colspan="2">
                  <p><b>Issued To</b></p>
                  <p>
                     {{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}
                     <br/>{{!empty($viewData['order']['customer_address']) ? $viewData['order']['customer_address'] : ''}}
                     <br/>{{!empty($viewData['order']['city_name']) ? $viewData['order']['city_name'] : ''}}
                  </p>
               </td>
            </tr>            
            <tr>
               <td style="padding:0px;" width="100%" align="left">
                  <span class="spanContent" style="float:left;display: inline-block;vertical-align: middle;">Sample Registration No.<?php echo Helper::getCustomerDefinedFieldSymbol('order_no',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="position:relative;left:10px;padding-left:11px !important;width: 40%;">{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}</span>
               </td>
               <td  width="100%" align="left"></td>
            </tr>
            <tr>
               <td style="padding:0px;" width="100%" align="left">
                  <span class="spanContent" style="float:left;display: inline-block;vertical-align: middle;"><?php echo !empty($viewData['orderParameterList']['generalWiseParameterList']) ? 'Sample Description' : 'Sample Name';?><?php echo Helper::getCustomerDefinedFieldSymbol('sample_description',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="position:relative;left:10px;padding-left:11px !important;width: 40%;">{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : ''}}</span>
               </td>
               <td width="100%" align="left">
                  <p style="position: relative;right: 0; width: 70%;float: right;"><span style="display: inline-block;vertical-align: middle;width: 50%;">Received On<?php echo Helper::getCustomerDefinedFieldSymbol('booking_date',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['booking_date']) ? date(DATEFORMAT,strtotime($viewData['order']['booking_date'])) : ''}}</span></p>
               </td>
            </tr>
            <tr>
               <td style="padding:0px;" width="100%" align="left">
                  <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;"><?php echo !empty($viewData['orderParameterList']['generalWiseParameterList']) ? 'Sample Location' : 'Sample Condition';?><?php echo Helper::getCustomerDefinedFieldSymbol('sample_condition',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="position:relative;left:10px;padding-left:11px !important;width: 40%;">{{!empty($viewData['order']['sample_condition']) ? $viewData['order']['sample_condition'] : ''}}</span>
               </td>
               <td width="100%" align="left">
                  <p style="position: relative;right: 0; width: 70%;float: right;"><span style="display: inline-block;vertical-align: middle;width: 50%;">Commenced On<?php echo Helper::getCustomerDefinedFieldSymbol('commenced_on_date',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['commenced_on_date']) ? date(DATEFORMAT,strtotime($viewData['order']['commenced_on_date'])) : ''}}</span>
               </td>
            </tr>
            <tr>
               <td style="padding:0px;" width="100%" align="left">
                  <b class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Sample details (if any)</b><b>:-</b>
               </td>
               <td width="100%" align="left">
                  <p style="position: relative;right: 0; width: 70%;float: right;"><span style="display: inline-block;vertical-align: middle;width: 50%;">Completed On<?php echo Helper::getCustomerDefinedFieldSymbol('test_completion_date',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['test_completion_date']) ? date(DATEFORMAT,strtotime($viewData['order']['test_completion_date'])) : ''}}</span></p>
               </td>
            </tr>
            
            <tr>
               <td style="padding:0px;@if(!empty($viewData['orderParameterList']['generalWiseParameterList']))visibility: hidden;@endif" width="100%" align="left">
                  <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Sample Quantity<?php echo Helper::getCustomerDefinedFieldSymbol('sample_qty',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : ''}}</span>
               </td>
               <td width="100%" align="left">
                  <p style="position: relative;right: 0; width: 70%;float: right;"><span style="display: inline-block;vertical-align: middle;width: 50%;">Date of Report<?php echo Helper::getCustomerDefinedFieldSymbol('finalizing_date',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['finalizing_date']) ? date(DATEFORMAT,strtotime($viewData['order']['finalizing_date'])) : ''}}</span></p>
               </td>
            </tr>
            @if(empty($viewData['orderParameterList']['generalWiseParameterList']))
            <tr>
               <td style="padding:0px;" width="100%" align="left">
                  <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Batch No.<?php echo Helper::getCustomerDefinedFieldSymbol('batch_no',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['batch_no']) ? $viewData['order']['batch_no'] : ''}}</span>
               </td>
               <td width="100%" align="left">
                  <p style="position: relative;right: 0; width: 70%;float: right;"><span style="display: inline-block;vertical-align: middle;width: 50%;"></p>
               </td>
            </tr>
            @endif
            <tr>
               <td style="padding:0px;" width="100%" align="left">
                  <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Sample Submission Type<?php echo Helper::getCustomerDefinedFieldSymbol('sample_mode_name',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['sample_mode_name']) ? $viewData['order']['sample_mode_name'] : ''}}</span>
               </td>
               <td width="100%" align="left" >
                  <p style="position: relative;right: 0; width: 70%;float: right;"><span style="display: inline-block;vertical-align: middle;width: 50%;"></p>
               </td>
            </tr>
            @if(!empty($viewData['order']['submission_type']) && $viewData['order']['submission_type'] == '5')
            <tr>
               <td style="padding:0px;" width="100%" align="left" colspan="2">
                  <span class="" style="display: inline-block;vertical-align:middle;width:20% !important;">Sampling Details<?php echo Helper::getCustomerDefinedFieldSymbol('remarks',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;overflow-wrap: break-word;word-wrap: break-word;">{{!empty($viewData['order']['remarks']) ? trim($viewData['order']['remarks']) : ''}}</span>
               </td>
            </tr>
            @endif
            <tr>
               <td colspan="2" style="padding:0px;" align="left">
                  <span class="" style="display: inline-block;vertical-align: middle;width:20% !important;">Customer Reference<?php echo Helper::getCustomerDefinedFieldSymbol('letter_no',$viewData['order']['customer_defined_fields']);?></span><b>:</b>
                  <span class="spanContent" style="padding-left:11px !important;">{{!empty($viewData['order']['reference_no']) ? trim($viewData['order']['reference_no']).'/' : ''}}{{!empty($viewData['order']['letter_no']) ? $viewData['order']['letter_no'] : ''}}</span>
               </td>
            </tr>
            <tr>
               <td colspan="2" style="padding:0px;"  align="left">
                  <span style="display: inline-block;vertical-align: middle;width: 20% !important;">Test Report as per<?php echo Helper::getCustomerDefinedFieldSymbol('test_std_name',$viewData['order']['customer_defined_fields']);?></span><b>:</b>
                  <span class="spanContent" style="padding-left:11px !important;">{{!empty($viewData['order']['test_std_name']) ? $viewData['order']['test_std_name'] : ''}}</span>
               </td>
            </tr>
         </table>
            
         <?php
         $hasEquipmentStyle = $hasLimitStyle = $hasTestStandardStyle = '';
         $containTestStandard = !empty($viewData['order']['test_standard']) && in_array($viewData['order']['test_standard'],array('80','258')) ? '1' : '0';
         if(empty($containTestStandard)){ 
            $hasTestStandardStyle = ' style="display:none;"';
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
         }else{
            $hasTestStandardStyle = '';
            $hasLimitStyle = ' style="display:none;"';
            if(empty($notContainEquipment) && empty($notContainLimit)){
               $colspanCounter = 7;
            }else if(!empty($notContainEquipment) && empty($notContainLimit)){
               $colspanCounter    = 6;
               $hasEquipmentStyle = ' style="display:none;"';
            }else if(empty($notContainEquipment) && !empty($notContainLimit)){
               $colspanCounter = 5;
               $hasTestStandardStyle = ' style="display:none;"';
            }else if(!empty($notContainEquipment) && !empty($notContainLimit)){
               $colspanCounter    = 4;
               $hasEquipmentStyle = ' style="display:none;"';
               $hasTestStandardStyle = ' style="display:none;"';
            }
         }
         ?>
         
         <!--GENERAL WISE PARAMETER LIST-->
         @if(!empty($viewData['orderParameterList']['generalWiseParameterList']))
            <table class="pdftable" width="100%" style="border:1px solid #000;border-collapse:collapse;margin-top:5px!important;">		
               
               <tr width="100%" style="border:1px solid #000;border-collapse:collapse;">
                  <td colspan="{{$colspanCounter}}">
                      <table width="100%" style="border-collapse:collapse;">
                          <tr>
                              <td align="left" width="49%" style="vertical-align: middle; padding-top: 5px ! important; padding-bottom: 5px ! important; border-top: 1px solid rgb(255, 255, 255);"><b>Test Report as per</b></td>
                              <td align="left" width="50%" style="vertical-align: middle; padding-top: 5px ! important; padding-bottom: 5px ! important; border-top: 1px solid rgb(255, 255, 255);">{{!empty($viewData['order']['test_std_name']) ? ' : '.$viewData['order']['test_std_name'] : ''}}</td>
                          </tr>
                      </table>
                  </td>
              </tr>
               
               <tr>
                  <td colspan="{{$colspanCounter}}" style="border:0px!important">
                     <table width="100%" style="border-collapse:collapse;">
                        @foreach($viewData['orderParameterList']['generalWiseParameterList'] as $generalParameters)
                           <tr>
                              <td>
                                 <table class="pdftable-general" width="100%" style="border-collapse:collapse;">
                                    <tr>
                                       <th align="left">{{trim($generalParameters['categoryName'])}}{{!empty($generalParameters['categoryNameSymbol']) ? trim($generalParameters['categoryNameSymbol']) : ''}}</th>
                                       <th align="left">&nbsp;</th>
                                    </tr>
                                    @foreach($generalParameters['categoryParams'] as $key => $parameterValues)
                                       <tr>
                                          <td style="padding:0px;" align="left" width="50%"><?php echo trim($parameterValues['test_parameter_name']);?><?php echo !empty($parameterValues['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($parameterValues['non_nabl_parameter_symbol']).'</sup>' : '';?></td>
                                          <td style="padding:0px;" align="left" width="50%"><span style="vertical-align: middle;">:&nbsp;{{$parameterValues['test_result']}}</span></td>
                                       </tr>
                                    @endforeach
                                 </table>
                              </td>
                           </tr>
                        @endforeach
                     </table>
                  </td>
               </tr>
            </table>
         @endif         
         <!--/GENERAL WISE PARAMETER LIST-->
         
         <!--Test Parameters Detail-->
         <table border="1" width="100%" class="pdftable" style="border-collapse:collapse;margin-top:5px!important;">
            
            <!--DESCRIPTION WISE PARAMETER LIST-->
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
               <tr><td style="padding:2px!important;" colspan="{{$colspanCounter}}">&nbsp;</td></tr>
            @endif
            <!--/DESCRIPTION WISE PARAMETER LIST-->
         
            <tr>
               <th align="center" class="sno">S.No.</th>
               <th align="center" class="parameter">Parameters</th>
               <th align="center" class="equipmentName"<?php echo $hasEquipmentStyle;?>>Instrument</th>
               <th align="center" class="methodName">Method</th>
               <th align="center" class="testResult">Result</th>
               <th align="center" class="requirementName"<?php echo $hasTestStandardStyle;?>>Acceptable Limit</th>
               <th align="center" class="requirementName"<?php echo $hasTestStandardStyle;?>>Permissible Limit</th>
               <th align="center" class="requirementName"<?php echo $hasLimitStyle;?>>Specification</th>
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
                           <td align="center" class="equipmentName"<?php echo $hasEquipmentStyle;?>>{{!empty($orderParameterCategoryParameters['equipment_name']) ? trim($orderParameterCategoryParameters['equipment_name']) : ''}}</td>
                           <td align="center" class="methodName">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
                           <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['test_result']) ? trim($orderParameterCategoryParameters['test_result']) : ''}}</td>
                           <td align="center" class="requirementName"<?php echo $hasTestStandardStyle;?>>{{!empty($orderParameterCategoryParameters['standard_value_from']) ? trim($orderParameterCategoryParameters['standard_value_from']) : ''}}</td>
                           <td align="center" class="requirementName"<?php echo $hasTestStandardStyle;?>>{{!empty($orderParameterCategoryParameters['standard_value_to']) ? trim($orderParameterCategoryParameters['standard_value_to']) : ''}}</td>
                           <td align="center" class="requirementName"<?php echo $hasLimitStyle;?>>{{!empty($orderParameterCategoryParameters['requirement_from_to']) ? trim($orderParameterCategoryParameters['requirement_from_to']) : ''}}</td>
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
                                 <td align="center" class="equipmentName"<?php echo $hasEquipmentStyle;?>>{{!empty($orderParameterCategoryParameters['equipment_name']) ? trim($orderParameterCategoryParameters['equipment_name']) : ''}}</td>
                                 <td align="center" class="methodName">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
                                 <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['test_result']) ? trim($orderParameterCategoryParameters['test_result']) : ''}}</td>
                                 <td align="center" class="requirementName"<?php echo $hasTestStandardStyle;?>>{{!empty($orderParameterCategoryParameters['standard_value_from']) ? trim($orderParameterCategoryParameters['standard_value_from']) : ''}}</td>
                                 <td align="center" class="requirementName"<?php echo $hasTestStandardStyle;?>>{{!empty($orderParameterCategoryParameters['standard_value_to']) ? trim($orderParameterCategoryParameters['standard_value_to']) : ''}}</td>
                                 <td align="center" class="requirementName"<?php echo $hasLimitStyle;?>>{{!empty($orderParameterCategoryParameters['requirement_from_to']) ? trim($orderParameterCategoryParameters['requirement_from_to']) : ''}}</td>
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
         <table class="pdftable" width="100%" style="border-collapse:collapse;margin-top:5px!important;">
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
   <!--/content-->

</body>
</html>
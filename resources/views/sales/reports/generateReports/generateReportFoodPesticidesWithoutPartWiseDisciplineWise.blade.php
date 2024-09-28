<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
   .page-break-always{ page-break-after: always;}
   .page-break-auto{ page-break-after:auto;}
   @if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus))
   @page { margin: 190px 20px 230px 20px;font-size:13px;}
   #header {left: 0;position: fixed;right: 0;text-align: center;top: -185px;width: 100%;height:auto;}
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
   .parameter{width:44%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .parameter p{vertical-align: middle!important;padding:0px!important;margin:0!important;display: inline-block!important;}
   .methodName{width:25%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .equipmentName{width:25%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .requirementName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .testResult{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .pdftable{table-layout:fixed;border-collapse: collapse;background: #fff; border:0px Solid #fff !important;}
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
   .spanContent{width:40% !important;}
</style>

<!---Header Start-->
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
                  <td width="43.3%" align="center">
                     <div style="padding:5px;font-size: 13px;font-weight:bold;">
                        <p>
                           TEST REPORT NO. :
                           <span style="border-bottom:2px dotted #000!important;">
                              {{!empty($viewData['order']['report_no']) ? $viewData['order']['report_no'] : ''}}
                              @if(!empty($reportWithRightLogo) && in_array($reportWithRightLogo,array('16','17'))) 
                                 {{ $reportWithRightLogo == '16' ? '&nbsp;(1)' : '&nbsp;(2)' }}
                              @endif
                           </span>
                        </p>
                        @if(!empty($viewData['order']['nabl_no']) && !empty($reportWithRightLogo) && in_array($reportWithRightLogo,array('7','16')))
                           <p>NABL ULR NO. : <span style="border-bottom:2px dotted #000!important;">{{!empty($viewData['order']['nabl_no']) ? $viewData['order']['nabl_no'] : ''}}</span></p>
                        @endif
                        @if(!empty($hasContainHeaderFooter) && $hasContainHeaderFooter == '2')
                           (DRAFT)
                        @endif
                     </div>
                  </td>
                  <td width="23.3%" align="right">
                     <b style="vertical-align: middle;">ORIGINAL<br> </b>
                     <span style="font-weight:normal" align="right">
                        @if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus))
                        <script type='text/php'>
                           if(isset($pdf)){ 
                              $font = $fontMetrics->get_font('serif','Normal');
                              $size = 10;
                              $y    = $pdf->get_height() - 705;
                              $x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
                              $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
                           }
                        </script>
                        @else
                        <script type='text/php'>
                           if(isset($pdf)){ 
                              $font = $fontMetrics->get_font('serif','Normal');
                              $size = 10;
                              $y    = $pdf->get_height() - 761;
                              $x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
                              $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
                           }
                        </script>
                        @endif
                     </span>
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

<body>      
   <!--content-->
   <div id="content">
      <div class="page-break-auto">         
         <table width="100%" class="pdftable" style="border-collapse:collapse;">
            <tbody>
               <tr>
                  <td align="left" colspan="2">
                     <p style="width:100%;vertical-align: middle;font-weight:bold;">Issued To : </p>
                     @if(!empty($viewData['order']['order_dynamic_fields']))
                        <?php $isSampleColSubBy = false;?>
                        @foreach($viewData['order']['order_dynamic_fields'] as $values)
                           @if(in_array(strtolower(trim($values['order_field_name'])),['name of cluster','cluster','block','district','state']))
                              <?php $isSampleColSubBy = true;?>
                              <p style="padding:0px 0px 0px 3px!important;;margin:0px!important;"><span style="display: inline-block;vertical-align: middle;width: 150px;font-weight: bold;"><?php echo !empty($values['order_field_name']) ? trim(ucwords($values['order_field_name'])) : ''?></span><b>:</b><span style="padding-left:11px !important;"><?php echo !empty($values['order_field_value']) ? trim(ucwords($values['order_field_value'])) : ''?></span></p>
                           @endif
                        @endforeach
                        @if($isSampleColSubBy)
                        <p style="width:100%;vertical-align: middle;font-weight:bold;padding-top:10px!important;">Sample Collected/Submitted By : </p>
                        @endif
                     @endif
                     <p style="width:100%;vertical-align: middle;padding-top:1px!important;text-transform: uppercase;">{{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}</p>
                     <p style="width:100%;vertical-align: middle;padding-top:0px!important;text-transform: uppercase;">{{!empty($viewData['order']['customer_address']) ? $viewData['order']['customer_address'] : ''}}</p>
                     <p style="width:100%;vertical-align: middle;padding-top:0px!important;text-transform: uppercase;">{{!empty($viewData['order']['city_name']) ? $viewData['order']['city_name'] : ''}}</p>
                  </td>
               </tr>
               <tr>
                  <td style="padding:0px;margin-top:5px!important;" width="100%" align="left">
                     <span class="spanContent" style="float:left;display: inline-block;vertical-align: middle;">Sample Registration No.<?php echo Helper::getCustomerDefinedFieldSymbol('order_no',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="position:relative;left:10px;padding-left:11px !important;width: 40%;">{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}</span>
                  </td>
                  <td width="100%" align="left"></td>
               </tr>
               <tr>
                  <td style="padding:0px;" width="100%" align="left">
                     <span class="spanContent" style="float:left;display: inline-block;vertical-align: middle;">Sample Name<?php echo Helper::getCustomerDefinedFieldSymbol('sample_description',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="position:relative;left:10px;padding-left:11px !important;width: 40%;">{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : ''}}</span>
                  </td>
                  <td  width="100%" align="left">
                     <p style="position: relative;right: 0; width: 70%;float: right;"><span class="" style="display: inline-block;vertical-align: middle;width: 50%;">Received On<?php echo Helper::getCustomerDefinedFieldSymbol('booking_date',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['booking_date']) ? date(DATEFORMAT,strtotime($viewData['order']['booking_date'])) : ''}}</span></p>
                  </td>
               </tr>
               <tr>
                  <td style="padding:0px;" width="100%" align="left">
                     <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Sample Condition<?php echo Helper::getCustomerDefinedFieldSymbol('sample_condition',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="position:relative;left:10px;padding-left:11px !important;width: 40%;">{{!empty($viewData['order']['sample_condition']) ? $viewData['order']['sample_condition'] : ''}}</span>
                  </td>
                  <td  width="100%" align="left" >
                     <p style="position: relative;right: 0; width: 70%;float: right;"><span  class="" style="display: inline-block;vertical-align: middle;width: 50%;">Commenced On<?php echo Helper::getCustomerDefinedFieldSymbol('commenced_on_date',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['commenced_on_date']) ? date(DATEFORMAT,strtotime($viewData['order']['commenced_on_date'])) : ''}}</span>
                  </td>
               </tr>
               <tr>
                  <td style="padding:0px;" width="100%" align="left">
                     <b class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Sample details (if any)</b><b>:-</b>
                  </td>
                  <td width="100%" align="left" >
                     <p style="position: relative;right: 0; width: 70%;float: right;"><span class="" style="display: inline-block;vertical-align: middle;width: 50%;">Completed On<?php echo Helper::getCustomerDefinedFieldSymbol('test_completion_date',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['test_completion_date']) ? date(DATEFORMAT,strtotime($viewData['order']['test_completion_date'])) : ''}}</span></p>
                  </td>
               </tr>
               <tr>
                  <td style="padding:0px;" width="100%" align="left">
                     <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Sample Quantity<?php echo Helper::getCustomerDefinedFieldSymbol('sample_qty',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : ''}}</span></td>
                  <td width="100%" align="left" >
                     <p style="position: relative;right: 0; width: 70%;float: right;"><span class="" style="display: inline-block;vertical-align: middle;width: 50%;">Date of Report<?php echo Helper::getCustomerDefinedFieldSymbol('finalizing_date',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['finalizing_date']) ? date(DATEFORMAT,strtotime($viewData['order']['finalizing_date'])) : ''}}</span></p>
                  </td>
               </tr>
               <tr>
                  <td style="padding:0px;" width="100%" align="left">
                     <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Batch No.<?php echo Helper::getCustomerDefinedFieldSymbol('batch_no',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['batch_no']) ? $viewData['order']['batch_no'] : ''}}</span>
                  </td>
                  <td  width="100%" align="left">
                     <p style="position: relative;right: 0; width: 70%;float: right;"><span class="" style="display: inline-block;vertical-align: middle;width: 50%;"></p>
                  </td>
               </tr>
               <tr>
                  <td style="padding:0px;" width="100%" align="left">
                     <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Sample Submission Type<?php echo Helper::getCustomerDefinedFieldSymbol('sample_mode_name',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['sample_mode_name']) ? $viewData['order']['sample_mode_name'] : ''}}</span>
                  </td>
                  <td width="100%" align="left">
                     <p style="position: relative;right: 0; width: 70%;float: right;"><span style="display: inline-block;vertical-align: middle;width: 50%;"></p>
                  </td>
               </tr>
               @if(!empty($viewData['order']['submission_type']) && $viewData['order']['submission_type'] == '5')
               <tr>
                  <td style="padding:0px;" width="100%" align="left" colspan="2">
                     <span style="display: inline-block;vertical-align: middle;width: 20% !important;">Sampling Details<?php echo Helper::getCustomerDefinedFieldSymbol('remarks',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;overflow-wrap: break-word;word-wrap: break-word;">{{!empty($viewData['order']['remarks']) ? trim($viewData['order']['remarks']) : ''}}</span>
                  </td>
               </tr>
               @endif
               <tr>
                  <td colspan="2" style="padding:0px;"  align="left">
                     <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 20% !important;">Customer Reference<?php echo Helper::getCustomerDefinedFieldSymbol('letter_no',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['reference_no']) ? trim($viewData['order']['reference_no']).'/' : ''}}{{!empty($viewData['order']['letter_no']) ? $viewData['order']['letter_no'] : ''}}</span>
                  </td>
               </tr>
               <tr>
                  <td style="padding:0px;" width="100%" align="left">
                     <span class="spanContent" style="float:left;display: inline-block;vertical-align: middle;">Date of Mfg.<?php echo Helper::getCustomerDefinedFieldSymbol('mfg_date',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="position:relative;left:10px;padding-left:11px !important;width: 40%;">{{!empty($viewData['order']['mfg_date']) ? $viewData['order']['mfg_date'] : ''}}</span>
                  </td>
                  <td  width="100%" align="left">
                     <p style="position: relative;right: 0; width: 70%;float: right;"><span class="" style="display: inline-block;vertical-align: middle;width: 50%;">Date of Exp.<?php echo Helper::getCustomerDefinedFieldSymbol('expiry_date',$viewData['order']['customer_defined_fields']);?></span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['expiry_date']) ? $viewData['order']['expiry_date'] : ''}}</span></p>
                  </td>
               </tr>
               @if(!empty($viewData['order']['order_dynamic_fields']))
                  @foreach($viewData['order']['order_dynamic_fields'] as $values)
                     @if(!in_array(strtolower(trim($values['order_field_name'])),['name of cluster','cluster','block','district','state']))
                        <tr>
                           <td style="padding:0px;" width="100%" align="left" colspan="2"><span style="display: inline-block;vertical-align: middle;width: 150px;"><?php echo !empty($values['order_field_name']) ? trim(ucwords($values['order_field_name'])) : ''?></span><b>:</b><span style="padding-left:11px !important;"><?php echo !empty($values['order_field_value']) ? trim(ucwords($values['order_field_value'])) : ''?></span></td>
                        </tr>
                     @endif
                  @endforeach
               @endif
               @if(!empty($viewData['order']['order_discipline_group_detail']))
                  <?php $orderDisciplineGroupDetail = array_values(array_slice($viewData['order']['order_discipline_group_detail'], 0, 1));?>
                  @if(!empty($orderDisciplineGroupDetail))
                     @foreach($orderDisciplineGroupDetail as $orderDisciplineGroupValues)
                        <tr>
                           <td style="padding:0px;font-weight:bold!important;" width="100%" align="left">
                              <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Discipline</span><b>:</b><span style="padding-left:11px !important;">{{!empty($orderDisciplineGroupValues['discipline_name']) ? $orderDisciplineGroupValues['discipline_name'] : ''}}</span>
                           </td>
                           <td width="100%" align="left">
                              <p style="position: relative;right: 0; width: 70%;float: right;"><span style="display: inline-block;vertical-align: middle;width: 50%;"></p>
                           </td>
                        </tr>
                        <tr>
                           <td style="padding:0px;font-weight:bold!important;" width="100%" align="left">
                              <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Group</span><b>:</b><span style="padding-left:11px !important;">{{!empty($orderDisciplineGroupValues['group_name']) ? $orderDisciplineGroupValues['group_name'] : ''}}</span>
                           </td>
                           <td width="100%" align="left">
                              <p style="position: relative;right: 0; width: 70%;float: right;"><span style="display: inline-block;vertical-align: middle;width: 50%;"></p>
                           </td>
                        </tr>
                     @endforeach
                  @endif
               @endif
            </tbody>
         </table>
            
         <?php
         $hasEquipmentStyle = $colspanCounter = '';
         if(empty($notContainEquipment)){
            $colspanCounter = 5;
            $hasEquipmentStyle = '';
         }else{
            $colspanCounter = 4;
            $hasEquipmentStyle = ' style="display:none;"';
         }
         ?>
         
         <!--Test Parameters Category Detail-->
         @if(!empty($viewData['orderParameterList']['group_name']))
            <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:10px!important;">
               <tr>
                  <th align="center" class="sno">S.NO.</th>
                  <th align="left" class="parameter">TEST PARAMETER CATEGORY</th>
                  <th align="center" class="equipmentName"<?php echo $hasEquipmentStyle;?>>INSTRUMENT</th>
                  <th align="center" class="methodName">TEST METHOD</th>
               </tr>                  
               <?php $groupCounter = 1;?>
               @foreach($viewData['orderParameterList']['group_name'] as $key => $groupNameDetail)
                     <tr>
                        <td align="center">{{$groupCounter++}}</td>
                        <td align="left"><?php echo trim($groupNameDetail['test_parameter_category']);?></td>
                        <td align="center"<?php echo $hasEquipmentStyle;?>>{{!empty($groupNameDetail['code_no']) ? trim($groupNameDetail['code_no']) : ''}} </td>
                        <td align="center">{{!empty($groupNameDetail['test_method']) ? trim($groupNameDetail['test_method']) : ''}} </td>
                     </tr>
               @endforeach               
            </table>               
         @endif
	      <!--/Test Parameters Category Detail-->
         
         <!--Test Parameters Category Parameters Detail with BLQ-->
         @if(!empty($viewData['orderParameterList']['group_detail_with_blq']))
            <table width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">
               @foreach($viewData['orderParameterList']['group_detail_with_blq'] as $key => $groupNameDetail)
                  <tr><th align="left" colspan="3" style="visibility: hidden;">&nbsp;</th></tr>                  
                  <tr><th align="left" colspan="3" style="font-size:13px!important;">{{ !empty($groupNameDetail['groupLabelName']) ? $groupNameDetail['groupLabelName'].' :' : '' }}</th></tr>
                  <tr><th align="left" colspan="3" style="visibility: hidden;">&nbsp;</th></tr>
                  @if(!empty($groupNameDetail['categoryParams']))
                     <?php $groupCategoryParameterData = array_chunk($groupNameDetail['categoryParams'],3); ?>
                     @if(!empty($groupCategoryParameterData))
                        @foreach($groupCategoryParameterData as $paraKey => $groupCategoryParameters)
                           <tr>
                              @foreach($groupCategoryParameters as $paraKey => $groupCategoryParams)
                                 <td class="parameter" align="left"><?php echo trim($groupCategoryParams['test_parameter_name']);?><?php echo !empty($groupCategoryParams['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($groupCategoryParams['non_nabl_parameter_symbol']).'</sup>' : '';?><?php echo !empty($groupCategoryParams['test_result']) ? '('.trim($groupCategoryParams['test_result']).')' :'';?></td>
                              @endforeach
                           </tr>
                        @endforeach
                     @endif
                  @endif
               @endforeach
            </table>
         @endif
	      <!--/Test Parameters Category Parameters Detail with BLQ-->
         
         <!--Test Parameters Category Parameters Detail without BLQ-->
         @if(!empty($viewData['orderParameterList']['group_detail_without_blq']))
            <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:10px!important;">
               <tr>
                  <th align="left" colspan="{{$colspanCounter}}" style="font-size:13px!important;border:1px solid #FFF!important;">List of molecules detected :</th>
               </tr>
               <tr>
                  <th align="center" class="sno">S.No.</th>
                  <th align="center" class="parameter">Parameters</th>
                  <th align="center" class="equipmentName"<?php echo $hasEquipmentStyle;?>>Instrument</th>
                  <th align="center" class="methodName">Method</th>
                  <th align="center" class="testResult">Result</th>
               </tr>
               @foreach($viewData['orderParameterList']['group_detail_without_blq'] as $key => $orderParameterCategoryName)
                  @if(!empty($orderParameterCategoryName['categoryParams']))
                     @foreach($orderParameterCategoryName['categoryParams'] as $orderParaKey => $orderParameterCategoryParameters)
                        <tr>
                           <td align="center" class="sno">{{ ++$orderParaKey }}.</td>
                           <td class="parameter" align="left"><?php echo trim($orderParameterCategoryParameters['test_parameter_name']);?><?php echo !empty($orderParameterCategoryParameters['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($orderParameterCategoryParameters['non_nabl_parameter_symbol']).'</sup>' : '';?></td>
			   <td align="center" class="equipmentName"<?php echo $hasEquipmentStyle;?>>{{!empty($orderParameterCategoryParameters['equipment_name']) ? trim($orderParameterCategoryParameters['equipment_name']) : ''}}</td>
                           <td align="center" class="methodName">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
                           <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['test_result']) ? trim($orderParameterCategoryParameters['test_result']) : ''}}</td>
                        </tr>
                     @endforeach				    
                  @endif
               @endforeach
            </table>
         @endif
	      <!--/Test Parameters Category Parameters Detail without BLQ-->
         
         <!--Note and Information Detail-->
         <table class="pdftable" width="100%" style="border-collapse:collapse;margin-top:5px!important;">
            <tr>
               <td width="100%" align="left" style="visibility:hidden;" colspan="3">&nbsp;</td>
            </tr>            
            <tr>
               <td colspan="3" style="border: 1px solid #fff;">
                  <table class="pdftable" width="100%" style="border-collapse:collapse;">
                       <tr>
                           <td width="100%" align="left" style="border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align:middle;font-size:11px!important;" colspan="3">
                              <span style="vertical-align: middle;font-weight:bold;">NOTES :</span>
                              <span style="vertical-align: middle;">
                                 @if(!empty($viewData['order']['order_nabl_remark_scope']))
                                    '&#x2A;' represents categories/test parameters not covered under NABL
                                 @endif
                                 @if(!empty($viewData['order']['order_outsource_remark_scope']))
                                    &nbsp;&#124;&nbsp;'&#x2A;&#x2A;' represents outsource sample
                                 @endif
                                 @if(!empty(Helper::getCustomerDefinedFieldSymbol('sample_description',$viewData['order']['customer_defined_fields'])))
                                    &nbsp;'&#x23;' represents Customer Defined Fields
                                 @endif
                              </span>
                           </td>
                       </tr>
                   </table>
               </td>
            </tr>
            <tr>
               <td width="100%" align="center" style="border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="3"><b style="font-size:14px!important;vertical-align: middle;">*****End Of Report*****</td>
            </tr>
         </table>
         <!--/Note and Information Detail-->
         
      </div>
   </div>
   <!--/content-->

</body>
</html>
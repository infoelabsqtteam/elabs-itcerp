<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
   .page-break-always{ page-break-after: always;}
   .page-break-auto{ page-break-after:auto;}
   @if(!empty($viewData['order']['nabl_no']) && !empty($nablLogoHeaderMarginStatus))
   @page { margin: 200px 20px 180px 20px;font-size:13px;}
   #header {left: 0;position: fixed;right: 0;text-align: center;top: -195px;width: 100%;height:auto;}
   @else
   @page { margin: 135px 20px 180px 20px;font-size:13px;}
   #header {left: 0;position: fixed;right: 0;text-align: center;top: -130px;width: 100%;height:auto;}
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
   .fontBld{font-weight : bold!important;}
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
                           Test Report No :
                           <span style="border-bottom:2px dotted #000 !important;">
                              {{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}
                              @if(!empty($reportWithRightLogo) && in_array($reportWithRightLogo,array('16','17'))) 
                                 {{ $reportWithRightLogo == '16' ? '&nbsp;(1)' : '&nbsp;(2)' }}
                              @endif
                           </span>
                        </p>
                        @if(!empty($viewData['order']['nabl_no']) && !empty($reportWithEICFormat))
                           <p>NABL ULR No. : <span style="border-bottom:2px dotted #000 !important;">{{!empty($viewData['order']['nabl_no']) ? $viewData['order']['nabl_no'] : ''}}</span></p>
                        @endif
                        @if(!empty($viewData['order']['sample_mode_name']))
                           <p><span style="border-bottom:2px dotted #000 !important;text-transform: uppercase;">{{!empty($viewData['order']['sample_mode_name']) ? $viewData['order']['sample_mode_name'] : ''}}</span></p>
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
                              $y    = $pdf->get_height() - 750;
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
            <td width="33.3%" align="center" valign="bottom">&nbsp;</td>
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
      <table width="100%">
         <tr>
            <td align="left" width="50%">
               <h3>Interstellar Testing Centre Pvt. Ltd.</h3>
               <h5>Plot No. 2.S.No.12/2A, Industrial Estate,</h5>
               <h5>Perungudi,Sholinganallur Taluk, Chennai - 600096</h5>
               <p>Phone : 044-24962512</p>
               <p>Email : info@itclabs.com</p>
               <p>Website : www.itclabs.com</p>
            </td>
            <td align="left" width="50%">
               <h3>Disclaimer :</h3>
               <ul>
                  <li>Sample on receipt to lab was "found to be fit" for analysis.Subsequently stored at-20&#176;C till disposal.</li>
                  <li>Sampling plan: As per ITC/CHN/GSOP/002(Doc No EIC/F& FP/Exlnst. March/2012issue 4).</li>
                  <li>This report shall not be reproduced except in full without our written approval. Samples are not drawn by us unless otherwise stated.</li>
               </ul>
            </td>
         </tr>
      </table>
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
                     <p><b>Issued To : </b></p>
                     <p>
                        {{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}
                        <br/>{{!empty($viewData['order']['customer_address']) ? $viewData['order']['customer_address'] : ''}}
                        <br/>{{!empty($viewData['order']['city_name']) ? $viewData['order']['city_name'] : ''}}
                     </p>
                  </td>
               </tr>
               
               <tr>
                  <td width="29%" align="left">Type & Nature of Product<?php echo Helper::getCustomerDefinedFieldSymbol('sample_description',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : ''}}</td>
	       </tr>
               
               <tr>
                  <td width="29%" align="left">Sample Registration No.<?php echo Helper::getCustomerDefinedFieldSymbol('order_no',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}</td>
	       </tr>
                  
               <tr>
                  <td width="29%" align="left">Name and Address of Processor<?php echo Helper::getCustomerDefinedFieldSymbol('customer_address',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['manufactured_by']) ? $viewData['order']['manufactured_by'] : ''}}</td>
	       </tr>
               
               <tr>
                  <td width="29%" align="left">Name and Address of Exporter<?php echo Helper::getCustomerDefinedFieldSymbol('customer_address',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['supplied_by']) ? $viewData['order']['supplied_by'] : ''}}</td>
	       </tr>
                  
               <tr>
                  <td width="29%" align="left">Customer Reference<?php echo Helper::getCustomerDefinedFieldSymbol('reference_no',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['reference_no']) ? $viewData['order']['reference_no'] : ''}}</td>
	       </tr>
               
               <tr>
                  <td width="29%" align="left">Date of Sampling<?php echo Helper::getCustomerDefinedFieldSymbol('sampling_date',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['sampling_date']) ? $viewData['order']['sampling_date'] : ''}}</td>
	       </tr>
                  
               <tr>
                  <td width="29%" align="left">Date of Sample Receipt<?php echo Helper::getCustomerDefinedFieldSymbol('order_date',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['sample_date']) ? date('d-m-Y',strtotime($viewData['order']['sample_date'])) : ''}}</td>
	       </tr>
               
               <tr>
                  <td width="29%" align="left">Condition of the sample<?php echo Helper::getCustomerDefinedFieldSymbol('order_date',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['sample_condition']) ? $viewData['order']['sample_condition'] : ''}}</td>
	       </tr>
                  
               <tr>
                  <td width="29%" align="left" style="text-decoration: underline;margin-top:5px!important;"><b>Details of Sample:</b></td>
                  <td width="1%" align="left">&nbsp;</td>
                  <td width="70%" align="left">&nbsp;</td>
	       </tr>
               
               @if(!empty($viewData['order']['order_dynamic_fields']))
                  @foreach($viewData['order']['order_dynamic_fields'] as $key => $value)
                     <tr>
                        <td width="29%" align="left"><?php echo $value['order_field_name'];?><?php echo Helper::getCustomerDefinedFieldSymbol($key,$viewData['order']['customer_defined_fields']);?></td>
                        <td width="1%" align="left"><b>:&nbsp;</b></td>
                        <td width="70%" align="left"><?php echo $value['order_field_value'];?></td>
                     </tr>
                  @endforeach
               @endif
               
               <tr>
                  <td width="29%" align="left"> Date of Start of Analysis<?php echo Helper::getCustomerDefinedFieldSymbol('analysis_start_date',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['analysis_start_date']) ? date('d-m-Y',strtotime($viewData['order']['analysis_start_date'])) : ''}}</td>
	       </tr>
                  
               <tr>
                  <td width="29%" align="left">Date of Completion of Analysis<?php echo Helper::getCustomerDefinedFieldSymbol('analysis_completion_date',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['analysis_completion_date']) ? date('d-m-Y',strtotime($viewData['order']['analysis_completion_date'])) : ''}}</td>
	       </tr>
               
               <tr>
                  <td width="29%" align="left"><?php echo !empty($viewData['order']['order_discipline_group_detail']['discipline_name']) && count($viewData['order']['order_discipline_group_detail']['discipline_name']) > '1' ? 'Disciplines' : 'Discipline';?> <?php echo Helper::getCustomerDefinedFieldSymbol('discipline_name',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['order_discipline_group_detail']['discipline_name']) ? implode(',',$viewData['order']['order_discipline_group_detail']['discipline_name']) : ''}}</td>
	       </tr>
                  
               <tr>
                  <td width="29%" align="left"><?php echo !empty($viewData['order']['order_discipline_group_detail']['group_name']) && count($viewData['order']['order_discipline_group_detail']['group_name']) > '1' ? 'Groups' : 'Group';?> <?php echo Helper::getCustomerDefinedFieldSymbol('group_name',$viewData['order']['customer_defined_fields']);?></td>
                  <td width="1%" align="left"><b>:&nbsp;</b></td>
                  <td width="70%" align="left">{{!empty($viewData['order']['order_discipline_group_detail']['group_name']) ? implode(',',$viewData['order']['order_discipline_group_detail']['group_name']) : ''}}</td>
	       </tr>
            
            </tbody>
         </table>
         
         <div class="page-break-always"></div>
         
         <!--Test Parameters Detail-->   
         <?php $colspanCounter = 11;?>
         <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">
            
            @if(!empty($viewData['descriptionParameters']))
               @foreach($viewData['descriptionParameters'] as $key => $descriptionParaCategoryName)
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
            
            <tr>
               <th align="center" class="sno">S.No.</th>
               <th align="center" class="parameter">Parameter,&nbsp;(Unit <br>of Measurement)</th>
               <th align="center" class="testResult">Result</th>
               <th align="center" class="testResult">Measurement of uncertainty</th>
               <th align="center" class="testResult">Level of recovery</th>
               <th align="center" class="testResult">Limit of Determination CC(alpha)/LOQ as applicable</th>
               <th align="center" class="testResult">LOD as applicable</th>
               <th align="center" class="testResult">*MRL<br>**MRPL<br>***ML<br>****LIMIT</th>
               <th align="center" class="testResult">Analytical Method</th>
               <th align="center" class="testResult">Specification<br>standard/test <br> method against <br> which product tested</th>
               <th align="center" class="testResult">Validation Protocol</th>
           </tr>

            @if(!empty($viewData['orderParameters']))
               @foreach($viewData['orderParameters'] as $key => $orderParameterCategoryName)
                  <tr>
                     <th class="sno" align="center">{{$key+1}}.</th>
                     <th class="category" align="left" colspan="{{$colspanCounter-1}}" style="padding:0 5px;">{{trim($orderParameterCategoryName['categoryName'])}}{{!empty($orderParameterCategoryName['categoryNameSymbol']) ? trim($orderParameterCategoryName['categoryNameSymbol']) : ''}}</th>
                  </tr>
                  @if(!empty($orderParameterCategoryName['categoryParams']))
                     <?php $charNum = 'a';?>
                     @foreach($orderParameterCategoryName['categoryParams'] as $orderParaKey => $orderParameterCategoryParameters)
                        <tr>
                           <td align="center" class="sno">{{ $charNum++ }}.</td>
                           <td class="parameter" align="left"><?php echo trim($orderParameterCategoryParameters['test_parameter_name']);?><?php echo !empty($orderParameterCategoryParameters['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.trim($orderParameterCategoryParameters['non_nabl_parameter_symbol']).'</sup>' : '';?>{{!empty($orderParameterCategoryParameters['claim_value_unit']) ? '&nbsp;'.trim($orderParameterCategoryParameters['claim_value_unit']) : ''}}</td>
                           @if(!empty($orderParameterCategoryParameters['description']))
                              <td class="text-justify parameter" colspan="{{$colspanCounter-2}}" align="left" style="padding:0 15px;">{{!empty($orderParameterCategoryParameters['description']) ? trim($orderParameterCategoryParameters['description']) : ''}} </td>
                           @else
                              <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['test_result']) ? trim($orderParameterCategoryParameters['test_result']) : ''}}</td>
                              <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['measurement_uncertainty']) ? trim($orderParameterCategoryParameters['measurement_uncertainty']) : ''}}</td>
                              <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['recovery_level']) ? trim($orderParameterCategoryParameters['recovery_level']) : ''}}</td>
                              <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['limit_determination']) ? trim($orderParameterCategoryParameters['limit_determination']) : ''}}</td>
                              <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['lod']) ? trim($orderParameterCategoryParameters['lod']) : ''}}</td>
                              <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['mrpl']) ? trim($orderParameterCategoryParameters['mrpl']) : ''}}</td>
                              <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
                              <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['equipment_name']) ? trim($orderParameterCategoryParameters['equipment_name']) : ''}}</td>
                              <td align="center" class="testResult">{{!empty($orderParameterCategoryParameters['validation_protocol']) ? $orderParameterCategoryParameters['validation_protocol'] : ''}}</td>
                           @endif
                        </tr>
                     @endforeach				    
                  @endif
               @endforeach
            @endif
            
         </table>
	 <!--/Test Parameters Detail-->
         
         <!--Parameter Indexing-->
         <table class="pdftable" width="100%" style="border-collapse:collapse;margin-top:5px!important;">
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
<html>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <style>
      .page-break-always{ page-break-after: always;}
      .page-break-auto{ page-break-after:auto;}
      @page { margin: 120px 20px 110px 20px;font-size:13px;}
      #header {left: 0;position: fixed;right: 0;text-align: center;top: -120px;width: 100%;height:auto;}
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
      .pdftable{table-layout:fixed;border-collapse: collapse;background: #fff; border:0px Solid #fff !important;}
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
      .spanContent{width:40% !important;}
   </style>
   
<body>
   <!--- Header Start-->
   <div id="header">
      <div class="header-content" @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('2','3')))style="visibility: hidden;"@endif>
      <?php echo !empty($viewData['order']['header_content']) ? htmlspecialchars_decode($viewData['order']['header_content']) : '';?>
   </div>
   <table width="100%" style="border-collapse: collapse;">
      <tr>
         <td colspan="5">
            <table width="100%" style="padding: 0px !important;">
               <tr>
                  <td width="33.3%" align="left"></td>
                  <td width="43.3%" align="center">
                     <span style="padding:5px;font-size: 16px; width: 150px;font-weight:bold;">TEST CONFIRMATION DOCUMENT<span style="padding:10px !important;border-bottom:2px dotted #000 !important;">{{!empty($viewData['order']['order_no']) ? $viewData['order']['order_no'] : ''}}</span></span>
                  </td>
                  <td width="23.3%" valign="top" align="right">
                     <b style="vertical-align: middle;">ORIGINAL<br> </b>
                     <span style="font-weight:normal" align="right">
                        <script type='text/php'>
                           if(isset($pdf)){ 
                              $font = $fontMetrics->get_font('serif','Normal');
                              $size = 10;
                              $y    = $pdf->get_height() - 750;
                              $x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
                              $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
                           }
                        </script>
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
               <span class="spanContent" style="float:left;display: inline-block;vertical-align: middle;">Sample Name</span><b>:</b><span style="position:relative;left:10px;padding-left:11px !important;width: 40%;">{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : ''}}</span></td>
            <td width="100%" align="left" >
               <p style="position: relative;right: 0; width: 70%;float: right;"><span class="" style="display: inline-block;vertical-align: middle;width: 50%;">Received On</span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['booking_date']) ? date(DATEFORMAT,strtotime($viewData['order']['booking_date'])) : ''}}</span></p>
            </td>
         </tr>
         <tr>
            <td style="padding:0px;visibility: hidden;" width="100%" align="left">
               <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Sample Appearance</span><b>:</b><span style="padding-left:11px !important;"></span>
            </td>
            <td  width="100%" align="left" >
               <p style="position: relative;right: 0; width: 70%;float: right;"><span  class="" style="display: inline-block;vertical-align: middle;width: 50%;">Commenced On</span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['commenced_on_date']) ? date(DATEFORMAT,strtotime($viewData['order']['commenced_on_date'])) : ''}}</span>
            </td>
         </tr>
         <tr>
            <td style="padding:0px;" width="100%" align="left">
               <b class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Sample details (if any)</b><b>:-</b>
            </td>
            <td width="100%" align="left">
               <p style="position: relative;right: 0; width: 70%;float: right;"><span class="" style="display: inline-block;vertical-align: middle;width: 50%;">Completed On</span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['test_completion_date']) ? date(DATEFORMAT,strtotime($viewData['order']['test_completion_date'])) : ''}}</span></p>
            </td>
         </tr>
         <tr>
            <td style="padding:0px;" width="100%" align="left"><span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Sample Quantity</span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : ''}}</span></td>
            <td width="100%" align="left">
               <p style="position: relative;right: 0; width: 70%;float: right;"><span class="" style="display: inline-block;vertical-align: middle;width: 50%;">Date of Report</span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['report_date']) ? date(DATEFORMAT,strtotime($viewData['order']['report_date'])) : ''}}</span></p>
            </td>
         </tr>
         <tr>
            <td style="padding:0px;" width="100%" align="left">
               <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Batch No.</span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['batch_no']) ? $viewData['order']['batch_no'] : ''}}</span>
            </td>
            <td width="100%" align="left">
               <p style="position: relative;right: 0; width: 70%;float: right;"><span class="" style="display: inline-block;vertical-align: middle;width: 50%;"></p>
            </td>
         </tr>
         <tr>
            <td style="padding:0px;" width="100%" align="left">
               <span class="spanContent" style="display: inline-block;vertical-align: middle;width: 100px;">Sample Submission Type</span><b>:</b><span style="padding-left:11px !important;">{{!empty($viewData['order']['sample_mode_name']) ? $viewData['order']['sample_mode_name'] : ''}}</span>
            </td>
            <td width="100%" align="left" >
               <p style="position: relative;right: 0; width: 70%;float: right;"><span class="" style="display: inline-block;vertical-align: middle;width: 50%;"></p>
            </td>
         </tr>
         @if(!empty($viewData['order']['submission_type']) && $viewData['order']['submission_type'] == '5')
         <tr>
            <td style="padding:0px;" width="100%" align="left" colspan="2">
               <span class="" style="display: inline-block;vertical-align: middle;width: 20% !important;">Sampling Details</span><b>:</b><span style="padding-left:11px !important;overflow-wrap: break-word;word-wrap: break-word;">{{!empty($viewData['order']['remarks']) ? trim($viewData['order']['remarks']) : ''}}</span>
            </td>
         </tr>
         @endif
         <tr>
            <td colspan="2" style="padding:0px;"  align="left">
               <span class="" style="display: inline-block;vertical-align: middle;width: 20% !important;">Customer Reference</span><b>:</b>
               <span class="spanContent" style="padding-left:11px !important;">{{!empty($viewData['order']['reference_no']) ? trim($viewData['order']['reference_no']).'/' : ''}}{{!empty($viewData['order']['letter_no']) ? $viewData['order']['letter_no'] : ''}}</span>
            </td>
         </tr>
      </table>
      
      <!--Test Parameters Detail-->
      <table border="1" width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:5px!important;">
         
            <tr>
               <th align="left" width="4%" style="padding:5px!important;">S.NO</th>
               <th align="left" width="35%">Parameters</th>
               <th align="center" width="12%">Instrument</th>
               <th align="center" width="12%">Method</th>
               <th align="center" width="12%">Result</th>
               <th align="center"width="25%">Specification</th>
            </tr>
               
            @if(!empty($viewData['orderParameters']))
               @foreach($viewData['orderParameters'] as $key => $orderParameterCategoryName)
                  <tr>
                     <th class="sno" align="center">{{$key+1}}.</th>
                     <th class="category" align="left" colspan="5" style="padding:0 5px;">{{trim($orderParameterCategoryName['categoryName'])}}</th>
                  </tr>
                  @if(!empty($orderParameterCategoryName['categoryParams']))
                     <?php $charNum = 'a';?>
                     @foreach($orderParameterCategoryName['categoryParams'] as $orderParaKey => $orderParameterCategoryParameters)
                        <tr>
                           <td align="center" class="sno">{{ $charNum++ }}.</td>
                           <td class="parameter" align="left"><?php echo trim($orderParameterCategoryParameters['test_parameter_name']);
                           echo !empty($orderParameterCategoryParameters['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.$orderParameterCategoryParameters['non_nabl_parameter_symbol'] .'</sup>' : ''; ?>
                             
                           </td>
                           @if(!empty($orderParameterCategoryParameters['description']))
                           <td class="text-justify parameter" colspan="4" align="left" style="padding:0 15px;">{{!empty($orderParameterCategoryParameters['description']) ? trim($orderParameterCategoryParameters['description']) : ''}} </td>
                           @else
                           <td align="center" class="equipmentName">{{!empty($orderParameterCategoryParameters['equipment_name']) ? trim($orderParameterCategoryParameters['equipment_name']) : ''}}</td>
                           <td align="center" class="methodName">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
                           <td align="center" class="testResult"></td>
                           <td align="center" class="requirementName">{{!empty($orderParameterCategoryParameters['requirement_from_to']) ? trim($orderParameterCategoryParameters['requirement_from_to']) : ''}}</td>
                           @endif
                        </tr>
                     @endforeach				    
                  @endif
               @endforeach
            @endif
            
            <tr>
               <td colspan="6" style="padding-top:5px!important;border: 1px solid #fff;margin-top:10px!important;">
                  <table class="pdftable" width="100%" style="border-collapse:collapse;">
                     <tr>
                        <td width="100%" align="center" style="border: 1px solid #fff;overflow-wrap: break-word;word-wrap: break-word;vertical-align: middle;" colspan="6"><b style="font-size:14px!important;vertical-align: middle;">*****End Of Document*****</td>
                     </tr>
                  </table>
               </td>
            </tr>
         </table>
         <!--/Test Parameters Detail-->
         
      </div>
   </div>
   <!--/content-->

</body>
</html>

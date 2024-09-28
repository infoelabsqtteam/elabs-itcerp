<html>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <style>
   .page-break-always{ page-break-after: always;}
   .page-break-auto{ page-break-after:auto;}
   @page { margin: 130px 20px 110px 20px;font-size:13px;}
   #header {left: 0;position: fixed;right: 0;text-align: center;top: -125px;width: 100%;height:auto;}
   #footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
   #content {bottom: 0;height: auto;left: 0;right: 0;width: 100%;}
   #footer .page:after { content: counter(page, upper-roman); }
   td table td {padding:1px!important;border-bottom: 0px!important;}
   p{padding:2px 0px !important;margin:0!important;}
   .sno{width:6%!important;font-size:12px!important;padding:5px 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .category{font-size:14px!important;padding:0 5px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;}
   .parameter{width:40%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .methodName{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .equipmentName{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .requirementName{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .testResult{width:15%!important;font-size:13px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
   .pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:3px 2px!important;vertical-align: middle;}
   .header-content table,footer-content table{border-collapse:collapse;}   
   .left-content{border: 0px none ! important; vertical-align: top;}
   .left-content .cetificate-section{color:#4d64a1;margin-top:-2px;font-size: 11px!important; border: 1px solid; display: block; vertical-align: middle; padding: 2px; width: 200px;}
   .left-content .validity-section{color:#4d64a1;font-size:11px!important; border: 1px solid; display: block; vertical-align: middle; padding: 2px; width: 111px;}   
   .middle-content{color:#4d64a1;border:0px !important;text-align:center!important;}
   .middle-content > p {margin: 0 !important;padding: 0 !important;}
   .middle-content h3{font-family: times of romen; font-weight: 600; color: rgb(77, 100, 161); margin: 0px; font-size: 30px !important;}
   .middle-content .form-section{color: rgb(138, 47, 48);margin:0px!important;font-weight: bold;border: 2px solid rgb(138, 47, 48);font-size: 15px;padding:2px!important;}
   .middle-content .lic-no-section{color:#4d64a1;font-size:11px!important;padding:5px!important;margin:0px!important;}
   .middle-content .title-section{color:#4d64a1;font-weight: 600; color: rgb(77, 100, 161);margin:-10px!important;font-size: 28px!important;line-height:28px!important;}
   .middle-content .sub-title-section{color:#4d64a1;margin:0px!important;font-size:11px!important;}
   .right-content{border: 0px none ! important; color: #d7563a; vertical-align: top; padding: 5px; text-align: center ! important; font-size: 12px ! important;vertical-align: top;}
   .footer-content h3 {font-family: times of romen; font-weight: 600; color:#4d64a1;margin: 0px; font-size: 15px !important;padding: 0 !important;}
   .footer-content p,h5 {font-size:11px !important;margin:0 !important;padding: 0 !important;}
   .footer-content ul {margin: 0 !important;padding: 0 !important;}
   .footer-content ul li {font-size: 11px !important;margin-top: 0px !important;padding: 0 !important;}
   .rightSection{display:none;}
   td.middle-content>p:nth-child(2) {padding: 5px 0 0 0!important;}
   </style>

<!--- header-->
<div id="header">

   <div class="header-content"@if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3'))) style="visibility: hidden;"@endif>
      <?php echo !empty($viewData['order']['header_content']) ? htmlspecialchars_decode($viewData['order']['header_content']) : '';?>
   </div>
   
   <table width="100%" style="border-collapse:collapse;margin:0px!important;padding:0px!important;@if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3')))visibility: hidden;@endif">
      <tr>
	 <td align="left" width="25%">&nbsp;</td>
	 <td width="60%" align="center"><b style="padding: 5px;font-size: 16px; width: 200px;">TEST CONFIRMATION DOCUMENT</b></td>
	 <td align="left" width="15%">&nbsp;</td>
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
	       <script type='text/php'>
		  if(isset($pdf)){ 
		     $font = $fontMetrics->get_font('serif','bold');
		     $size = 11;
		     $y    = $pdf->get_height() - 765;
		     $x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
		     $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
		  }
	       </script>
	       </b>
	    </td>
	 </tr>
      </table>
   </div>
   <div @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('3')))style="visibility:hidden;"@endif>
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
		  <td style="padding:0px;" align="left" colspan="3"><b style="display: inline-block;vertical-align: middle;width: 100px;">Sample Name</b><b>:</b><b>{{!empty($viewData['order']['sample_description']) ? $viewData['order']['sample_description'] : ''}}</b></td>
	       </tr>	       
	       <tr>
		  <td style="padding:0px;" align="left" colspan="2" width="66.7%"><b style="display: inline-block;vertical-align: middle;vertical-align: middle;width: 100px;">Supplied By</b><b>:</b>{{!empty($viewData['order']['supplied_by']) ? $viewData['order']['supplied_by'] : '' }}</td>
		  <td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Report Date</b><b>:</b></td>
	       </tr>	       
	       <tr>
		  <td style="padding:0px;" align="left" colspan="2" width="66.7%"><b style="display: inline-block;vertical-align: middle;width: 100px;">Manufactured By</b><b>:</b>{{!empty($viewData['order']['manufactured_by']) ?  $viewData['order']['manufactured_by'] : ''}}</td>
		  <td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Sample Reg. No.</b><b>:</b>{{!empty($viewData['order']['order_no']) ?  $viewData['order']['order_no'] : ''}}</td>
	       </tr>
	       <tr>
		  <td style="padding:0px;" align="left" colspan="2" width="66.7%"><b style="display: inline-block;vertical-align: middle;width: 100px;">Submitted By</b><b>:</b>{{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}</td>
		  <td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Booking Date</b><b>:</b>{{!empty($viewData['order']['order_date']) ?  date(DATEFORMAT,strtotime($viewData['order']['order_date'])) :''}}</td>
	       </tr>	 
	       <tr>
		  <td style="padding:0px;" align="left" colspan="3" width="66.7%"><b style="display: inline-block;vertical-align: middle;width: 100px;">Address</b><b>:</b>{{!empty($viewData['order']['customer_name']) ? $viewData['order']['customer_name'] : ''}}-{{!empty($viewData['order']['customer_address']) ? $viewData['order']['customer_address'] : ''}}</td>
	       </tr>	    
	       <tr>
		  <td style="padding:0px;" align="left" colspan="2" width="66.7%"><b style="display: inline-block;vertical-align: middle;width: 100px;">Mfg. Lic. No.</b><b>:</b>{{!empty($viewData['order']['mfg_lic_no']) ? $viewData['order']['mfg_lic_no'] : ''}}</td>
		  <td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Party Ref. No.</b><b>:</b>{{!empty($viewData['order']['reference_no']) ?  $viewData['order']['reference_no'] : ''}}</td>
	       </tr>
	       <tr>
		  <td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 100px;">Batch No.</b><b>:</b>{{!empty($viewData['order']['batch_no']) ?  $viewData['order']['batch_no'] : ''}} </td>
		  <td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 60px;">Batch Size</b><b>:</b>{{!empty($viewData['order']['batch_size']) ? $viewData['order']['batch_size'] :'' }}</td>
		  <td style="padding:0px;"><b style="display: inline-block;vertical-align: middle;width: 95px;">Party Ref. Date</b><b>:</b>{{!empty($viewData['order']['letter_no']) ?  $viewData['order']['letter_no'] : ''}}</td>
	       </tr>	       
	       <tr>
		  <td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 100px;">D/M</b><b>:</b>{{!empty($viewData['order']['mfg_date']) ? $viewData['order']['mfg_date'] : ''}}</td>
		  <td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 60px;">D/E</b><b>:</b>{{!empty($viewData['order']['expiry_date']) ? $viewData['order']['expiry_date'] : ''}}</td>
		  <td align="left" style="padding:0px;" width="33.3%"><b style="display: inline-block;vertical-align: middle;width: 95px;">Sample Qty</b><b>:</b>{{!empty($viewData['order']['sample_qty']) ? $viewData['order']['sample_qty'] : ''}}</td>
	       </tr>	      
	       @if(!empty($viewData['order']['header_note']))
		  <tr>
		     <td align="center" colspan="3" style="padding:0px;" width="50%"><b style="margin:0px;padding:0px;">{{!empty($viewData['order']['header_note']) ? $viewData['order']['header_note'] : ''}}</b></td>
		  </tr>
	       @endif
	    </tbody>
	 </table>
	 
	 <?php $colspanCounter = empty($notContainEquipment) ? 6 : 5 ;?>
	 
	 <table width="100%" border="1" class="pdftable" style="margin:0 auto;border-collapse:collapse;margin-top:10px!important;">
	    
	    <tr>
	       <td align="left" colspan="{{$colspanCounter}}">
		  <b style="padding-top:7px!important;padding-bottom:7px!important;font-size:14px!important;display: inline-block;vertical-align: middle;">PART C : TEST RESULTS </b>
	       </td>
	    </tr>
	       
	    <tr>
	       <th align="center" style="width:5%!important;">S.No.</th>
	       <th align="center" style="width:35%!important;">Test Parameter</th>
	       @if(empty($notContainEquipment))
	       <th align="center" style="width:15%!important;">Inst. Used</th>
	       @endif
	       <th align="center" style="{{ empty($notContainEquipment) ? 'width:15%!important;' : 'width:20%!important'}}">Method</th>
	       <th align="center" style="{{ empty($notContainEquipment) ? 'width:15%!important;' : 'width:20%!important'}}">Requirement</th>
	       <th align="center" style="{{ empty($notContainEquipment) ? 'width:19%!important;' : 'width:24%!important'}}">Result</th>
	    </tr>
	       
	    <tr>
	       <td colspan="{{$colspanCounter}}" style="text-align:left;padding: 5px;">
		  <b>Test Details :</b>&nbsp;<span>{{!empty($viewData['order']['header_note']) ? ucfirst($viewData['order']['header_note']) : '' }}</span>
	       </td>
	    </tr>
	    
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
			   <td class="parameter" align="left"><?php echo trim($orderParameterCategoryParameters['test_parameter_name']);
				echo !empty($orderParameterCategoryParameters['non_nabl_parameter_symbol']) ? '&nbsp;<sup>'.$orderParameterCategoryParameters['non_nabl_parameter_symbol'] .'</sup>' : ''; 
		  		?>
			   </td>
			   @if(!empty($orderParameterCategoryParameters['description']))
			      <td  class="text-justify parameter" colspan="{{$colspanCounter-2}}" align="left" style="padding:0 15px;">{{!empty($orderParameterCategoryParameters['description']) ? trim($orderParameterCategoryParameters['description']) : ''}} </td>
			   @else
			      @if(empty($notContainEquipment))
			      <td class="equipmentName" align="center">{{!empty($orderParameterCategoryParameters['equipment_name']) ? trim($orderParameterCategoryParameters['equipment_name']) : ''}}</td>
			      @endif
			      <td class="methodName" align="center">{{!empty($orderParameterCategoryParameters['method_name']) ? trim($orderParameterCategoryParameters['method_name']) : ''}}</td>
			      <td class="requirementName" align="center">{{!empty($orderParameterCategoryParameters['requirement_from_to']) ? trim($orderParameterCategoryParameters['requirement_from_to']) : ''}}</td>
			      <td class="testResult" align="left"></td>
			   @endif		     
			</tr>
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
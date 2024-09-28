<html>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <style>
      .page-break-always{ page-break-after: always;}
      .page-break-auto{ page-break-after:auto;}
      @page { margin: 250px 20px 20px 30px;font-size:14px;}
      #header {left: 0;position: fixed;right: 0;text-align: center;top: -240px;width: 100%;height:auto;}
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
      .pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:5px 2px!important;vertical-align: middle;}
      .pdftable tr th {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:5px 2px!important;vertical-align: middle;}
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
	 <?php echo !empty($viewData['header_content']) ? htmlspecialchars_decode($viewData['header_content']) : '';?>
      </div>
	  
      <table width="100%" style="border-collapse: collapse;">
	  <tr>
	      <td colspan="6">
		  <table width="100%" style="padding: 0px !important;">
		      <tr>
			  <td width="33.3%" align="left"></td>
			  <td width="33.3%" align="center"><b style="padding: 5px;font-size: 18px; width: 150px;">STABILITY TESTING FORMAT</b></td>
			  <td width="33.3%" align="right">
			      <span style="font-weight:normal" align="right">
				 <script type='text/php'>
				 if(isset($pdf)){ 
				    $font = $fontMetrics->get_font('serif','Normal');
				    $size = 10;
				    $y    = $pdf->get_height() - 507;
				    $x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
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
      <table width="100%" border="1" style="padding:1px!important;border-collapse:collapse;">
	 
	 <tr>
	    <td width="50%" style="border-bottom:1px solid #FFF!important;border-left:1px solid #FFF!important;border-right:1px solid #FFF!important;">
	       <p style="font-size:14px;padding:5px 2px!important;margin:0!important;"><b style="width:100px!important;float:left;">Product Name</b><b>:</b>{{!empty($viewData['part_a']['product_name']) ? $viewData['part_a']['product_name'] : ''}}</p>
	    </td>
	    <td width="50%" style="border-bottom:1px solid #FFF!important;border-left:1px solid #FFF!important;border-right:1px solid #FFF!important;">
	       <p style="font-size:14px;padding:5px 2px!important;margin:0!important;"><b style="width:120px!important;float:left;">Protocol&nbsp;#</b><b>:</b>{{!empty($viewData['part_a']['protocol']) ? $viewData['part_a']['protocol'] : ''}}</p>
	    </td>
	 </tr>
	 <tr>
	    <td width="50%" colspan="2" style="border:1px solid #FFF!important;">
	       <p style="font-size:14px;padding:5px 2px!important;margin:0!important;"><b style="width:100px!important;float:left;">Product Desc.</b><b>:</b>{{!empty($viewData['part_a']['product_description']) ? $viewData['part_a']['product_description'] : ''}}</p>
	    </td>
	 </tr>
	 <tr>
	    <td width="50%" style="border-top:1px solid #FFF!important;border-left:1px solid #FFF!important;border-right:1px solid #FFF!important;">
	       <p style="font-size:14px;padding:5px 2px!important;margin:0!important;"><b style="width:100px!important;float:left;">Batch No.</b><b>:</b>{{!empty($viewData['part_a']['batch_no']) ? $viewData['part_a']['batch_no'] : ''}}</p>
	    </td>
	    <td width="50%" style="border-top:1px solid #FFF!important;border-left:1px solid #FFF!important;border-right:1px solid #FFF!important;">
	       <p style="font-size:14px;padding:5px 2px!important;margin:0!important;"><b style="width:120px!important;float:left;">Storage Conditions</b><b>:</b>{{!empty($viewData['part_a']['storage_condition']) ? $viewData['part_a']['storage_condition'] : ''}}</p>
	    </td>
	 </tr>
	 
      </table>
</div>
<!--- /header end-->

<!--- footer start-->
<div id="footer" class="footer-content">    
   <div @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('2','3')))style="visibility: hidden;"@endif>
      <?php echo !empty($viewData['footer_content']) ? htmlspecialchars_decode($viewData['footer_content']) : '';?>
      <table border="1" class="pdftable" width="100%" style="border-collapse:collapse;">            
         <tr><td align="left" colspan="2" style="border-top: 1px solid #000!important;border-left: 1px solid #FFF!important;border-right: 1px solid #FFF!important;border-bottom: 1px solid #FFF!important;">&nbsp;</td></tr>
      </table>
   </div>
</div>
<!--- /footer start-->

<body>
   <div id="content">
      <div class="page-break-auto">
	 
	 <table class="pdftable" width="100%" style="border-collapse:collapse;">
	 
	    <tr>
               <td align="left" style="font-size:15px!important;border-bottom: 1px solid rgb(255, 255, 255);padding-top:7px!important;padding-bottom:7px!important;" width="50%" colspan="4"><b style="display: inline-block;vertical-align: middle;font-size:14px;">SAMPLE INFORMATION :</b></td>
            </tr>
	     
	    <tr>
	       <td width="20%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">Date Of Manufacturing</td>
	       <td width="30%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">:&nbsp;{{!empty($viewData['part_a']['date_of_manufacturing']) ? $viewData['part_a']['date_of_manufacturing'] : ''}}</td>
	       <td width="20%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">Storage condition on Sample Pack</td>
	       <td width="30%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">:&nbsp;{{!empty($viewData['part_a']['storage_cond_sample_pack']) ? $viewData['part_a']['storage_cond_sample_pack'] : ''}}</td>
	    </tr>
	    
	    <tr>
	       <td width="20%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">Date of Expiry</td>
	       <td width="30%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">:&nbsp;{{!empty($viewData['part_a']['date_of_expiry']) ? $viewData['part_a']['date_of_expiry'] : ''}}</td>
	       <td width="20%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">Quantity of Sample</td>
	       <td width="30%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">:&nbsp;{{!empty($viewData['part_a']['sample_qty']) ? $viewData['part_a']['sample_qty'] : ''}}{{!empty($viewData['part_a']['sample_qty_unit']) ? '&nbsp;'.$viewData['part_a']['sample_qty_unit'] : ''}}</td>
	    </tr>
	       
	    <tr>
	       <td width="20%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">Date of Incubation</td>
	       <td width="30%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">:&nbsp;{{!empty($viewData['part_a']['date_of_incubation']) ? $viewData['part_a']['date_of_incubation'] : ''}}</td>
	       <td width="20%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">Sample Pack</td>
	       <td width="30%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">:&nbsp;{{!empty($viewData['part_a']['sample_pack']) ? $viewData['part_a']['sample_pack'] : ''}}</td>
	    </tr>
	       
	    <tr>
	       <td width="20%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">Orientation</td>
	       <td width="30%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">:&nbsp;{{!empty($viewData['part_a']['orientation']) ? $viewData['part_a']['orientation'] : ''}}</td>
	       <td width="20%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">Pack Code</td>
	       <td width="30%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">:&nbsp;{{!empty($viewData['part_a']['sample_pack_code']) ? $viewData['part_a']['sample_pack_code'] : ''}}</td>
	    </tr>
	    
	    <tr>
	       <td width="20%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">Sample</td>
	       <td colspan="3" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">:&nbsp;{{!empty($viewData['part_a']['sample_description_name']) ? $viewData['part_a']['sample_description_name'] : ''}}</td>
	    </tr>
	    
	    <tr>
	       <td width="20%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">Frequency of Testing</td>
	       <td colspan="3" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">:&nbsp;{{!empty($viewData['part_a']['frequency_of_testing']) ? $viewData['part_a']['frequency_of_testing'] : ''}}</td>
	    </tr>

	    <tr>
	       <td width="20%" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">Test to be Performed</td>
	       <td colspan="3" align="left" style="font-size:15px!important;padding:10px 2px!important;margin:0!important;">
		  @if(!empty($viewData['part_b']['tbody']))
		     <?php $testParameterToBePerformed = array();?>
		     @foreach($viewData['part_b']['tbody'] as $key => $tbodyData)
			<?php $testParameterToBePerformed[] = trim(strip_tags($tbodyData['parameter_name']));?>
		     @endforeach
		     <div style="font-size:14px!important;">:&nbsp;<?php echo !empty($testParameterToBePerformed) ? trim(implode(', ', $testParameterToBePerformed)) : '';?></div>
		  @endif
	       </td>
	    </tr>

	 </table>
      </div>
      
      <div class="page-break-always"></div>
	 
      <div class="page-break-auto">

	 @if(!empty($viewData['part_b']['thead']))
	    
	    <?php $colspanCounterPartB = !empty($viewData['part_b']['thead']) ? count($viewData['part_b']['thead']) : '0';?>
	    
	    <table class="pdftable" border="1" width="100%" style="border-collapse:collapse;">
	       
	       <tr>
		  <td align="left" colspan="{{$colspanCounterPartB}}" style="border:1px solid #FFF!important;">
		     <b style="padding-top:7px!important;padding-bottom:7px!important;font-size:14px!important;display: inline-block;vertical-align: middle;">TEST RESULTS : {{!empty($viewData['part_a']['stability_type_name']) ? $viewData['part_a']['stability_type_name'] : ''}} {{!empty($viewData['part_a']['storage_condition']) ? '('.$viewData['part_a']['storage_condition'].')' : ''}}</b>
		  </td>
	       </tr>
	       
	       <tr>
		  @foreach($viewData['part_b']['thead'] as $key => $thValue)
		     @if($thValue == 'parameter_name')
			<th style="width:25%!important;padding:8px 1px!important;margin:0!important;overflow-wrap:break-word;word-wrap:break-word;" align="center">{{strtoupper(str_replace('_',' ',$thValue))}}</th>
		     @else
			<th style="padding:5px 1px!important;margin:0!important;overflow-wrap:break-word;word-wrap:break-word;" align="center">{{strtoupper(str_replace('_',' ',$thValue))}}</th>
		     @endif
		  @endforeach
	       </tr>
		
	       @if(!empty($viewData['part_b']['tbody']))
		  @foreach($viewData['part_b']['tbody'] as $key => $tbodyData)
		     @if(is_array($tbodyData) && !empty($tbodyData))
			<?php $charNum = 'a';?>
			<tr>
			   @foreach($tbodyData as $tbodyTrKey => $tbodyValueTr)
			      @if($tbodyTrKey == 'parameter_name')
				 <td align="left" style="overflow-wrap:break-word;word-wrap:break-word;"><?php echo $tbodyValueTr;?></td>
			      @else
				 <td align="center" style="overflow-wrap:break-word;word-wrap:break-word;"><?php echo $tbodyValueTr;?></td>
			      @endif
			   @endforeach
			</tr>
		     @endif
		    @endforeach
		@endif	
	    </table>
	 @endif
	 
      </div>
      
      <div class="page-break-always"></div>
      
      <div class="page-break-auto">
	 
	 @if(!empty($viewData['part_c']['thead']))
	    
	    <?php $colspanCounterPartC = !empty($viewData['part_c']['thead']) ? count($viewData['part_c']['thead']) : '0';?>
	       
	    <table class="pdftable" border="1" width="100%" style="border-collapse:collapse;">
	       
	       <tr>
		  <td align="left" colspan="{{$colspanCounterPartC}}" style="border:1px solid #FFF!important;">
		     <b style="padding-top:7px!important;padding-bottom:7px!important;font-size:14px!important;display: inline-block;vertical-align: middle;">STF APPROVAL STAGE :</b>
		  </td>
	       </tr>
	       
	       <tr>
		  @foreach($viewData['part_c']['thead'] as $key => $thValue)
		    <th style="padding:8px 0!important;overflow-wrap:break-word;word-wrap:break-word;" align="center">{{ strtoupper(str_replace('_',' ',$thValue)) }}</th>
		  @endforeach
	       </tr>
		
	       @if(!empty($viewData['part_c']['tbody']))
		  @foreach($viewData['part_c']['tbody'] as $key => $tbodyData)
		     @if(is_array($tbodyData) && !empty($tbodyData))
			<?php $charNum = 'a';?>
			<tr>
			@foreach($tbodyData as $tbodyTrKey => $tbodyValueTr)
			   <td align="center" style="overflow-wrap:break-word;word-wrap:break-word;"><?php echo $tbodyValueTr;?></td>
			@endforeach
			</tr>
		     @endif
		    @endforeach
		@endif
		
	    </table>                  
	 @endif
      </div>
      
   </div>
</body>
</html>
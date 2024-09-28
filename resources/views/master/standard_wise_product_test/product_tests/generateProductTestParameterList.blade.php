<html>

   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <style>
   .page-break-always{ page-break-after: always;}
   .page-break-auto{ page-break-after:auto;}	
   @page { margin: 100px 20px 30px 20px;font-size:13px;}	
   #header {left: 0;position: fixed;right: 0;text-align: center;top: -100px;width: 100%;height:auto;}
   #footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
   #content {bottom: 0;height: auto;left: 0;right: 0;width: 100%;}
   #footer .page:after { content: counter(page, upper-roman); }
   .cntr_hdng h5,.cntr_hdng p {font-size: 13px;margin: 3px;font-weight: 700;color: #4d64a1;}
   .side_cntnt {width: 20%;border: 0px!important;font-size: 12px!important;color: #4d64a1;}
   .side_cntnt p {margin: 0;}
   td table td {padding:1px!important;border-bottom: 0px!important;}
   p{padding:2px 0px !important;margin:0!important;}
   .pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
   .pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:3px 2px!important;vertical-align: middle;}
   </style>

<!--- header-->
<div id="header">
	<table width="100%"  border="1" style="border-collapse:collapse;">
		<tr>
			<td align="left" colspan="6" width="100%"><b style="margin: 0 5px;text-align:center;display: inline-block;vertical-align: middle;padding:5px">PRODUCT TEST DETAILS</b></td>
		</tr>
		 <tr>
			<td colspan="6">
				<table width="100%">
				  <tr>
					 <td colspan="2">Test Code : {{!empty($viewData['testDetails']['test_code'])?$viewData['testDetails']['test_code']:'-'}}</td>
					 <td colspan="2">Product Name : {{!empty($viewData['testDetails']['product_name'])?$viewData['testDetails']['product_name']:'-'}}</td>					
					 <td colspan="2">Test Standard Name : {{!empty($viewData['testDetails']['test_std_name'])?$viewData['testDetails']['test_std_name']:'-'}}</td>					
				  </tr>
				  <tr>
					 <td colspan="2">Wef : {{!empty($viewData['testDetails']['wef'])?$viewData['testDetails']['wef']:''}}</td>
					 <td colspan="2">Upto : {{!empty($viewData['testDetails']['upto'])?$viewData['testDetails']['upto']:''}}</td>
					 <td colspan="2">Created At: {{!empty($viewData['testDetails']['created_at'])?$viewData['testDetails']['created_at']:'-'}}</td>
				  </tr>
				</table>
			</td>
		 </tr>
	</table>
</div>
<!--- header end-->

<!--- footer start-->
<div id="footer">
	<table valign="bottom" width="100%" style="margin:0 auto;border-collapse:collapse;">
	  	<tr>
			<td align="center" colspan="6" style="margin-top: 0px!important;">
			   <b style="text-align:center;">
			      <script type='text/php'>
					if(isset($pdf)){ 
						$font = $fontMetrics->get_font('serif','bold');
						$size = 11;
						$y    = $pdf->get_height() - 30;
						$x    = $pdf->get_width() - 305 - $fontMetrics->get_text_width('1/1', $font, $size);
						$pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
					}
			     </script>
			   </b>
		     </td>
	  	</tr>
	</table>
</div>
<!--- footer start-->

<body>
   <!--content-->
   <div id="content">
      <div class="page-break-auto">
	<table class="pdftable" width="100%" border="1" style="border-collapse:collapse;">
	    <tr>
	       <td align="left" colspan="7" width="100%"><b style="margin: 0 5px;display: inline-block;vertical-align: middle;padding:5px">PRODUCT TEST PARAMETERS</b></td>
	    </tr>
	    <tr>
	       <th align="center" width="5%">S.No.</th>
	       <th align="left" width="35 %" class="table-head">Test Parameter</th>
	       <th align="center"  width="12%">Equipment</th>
	       <th align="center"  width="12%">Method</th>
	       <th align="center" width="12%">Std Value From</th>
	       <th align="center"  width="12%">Std Value To</th>
	       <th align="center"  width="12%">Time Taken</th>
	    </tr>
		  <?php $paraCount=1; ?>
		  @if(!empty($viewData['testParameterList'])) 
			  @foreach($viewData['testParameterList'] as $key=>$orderParameterCategoryName)
				  <tr>
				       <td align="center" width="5%">{{++$key}}.</td>
				       <th colspan="6"  style="width:5%;text-align:left;" style="padding:0 5px">{{$orderParameterCategoryName['categoryName']}}</th>						
				  </tr>					
				  @if(!empty($orderParameterCategoryName['categoryParams']))
					  <?php $char='a';  ?> 
					  @foreach($orderParameterCategoryName['categoryParams'] as $orderParaKey=>$orderParameterCategoryParameters)
					     <tr>
						<td align="center" width="5%">{{ $char++ }}.<?php $paraCount++; ?></td>
						<td colspan="" style="width:5%;text-align:left;white-space:wrap!important;word-break:break-all;"><?php echo trim($orderParameterCategoryParameters[ 'test_parameter_name']);?></td>
						@if(!empty($orderParameterCategoryParameters['description']))
						   <td class="text-justify" colspan="5" align="left" style="padding:0 15px;">{{!empty($orderParameterCategoryParameters['description']) ? $orderParameterCategoryParameters['description'] : ''}} </td>
						@else
						   <td style="text-align:left;">
						   {{ !empty($orderParameterCategoryParameters['equipment_name'])?$orderParameterCategoryParameters['equipment_name']:'-'}}
						   </td>
						   <td width="12%"  style="text-align:left;white-space:wrap!important;word-break:break-all;">
						   {{ !empty($orderParameterCategoryParameters['method_name'])?$orderParameterCategoryParameters['method_name']:'-'}}
						   </td>
						   <td  style="text-align:left;">
						   {{ !empty($orderParameterCategoryParameters['standard_value_from'])?$orderParameterCategoryParameters['standard_value_from']:'-'}}
						   </td>
						   <td  style="text-align:left;">
						   {{ !empty($orderParameterCategoryParameters['standard_value_to'])?$orderParameterCategoryParameters['standard_value_to']:'-'}}
						   </td>
						   <td  style="text-align:left;">
						   {{ !empty($orderParameterCategoryParameters['time_taken_days'])?$orderParameterCategoryParameters['time_taken_days'].' Days':''}} {{ !empty($orderParameterCategoryParameters['time_taken_mins'])!='00:00'?$orderParameterCategoryParameters['time_taken_mins'].' Minutes':''}} 
						   </td>
						@endif								
					     </tr>							
					  @endforeach
				  @endif  
			  <?php $paraCount++; ?>
			  @endforeach 
		  @endif
	    </table>
	</div>
      </div>
      <!--/content-->
</body>
</html>
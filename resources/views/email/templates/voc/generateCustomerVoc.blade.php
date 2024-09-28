<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
   .page-break-always{ page-break-after: always;}
   .page-break-auto{ page-break-after:auto;}
   @page { margin: 130px 20px 20px 20px;font-size:13px;}
   #header {left: 0;position: fixed;right: 0;text-align: center;top: -125px;width: 100%;height:auto;}	
   #footer {left: 0;position: fixed;right: 0;bottom:0px;width: 100%;height:auto;}
   #content {bottom: 0;height:auto;left: 0;right: 0;width: 100%;}
   td table td {border-bottom: 0px!important;}
   p{padding:2!important;margin:0!important;}
   .sno{width:6%!important;font-size:12px!important;padding:5px 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .category{font-size:13px!important;padding:0 5px!important;font-weight:bold;overflow-wrap: break-word;word-wrap: break-word;}
   .parameter{width:25%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .parameter p{padding:0px!important;margin:0!important;display: inline-block!important;}
   .methodName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .equipmentName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .requirementName{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .testResult{width:15%!important;font-size:12px!important;padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
   .pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
   .pdftable tr th {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:5px 2px!important;vertical-align: middle;}
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
</style> 
<!--- header-->     
<div id="header">
    
   <div class="header-content"@if(!empty($hasContainHeaderFooter) && $hasContainHeaderFooter == '3') style="visibility: hidden;"@endif>
      <?php echo !empty($viewData['header_content']) ? htmlspecialchars_decode($viewData['header_content']) : '';?>
   </div>
      
   <table width="100%" style="border-collapse: collapse;">
      <tr>
	 <td colspan="6">
	    <table width="100%" style="border-collapse: collapse;padding: 0px 0px 10px 0px !important;">
	       <tr>
		  <td width="33.3%" align="left"></td>
		  <td width="33.3%" align="center"><b style="padding: 0px;font-size: 18px; width: 150px;">VOICE OF CUSTOMER</b></td>
		  <td width="33.3%" align="right">
		     <span style="font-weight:normal" align="right">
			<script type='text/php'>
			if(isset($pdf)){ 
			   $font = $fontMetrics->get_font('serif','Normal');
			   $size = 10;
			   $y    = $pdf->get_height() - 758;
			   $x    = $pdf->get_width() - 55 - $fontMetrics->get_text_width('1/1', $font, $size);
			   $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
			}
			</script>
		     </span>
		  </td>
	       </tr>
	    </table>
	 </td>
      </tr>
   </table>
      
</div>
<!--- /header-->

<!--- footer start-->
<div id="footer" class="footer-content">
   <div @if(!empty($hasContainHeaderFooter) && in_array($hasContainHeaderFooter,array('2','3')))style="visibility: hidden;"@endif>
      <?php echo !empty($viewData['footer_content']) ? htmlspecialchars_decode($viewData['footer_content']) : '&nbsp;';?>
   </div>
</div>
<!--- /footer start-->

<body>
   <div id="content">
      <div class="page-break-auto">
	 <table class="pdftable" width="100%" style="border-collapse:collapse;">
	    <tbody>
	       <tr>
		  <td colspan="{{count($viewData['tableHead'])}}">
		     <h4 style="text-align: left;font-size: 17px;">Dear {{$viewData['customer_name']}},</h4>
		     <div style="margin-top:-10px!important;text-align: left;font-size: 15px;">Please find the below detail regarding Sample Booked in this month: </div>
		  </td>
	       </tr>
	       <tr>
		  <th colspan="{{count($viewData['tableHead'])}}" style="margin-top:10px!important;;text-align:left;font-size:15px;">{{$viewData['heading']}}</th>
	       </tr>
	    </tbody>
	 </table>	 
	 <table class="pdftable" border="1" width="100%" style="border-collapse:collapse;">
	       <thead>			   
		  <tr>
		     @if(!empty($viewData['tableHead']))
			@foreach($viewData['tableHead'] as $theadData)
			   @if(str_replace(' ','',$theadData) == 'SNO')
			      <th class="sno">{{$theadData}}</th>
			   @elseif(str_replace(' ','',$theadData) == 'SAMPLEDESCRIPTION')
			      <th class="parameter">{{$theadData}}</th>
			   @else
			      <th>{{$theadData}}</th>
			   @endif
			@endforeach
		     @endif
		  </tr>
	       </thead>
	       <tbody>
		  @if(!empty($viewData['tableBody']['orderData']))
		     @foreach($viewData['tableBody']['orderData'] as $key => $tableBody)		  
			<tr>
			   @if(!empty($tableBody) && is_array($tableBody))
			      @foreach($tableBody as $key => $trValue)			   
				 <td align="center">{{trim($trValue)}}</td>
			      @endforeach
			   @endif
			</tr>
		     @endforeach
		  @endif
	       </tbody>	       
	    </tbody>
	 </table>
	 <table class="pdftable" width="100%" style="border-collapse:collapse;margin-top:10px!important;">
	    <thead>
	       <tr>
		  <th colspan="{{count($viewData['summaryTableHead'])}}" style="padding: 5px 0 5px 5px;text-align:left;font-size:15px;">{{$viewData['summaryHeading']}}</th>
	       </tr>
	    </thead>
	 </table>
	 <table class="pdftable" border="1" width="100%" style="border-collapse:collapse;margin-top:5px!important;text-align:center!important;">
	    <thead>			   
	       @if(!empty($viewData['summaryTableHead']))
		  <tr>
		     @foreach($viewData['summaryTableHead'] as $summaryTheadData)
			<th>{{$summaryTheadData}}</th>
		     @endforeach
		  </tr>
	       @endif
	    </thead>
	    <tbody>
	       @if(!empty($viewData['summaryTableBody']))			   
		  <tr>
		     @foreach($viewData['summaryTableBody'] as $key => $trValue)			   
			<td align="center">{{trim($trValue)}}</td>
		     @endforeach
		  </tr>
	       @endif
	    </tbody>
	 </table>
      </div>
   </div>
</body>
</html>
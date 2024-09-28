<html>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <style>
   *{padding: 0;font-family: sans-serif;font-size: 12px;}
  .page-break-always{ page-break-after: always;}
   .page-break-auto{ page-break-after:auto;}
   @page { margin: 100px 20px 20px; }
   #header { position: fixed; left: 0px; top: -100px; right: 0px; width:100%; height: auto; text-align: center; }
   #footer { position:  fixed;  left: 0px; bottom: 10px; right: 0px; width:100%; height:50px }
   #footer .page:after { content: counter(page, upper-roman); }
   #content{width:100%; left: 0px; bottom: 0px; right: 0px;}
   .cntr_hdng h5,.cntr_hdng p {font-size: 13px;margin: 3px;font-weight: 700;color: #4d64a1;}
   .side_cntnt {width: 20%;border: 0px!important;font-size: 12px!important;color: #4d64a1;}
   .side_cntnt p {margin: 0;}
   table tr td {border-bottom: 0 none !important;padding:2px !important;}
   p{padding:0!important;margin:0!important;}
   </style>
       <!--header-->
   <div id="header">
      <div class="header-content">
	 <?php echo !empty($viewData['header_content']) ? htmlspecialchars_decode($viewData['header_content']) : '';?>
      </div>
   </div>
   <!--/header-->
<body>
   <!--content-->
   <div id="content">
   	 <p ><h4 style="font-size:1.1em;margin-top: -14px; margin-bottom: 0px ! important; text-align:center">{{!empty($viewData['heading']) ? $viewData['heading'] : 'Total'}}</h4></p>
	<br/>
      <div class="page-break-auto">
	 <table width="100%" border="1" style="margin:0 auto;border-collapse:collapse;">
	 <thead>
	    <tr>
	       @if(!empty($viewData['tableHead']))
		  <?php $count = '1';?>
		  <th>SR.NO.</th>
		  @foreach($viewData['tableHead'] as $key => $tdValue)
		     <th align="center">{{str_replace('_', ' ', strtoupper($tdValue))}}</th>
		  @endforeach
	       @endif
	    </tr>
	 </thead>
	    <tbody>
	    @if(!empty($viewData['tableBody']))
	       @foreach($viewData['tableBody'] as $key => $tableBody)
		  
		  <tr>
		    <td align="center">{{$count++}}.</td>
		     @if(!empty($tableBody) && is_array($tableBody))
			@foreach($tableBody as $key => $trValue)
			   
			   <td align="center">{{trim(wordwrap($trValue,20))}}</td>
			@endforeach
		     @endif
		  </tr>
	       @endforeach
	    @endif
	    </tbody>
	 </table>
      </div>
   </div>
   <!--/content -->
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
		       $x    = $pdf->get_width() - 450 - $fontMetrics->get_text_width('1/1', $font, $size);
		       $pdf->page_text($x, $y, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, $size);
	       }
	   </script>
	 </b>
      </td>
   </tr>
</table>
</div>
<!--- footer start-->
</body>
</html>
   <?php //die;?>

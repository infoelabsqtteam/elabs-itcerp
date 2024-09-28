<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>
  .page-break-always{ page-break-after: always;}
  .page-break-auto{ page-break-after:auto;}
   @page { margin: 100px 20px 40px 20px;font-size:13px;}
  #header { position: fixed; left: 0px; top: -100px; right: 0px; width:100%; height: auto; text-align: center; }
  #footer { position:  fixed;  left: 0px; bottom: 10px; right: 0px; width:100%; height:50px }
  #content{width:100%; left: 0px; bottom: 0px; right: 0px;}
  p{padding:0!important;margin:0!important;}
  .pdftable{table-layout:fixed;border-collapse: collapse;background: #fff;}
  .pdftable tr th {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:3px 2px!important;vertical-align: middle;}
  .pdftable tr td {text-overflow:ellipsis;overflow:hidden;white-space:wrap;padding:3px 2px!important;vertical-align: middle;}
  .sno{width:4%!important;font-size:12px!important;padding:5px 5px!important;overflow-wrap: break-word;word-wrap: break-word;}
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
  </style>
</head>

<!--header-->
<div id="header" class="header-content">
	
  <?php echo !empty($viewData['header_content']) ? htmlspecialchars_decode($viewData['header_content']) : '';?>
</div>
<!--/header-->

<!--footer-->
<div id="footer" class="footer-content">  
  <table width="100%" style="margin:0 auto;border-collapse:collapse;">
      <tr>
	 <td align="center" colspan="{{count($viewData['tableHead'])}}">
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
  <?php echo !empty($viewData['footer_content']) ? htmlspecialchars_decode($viewData['footer_content']) : '';?>  
</div>
<!--/footer-->

<body>
   <!--content-->
   <div id="content">      
      <div class="page-break-auto">      
	  <h5 style="font-size:20px!important;text-align:center;margin-top:-20px!important;">{{!empty($viewData['heading']) ? $viewData['heading'] : 'All'}}</h5>
	  <table class="pdftable" width="100%" border="1" style="margin:0 auto;border-collapse:collapse;">
	    <thead>
	       <tr>	       
		   @if(!empty($viewData['tableHead']))
		      <?php $count='1';?>
		      <th align="center" class="sno">SR.NO.</th>
		      @foreach($viewData['tableHead'] as $key => $tdValue)
		      <th align="center" style="padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;">{{str_replace('_', ' ', strtoupper($tdValue))}}</th>
		      @endforeach
		   @endif
	       </tr>
	    </thead>
	    <tbody>
	    @if(!empty($viewData['tableBody']))
	       @foreach($viewData['tableBody'] as $key => $tableBody)
		 <tr>
		    <td align="center" class="sno">{{$count++}}</td>
		    @if(!empty($tableBody) && is_array($tableBody))
		      @foreach($tableBody as $key => $trValue)
			@if($key == 'barcode')
			<td align="left" width="15%" style="padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;"><img height="18px" src="{{!empty($trValue) ? $trValue : ''}}"></td>
			@else
			<td align="center" style="padding:0 5px!important;overflow-wrap: break-word;word-wrap: break-word;"><?php echo trim($trValue);?></td>
			@endif
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
</body>
</html>
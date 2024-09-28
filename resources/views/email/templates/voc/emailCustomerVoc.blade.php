<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta name="viewport" content="width=device-width" />
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body bgcolor="#FFFFFF">
   <table   width="100%" class="pdftable" style="margin:0 auto;border-collapse:collapse;">
      <tbody>
	 <tr>
	    <td colspan="{{count($order['tableHead'])}}">
	       <h4 style="background: #333;color: white;padding: 5px 0;text-align: center;font-size: 20px;">Dear {{$order['customer_name']}}</h4>
	       <p style="margin: 0 0 19px 6px;text-align: left;">Please find the below detail regarding Sample Booked in this month: </p>
	    </td>
	 </tr>
	 <tr>
	    <td>
	       <table width="100%" border="1" class="pdftable" style="border-collapse:collapse;">
		  <thead>
		     <tr>
			<th colspan="{{count($order['tableHead'])}}" style="padding: 5px 0;text-align:left;font-size:15px;">{{$order['heading']}}</th>
		     </tr>
		     <tr>
			@if(!empty($order['tableHead']))
			   @foreach($order['tableHead'] as $theadData)
			   <th>{{$theadData}}</th>
			   @endforeach
			@endif
		     </tr>
		  </thead>
		  <tbody>
		  @if(!empty($order['tableBody']['orderData']))
		     @foreach($order['tableBody']['orderData'] as $key => $tableBody)		  
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
	       </table>
	    </td>
	 </tr>
	 <tr><td colspan="{{count($order['tableHead'])}}" style="border:1px solid #fff;">&nbsp;</td></tr>
	 <tr>
	    <td colspan="{{count($order['tableHead'])}}">
	       <table width="100%" border="1" class="pdftable" style="border-collapse:collapse;">
		  <tr>
		     <th colspan="{{count($order['summaryTableHead'])}}" style="padding: 5px 0;text-align:left;font-size:15px;">{{$order['summaryHeading']}}</th>
		  </tr>
		  @if(!empty($order['summaryTableHead']))
		     @foreach($order['summaryTableHead'] as $summaryTheadData)
			<th>{{$summaryTheadData}}</th>
		     @endforeach
		  @endif		  
		  @if(!empty($order['summaryTableBody']))
		     <tr>
			@foreach($order['summaryTableBody'] as $key => $trValue)			   
			   <td align="center">{{trim($trValue)}}</td>
			@endforeach
		     </tr>
		  @endif		
	       </table>
	    </td>
	 </tr>
	 <tr><td colspan="{{count($order['tableHead'])}}" style="border:1px solid #fff;">&nbsp;</td></tr>
	 <tr>
	    <td colspan="{{count($order['tableHead'])}}">
	       <p style="margin: 0 0 19px 6px;text-align: left;">Please also provide your feedback and review in the attached Excel document below.</p>
	    </td>
	 </tr>
      </tbody>
   </table>
</body>
</html>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <link href="{!! asset('public/css/email.css') !!}" rel="stylesheet" type="text/css"/>
      <style>
      .content{font-size: 13px !important;}
      .width40{ width: 40% !important;}
      .width20{ width: 20% !important;}
      .content table >tbody >tr> td {padding: 2px !important;}
      </style>
   </head>
   <body bgcolor="#FFFFFF">
      
      <!-- BODY -->
      <table class="body-wrap">
	 <tr>
	    <td></td>
	    <td class="container" bgcolor="#FFFFFF">
	       <div class="content">
		  <table>
		     <tr>
			<td>
			   <h4> Dear Sir/Mam,</h4>
			   <p class="lead">This to inform you that as per the schedule of your sample stability study with us, your sample pull out is due on {{ !empty($stabilityOrderOnePrototype->stb_end_date) ? trim($stabilityOrderOnePrototype->stb_end_date) : '-'}}. We will pullout the below batches from Stability chamber for further analysis.</p>
			</td>
		     </tr>
		  </table>
	       </div><!-- /content -->
	    </td>
	    <td></td>
	 </tr>
      </table>

      <table class="body-wrap content">
	 <tr>
	    <td bgcolor="#FFFFFF">
	       <table class="table table-striped" border="1px solid black" style="border-collapse:collapse;">
		  <thead>
		     <tr>
			<th align="center">S.No.</th>
			<th align="center">Prototype No.</th>
			<th align="left">Product Name</th>
			<th align="center">Batch No</th>
			<th align="center">Pull Out Date</th>
			<th align="center">Storage Condition</th>
		     </tr>
		  </thead>		     
		  <tbody>
		     @if(!empty($stabilityOrderPrototypesList))
			@foreach($stabilityOrderPrototypesList as $key => $values)
			   <tr>
			      <td align="center">{{$key + 1}}</td>
			      <td align="center">{{!empty($stbOrderList->stb_prototype_no) ? trim($stbOrderList->stb_prototype_no) : ''}}</td>
			      <td align="left">{{!empty($stbOrderList->stb_sample_description_name) ? $stbOrderList->stb_sample_description_name : ''}}</td>
			      <td align="center">{{!empty($stbOrderList->stb_batch_no) ? trim($stbOrderList->stb_batch_no) : ''}}</td>
			      <td align="center">{{!empty($values->stb_end_date) ? $values->stb_end_date : ''}}</td>
			      <td align="center">
				 {{!empty($values->stb_stability_type_name) ? strtoupper($values->stb_stability_type_name) : ''}}
				 &nbsp;
				 {{!empty($values->stb_condition_temperature) ? $values->stb_condition_temperature : ''}}
			      </td>
			   </tr>
			@endforeach
		     @endif
		  </tbody>
	       </table>
	    </td>
	 </tr>
      </table>
      
      <table class="body-wrap">
	 <tr>
	    <td></td>
	    <td class="container" bgcolor="#FFFFFF">
	       <div class="content">
		  <table>
		     <tr>
			<td>
			   <p class="lead"><i>This is system generated mail,please do not reply on this mail.For any clarification please contact CRM desk.</i></p>
			</td>
		     </tr>
		  </table>
	       </div><!-- /content -->
	    </td>
	    <td></td>
	 </tr>
      </table>
      <!-- /BODY -->
	 
      <!-- FOOTER -->
      <table class="footer-wrap">
	 <tr>
	    <td></td>
	    <td class="" >
	       <!-- content -->
	       <div class="content" style="padding:5px;margin-left: -1px;">
		  <table>
		     <tr>
			<td align="left">
			   <?php echo !empty($user['footer_content']) ? htmlspecialchars_decode($user['footer_content']) : '';?>
			</td>
		     </tr>
		  </table>
	       </div>
	       <!-- /content -->
	    </td>
	    <td></td>
	 </tr>
      </table>
      <!-- /FOOTER -->
      
   </body>
</html>
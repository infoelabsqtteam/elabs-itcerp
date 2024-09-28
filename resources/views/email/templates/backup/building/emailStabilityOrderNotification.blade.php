<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta name="viewport" content="width=device-width" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
			   <p class="lead">This to inform you that  we will pullout below batches from Stability chamber after two days for further analysis.</p>
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
			<th align="center">Storage Name</th>
			<th align="center">Storage Condition</th>
		     </tr>
		  </thead>		     
		  <tbody>
		     @if(!empty($stabilityOrderPrototypesList))
			@foreach($stabilityOrderPrototypesList as $key => $values)
			   <tr>
			      <td align="center">{{$key + 1}}</td>
			      <td align="center">{{!empty($stbOrderList->stb_prototype_no) ? trim($stbOrderList->stb_prototype_no) : ''}}</td>
			      <td align="left">{{!empty($values->product_name) ? $values->product_name : ''}}</td>
			      <td align="center">{{!empty($stbOrderList->stb_batch_no) ? trim($stbOrderList->stb_batch_no) : ''}}</td>
			      <td align="center">{{!empty($values->stb_end_date) ? $values->stb_end_date : ''}}</td>
			      <td align="center">{{!empty($values->stb_stability_type_name) ? strtoupper($values->stb_stability_type_name) : ''}}</td>
			      <td align="center">{{!empty($values->stb_condition_temperature) ? $values->stb_condition_temperature : ''}}</td>
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
			   <p><strong>Thanks & Regards,</strong></p>
			   <p>Kamna Rana(kamna.rana@itclabs.com) / Mob. No. : +91-8360693046</p>
			   <p>Ashwani Mishra(ashwani.mishra@itclabs.com) / Mob. No. : +91-7087835622</p>
			   <p>Phone No.-0172-2561543</p>
			   <p>For Sales related queries,contact sales@itclabs.com</p>
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
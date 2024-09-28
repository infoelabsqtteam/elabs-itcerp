<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- If you delete this meta tag, Half Life 3 will never be released. -->
<meta name="viewport" content="width=device-width" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ZURBemails</title>
	
		<link href="{!! asset('public/css/email.css') !!}" rel="stylesheet" type="text/css"/>

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
					<h4> Dear {{ucfirst('ruby')}},</h4>
					<p class="lead">Invoice for following order number has been generated.</p>
					<table class="social" width="100%" style="background:#ECF8FF">
					<tr>
						<td><h4 class="">Your Order number is:</h4></td>
						<td><h5 class="">Order number</h5></td>
					</tr>
				</table><!-- /social & contact -->
			</div><!-- /content -->
		</td>
	</tr>
</table><!-- /BODY -->

<!-- FOOTER -->
	<table class="footer-wrap">
		<tr>
			<td></td>
			<td class="container" >
				<!-- content -->
				<div class="content" style="padding:5px;margin-left: 11px;">
					<table>
					<tr>
						<td align="left">
							<p style="margin-bottom: 0px;">
							{{ucfirst('thanks,')}}	
							</p>
                            <p  style="margin-bottom: 0px;"> 
							{{ucfirst('Invoice generator')}}	
							</p>
						</td>
					</tr>
					</table>
				</div><!-- /content -->
			</td>
			<td></td>
		</tr>
	</table><!-- /FOOTER -->
</body>
	
</html>
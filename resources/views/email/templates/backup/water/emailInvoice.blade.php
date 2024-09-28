<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!-- If you delete this meta tag, Half Life 3 will never be released. -->
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="{!! asset('public/css/email.css') !!}" rel="stylesheet" type="text/css"/>
</head>
 
<body bgcolor="#FFFFFF">
	<table class="body-wrap">
		<tr>
			<td></td>
			<td class="container" bgcolor="#FFFFFF">
				<div class="content">
					<table>
						<tr>
							<td>
								<h4> Dear Mr/Mrs {{!empty($user['name']) ? ucfirst($user['name']): ''}},</h4>
								<p class="lead">Thanks a lot for the Sample Submission with us.We acknowledge it.</p>
								<p class="lead">Please find the attached Invoice.</p>
								
								<table class="social" width="100%" style="background:#ECF8FF">
									<tr>
										<td><h5>Your Invoice number is:</h5></td>
										<td><h5>{{!empty($user['invoice_no']) ? $user['invoice_no'] : ''}}</h5></td>
									</tr>
								</table>
								
								<p class="lead">For any clarification please contact CRM desk.</p>
							</td>
						</tr>
					</table>
				</div><!-- /content -->
			</td>
		</tr>
	</table>
	<!-- /BODY -->
	
	<!-- FOOTER -->
	<table class="footer-wrap">
		<tr>
			<td></td>
			<td class="container" >
				<!-- content -->
				<div class="content" style="padding:5px;margin-left: -1px;">
					<table>
						<tr>
							<td align="left">
								<p><strong>Thanks,</strong></p>								
								<p>Manisha(manisha@itclabs.com) / Mob. No. : +91-7087338186</p>
								<p>Ashwani Mishra(ashwani.mishra@itclabs.com) / Mob. No. : +91-7087835622</p>
								<p>Phone No.-0172-2561543</p>
								<p>For Sales related queries,contact sales@itclabs.com</p>
								<p>For Chennai related queries,please contact Smita Panda(smita.panda@itclabs.com / Mob. No. : +91-7397791913)</p>
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
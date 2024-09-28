<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
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
									<h4>Dear {{!empty($user['name']) ? ucfirst($user['name']) : 'Customer'}}, </h4>
									<p class="lead">Please find your report in below attachment </p>
								</td>
							</tr>
						</table>
					</div><!-- /content -->
				</td>
				<td></td>
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
								<p><strong>Thanks,</strong></p>								
								<p>Manisha(manisha@itclabs.com) / Mob. No. : +91-7087338186</p>
								<p>Ashwani Mishra(ashwani.mishra@itclabs.com) / Mob. No. : +91-7087835622</p>
								<p>Phone No.-0172-2561543</p>
								<p>For Sales related queries,contact sales@itclabs.com</p>
								<p>For Chennai related queries,please contact Smita Panda(smita.panda@itclabs.com / Mob. No. : +91-7397791913)</p>
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

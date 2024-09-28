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
								<h4> Dear {{!empty($user['name']) ? ucfirst($user['name']): ''}},</h4>
								<p class="lead">Please find the attached report having Report No.-<b>{{!empty($user['report_no']) ? $user['report_no'] : '-'}} of {{!empty($user['sample_name']) ? $user['sample_name'] : ''}}</b></p>
								<p class="lead">This is system generated mail,please do not reply on this mail.For any clarification please contact CRM desk.</p>
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
			<td class="container" >
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

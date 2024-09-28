<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
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
								<p class="lead">Please find the attached report having Report No. <b>{{!empty($user['order_no']) ? $user['order_no'] : '-'}} </b>of<b> {{!empty($user['sample_name']) ? $user['sample_name'].'.' : ''}}</b></p>
								<p><i>This is system generated mail,please do not reply on this mail.For any clarification please contact CRM desk.</i></p>
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
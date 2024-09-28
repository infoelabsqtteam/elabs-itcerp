<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
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
							<td>
								<p> Dear {{!empty($user['name']) ? ucfirst($user['name']): ''}},</p>
								<p>Thanks for your new sample. As per our company policy we need advance payment for the processing of the sample.</p>
								<p>You are requested to release the payment to proceed for the testing.</p>
								<p class="lead"><small>This is system generated mail,Please do not reply on this mail.</small></p>
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
				<div class="content" style="padding:5px;margin-left: -1px;">
					<table>
						<tr>
							@if(!empty($user['footer_content']))
								<td align="left">
									<?php echo !empty($user['footer_content']) ? htmlspecialchars_decode($user['footer_content']) : '';?>
								</td>
							@else
								<td align="left" colspan="2" style="padding-top:5px;">
									<p style="padding:0px!important;margin:0px!important;"><strong>For any clarification please contact</strong></p>
									<p style="padding:0px!important;margin:0px!important;">Ambika Rani</p>
									<p style="padding:0px!important;margin:0px!important;">Accounts Receivable</p>
									<p style="padding:0px!important;margin:0px!important;">Mob. No. : +91-6283062099</p>
									<p style="padding:0px!important;margin:0px!important;">Email id : commercial3@itclabs.com</p>
								</td>
							@endif
						</tr>
					</table>
				</div>
				<!-- /content -->
			</td>
			<td></td>
		</tr>
	</table><!-- /FOOTER -->
</body>
</html>
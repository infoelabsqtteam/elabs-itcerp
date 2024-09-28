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
								<h4> Dear {{!empty($user['name']) ? ucfirst($user['name']): ''}},</h4>
								<p class="lead">Thanks a lot for the Sample Submission with us.We acknowledge it.</p>
								<p class="lead">The Report will be delivered tentatively by <b>{{!empty($user['expected_due_date']) ? date('d/m/Y',strtotime($user['expected_due_date'])): '-'}}</b></p>
								
								<table class="social" width="100%" style="background:#ECF8FF">
									@if(!empty($user['order_no']))
									<tr>
										<td><h5>Your Order Number :</h5></td>
										<td><h5>{{!empty($user['order_no']) ? $user['order_no'] : ''}}</h5></td>
									</tr>
									@endif
									@if(!empty($user['sample_no']))
									<tr>
										<td><h5>Your Sample Receiving Number :</h5></td>
										<td><h5>{{!empty($user['sample_no']) ? $user['sample_no'] : ''}}</h5></td>
									</tr>
									@endif
									@if(!empty($user['sample_name']))
									<tr>
										<td><h5>Your Sample Name :</h5></td>
										<td><h5>{{!empty($user['sample_name']) ? $user['sample_name'] : ''}}</h5></td>
									</tr>
									@endif
									@if(!empty($user['batch_no']))
									<tr>
										<td><h5>Your Batch Number :</h5></td>
										<td><h5>{{!empty($user['batch_no']) ? $user['batch_no'] : ''}}</h5></td>
									</tr>
									@endif
								</table>
								
								<p class="lead">This is system generated mail,Please do not reply on this mail.For any clarification please contact CRM desk.</p>
								<p class="lead">Attached a proforma for the confirmation of test to be conducted with a template of our test report. Please revert immediately for any clarification.</p>
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
	</table><!-- /FOOTER -->
</body>
</html>
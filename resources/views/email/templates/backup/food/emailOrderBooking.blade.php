<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
									<tr>
										<td><h5>Your Order number is:</h5></td>
										<td><h5>{{!empty($user['order_no']) ? $user['order_no'] : '-'}}</h5></td>
									</tr>
									<tr>
										<td><h5>Your Sample Name is:</h5></td>
										<td><h5>{{!empty($user['sample_name']) ? $user['sample_name'] : '-'}}</h5></td>
									</tr>
									<tr>
										<td><h5>Your Batch Number is:</h5></td>
										<td><h5>{{!empty($user['batch_no']) ? $user['batch_no'] : '-'}}</h5></td>
									</tr>
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
								<p><strong>Thanks & Regards,</strong></p>
								<p>Sangeeta(sangeeta@itclabs.com) / Mob. No. : +91-7347001294</p>
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
	</table><!-- /FOOTER -->
</body>
</html>
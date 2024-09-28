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
			<td>&nbsp;</td>
			<td class="container" bgcolor="#FFFFFF">
				<div class="content">
					<table>
						<tr>
							<td>
								<p class="lead">Please find the TAT Reports below:</p>
							</td>
						</tr>
					</table>
				</div><!-- /content -->
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="container" bgcolor="#FFFFFF">
				<div class="content">
					<table>
						<tr>
							<td>
								@if(!empty($user['main_content']))
									<?php $counter = 0;?>
									@foreach($user['main_content'] as $keyHeading => $valuesAll)
										@if(!empty($valuesAll) && is_array($valuesAll))
											<h4 style="font-weight: bold; font-size: 16px; text-decoration:underline;">{{$keyHeading}}</h4>
											@foreach($valuesAll as $key => $value)
												@if($counter > 1)
													<span style="width: 50%; float: left; font-size: 13px;"><b>{{$key}}&nbsp;:</b>&nbsp;{{$value}}</span>
												@else
													<span style="width: 100%; float: left; font-size: 13px;"><b>{{$key}}&nbsp;:</b>&nbsp;{{$value}}</span>
												@endif
											@endforeach
											<span style="width: 100%; float: left; font-weight: bold; padding-bottom: 5px;">&nbsp;</span>
										@endif
										<?php $counter++;?>
									@endforeach
								@endif
							</td>
						</tr>
					</table>
				</div><!-- /content -->
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="container" bgcolor="#FFFFFF">
				<div class="content">
					<table>
						<tr>
							<td>
								<p class="lead"><b>For more clarification Please find the attached excel sheets.</b></p>
								<p><i>This is system generated mail,please do not reply on this mail.</i></p>
							</td>
						</tr>
					</table>
				</div><!-- /content -->
			</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<!-- /BODY -->
	
	<!-- FOOTER -->
	<table class="footer-wrap">
		<tr>
			<td class="container" >
				<!-- content -->
				<div class="content" style="padding:5px;margin-left:-1px;">
					<table>
						<tr>
							<td align="left" colspan="2" style="padding-top:15px;">
								<p style="padding:0;margin:0!important;"><strong>Thanks & Regards,</strong></p>
								<p style="padding:0;margin:0!important;">{{ defined('ADMIN_NAME') ? ADMIN_NAME : 'ITC-LAB' }}</p>
							</td>
						</tr>
					</table>
				</div>
				<!-- /content -->
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<!-- /FOOTER -->	
</body>
</html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	</head>
	<body bgcolor="#FFFFFF">
		
		<table width="50%" style="border-collapse:collapse;margin:auto">
			<tr>
				<td colspan="2">
					<h4 style="background: #333;color: white;padding: 10px 0;text-align: center;font-size: 20px;">Dear Admin,</h4>
					<p style="margin: 0 0 10px 0;text-align: center;">Please register the new customer. Details are given as below: </p>
				</td>
			</tr>
			<tr>
				<td style="border:1px solid;border: 1px solid;font-size: 18px;text-align: center;padding: 10px 0;font-weight: 600;background: #e4e3e3;">Customer Name</td>
				<td style="border:1px solid;font-size: 18px;text-align: center;padding: 10px 0;font-weight: 600;background: #e4e3e3;">Customer Email</td>
			</tr>
			<tr>
				<td style="border:1px solid;font-size: 16px;text-align: center;padding: 10px 0;">{{ !empty($user['name']) ? ucfirst($user['name']) : '-' }}</td>
				<td style="border:1px solid;font-size: 16px;text-align: center;padding: 10px 0;">{{ !empty($user['email']) ? $user['email'] : '-' }}</td>
			</tr>
			<tr>
				<td align="left" colspan="2" style="padding-top:15px;">
					<p><strong>Thanks,</strong></p>
					<p> {{ defined('FROM_NAME') ? FROM_NAME : 'ITC Adminstrator' }}</p>
				</td>
			</tr>
		</table><!-- /column 2 -->
			<!-- /social & contact -->				
		<!-- /BODY -->
	
	</body>
	
</html>



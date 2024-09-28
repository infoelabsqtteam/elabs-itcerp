<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">	
    <title>
	@if(!empty($title) && defined('SITE_NAME'))
	    {{ SITE_NAME.' : '.trim($title) }}
	@elseif(!empty($title) && !defined('SITE_NAME'))
	    {{ trim($title) }}
	@else
	    {{'ITC-LAB'}}
	@endif
    </title>
    <link rel="shortcut icon" href="{!! asset('public/favicon.ico') !!}">
    <style>
    html, body {height: 100%;}
    body {margin: 0;padding: 0;width: 100%;color: #B0BEC5;display: table;font-weight: 100;font-family: 'Lato', sans-serif;}
    .container {text-align: center;display: table-cell;vertical-align: middle;}
    .content {text-align: center;display: inline-block;}
    .title {font-size: 72px;margin-bottom: 40px;}
    .btn {display: inline-block;margin-bottom: 0;font-weight: 400;text-align: center;vertical-align: middle;touch-action: manipulation;cursor: pointer;border: 1px solid transparent;white-space: nowrap;padding: 6px 12px;font-size: 14px;line-height: 1.6;border-radius: 4px;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;}
    .btn-primary {background-color: #3097d1;border-color: #2a88bd;color: #fff;font-weight: 600;text-decoration: none;}
    </style>
</head>
<body>
    <div class="container">
	<div class="content">
	    <div class="title">Openning Sofware in new session is not allow.</div>
            <div class="col-md-8"><a class="btn btn-primary" href="javascript:window.close();">Close this window tab</a></div>
	</div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>
	@if(!empty($title) && defined('SITE_NAME'))
	    {{ SITE_NAME.' : '.trim($title) }}
	@elseif(!empty($title) && !defined('SITE_NAME'))
	    {{ trim($title) }}
	@else
	    {{'ITC-LAB'}}
	@endif
    </title>
    <style>
    html, body {height: 100%;}
    body {margin: 0;padding: 0;width: 100%;color: #B0BEC5;display: table;font-weight: 100;font-family: 'Lato', sans-serif;}
    .container {text-align: center;display: table-cell;vertical-align: middle;}
    .content {text-align: center;display: inline-block;}
    .title {font-size: 72px;margin-bottom: 40px;}
    </style>
</head>
<body>
    <div class="container">
	<div class="content">
	    <div class="title">{{ 'Error! '.$exception->getStatusCode() }} {{ !empty($exception->getMessage()) ? ': '.$exception->getMessage() : '' }}</div>
            <div class="col-md-8"><a title="Go To Dashboard" href="{{url('/')}}">Go To Dashboard</a></div>
	</div>
    </div>
</body>
</html>
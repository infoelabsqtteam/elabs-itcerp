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
	<!-------scripts common for all pages start--------------->
	<link rel="shortcut icon" href="{!! asset('public/favicon.ico') !!}">
	<link href="{!! asset('public/css/bootstrap.css') !!}" rel="stylesheet" type="text/css"/>
	<link href="{!! asset('public/css/style.css') !!}" rel="stylesheet" type="text/css"/>
	<link href="{!! asset('public/css/custom.css') !!}" rel="stylesheet" type="text/css"/>
	<link href="{!! asset('public/css/sorting.css') !!}" rel="stylesheet" type="text/css"/>
	<link href="{!! asset('public/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css"/>
	<link href="{!! asset('public/css/angular-flash.min.css') !!}" rel="stylesheet" type="text/css" />
	<link href="{!! asset('public/css/loader.css') !!}" rel="stylesheet" rel="stylesheet" type="text/css"/>
	<link href="{!! asset('public/css/angular-material1.0.3.min.css') !!}" rel="stylesheet" type="text/css"/>	
	
	<!------- Angular js start --------------->
	<script>var SITE_URL = '<?php echo defined('SITE_URL') ? SITE_URL : url('/').'/'; ?>';</script>
    <script>var CURRENTROLE = '<?php echo defined('ROLEID') ? ROLEID : '1'; ?>';</script>
	<script>var CURRENTDATE = '<?php echo date('m/d/Y'); ?>';</script>
	<script>var BROWSER_RESTRICTION = "{{env('BROWSER_RESTRICTION')}}";</script>
	<script type="text/javascript" src="{!! asset('public/js/jquery-3.1.1.min.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/js/angular1.4.9.min.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/ang/app.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/js/autocomplete/angular-animate1.4.9.min.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/js/autocomplete/angular-aria1.4.9.min.js') !!}"></script>
	<!------- /Angular js end --------------->
	
	<!--Cookie plugin-->
	<script type="text/javascript" src="{!! asset('public/js/jquery.cookie.js') !!}"></script>
	<!--/Cookie plugin-->
	
	<!------- Custom js module --------------->	
	<script type="text/javascript" src="{!! asset('public/js/custom.js') !!}"></script>
	<!------- /Custom js module --------------->
	
	<!------- Angular js modules start  --------------->
	<script type="text/javascript" src="{!! asset('public/ang/modules/angular-material1.0.3.min.js') !!}"></script>	
	<script type="text/javascript" src="{!! asset('public/ang/modules/angular-messages.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/ang/modules/angular-validation-match.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/ang/modules/ngPagi.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/ang/modules/angular-drag-and-drop-lists.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/ang/angular-route.js') !!}"></script>
	
	<!------- /Angular js modules end 	 --------------->		
	<link href="{!! asset('public/css/app.css') !!}" rel="stylesheet">	
	<script>var csrfToken = '<?php echo csrf_token(); ?>';window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token(),]); ?></script>	
	<!-------datatables scripts----------------------->
	<link href="{!! asset('public/css/bootstrap-datepicker.css') !!}"  rel="stylesheet" type="text/css"/>
	<script type="text/javascript" language="javascript" src="{!! asset('public/js/bootstrap-datepicker.min.js') !!}"></script>
	<!-------/datatables scripts----------------------->
	
	<!-----angular-confirm css/js-------->
	<script type="text/javascript" src="{!! asset('public/js/angular-confirm.js') !!}"></script>	
	<link href="{!! asset('public/css/angular-confirm.css') !!}" rel="stylesheet" type="text/css"/>
	<!-----/angular-confirm css/js-------->
	
	<!--Including global Message-->
	<script type="text/javascript" src="{!! asset('public/ang/modules/message-constant.js') !!}"></script>
	<!--/Including global Message-->
	
	<!--treeview js-->
	<script type="text/javascript" src="{!! asset('public/js/tree.js') !!}"></script>
	<link href="{!! asset('public/css/tree.css') !!}" rel="stylesheet" type="text/css"/>
	<!--/treeview js-->
	
	<script type="text/javascript" src="{!! asset('public/js/moment-with-locales.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/js/bootstrap-datetimepicker.min.js') !!}"></script>
	<link href="{!! asset('public/css/bootstrap-datetimepicker.css') !!}"  rel="stylesheet" type="text/css"/>
	<link href="{!! asset('public/css/jquery-ui-timepicker-addon.css') !!}"  rel="stylesheet" type="text/css"/>
	<link href="{!! asset('public/css/jquery-ui.css') !!}"  rel="stylesheet" type="text/css"/>
	
	<!--textAngular-sanitize-->
	<script type="text/javascript" src="{!! asset('public/ang/modules/textAngular-sanitize.min.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/ang/modules/textAngular.min.js') !!}"></script>
	<!--/textAngular-sanitize-->
	
	<!--tinymce plugin-->
	<script type="text/javascript" src="{!! asset('public/js/tinymce/tinymce.min.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/js/tinymce/tinymce.ang.js') !!}"></script>
	<!--/tinymce plugin-->
	
	<!--Table Sorting plugin-->
	<script type="text/javascript" src="{!! asset('public/js/jquery-sorting-ui.js') !!}"></script>
	<!--/Table Sorting plugin-->

	@if(!defined('IS_ADMIN') && defined('MULTISESSIONALLOW') && !MULTISESSIONALLOW)
	<!--JQUERY-MULTI-SESSION MODULE -->	
	<script type="text/javascript" src="{!! asset('public/js/jquery-multi-session.js') !!}"></script>
	<!--/JQUERY-MULTI-SESSION MODULE -->	
        @endif
	
	<!--Notification-->
	<link href="{!! asset('public/css/notification.css') !!}" rel="stylesheet" type="text/css"/>
	<!--/Notification-->
</head>
<body id="login_body" ng-app="itcApp">

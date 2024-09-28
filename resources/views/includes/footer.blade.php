</div>
<!--container-main-->

<style>
	.ui-menu .ui-menu-item a.ui-corner-all:hover,
	.ui-menu .ui-menu-item a.ui-corner-all:focus,
	.ui-menu .ui-menu-item a.ui-corner-all:active {
		background: #ff8a00 !important;
		color: #000;
		border-radius: 0;
	}
	.ui-state-hover,
	.ui-widget-content .ui-state-hover,
	.ui-widget-header .ui-state-hover,
	.ui-state-focus,
	.ui-widget-content .ui-state-focus,
	.ui-widget-header .ui-state-focus {
		background: #ff8a00;
		border: none;
		color: #000;
		border-radius: 0;
		font-weight: normal;
	}
</style>

<footer>
	<div class="footer">&copy; 2017 ITC Lab. All Rights Reserved.</div>

	<!--Including all jQuery Js Files-->
	<script src="{!! asset('public/js/app.js') !!}"></script>
	<script src="{!! asset('public/js/onload.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/js/jquery-ui.min.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/js/jquery-ui.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/js/jquery-ui-timepicker-addon.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/js/jquery-ui-sliderAccess.js') !!}"></script>
	<link href="{{url('/public')}}/css/bootstrap-timepicker.min.css" rel="stylesheet">
	<script type="text/javascript" src="{{url('/public')}}/js/bootstrap-timepicker.min.js"></script>
	<script type="text/javascript" src="{{url('/public')}}/js/popper.min.js"></script>
	<!--/Including all jQuery Js Files-->

	<!--Unseting all session Msg-->
	@if(Session::has('successMsg'))
		{{Session::forget('successMsg')}}
	@endif
	@if(Session::has('errorMsg'))
		{{Session::forget('errorMsg')}}
	@endif
	@if(Session::has('alertMsg'))
		{{Session::forget('alertMsg')}}
	@endif
	<!--/Unseting all session Msg-->

	<script>
		if (BROWSER_RESTRICTION) {
			document.onkeydown = function(e) {
				if (event.keyCode == 123) {
					return false;
				}
				if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
					return false;
				}
				if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
					return false;
				}
				if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
					return false;
				}
				if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
					return false;
				}
			};
			if (document.layers) {
				document.captureEvents(Event.MOUSEDOWN);
				$(document).mousedown(function() {
					return false;
				});
			} else {
				$(document).mouseup(function(e) {
					if (e != null && e.type == "mouseup") {
						if (e.which == 2 || e.which == 3) {
							return false;
						}
					}
				});
			}
			$(document).contextmenu(function() {
				return false;
			});
			document.addEventListener("keydown", function(event){
				var key = event.key || event.keyCode;
				if (key == 123) {
					return false;
				} else if ((event.ctrlKey && event.shiftKey && key == 73) || (event.ctrlKey && event.shiftKey && key == 74)) {
					return false;
				}
			}, false);
		}
	</script>

</footer>
</body>
</html>

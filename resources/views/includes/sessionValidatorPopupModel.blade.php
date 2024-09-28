<div class="modal fade" data-keyboard="false" id="sessionValidatorPopupWindowDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body height0" id="sessionExpireErrorMsg" style="display:none;"><p>Your session has expired.Please Click OK to login!</p></div>
			<div class="modal-body height0" id="internetConnectionErrorMsg" style="display:none;"><p>You're offline. Please check your network connection!</p></div>
			<div class="modal-footer custom-footer" id="sessionExpireErrorActionBtn" style="display:none;"><a href="{{url('/')}}" class="btn btn-primary btn-sm">OK</a></div>
			<div class="modal-footer custom-footer" id="internetConnectionErrorActionBtn" style="display:none;"><button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button></div>
		</div>
	</div>
	<script>
	$(document).ready(function(){
		var itcSessionIntervalTimer;
		function funCheckAuthSession(){	
			if(!navigator.onLine){
				$("#sessionExpireErrorMsg,#sessionExpireErrorActionBtn").hide();
				$("#internetConnectionErrorMsg,#internetConnectionErrorActionBtn").show();
				if(!$('body').hasClass('modal-open'))$(sessionValidatorPopupWindowDetail).modal('show');
			}else{
				$.ajax({
					url: SITE_URL+'validate-auth-session',
					success: function(result){
						if(result.error == '1'){
							$("#sessionExpireErrorMsg,#sessionExpireErrorActionBtn").show();
							if(!$('body').hasClass('modal-open'))$(sessionValidatorPopupWindowDetail).modal('show');
							clearInterval(itcSessionIntervalTimer);
						}else{
							$("#sessionExpireErrorMsg,#sessionExpireErrorActionBtn").hide();
							$("#internetConnectionErrorMsg,#internetConnectionErrorActionBtn").hide();
							$(sessionValidatorPopupWindowDetail).modal('hide');
						}
					},
					error: function(result){
						$("#sessionExpireErrorMsg,#sessionExpireErrorActionBtn").hide();
						$("#internetConnectionErrorMsg,#internetConnectionErrorActionBtn").hide();
						$(sessionValidatorPopupWindowDetail).modal('hide');
						clearInterval(itcSessionIntervalTimer);
					}
				});
			}
		}
		itcSessionIntervalTimer = setInterval(funCheckAuthSession,18000);
	});
	</script>
</div>
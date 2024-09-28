<div id="messagePopupDiv">		
	<div id="successMessagePopup" ng-hide="IsVisiableSuccessMsgPopup" role="alert" class="alert alert-success">
		<span ng-bind-html="successMessagePopup"></span>
		<span class="text-right"><a href="javascript:;" ng-click="hideAlertMsgPopup()" href="" class="closeAlert" aria-label="close">×</a></span>
	</div>
	<div id="errorMessagePopup" ng-hide="IsVisiableErrorMsgPopup" role="alert" class="alert alert-danger">
		<span ng-bind-html="errorMessagePopup"></span>
		<span class="text-right"><a href="javascript:;" ng-click="hideAlertMsgPopup()" href="" class="closeAlert" aria-label="close">×</a></span>
	</div>
</div>
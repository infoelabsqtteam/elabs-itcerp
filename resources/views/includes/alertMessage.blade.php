<div class="row">		
	<div id="successMessage" ng-hide="IsVisiableSuccessMsg" role="alert" class="alert alert-success">
		<span id="successMessageContent" ng-bind-html="successMessage"></span>
		<span class="text-right"><a ng-click="hideAlertMsg()" href="" class="closeAlert" aria-label="close">×</a></span>
	</div>
	<div id="errorMessage" ng-hide="IsVisiableErrorMsg" role="alert" class="alert alert-danger">
		<span id="errorMessageContent" class="errorMsg" ng-bind-html="errorMessage"></span>
		<span class="text-right"><a ng-click="hideAlertMsg()" href="" class="closeAlert" aria-label="close">×</a></span>
	</div>
</div>
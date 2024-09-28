<div class="row">		
	<div ng-if="savedMessage.length" ng-hide="savedMessageHide" role="alert" class="alert alert-success">
		<span ng-bind-html="savedMessage"></span><a ng-click="hideMultipleAlertMsg()" href="javascript:;" class="closeAlert" aria-label="close">×</a>
	</div>
    <div ng-if="duplicateMessage.length"  ng-hide="duplicateMessageHide" role="alert" class="alert alert-warning">
		<span ng-bind-html="duplicateMessage"></span><a ng-click="hideMultipleAlertMsg()" href="javascript:;" class="closeAlert" aria-label="close">×</a>
	</div>
	<div ng-if="notSavedMessage.length"  ng-hide="notSavedMessageHide" role="alert" class="alert alert-danger">
		<span ng-bind-html="notSavedMessage"></span><a ng-click="hideMultipleAlertMsg()" href="javascript:;" class="closeAlert" aria-label="close">×</a>
	</div>
</div>
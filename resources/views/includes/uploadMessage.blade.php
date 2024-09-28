<div class="row">		
	<div ng-if="uplodedMessage.length" ng-hide="uplodedMessageHide" role="alert" class="alert alert-success">
			[[uplodedMessage]]<a ng-click="hideUploadAlertMsg()" href="" class="closeAlert" aria-label="close">×</a>
	</div>
    <div ng-if="duplicateMessage.length"  ng-hide="duplicateMessageHide" role="alert" class="alert alert-warning">
			[[duplicateMessage]]<a ng-click="hideUploadAlertMsg()" href="" class="closeAlert" aria-label="close">×</a>
	</div>
	<div ng-if="notUplodedMessage.length"  ng-hide="notUplodedMessageHide" role="alert" class="alert alert-danger">
			[[notUplodedMessage]]<a ng-click="hideUploadAlertMsg()" href="" class="closeAlert" aria-label="close">×</a>
	</div>
</div>
<div ng-if="{{defined('IS_ADMIN') && IS_ADMIN || defined('IS_REPORTER') && IS_REPORTER || defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_FINALIZER') && IS_FINALIZER || defined('IS_APPROVAL') && IS_APPROVAL}}" class="col-md-12 col-xs-12 botm-row" style="padding:0">
	<div ng-bind-html="viewOrderReport.footer_content" class="col-md-12 col-xs-12 footer-content"></div>
</div>
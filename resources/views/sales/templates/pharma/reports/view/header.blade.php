<div class="row pdng-20">
	<span class="pull-right pull-custom">
		<button type="button" class="btn btn-primary hideContentOnPdf" ng-click="backButton()">Back</button>
	</span>

	<div class="row pdng-20" ng-if="{{defined('IS_ADMIN') && IS_ADMIN || defined('IS_REPORTER') && IS_REPORTER || defined('IS_REVIEWER') && IS_REVIEWER || defined('IS_FINALIZER') && IS_FINALIZER || defined('IS_APPROVAL') && IS_APPROVAL || defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR || defined('IS_DISPATCHER') && IS_DISPATCHER || defined('IS_CRM') && IS_CRM}}">		
		<div class="col-sm-12 col-xs-12 flexCenter header-content mT10" ng-bind-html="viewOrderReport.header_content"></div>
		<div class="col-sm-12 col-xs-12 dis_flex pharma-header-text pht-margin">
			<div class="col-sm-4 col-xs-4"></div>
			<div class="col-sm-4 col-xs-4"><h3 class="text-center cntr_txt">TEST REPORT - [[viewOrderReport.department_name | capitalizeAll]]</h3></div>
			<div class="col-sm-4 col-xs-4"></div>
		</div>	
	</div>
</div>
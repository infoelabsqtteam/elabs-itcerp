<div class="container-fluid pdng-20 orderPdf">
	<span class="pull-right pull-custom">
		<button type="button" class="btn btn-primary hideContentOnPdf" ng-click="backButton()">Back</button>
	</span>
	<div class="col-sm-12 col-xs-12 dis_flex header-content mT10" ng-bind-html="viewOrder.header_content" ng-if="{{defined('IS_ADMIN') && IS_ADMIN }}"></div>
</div>
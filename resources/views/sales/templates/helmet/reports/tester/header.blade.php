<div class="container-fluid pdng-20">
	<!--header-->
	<div ng-bind-html="addOrderReport.header_content" class="col-sm-12 col-xs-12 dis_flex header-content"></div>
	<div class="col-sm-12 col-xs-12">
	    <div class="text-center"><strong>ADD [[addOrderReport.department_name | capitalizeAll]] TEST REPORT : [[addOrderReport.order_no]]</strong></div>
	    <div role="new" class="navbar-form navbar-right" style="margin-top: 2px;"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></div>
	</div>
	<!--header-->
</div>
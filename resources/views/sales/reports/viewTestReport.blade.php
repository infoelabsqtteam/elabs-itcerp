<div class="row" ng-hide="IsViewDisplayTestReport">
	
	<!--textile report template form-->
	<div id="isViewReportTextileSection" ng-if="isViewReportTextileSection">
		@include('sales.templates.textile.reports.view.index')
	</div>
	<!--textile report template form-->
	
	<!--food report template form-->
	<div id="isViewReportFoodSection" ng-if="isViewReportFoodSection">	
		@include('sales.templates.food.reports.view.index') 
	</div>
	<!--food report template form-->
	
	<!--pharma report template form-->
	<div id="isViewReportPharmaSection" ng-if="isViewReportPharmaSection">
		@include('sales.templates.pharma.reports.view.index')
	</div>
	<!--pharma report template form-->
	
	<!--environment report template form-->
	<div id="isViewReportEnvironmentSection" ng-if="isViewReportEnvironmentSection">
		@include('sales.templates.environment.reports.view.index')
	</div>
	<!--environment report template form-->
	
	<!--water report template form-->
	<div id="isViewReportWaterSection" ng-if="isViewReportWaterSection">
		@include('sales.templates.water.reports.view.index')
	</div>
	<!--water report template form-->
	
	<!--helmet report template form-->
	<div id="isViewReportHelmetSection" ng-if="isViewReportHelmetSection">
		@include('sales.templates.helmet.reports.view.index')
	</div>
	<!--helmet report template form-->
	
	<!--building report template form-->
	<div id="isViewReportBuildingSection" ng-if="isViewReportBuildingSection">
		@include('sales.templates.building.reports.view.index')
	</div>
	<!--building report template form-->
	
	<!--ayurvedic report template form-->
	<div id="isViewReportAyurvedicSection" ng-if="isViewReportAyurvedicSection">
		@include('sales.templates.ayurvedic.reports.view.index')
	</div>
	<!--ayurvedic report template form-->
	
	<!--Default report template form-->
	<div id="isViewReportDefaultSection" ng-if="isViewReportDefaultSection">
		@include('sales.templates.default.reports.view.index')
	</div>
	<!--Default report template form-->	
</div>
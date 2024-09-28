<div class="row" ng-hide="IsViewAddReportByReporterAndReviewer">
	
	<!--1 food report template form-->
	<div id="isViewReportFoodSectionByReporter" ng-if="isAddReportFoodSectionByReporterAndReviewer">	
		@include('sales.templates.food.reports.reporter_and_reviewer.index') 
	</div>
	<!--food report template form-->
	
	<!--2 pharma report template form-->
	<div id="isViewReportPharmaSectionByReporter" ng-if="isAddReportPharmaSectionByReporterAndReviewer">
		@include('sales.templates.pharma.reports.reporter_and_reviewer.index')
	</div>
	<!--pharma report template form-->
	
	<!--3 environment report template form-->
	<div id="isViewReportEnvironmentSectionByReporter" ng-if="isAddReportEnvironmentSectionByReporterAndReviewer">
		@include('sales.templates.environment.reports.reporter_and_reviewer.index')
	</div>
	<!--environment report template form-->
	
	<!--4 water report template form-->
	<div id="isViewReportWaterSectionByReporter" ng-if="isAddReportWaterSectionByReporterAndReviewer">
		@include('sales.templates.water.reports.reporter_and_reviewer.index')
	</div>
	<!--water report template form-->
	
	<!--5 helmet report template form-->
	<div id="isViewReportHelmetSectionByReporter" ng-if="isAddReportHelmetSectionByReporterAndReviewer">
		@include('sales.templates.helmet.reports.reporter_and_reviewer.index')
	</div>
	<!--helmet report template form-->
	
	<!--6 building report template form-->
	<div id="isViewReportBuildingSectionByReporter" ng-if="isAddReportBuildingSectionByReporterAndReviewer">
		@include('sales.templates.building.reports.reporter_and_reviewer.index')
	</div>
	<!--building report template form-->

	<!--7 ayurvedic report template form-->
	<div id="isViewReportAyurvedicSectionByReporter" ng-if="isAddReportAyurvedicSectionByReporterAndReviewer">
		@include('sales.templates.ayurvedic.reports.reporter_and_reviewer.index')
	</div>
	<!--ayurvedic report template form-->
	
	<!--8 textile report template form-->
	<div id="isViewReportTextileSectionByReporter" ng-if="isAddReportTextileSectionByReporterAndReviewer">
		@include('sales.templates.textile.reports.reporter_and_reviewer.index')
	</div>
	<!--textile report template form-->
	
	<!--9 Default report template form-->
	<div id="isViewReportDefaultSectionByReporter" ng-if="isAddReportDefaultSectionByReporterAndReviewer">
		@include('sales.templates.default.reports.reporter_and_reviewer.index')
	</div>
	<!--Default report template form-->
	
</div>
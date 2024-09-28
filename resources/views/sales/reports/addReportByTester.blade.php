<div class="row" ng-hide="IsViewAddReportByTester">

	<!--1. food report template form-->
	<div ng-if="isAddReportFoodSectionByTester">	
		@include('sales.templates.food.reports.tester.index')
	</div>
	<!--food report template form-->
	
	<!--2. pharma report template form-->
	<div ng-if="isAddReportPharmaSectionByTester">
		@include('sales.templates.pharma.reports.tester.index')
	</div>
	<!--pharma report template form-->
	
	<!--3. environment report template form-->
	<div ng-if="isAddReportEnvironmentSectionByTester">
		@include('sales.templates.environment.reports.tester.index')
	</div>
	<!--environment report template form-->
	
	<!--4. water report template form-->
	<div ng-if="isAddReportWaterSectionByTester">
		@include('sales.templates.water.reports.tester.index')
	</div>
	<!--water report template form-->
	
	<!--5. helmet report template form-->
	<div ng-if="isAddReportHelmetSectionByTester">
		@include('sales.templates.helmet.reports.tester.index')
	</div>
	<!--helmet report template form-->
	
	<!--6. building report template form-->
	<div ng-if="isAddReportBuildingSectionByTester">
		@include('sales.templates.building.reports.tester.index')
	</div>
	<!--building report template form-->
	
	<!--7. ayurvedic report template form-->
	<div ng-if="isAddReportAyurvedicSectionByTester">
		@include('sales.templates.ayurvedic.reports.tester.index')
	</div>
	<!--ayurvedic report template form-->
	
	<!--8. textile report template form-->
	<div ng-if="isAddReportTextileSectionByTester">
		@include('sales.templates.textile.reports.tester.index')
	</div>
	<!--textile report template form-->
	
	<!--9. Default report template form-->
	<div ng-if="isAddReportDefaultSectionByTester">
		@include('sales.templates.default.reports.tester.index')
	</div>
	<!--Default report template form-->
</div>
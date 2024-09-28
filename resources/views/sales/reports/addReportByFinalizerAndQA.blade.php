<div class="row" ng-hide="IsViewAddReportByQADept">

	<!--food report template form-->
	<div ng-if="isAddReportFoodSectionByQADept" id="isViewReportFoodSectionByQADept">
		@include('sales.templates.food.reports.finalize.index')
	</div>
	<!--/food report template form-->

	<!--pharma report template form-->
	<div ng-if="isAddReportPharmaSectionByQADept" id="isViewReportPharmaSectionByQADept">
		@include('sales.templates.pharma.reports.finalize.index')
	</div>
	<!--/pharma report template form-->

	<!--environment report template form-->
	<div ng-if="isAddReportEnvironmentSectionByQADept" id="isViewReportEnvironmentSectionByQADept">
		@include('sales.templates.environment.reports.finalize.index')
	</div>
	<!--/environment report template form-->

	<!--water report template form-->
	<div ng-if="isAddReportWaterSectionByQADept" id="isViewReportWaterSectionByQADept">
		@include('sales.templates.water.reports.finalize.index')
	</div>
	<!--/water report template form-->

	<!--helmet report template form-->
	<div ng-if="isAddReportHelmetSectionByQADept" id="isViewReportHelmetSectionByQADept">
		@include('sales.templates.helmet.reports.finalize.index')
	</div>
	<!--/helmet report template form-->

	<!--building report template form-->
	<div ng-if="isAddReportBuildingSectionByQADept" id="isViewReportBuildingSectionByQADept">
		@include('sales.templates.building.reports.finalize.index')
	</div>
	<!--building report template form-->

	<!--ayurvedic report template form-->
	<div ng-if="isAddReportAyurvedicSectionByQADept" id="isViewReportAyurvedicSectionByQADept">
		@include('sales.templates.ayurvedic.reports.finalize.index')
	</div>
	<!--/ayurvedic report template form-->

	<!--textile report template form-->
	<div ng-if="isAddReportTextileSectionByQADept" id="isViewReportTextileSectionByQADept">
		@include('sales.templates.textile.reports.finalize.index')
	</div>
	<!--/textile report template form-->

	<!--Default report template form-->
	<div ng-if="isAddReportDefaultSectionByQADept" id="isViewReportDefaultSectionByQADept">
		@include('sales.templates.default.reports.finalize.index')
	</div>
	<!--/Default report template form-->

</div>
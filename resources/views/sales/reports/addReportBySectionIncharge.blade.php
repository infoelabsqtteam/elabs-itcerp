<div class="row" ng-hide="IsViewAddReportBySectionIncharge">
	
	<!--1 food report template form-->
	<div id="isViewReportFoodSectionBySectionIncharge" ng-if="isAddReportFoodSectionBySectionIncharge">	
		@include('sales.templates.food.reports.section_incharge.index') 
	</div>
	<!--/ food report template form-->
	
	<!--2 Pharma report template form-->
	<div id="isViewReportPharmaSectionBySectionIncharge" ng-if="isAddReportPharmaSectionBySectionIncharge">	
		@include('sales.templates.pharma.reports.section_incharge.index') 
	</div>
	<!--/Pharma report template form-->
	
	<!--3 Water report template form-->
	<div id="isViewReportWaterSectionBySectionIncharge" ng-if="isAddReportWaterSectionBySectionIncharge">	
		@include('sales.templates.water.reports.section_incharge.index') 
	</div>
	<!--/ Water report template form-->
	
	<!--4 Helmet report template form-->
	<div id="isViewReportHelmetSectionBySectionIncharge" ng-if="isAddReportHelmetSectionBySectionIncharge">	
		@include('sales.templates.helmet.reports.section_incharge.index') 
	</div>
	<!--/ Helmet report template form-->
	
	<!--5 Ayurvedic report template form-->
	<div id="isViewReportAyurvedicSectionBySectionIncharge" ng-if="isAddReportAyurvedicSectionBySectionIncharge">	
	@include('sales.templates.ayurvedic.reports.section_incharge.index') 
	</div>
	<!--/Ayurvedic report template form-->
	
	<!--6 Building report template form-->
	<div id="isViewReportBuildingSectionBySectionIncharge" ng-if="isAddReportBuildingSectionBySectionIncharge">	
		@include('sales.templates.building.reports.section_incharge.index') 
	</div>
	<!--/ Building report template form-->
	
	<!--7 Textile report template form-->
	<div id="isViewReportTextileSectionBySectionIncharge" ng-if="isAddReportTextileSectionBySectionIncharge">	
		@include('sales.templates.textile.reports.section_incharge.index') 
	</div>
	<!--/Textile report template form-->
	
	<!--8 Environment report template form-->
	<div id="isViewReportEnvironmentSectionBySectionIncharge" ng-if="isAddReportEnvironmentSectionBySectionIncharge">	
		@include('sales.templates.environment.reports.section_incharge.index') 
	</div>
	<!--/Environment report template form-->
	
	<!--9 Default report template form-->
	<div id="isViewReportDefaultSectionBySectionIncharge" ng-if="isAddReportDefaultSectionBySectionIncharge">
		@include('sales.templates.default.reports.section_incharge.index') 
	</div>
	<!--/Default report template form-->
	
</div>
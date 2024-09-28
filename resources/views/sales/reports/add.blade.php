<div class="row" ng-hide="IsViewAddTestReport">

	<!--food report template form-->
	<div ng-if="isAddReportFoodSection">	
		@include('sales.templates.food.reports.add.tester.index')
	</div>
	<!--food report template form-->
	
	<!--pharma report template form-->
	<div ng-if="isAddReportPharmaSection">
		@include('sales.templates.pharma.reports.add.tester.index')
	</div>
	<!--pharma report template form-->
	
</div>
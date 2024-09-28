<div ng-if="printOrderParametersList.length" >
	<div id="printSchedulingJobSheet">
		<!--Invoice Header-->
		<div class="row">
			<div class="orderView header">
				<span class="pull-left mT7 pL10"><strong  id="form_title"> Order Detail : [[printOrderReport.order_no ? printOrderReport.order_no : '-']]</strong></span>
			</div>
		</div>
		<div class="row col-sm-12 col-xs-12">
			<form method="POST" role="form" id="erpViewSchedulingJobResultReportForm" name="erpViewSchedulingJobResultReportForm" novalidate>
				<div class="row">
					@include('schedulings.jobPrint.jobSheet.index')
				</div>
			</form>
		</div>
	</div>
		
	<!--Invoice Action-->	
	<div id="printButtonDiv" class="row"> 					                    
		<div class="col-xs-12 form-group text-right">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generateAnalyticalCriteriaId">Generate Analytical Sheet-II</button>
			<!--generate Analytical Criteria Popup--> 
			@include('schedulings.jobPrint.generateAnalyticalSheetCriteriaPopup')
			<!--/generate Analytical Criteria Popup-->
			<button type="button" class="btn btn-default" ng-click="close()">Close</button>
		</div>                    
	</div>
	<!--/Invoice Action-->
</div>
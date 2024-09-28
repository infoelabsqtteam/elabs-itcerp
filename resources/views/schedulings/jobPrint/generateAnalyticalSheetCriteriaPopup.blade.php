<div class="modal fade" data-backdrop="static" data-keyboard="false" id="generateAnalyticalCriteriaId" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">
		<form name="generateAnalyticalSheetIIPdf" action="{{ url('scheduling/generate-analytical-sheet-II-pdf') }}" method="POST">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-left">Select Analytical Sheet Type :</h4>
				</div>
				<div class="modal-body">		
					<div class="radio text-left">
						<label><input type="radio" ng-model="downloadType" name="downloadType" value="1">Analytical Sheet-II</label>
					</div>
					<div class="radio text-left">
						<label><input type="radio" ng-model="downloadType" name="downloadType" value="2">Calculation Sheet</label>
					</div>
				</div>
				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>	
					<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
						<input type="hidden" ng-value="printOrderReport.order_id" name="order_id" ng-model="printOrderReport.order_id">
						<input type="submit" formtarget="_blank" name="generate_analytical_sheet_II_pdf" value="Generate PDF" class="btn btn-primary">
					</span>
					<span ng-if="{{defined('IS_JOB_SCHEDULER') && IS_JOB_SCHEDULER}}">
						<input type="hidden" ng-value="printOrderReport.order_id" name="order_id" ng-model="printOrderReport.order_id">
						<input type="submit" formtarget="_blank" name="generate_analytical_sheet_II_pdf" value="Generate PDF" class="btn btn-primary">
					</span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
				</div>
			</div>
		</form>
	</div>
</div>
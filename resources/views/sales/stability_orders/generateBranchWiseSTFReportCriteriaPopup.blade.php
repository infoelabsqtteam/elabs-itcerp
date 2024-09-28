<div class="modal fade" id="generateStabilityTestFormatReportPopup" role="dialog">
	  <div class="modal-dialog">
		    <form name="generateStabilityTestFormatReport" id="generateStabilityTestFormatReport" action="{{ url('sales/stability-orders/download-bw-stability-test-format-report') }}" method="POST">
			      <div class="modal-content" ng-if="bookedStabilityConditionList.length">
					<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal">&times;</button>
						  <h4 class="modal-title">Select Storage Condition:</h4>
					</div>
					<div class="modal-body custom-rt-scroll">
						  <div class="col-sm-12 pull-left checkbox mB5" ng-repeat="stabilityConditionObj in bookedStabilityConditionList track by $index">
						      <div class="pull-left"><input type="radio" id="stability_test_format_document[[stabilityConditionObj.id]]" ng-checked="stabilityConditionObj.id == 1" name="stb_stability_type_id" ng-model="stabilityTestFormatDocument.stability_test_format_document[[stabilityConditionObj.id]]" ng-value="stabilityConditionObj.id">&nbsp;[[stabilityConditionObj.name]]</div>
						  </div>
					</div>
					<div class="modal-footer">
						  <label for="submit">{{ csrf_field() }}</label>
						  <input type="hidden" ng-value="updateStabilityOrder.stb_order_hdr_id" name="stb_order_hdr_id" id="stb_order_hdr_id" ng-model="updateStabilityOrder.stb_order_hdr_id">
						  <input type="submit" formtarget="_blank" name="generate_stability_test_format_documents" value="Generate STF Report" class="btn btn-primary">
						  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
			      </div>
		    </form>
	  </div>
</div>
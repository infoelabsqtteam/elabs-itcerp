<!-- Modal -->
<div ng-if="isViewInteralTransferSampleLink" id="interal_transfer_sample_popup" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog">
	<div class="modal-dialog">	
		<div class="modal-content">
			<form method="POST" role="form" id="erpAddInteralTransferSampleForm" name="erpAddInteralTransferSampleForm" novalidate>
			
				<!--modal-header-->
				<div class="modal-header">
					<button type="button" class="close" ng-click="funHideInternalTransferSample();">&times;</button>
					<h4 class="modal-title">Internal Transfer of Sample</h4>
				</div>
				<!--/modal-header-->
				
				<!--modal-body-->
				<div class="modal-body">
					<!--Parent Product Category-->
					<div class="form-group">																
						<label for="product_category_id">Select Department<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="it_sample_product_category_id"
								id="it_sample_product_category_id"
								ng-model="addInternalTransferSample.it_sample_product_category_id"
								ng-options="item.name for item in parentCategoryList track by item.id"
								ng-required='true'>
							<option value="">Select Department</option>
						</select>
					</div>
					<!--/Parent Product Category-->
				</div>					
				<div class="modal-footer">
					<input type="hidden" ng-model="addInternalTransferSample.it_sample_id" name="it_sample_id" ng-value="sampleID" id="it_sample_id">
					<button type="button" class="btn btn-primary" ng-click="funAddInternalTransferSample(sampleID);">Transfer</button>
					<button type="button" class="btn btn-default" ng-click="funHideInternalTransferSample();">Close</button>
				</div>					
			</form>
		</div>
	</div>
</div>
<!-- Modal -->
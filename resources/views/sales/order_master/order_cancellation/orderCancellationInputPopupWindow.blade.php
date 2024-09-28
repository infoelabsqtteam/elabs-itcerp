<div class="modal fade" data-backdrop="static" data-keyboard="false" id="orderCancellationInputPopupWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">
		<form method="POST" role="form" id="erpOrderCancellationInputPopupForm" name="erpOrderCancellationInputPopupForm" novalidate>	
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-left">Cancellation Detail : <span ng-if="cancelledOrder.order_no" ng-bind="cancelledOrder.order_no"></span></h4>
				</div>
				<div class="modal-body custom-rt-scroll">	
					
					<!--Cancellation Type-->
					<div class="col-xs-12 form-group">
						<label for="cancellation_type_id">Cancellation Type<em class="asteriskRed">*</em></label>
						<select class="form-control"
							name="cancellation_type_id"
							id="cancellation_type_id"
							ng-model="cancelledOrder.cancellation_type_id"
							ng-required="true"
							ng-options="cancellationType.name for cancellationType in cancellationTypeList track by cancellationType.id">
							<option value="">Select Cancellation Type</option>
						</select>
						<span ng-messages="erpOrderCancellationInputPopupForm.cancellation_type_id.$error" ng-if="erpOrderCancellationInputPopupForm.cancellation_type_id.$dirty || erpOrderCancellationInputPopupForm.$submitted" role="alert">
							<span ng-message="required" class="error">Cancellation Type is required</span>
						</span>
					</div>
					<!--Cancellation Type-->
					
					<!-- Description-->
					<div class="col-xs-12 form-group">
						<label for="cancellation_description">Description<em class="asteriskRed">*</em></label>
						<textarea
							id="cancellation_description"
							ng-model="cancelledOrder.cancellation_description"
							ng-required="true"
							name="cancellation_description"
							class="form-control"
							placeholder="Description">							
						</textarea>						
						<span ng-messages="erpOrderCancellationInputPopupForm.cancellation_description.$error" ng-if="erpOrderCancellationInputPopupForm.cancellation_description.$dirty || erpOrderCancellationInputPopupForm.$submitted" role="alert">
							<span ng-message="required" class="error">Description is required</span>
						</span>
					</div>
					<!-- /Description-->
				</div>
				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>
					<input type="hidden" ng-value="cancelledOrder.order_id" name="order_id" ng-model="cancelledOrder.order_id">
					<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
						<input type="submit" ng-disabled="erpOrderCancellationInputPopupForm.$invalid" name="cancel_order" value="Submit" ng-click="funOrderCancellation()" class="btn btn-primary">
					</span>					
					<span ng-if="{{defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER}}">
						<input type="submit" ng-disabled="erpOrderCancellationInputPopupForm.$invalid" name="cancel_order" value="Submit" ng-click="funOrderCancellation()" class="btn btn-primary">
					</span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
				</div>
			</div>
		</form>
	</div>
</div>
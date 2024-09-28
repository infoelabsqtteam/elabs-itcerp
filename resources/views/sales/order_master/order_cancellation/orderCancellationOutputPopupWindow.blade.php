<div class="modal fade" data-backdrop="static" data-keyboard="false" id="orderCancellationOutputPopupWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-left">Cancelled Order Detail :[[viewCancelledOrderDetail.order_no]]</h4>
			</div>
			<div class="modal-body custom-rt-scroll">	
				
				<!--Cancellation Type-->
				<div class="col-xs-12 form-group">
					<label class="col-xs-4" for="order_cancellation_type_name">Cancellation Type</label>
					<span class="col-xs-8">&nbsp;:&nbsp;[[viewCancelledOrderDetail.order_cancellation_type_name]]</span>
				</div>
				<!--/Cancellation Type-->
				
				<!-- Cancellation date-->
				<div class="col-xs-12 form-group">
					<label class="col-xs-4" for="cancelled_date">Cancellation Date</label>
					<span class="col-xs-8">&nbsp;:&nbsp;[[viewCancelledOrderDetail.cancelled_date]]</span>
				</div>
				<!-- /Cancellation date-->
				
				<!-- Description-->
				<div class="col-xs-12 form-group">
					<label class="col-xs-4" for="cancellation_description">Description</label>
					<span class="col-xs-8">&nbsp;:&nbsp;[[viewCancelledOrderDetail.cancellation_description]]</span>
				</div>
				<!-- /Description-->
				
				<!-- Cancelled by-->
				<div class="col-xs-12 form-group">
					<label class="col-xs-4" for="cancelled_by_name">Cancelled By</label>
					<span class="col-xs-8">&nbsp;:&nbsp;[[viewCancelledOrderDetail.cancelled_by_name]]</span>
				</div>
				<!-- /Cancelled date-->
				
				<!-- Cancellation Stage-->
				<div class="col-xs-12 form-group">
					<label class="col-xs-4" for="stage_of_cancellation">Cancellation Stage</label>
					<span class="col-xs-8">&nbsp;:&nbsp;[[viewCancelledOrderDetail.stage_of_cancellation]]</span>
				</div>
				<!-- /Cancellation Stage-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
			</div>
		</div>		
	</div>
</div>
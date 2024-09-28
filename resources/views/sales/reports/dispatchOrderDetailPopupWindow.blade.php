<div class="modal fade" data-backdrop="static" data-keyboard="false" id="dispatchOrderPopupWindowDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-left">Dispatch Detail :<b>[[viewDispatchData.order_no]]</b> </h4>
			</div>
			<div class="modal-body custom-rt-scroll">	
				
				<!--AR BILL No-->
				<div class="col-xs-12 form-group">
					<label class="col-xs-3" for="ar_bill_no">AR Bill No.</label>
					<span class="col-xs-9">&nbsp;:&nbsp;[[viewDispatchData.ar_bill_no]]</span>
				</div>
				<!--AR BILL No-->
				
				<!-- Dispatch date.-->
				<div class="col-xs-12 form-group">
					<label class="col-xs-3" for="dispatch_date">Dispatch Date</label>
					<span class="col-xs-9">&nbsp;:&nbsp;[[viewDispatchData.dispatch_date]]</span>
				</div>
				<!-- /Dispatch date.-->
				
				<!-- Dispatch by.-->
				<div class="col-xs-12 form-group">
					<label class="col-xs-3" for="dispatch_date">Dispatch By</label>
					<span class="col-xs-9">&nbsp;:&nbsp;[[viewDispatchData.dispatched_by]]</span>
				</div>
				<!-- /Dispatch date.-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
			</div>
		</div>		
	</div>
</div>
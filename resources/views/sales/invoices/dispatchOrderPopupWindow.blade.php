<div class="modal fade" data-backdrop="static" data-keyboard="false" id="dispatchOrderPopupWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">
		<form method="POST" role="form" id="erpDispatchOrderPopupForm" name="erpDispatchOrderPopupForm" novalidate>	
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-left">Dispatch Detail : <span ng-if="dispatchOrder.invoice_no" ng-bind="dispatchOrder.invoice_no"></span></h4>
				</div>
				<div class="modal-body" style="height: 80px;">	
					<!--AR BILL No-->
					<div class="col-xs-6 form-group">
						<label for="ar_bill_no">AR Bill No.</label>
						<input type="text"
								class="form-control"
								id="ar_bill_no"
								ng-model="dispatchOrder.ar_bill_no"
								name="ar_bill_no"
								placeholder="AR Bill No.">
						<span ng-messages="erpDispatchOrderPopupForm.ar_bill_no.$error" ng-if="erpDispatchOrderPopupForm.ar_bill_no.$dirty || erpDispatchOrderPopupForm.$submitted" role="alert">
							<span ng-message="required" class="error">AR Bill No. is required</span>
						</span>
					</div>
					<!--AR BILL No-->
					
					 <!-- Dispatch date.-->
					<div class="col-xs-6 form-group">
						<label for="dispatch_date">Dispatch Date<em class="asteriskRed">*</em></label>
						<div class="input-group date" data-provide="datepicker">
							<input readonly
									type="text"
									id="dispatch_date"
									ng-model="dispatchOrder.dispatch_date"
									ng-required="true"
									name="dispatch_date"
									class="form-control bgWhite"
									placeholder="Dispatch Date">
							<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
						</div>   
						<span ng-messages="erpDispatchOrderPopupForm.dispatch_date.$error" ng-if="erpDispatchOrderPopupForm.dispatch_date.$dirty  || erpDispatchOrderPopupForm.$submitted" role="alert">
							<span ng-message="required" class="error">Dispatch date is required</span>
						</span>
					</div>
					<!-- /Dispatch date.-->
				</div>
				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>
					<input type="hidden" ng-value="dispatchOrder.invoice_id" name="invoice_id" ng-model="dispatchOrder.invoice_id">
					<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
						<input type="submit" ng-disabled="erpDispatchOrderPopupForm.$invalid" name="dispatch_order" value="Dispatch" ng-click="funDispatchOrder(divisionID)" class="btn btn-primary">
					</span>					
					<span ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}">
						<input type="submit" ng-disabled="erpDispatchOrderPopupForm.$invalid" name="dispatch_order" value="Dispatch" ng-click="funDispatchOrder(divisionID)" class="btn btn-primary">
					</span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
				</div>
			</div>
		</form>
	</div>
</div>
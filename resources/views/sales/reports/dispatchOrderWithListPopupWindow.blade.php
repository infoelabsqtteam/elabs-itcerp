<div class="modal fade" data-backdrop="static" data-keyboard="false" id="dispatchOrderWithListPopupWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">
		<div class="row modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-left"><span ng-click="funGetDispatchDetail(dispatchOrder.order_id)">Dispatch Detail : <span ng-if="dispatchOrder.order_no" ng-bind="dispatchOrder.order_no"></span></span></h4>
				
				<!--Alert Message Popup-->
				@include('includes.alertMessagePopup')
				<!--/Alert Message Popup-->
			</div>
			<div id="modal-body-dol" class="modal-body">
				
				<!--Dispatch Form--> 
				<div class="row">
					<form method="POST" role="form" id="erpDispatchOrderPopupForm" name="erpDispatchOrderPopupForm" novalidate>
						<!--AR BILL No-->
						<div class="col-xs-5 form-group">
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
						
						 <!-- Dispatch date-->
						<div class="col-xs-5 form-group">
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
						<!-- /Dispatch date-->
						
						<!--Save Button-->
						<div class="col-xs-2 form-group mT25">
							<label for="submit">{{ csrf_field() }}</label>
							<input type="hidden" ng-value="dispatchOrder.order_id" name="order_id" ng-model="dispatchOrder.order_id">
							<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
								<input type="submit" ng-disabled="erpDispatchOrderPopupForm.$invalid" name="dispatch_order" value="Dispatch" ng-click="funDispatchReport()" class="btn btn-primary">
							</span>
							<span ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}">
								<input type="submit" ng-disabled="erpDispatchOrderPopupForm.$invalid" name="dispatch_order" value="Dispatch" ng-click="funDispatchReport()" class="btn btn-primary">
							</span>
						</div>
						<!--/Save Button-->
					</form>
				</div>
				<!--Dispatch Form-->
			
				<!--Dispatch Detail--> 
				<div class="mT20" ng-if="dispatchOrderList.length">
					<table class="col-sm-12 table-striped table-condensed cf font13">
						<thead class="cf">
							<tr>                            
								<th><label class="sortlabel">S.No.</label></th>
								<th><label class="sortlabel">AR Bill No.</label></th>
								<th><label class="sortlabel">Dispatch Date</label></th>
								<th><label class="sortlabel">Dispatch By</label></th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="dispatchOrderObj in dispatchOrderList | orderBy:predicate:reverse track by $index">
								<td data-title="S.No." class="ng-binding">[[$index + 1]]</td>
								<td data-title="AR Bill No." class="ng-binding">[[dispatchOrderObj.ar_bill_no]]</td>
								<td data-title="Dispatch Date" class="ng-binding">[[dispatchOrderObj.dispatch_date]]</td>
								<td data-title="Dispatch By" class="ng-binding">[[dispatchOrderObj.dispatched_by]]</td>     
							</tr>
							<tr ng-if="!dispatchOrderList.length"><td colspan="4">No record found.</td></tr>
						</tbody>							
					</table>
				</div>
				<!--/Dispatch Detail-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
			</div>
		</div>		
	</div>
</div>
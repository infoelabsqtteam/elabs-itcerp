<div class="modal fade" data-backdrop="static" data-keyboard="false" id="updateOrderExpectedDueDatePopupWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<!--If Order hasnot Customer Wise TAT defined-->
	<div class="modal-dialog" ng-if="!updateOrderExpectedDueDate.tat_in_days">
		<form method="POST" role="form" id="erpUpdateOrderExpectedDueDatePopupForm" name="erpUpdateOrderExpectedDueDatePopupForm" novalidate>	
			<div class="modal-content">
				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-left">Order No. : <span ng-if="updateOrderExpectedDueDate.order_no" ng-bind="updateOrderExpectedDueDate.order_no"></span></h4>
			
					<!--Alert Message Popup-->
					@include('includes.alertMessagePopup')
					<!--/Alert Message Popup-->
				</div>
				
				<div class="modal-body">
					<div class="row">
					
						<!-- Current Expected Due Date-->
						<div class="col-xs-12 form-group">
							<label for="no_of_days">Current Expected Due Date</label>
							<input 
								type="text"	
								readonly 
								id="expected_due_date"
								name="expected_due_date"
								ng-required="true"
								ng-model="updateOrderExpectedDueDate.expected_due_date" 
								class="form-control col-xs-6">
						</div>
						<!-- /Current Expected Due Date-->
						
						<!-- No. of Days-->
						<div class="col-xs-6 form-group">
							<label for="no_of_days">No. of Days<em class="asteriskRed">*</em></label>
							<input
								type="text"
								id="no_of_days"
								ng-model="updateOrderExpectedDueDate.no_of_days"
								ng-required="true"
								name="no_of_days"
								class="form-control"
								placeholder="Enter no. of days">
							<span ng-messages="erpUpdateOrderExpectedDueDatePopupForm.no_of_days.$error" ng-if="erpUpdateOrderExpectedDueDatePopupForm.no_of_days.$dirty  || erpUpdateOrderExpectedDueDatePopupForm.$submitted" role="alert">
								<span ng-message="required" class="error">No. of Days is required</span>
							</span>
						</div>
						<!-- /No. of Days-->

						<!-- Reason for Change-->
						<div class="col-xs-6 form-group">
							<label class="width100" for="reason_of_change">Reason of Change<em class="asteriskRed">*</em></label>
							<textarea
								id="reason_of_change"
								ng-model="updateOrderExpectedDueDate.reason_of_change"
								ng-required="true"
								row="1"
								name="reason_of_change"
								class="form-control"
								placeholder="Reason of Change">
							</textarea>
							<span ng-messages="erpUpdateOrderExpectedDueDatePopupForm.reason_of_change.$error" ng-if="erpUpdateOrderExpectedDueDatePopupForm.reason_of_change.$dirty  || erpUpdateOrderExpectedDueDatePopupForm.$submitted" role="alert">
								<span ng-message="required" class="error">Reason of Change is required</span>
							</span>
						</div>
						<!-- /Reason for Change-->
						
						<!-- Without Calculation-->
						<div class="col-xs-6 form-group">
							<label class="width100" for="exclude_logic_calculation">
								<div class="col-xs-1">
									<input
										type="checkbox"
										id="exclude_logic_calculation"
										ng-model="updateOrderExpectedDueDate.exclude_logic_calculation"
										name="exclude_logic_calculation"
										class="checkbox">
								</div>
								<div class="col-xs-10 p5">&nbsp;Exclude Calculation</div>
							</label>
						</div>
						<!-- /Without Calculation-->

						@if((defined('IS_ADMIN') && IS_ADMIN) || (defined('IS_CRM') && IS_CRM))
						<!-- Send Mail-->
						<div class="col-xs-6 form-group">
							<label class="width100" for="send_mail_status">
								<div class="col-xs-1">
									<input
										type="checkbox"
										id="send_mail_status"
										ng-model="updateOrderExpectedDueDate.send_mail_status"
										name="send_mail_status"
										class="checkbox">
								</div>
								<div class="col-xs-10 p5">&nbsp;Send Mail</div>
							</label>
						</div>
						<!-- /Send Mail-->
						@endif

					</div>					
				</div>

				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>
					<input type="hidden" ng-value="updateOrderExpectedDueDate.order_id" name="order_id" ng-model="updateOrderExpectedDueDate.order_id">
					<input type="submit" ng-disabled="erpUpdateOrderExpectedDueDatePopupForm.$invalid" name="update_order_expected_duedate" value="Update" ng-click="funUpdateOrderExpectedDueDate()" class="btn btn-primary">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
				</div>
			</div>
		</form>
	</div>
	<!--/If Order hasnot Customer Wise TAT defined-->
	
	<!--If Order has Customer Wise TAT defined-->
	<div class="modal-dialog" ng-if="updateOrderExpectedDueDate.tat_in_days">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-left">Order No. : <span ng-if="updateOrderExpectedDueDate.order_no" ng-bind="updateOrderExpectedDueDate.order_no"></span></h4>
			</div>
			<div class="modal-body" style="height: 45px;">	
				<div class="col-xs-12 form-group alert alert-info"><strong>Info! </strong>Expected Due Date cannot be edited as Order has Customer Wise TAT defined !</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
			</div>
		</div>
	</div>
	<!--If Order has Customer Wise TAT defined-->
	
</div>
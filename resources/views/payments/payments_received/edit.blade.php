<div class="row" ng-hide="editPaymentFormBladeDiv">
    <div class="panel panel-default">		           
		<div class="panel-body">		
			<div class="row header-form">
				<span class="pull-left headerText"><strong>Edit Payment Received : <span ng-bind="editBWPaymentReceived.payment_received_no"></span></strong></span>
				<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="navigatePaymentReceivedPage()">Back</button></span>
			</div>			
			<form method="POST" role="form" id="erpEditBWPaymentReceivedForm" name="erpEditBWPaymentReceivedForm" novalidate>
			
				<div class="row">
                    
                    <!-- Payment Received No.-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_received_no">Payment Received No.<em class="asteriskRed">*</em></label>
                        <input disabled
								type="text"
                                id="payment_received_no"
                                ng-model="editBWPaymentReceived.payment_received_no"
                                ng-required="true"
                                name="payment_received_no"
                                class="form-control"
                                placeholder="Payment Received No.">
						<span ng-messages="erpEditBWPaymentReceivedForm.payment_received_no.$error" ng-if="erpEditBWPaymentReceivedForm.payment_received_no.$dirty || erpEditBWPaymentReceivedForm.$submitted" role="alert">
							<span ng-message="required" class="error">Payment Received No. is required</span>
						</span>
                    </div>
                    <!-- /Payment Received Date--->
                    
                    <!-- Payment Received Date.-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_received_date">Payment Received Date<em class="asteriskRed">*</em></label>
                        <div class="input-group date" data-provide="datepicker">
                            <input readonly
                                    type="text"
                                    id="payment_received_date"
                                    ng-model="editBWPaymentReceived.payment_received_date"
                                    ng-required="true"
                                    name="payment_received_date"
                                    class="form-control bgWhite"
                                    placeholder="Payment Received Date">
							<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                        </div>
						<span ng-messages="erpEditBWPaymentReceivedForm.payment_received_date.$error" ng-if="erpEditBWPaymentReceivedForm.payment_received_date.$dirty || erpEditBWPaymentReceivedForm.$submitted" role="alert">
							<span ng-message="required" class="error">Payment Received Date is required</span>
						</span>
                    </div>
                    <!-- /Payment Received Date--->
					
					<!--Branch -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-6 form-group view-record">
                        <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="division_id"
                            id="division_id"
                            ng-model="editBWPaymentReceived.division_id.selectedOption"
							ng-required="true"
                            ng-options="division.name for division in divisionsCodeList track by division.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpEditBWPaymentReceivedForm.division_id.$error" ng-if="erpEditBWPaymentReceivedForm.division_id.$dirty || erpEditBWPaymentReceivedForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <div ng-if="{{$division_id}} > 0">
						<input type="hidden" id="division_id" ng-model="editBWPaymentReceived.division_id" name="division_id" value="{{$division_id}}">
                    </div>
                    <!--/Branch -->
										
					<!--Customer -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="customer_id">Customer<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="customer_id"
                            id="customer_id"
							ng-required="true"
                            ng-model="editBWPaymentReceived.customer_id.selectedOption"
                            ng-options="customerList.name for customerList in customerListData track by customerList.id">
                            <option value="">Select Customer</option>
                        </select>
                        <span ng-messages="erpEditBWPaymentReceivedForm.customer_id.$error" ng-if="erpEditBWPaymentReceivedForm.customer_id.$dirty || erpEditBWPaymentReceivedForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Customer is required</span>
                        </span>
                    </div>                    
                    <!--/Customer -->
                    
                    <!-- Payment Amount Received-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_amount_received">Amount Received<em class="asteriskRed">*</em></label>
                        <input type="text"
                            id="payment_amount_received"
                            ng-model="editBWPaymentReceived.payment_amount_received"
                            ng-required="true"
                            name="payment_amount_received"
                            class="form-control bgWhite"
                            placeholder="Amount Received">
						<span ng-messages="erpEditBWPaymentReceivedForm.payment_amount_received.$error" ng-if="erpEditBWPaymentReceivedForm.payment_amount_received.$dirty || erpEditBWPaymentReceivedForm.$submitted" role="alert">
							<span ng-message="required" class="error">Amount Received is required</span>
						</span>
                    </div>
                    <!-- /Payment Amount Received--->
                    
                    <!--Payment Source-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_source_id">Deposited With<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="payment_source_id"
                            id="payment_source_id"
                            ng-required="true"
                            ng-model="editBWPaymentReceived.payment_source_id.selectedOption"
                            ng-options="paymentSource.name for paymentSource in paymentSourceList track by paymentSource.id">
                            <option value="">Select Deposited With</option>
                        </select>
                        <span ng-messages="erpEditBWPaymentReceivedForm.payment_source_id.$error" ng-if="erpEditBWPaymentReceivedForm.payment_source_id.$dirty || erpEditBWPaymentReceivedForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Deposited With is required</span>
                        </span>
                    </div>                    
                    <!--/Payment Source-->
                    
                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" ng-model="editBWPaymentReceived.payment_received_hdr_id" name="payment_received_hdr_id" ng-value="editBWPaymentReceived.payment_received_hdr_id" id="payment_received_hdr_id">
                        <button type="submit" ng-disabled="erpEditBWPaymentReceivedForm.$invalid" class="btn btn-primary" ng-click="funUpdateBranchWisePaymentReceived(divisionID)">Update</button>
                        <button type="button" class="btn btn-default" ng-click="backButton()">Cancel</button>
                    </div>
                    <!--Save Button-->
                    
				</div>			
					
		    </form>
		</div>	
    </div>	
</div>
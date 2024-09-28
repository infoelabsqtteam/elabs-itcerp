<div class="row" ng-hide="addPaymentFormBladeDiv">
    <div class="panel panel-default">		           
		<div class="panel-body">		
			<div class="row header-form">
				<span class="pull-left headerText"><strong>Add New Payment Received</strong></span>
				<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="navigatePaymentReceivedPage()">Back</button></span>
			</div>			
			<form method="POST" role="form" id="erpAddBWPaymentReceivedForm" name="erpAddBWPaymentReceivedForm" novalidate>
			
				<div class="row">
				
					<!-- Payment Received No.-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_received_no">Payment Received No.<em class="asteriskRed">*</em></label>
						<span class="generate"><a href="javascript:;" ng-click="generatePaymentReceivedNumber();">Generate</a></span>
                        <input readonly
								ng-init="generatePaymentReceivedNumber();"
								type="text"
                                id="payment_received_no"
                                ng-model="addBWPaymentReceived.payment_received_no"
								ng-value="defaultPaymentReceivedNumber"
                                name="payment_received_no"
                                class="form-control"
                                placeholder="Payment Received No.">
						<span ng-messages="erpAddBWPaymentReceivedForm.payment_received_no.$error" ng-if="erpAddBWPaymentReceivedForm.payment_received_no.$dirty || erpAddBWPaymentReceivedForm.$submitted" role="alert">
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
                                    ng-model="addBWPaymentReceived.payment_received_date"
                                    ng-required="true"
                                    name="payment_received_date"
                                    class="form-control bgWhite"
                                    placeholder="Payment Received Date">
							<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                        </div>
						<span ng-messages="erpAddBWPaymentReceivedForm.payment_received_date.$error" ng-if="erpAddBWPaymentReceivedForm.payment_received_date.$dirty || erpAddBWPaymentReceivedForm.$submitted" role="alert">
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
                            ng-model="addBWPaymentReceived.division_id"
							ng-required="true"
                            ng-options="division.name for division in divisionsCodeList track by division.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpAddBWPaymentReceivedForm.division_id.$error" ng-if="erpAddBWPaymentReceivedForm.division_id.$dirty || erpAddBWPaymentReceivedForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <div ng-if="{{$division_id}} > 0">
						<input type="hidden" id="division_id" ng-model="addBWPaymentReceived.division_id" name="division_id" value="{{$division_id}}">
                    </div>
                    <!--/Branch -->
                    
                    <!--Customer -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="customer_id">Customer<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="customer_id"
                            id="customer_id"
							ng-required="true"
                            ng-model="addBWPaymentReceived.customer_id"
                            ng-options="customerList.name for customerList in customerListData track by customerList.id">
                            <option value="">Select Customer</option>
                        </select>
                        <span ng-messages="erpAddBWPaymentReceivedForm.customer_id.$error" ng-if="erpAddBWPaymentReceivedForm.customer_id.$dirty || erpAddBWPaymentReceivedForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Customer is required</span>
                        </span>
                    </div>                    
                    <!--/Customer -->
                    
                    <!-- Payment Amount Received-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_amount_received">Amount Received<em class="asteriskRed">*</em></label>
                        <input type="text"
                            id="payment_amount_received"
                            ng-model="addBWPaymentReceived.payment_amount_received"
                            ng-required="true"
                            name="payment_amount_received"
                            class="form-control bgWhite"
                            placeholder="Amount Received">
						<span ng-messages="erpAddBWPaymentReceivedForm.payment_amount_received.$error" ng-if="erpAddBWPaymentReceivedForm.payment_amount_received.$dirty || erpAddBWPaymentReceivedForm.$submitted" role="alert">
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
                            ng-model="addBWPaymentReceived.payment_source_id"
                            ng-options="paymentSource.name for paymentSource in paymentSourceList track by paymentSource.id">
                            <option value="">Select Deposited With</option>
                        </select>
                        <span ng-messages="erpAddBWPaymentReceivedForm.payment_source_id.$error" ng-if="erpAddBWPaymentReceivedForm.payment_source_id.$dirty || erpAddBWPaymentReceivedForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Deposited With is required</span>
                        </span>
                    </div>                    
                    <!--/Payment Source-->
                    
                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>					
                        <button type="submit" ng-disabled="erpAddBWPaymentReceivedForm.$invalid" class="btn btn-primary" ng-click="funAddBranchWisePaymentReceived(divisionID)">Save</button>
                        <button type="button" class="btn btn-default" ng-click="resetButton()">Reset</button>
                    </div>
                    <!--Save Button-->
                    
				</div>			
					
		    </form>
		</div>	
    </div>	
</div>
<div class="row" ng-hide="editPaymentFormBladeDiv">
    <div class="panel panel-default">		           
		<div class="panel-body">		
			<div class="row header-form">
				<span class="pull-left headerText"><strong>Edit Payment Made : <span ng-bind="editBWPaymentMade.payment_made_no"></span></strong></span>
				<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="navigatePaymentMadePage()">Back</button></span>
			</div>			
			<form method="POST" role="form" id="erpEditBWPaymentMadeForm" name="erpEditBWPaymentMadeForm" novalidate>
			
				<div class="row">
                    
                    <!-- Payment Made No.-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_made_no">Payment Made No.<em class="asteriskRed">*</em></label>
                        <input disabled
								type="text"
                                id="payment_made_no"
                                ng-model="editBWPaymentMade.payment_made_no"
                                ng-required="true"
                                name="payment_made_no"
                                class="form-control"
                                placeholder="Payment Made No.">
						<span ng-messages="erpEditBWPaymentMadeForm.payment_made_no.$error" ng-if="erpEditBWPaymentMadeForm.payment_made_no.$dirty || erpEditBWPaymentMadeForm.$submitted" role="alert">
							<span ng-message="required" class="error">Payment Made No. is required</span>
						</span>
                    </div>
                    <!-- /Payment Made Date--->
                    
                    <!-- Payment Made Date.-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_made_date">Payment Made Date<em class="asteriskRed">*</em></label>
                        <div class="input-group date" data-provide="datepicker">
                            <input readonly
                                    type="text"
                                    id="payment_made_date"
                                    ng-model="editBWPaymentMade.payment_made_date"
                                    ng-required="true"
                                    name="payment_made_date"
                                    class="form-control bgWhite"
                                    placeholder="Payment Made Date">
							<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                        </div>
						<span ng-messages="erpEditBWPaymentMadeForm.payment_made_date.$error" ng-if="erpEditBWPaymentMadeForm.payment_made_date.$dirty || erpEditBWPaymentMadeForm.$submitted" role="alert">
							<span ng-message="required" class="error">Payment Made Date is required</span>
						</span>
                    </div>
                    <!-- /Payment Made Date--->
					
					<!--Branch -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-6 form-group view-record">
                        <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="division_id"
                            id="division_id"
                            ng-model="editBWPaymentMade.division_id.selectedOption"
							ng-required="true"
                            ng-options="division.name for division in divisionsCodeList track by division.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpEditBWPaymentMadeForm.division_id.$error" ng-if="erpEditBWPaymentMadeForm.division_id.$dirty || erpEditBWPaymentMadeForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <div ng-if="{{$division_id}} > 0">
						<input type="hidden" id="division_id" ng-model="editBWPaymentMade.division_id" name="division_id" value="{{$division_id}}">
                    </div>
                    <!--/Branch -->
										
					<!--Vendor -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="vendor_id">Vendor<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="vendor_id"
                            id="vendor_id"
							ng-required="true"
                            ng-model="editBWPaymentMade.vendor_id.selectedOption"
                            ng-options="vendorList.name for vendorList in vendorListData track by vendorList.id">
                            <option value="">Select Vendor</option>
                        </select>
                        <span ng-messages="erpEditBWPaymentMadeForm.vendor_id.$error" ng-if="erpEditBWPaymentMadeForm.vendor_id.$dirty || erpEditBWPaymentMadeForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Vendor is required</span>
                        </span>
                    </div>                    
                    <!--/Vendor -->
                    
                    <!-- Payment Amount Made-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_made_amount">Amount<em class="asteriskRed">*</em></label>
                        <input type="text"
                            id="payment_made_amount"
                            ng-model="editBWPaymentMade.payment_made_amount"
                            ng-required="true"
                            name="payment_made_amount"
                            class="form-control bgWhite"
                            placeholder="Amount">
						<span ng-messages="erpEditBWPaymentMadeForm.payment_made_amount.$error" ng-if="erpEditBWPaymentMadeForm.payment_made_amount.$dirty || erpEditBWPaymentMadeForm.$submitted" role="alert">
							<span ng-message="required" class="error">Amount is required</span>
						</span>
                    </div>
                    <!-- /Payment Amount Made--->
                    
                    <!--Payment Source-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_source_id">Paid From<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="payment_source_id"
                            id="payment_source_id"
                            ng-required="true"
                            ng-model="editBWPaymentMade.payment_source_id.selectedOption"
                            ng-options="paymentSource.name for paymentSource in paymentSourceList track by paymentSource.id">
                            <option value="">Select Paid From</option>
                        </select>
                        <span ng-messages="erpEditBWPaymentMadeForm.payment_source_id.$error" ng-if="erpEditBWPaymentMadeForm.payment_source_id.$dirty || erpEditBWPaymentMadeForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Paid From is required</span>
                        </span>
                    </div>                    
                    <!--/Payment Source-->
                    
                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" ng-model="editBWPaymentMade.payment_made_hdr_id" name="payment_made_hdr_id" ng-value="editBWPaymentMade.payment_made_hdr_id" id="payment_made_hdr_id">
                        <button type="submit" ng-disabled="erpEditBWPaymentMadeForm.$invalid" class="btn btn-primary" ng-click="funUpdateBranchWisePaymentMade(divisionID)">Update</button>
                        <button type="button" class="btn btn-default" ng-click="backButton()">Cancel</button>
                    </div>
                    <!--Save Button-->
                    
				</div>			
					
		    </form>
		</div>	
    </div>	
</div>
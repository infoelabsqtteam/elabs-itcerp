<div class="row" ng-hide="addPaymentFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row header-form">
                <span class="pull-left headerText"><strong>Add New Payment Made</strong></span>
                <span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="navigatePaymentMadePage()">Back</button></span>
            </div>
            <form method="POST" role="form" id="erpAddBWPaymentMadeForm" name="erpAddBWPaymentMadeForm" novalidate>

                <div class="row">

                    <!-- Payment Made No.-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_made_no">Payment Made No.<em class="asteriskRed">*</em></label>
                        <span class="generate"><a href="javascript:;" ng-click="generatePaymentMadeNumber();">Generate</a></span>
                        <input readonly ng-init="generatePaymentMadeNumber();" type="text" id="payment_made_no" ng-model="addBWPaymentMade.payment_made_no" ng-value="defaultPaymentMadeNumber" name="payment_made_no" class="form-control" placeholder="Payment Made No.">
                        <span ng-messages="erpAddBWPaymentMadeForm.payment_made_no.$error" ng-if="erpAddBWPaymentMadeForm.payment_made_no.$dirty || erpAddBWPaymentMadeForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Payment Made No. is required</span>
                        </span>
                    </div>
                    <!-- /Payment Made Date--->

                    <!-- Payment Made Date.-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_made_date">Payment Made Date<em class="asteriskRed">*</em></label>
                        <div class="input-group date" data-provide="datepicker">
                            <input readonly type="text" id="payment_made_date" ng-model="addBWPaymentMade.payment_made_date" ng-required="true" name="payment_made_date" class="form-control bgWhite" placeholder="Payment Made Date">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                        </div>
                        <span ng-messages="erpAddBWPaymentMadeForm.payment_made_date.$error" ng-if="erpAddBWPaymentMadeForm.payment_made_date.$dirty || erpAddBWPaymentMadeForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Payment Made Date is required</span>
                        </span>
                    </div>
                    <!-- /Payment Made Date--->

                    <!--Branch -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-6 form-group view-record">
                        <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="division_id" id="division_id" ng-model="addBWPaymentMade.division_id" ng-required="true" ng-options="division.name for division in divisionsCodeList track by division.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpAddBWPaymentMadeForm.division_id.$error" ng-if="erpAddBWPaymentMadeForm.division_id.$dirty || erpAddBWPaymentMadeForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <div ng-if="{{$division_id}} > 0">
                        <input type="hidden" id="division_id" ng-model="addBWPaymentMade.division_id" name="division_id" value="{{$division_id}}">
                    </div>
                    <!--/Branch -->

                    <!--Vendor -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="vendor_id">Vendor<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="vendor_id" id="vendor_id" ng-model="addBWPaymentMade.vendor_id" ng-required="true" ng-options="vendorList.name for vendorList in vendorListData track by vendorList.id">
                            <option value="">Select Vendor</option>
                        </select>
                        <span ng-messages="erpAddBWPaymentMadeForm.vendor_id.$error" ng-if="erpAddBWPaymentMadeForm.vendor_id.$dirty || erpAddBWPaymentMadeForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Vendor is required</span>
                        </span>
                    </div>
                    <!--/Vendor -->

                    <!-- Payment Amount Made-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_made_amount">Amount<em class="asteriskRed">*</em></label>
                        <input type="text" id="payment_made_amount" ng-model="addBWPaymentMade.payment_made_amount" ng-required="true" name="payment_made_amount" class="form-control bgWhite" placeholder="Amount">
                        <span ng-messages="erpAddBWPaymentMadeForm.payment_made_amount.$error" ng-if="erpAddBWPaymentMadeForm.payment_made_amount.$dirty || erpAddBWPaymentMadeForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Amount is required</span>
                        </span>
                    </div>
                    <!-- /Payment Amount Made--->

                    <!--Payment Source-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="payment_source_id">Paid From<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="payment_source_id" id="payment_source_id" ng-required="true" ng-model="addBWPaymentMade.payment_source_id" ng-options="paymentSource.name for paymentSource in paymentSourceList track by paymentSource.id">
                            <option value="">Select Paid From</option>
                        </select>
                        <span ng-messages="erpAddBWPaymentMadeForm.payment_source_id.$error" ng-if="erpAddBWPaymentMadeForm.payment_source_id.$dirty || erpAddBWPaymentMadeForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Paid From is required</span>
                        </span>
                    </div>
                    <!--/Payment Source-->

                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>
                        <button type="submit" ng-disabled="erpAddBWPaymentMadeForm.$invalid" class="btn btn-primary" ng-click="funAddBranchWisePaymentMade(divisionID)">Save</button>
                        <button type="button" class="btn btn-default" ng-click="resetButton()">Reset</button>
                    </div>
                    <!--Save Button-->

                </div>

            </form>
        </div>
    </div>
</div>

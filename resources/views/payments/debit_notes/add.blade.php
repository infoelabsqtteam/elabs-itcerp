<div class="row" ng-hide="addDebitNoteFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row header-form">
                <span class="pull-left headerText"><strong>Add New Debit Note</strong></span>
                <span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="navigateDebitNotePage()">Back</button></span>
            </div>

            <form method="POST" role="form" id="erpAddBWDebitNoteForm" name="erpAddBWDebitNoteForm" novalidate>

                <div class="row">

                    <!-- Debit Note No.-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="debit_note_no">Debit Note No.<em class="asteriskRed">*</em></label>
                        <span class="generate"><a href="javascript:;" ng-click="generateDebitNoteNumber();">Generate</a></span>
                        <input readonly ng-init="generateDebitNoteNumber();" type="text" id="debit_note_no" ng-model="addBWDebitNote.debit_note_no" ng-value="defaultDebitNoteNumber" name="debit_note_no" class="form-control" placeholder="Debit Note No.">
                        <span ng-messages="erpAddBWDebitNoteForm.debit_note_no.$error" ng-if="erpAddBWDebitNoteForm.debit_note_no.$dirty || erpAddBWDebitNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Debit Note No. is required</span>
                        </span>
                    </div>
                    <!-- /Debit Note No.--->

                    <!--Debit Note Type -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="debit_note_type_id">Debit Note Type<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="debit_note_type_id" id="debit_note_type_id" ng-model="addBWDebitNote.debit_note_type_id" ng-change="funGetDebitNoteType(addBWDebitNote.debit_note_type_id.id)" ng-required="true" ng-options="debitNoteType.name for debitNoteType in debitNoteTypeList track by debitNoteType.id">
                            <option value="">Select Debit Note Type</option>
                        </select>
                        <span ng-messages="erpAddBWCreditNoteForm.debit_note_type_id.$error" ng-if="erpAddBWCreditNoteForm.debit_note_type_id.$dirty || erpAddBWCreditNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Debit Note Type is required</span>
                        </span>
                    </div>
                    <!--/Debit Note Type-->

                    <!-- Debit Note Date-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="debit_note_date">Debit Note Date<em class="asteriskRed">*</em></label>
                        <div class="input-group date">
                            <input readonly type="text" id="debit_note_date" ng-model="addBWDebitNote.debit_note_date" ng-required="true" name="debit_note_date" class="form-control bgWhite" placeholder="Debit Note Date">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                        </div>
                        <span ng-messages="erpAddBWDebitNoteForm.debit_note_date.$error" ng-if="erpAddBWDebitNoteForm.debit_note_date.$dirty || erpAddBWDebitNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Debit Note Date is required</span>
                        </span>
                    </div>
                    <!-- /Debit Note Date--->

                    <!--Customer -->
                    <div class="col-xs-6 form-group view-record">
                        <label class="width100" for="customer_id">Customer<em class="asteriskRed">*</em><span class="pull-right"><a title="Filter Customer City" class="generate mL5 cursor-pointer" ng-click="funShowCountryStateViewPopup(1)">Select</a></span></label>
                        <select class="form-control" name="customer_id" id="customer_id" ng-required="true" ng-model="addBWDebitNote.customer_id" ng-options="customerList.name for customerList in customerListData track by customerList.id" ng-change="funGetInvoiceNumbers(addBWDebitNote.customer_id.id)">
                            <option value="">Select Customer</option>
                        </select>
                        <span ng-messages="erpAddBWDebitNoteForm.customer_id.$error" ng-if="erpAddBWDebitNoteForm.customer_id.$dirty || erpAddBWDebitNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Customer is required</span>
                        </span>
                    </div>
                    <!--/Customer -->

                    <!--Invoice numbers -->
                    <div class="col-xs-6 form-group view-record" ng-if="addBWDebitNote.debit_note_type_id.id == '1'">
                        <label for="customer_id">Invoice Number<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="invoice_id" id="invoice_id" ng-required="true" ng-model="addBWDebitNote.invoice_id" ng-change="funGetInvoiceDetail(addBWDebitNote.invoice_id.id)" ng-options="customerInvoiceList.name for customerInvoiceList in customerInvoiceNumberList track by customerInvoiceList.id">
                            <option value="">Select Invoice Number</option>
                        </select>
                        <span ng-messages="erpAddBWDebitNoteForm.invoice_id.$error" ng-if="erpAddBWDebitNoteForm.invoice_id.$dirty || erpAddBWDebitNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Invoice number is required</span>
                        </span>
                    </div>
                    <!--/Invoice numbers -->

                    <!--Branch -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-6 form-group view-record">
                        <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="division_id" id="division_id" ng-model="addBWDebitNote.division_id" ng-required="true" ng-options="division.name for division in divisionsCodeList track by division.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpAddBWDebitNoteForm.division_id.$error" ng-if="erpAddBWDebitNoteForm.division_id.$dirty || erpAddBWDebitNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <div ng-if="{{$division_id}} > 0">
                        <input type="hidden" id="division_id" ng-model="addBWDebitNote.division_id" name="division_id" value="{{$division_id}}">
                    </div>
                    <!--/Branch -->

                    <!--Parent Product Category-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="product_category_id">Department<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="product_category_id" id="product_category_id" ng-model="addBWDebitNote.product_category_id" ng-options="item.name for item in parentCategoryList track by item.id" ng-required='true'>
                            <option value="">Select Department</option>
                        </select>
                        <span ng-messages="erpAddBWDebitNoteForm.product_category_id.$error" ng-if="erpAddBWDebitNoteForm.product_category_id.$dirty || erpAddBWDebitNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Department is required</span>
                        </span>
                    </div>
                    <!--/Parent Product Category-->

                    <!--Reference Number -->
                    <div class="col-xs-6 form-group view-record" ng-if="addBWDebitNote.debit_note_type_id.id == '2'">
                        <label for="debit_reference_no">Reference Number<em class="asteriskRed">*</em></label>
                        <input type="text" id="debit_reference_no" ng-model="addBWDebitNote.debit_reference_no" ng-required="true" name="debit_reference_no" class="form-control" placeholder="Reference Number">
                        <span ng-messages="erpEditBWCreditNoteForm.debit_reference_no.$error" ng-if="erpEditBWCreditNoteForm.debit_reference_no.$dirty || erpEditBWCreditNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Reference Number is required</span>
                        </span>
                    </div>
                    <!--/Reference Number-->

                    <!-- Payment Amount Made-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="debit_note_amount">Amount<em class="asteriskRed">*</em></label>
                        <input type="text" id="debit_note_amount" ng-model="addBWDebitNote.debit_note_amount" ng-required="true" name="debit_note_amount" class="form-control bgWhite" placeholder="Amount">
                        <span ng-messages="erpAddBWDebitNoteForm.debit_note_amount.$error" ng-if="erpAddBWDebitNoteForm.debit_note_amount.$dirty || erpAddBWDebitNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Amount is required</span>
                        </span>
                    </div>
                    <!-- /Payment Amount Made--->

                    <!--Remarks-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="debit_note_remark">Remark<em class="asteriskRed">*</em></label>
                        <textarea row="2" class="form-control" id="debit_note_remark" ng-model="addBWDebitNote.debit_note_remark" name="debit_note_remark" ng-required="true" placeholder="Remarks"></textarea>
                        <span ng-messages="erpAddBWDebitNoteForm.debit_note_remark.$error" ng-if="erpAddBWDebitNoteForm.debit_note_remark.$dirty || erpAddBWDebitNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Remark is required</span>
                        </span>
                    </div>
                    <!--/Remarks-->

                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>
                        <button type="submit" ng-disabled="erpAddBWDebitNoteForm.$invalid" class="btn btn-primary" ng-click="funAddBranchWiseDebitNote(divisionID)">Save</button>
                        <button type="button" class="btn btn-default" ng-click="resetButton()">Reset</button>
                    </div>
                    <!--Save Button-->

                </div>
            </form>
        </div>
    </div>
</div>

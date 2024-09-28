<div class="row" ng-hide="editCreditNoteFormBladeDiv">
    <div class="panel panel-default">		           
		<div class="panel-body">		
			<div class="row header-form">
				<span class="pull-left headerText"><strong>Edit Credit Note : <span ng-bind="editBWCreditNote.credit_note_no"></span></strong></span>
				<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="navigateCreditNotePage()">Back</button></span>
			</div>			
			<form method="POST" role="form" id="erpEditBWCreditNoteForm" name="erpEditBWCreditNoteForm" novalidate>
			
				<div class="row">
				
					<!-- Credit Note No.-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="credit_note_no">Credit Note No.<em class="asteriskRed">*</em></label>
                        <input disabled								
								type="text"
                                id="credit_note_no"
                                ng-model="editBWCreditNote.credit_note_no"								                      
                                class="form-control"
								ng-required="true"
                                placeholder="Credit Note No.">
						<span ng-messages="erpEditBWCreditNoteForm.credit_note_no.$error" ng-if="erpEditBWCreditNoteForm.credit_note_no.$dirty || erpEditBWCreditNoteForm.$submitted" role="alert">
							<span ng-message="required" class="error">Credit Note No. is required</span>
						</span>
                    </div>
                    <!-- /Credit Note Date--->
                    
                    <!-- Credit Note Date.-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="credit_note_date">Credit Note Date<em class="asteriskRed">*</em></label>
                        <div class="input-group date" data-provide="datepicker">
                            <input readonly
                                    type="text"
                                    id="credit_note_date"
                                    ng-model="editBWCreditNote.credit_note_date"
                                    ng-required="true"
                                    name="credit_note_date"
                                    class="form-control bgWhite"
                                    placeholder="Credit Note Date">
							<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                        </div>
						<span ng-messages="erpEditBWCreditNoteForm.credit_note_date.$error" ng-if="erpEditBWCreditNoteForm.credit_note_date.$dirty || erpEditBWCreditNoteForm.$submitted" role="alert">
							<span ng-message="required" class="error">Credit Note Date is required</span>
						</span>
                    </div>
                    <!-- /Credit Note Date--->
					
					<!--Branch -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-6 form-group view-record">
                        <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="division_id"
                            id="division_id"
                            ng-model="editBWCreditNote.division_id.selectedOption"
							ng-required="true"
                            ng-options="division.name for division in divisionsCodeList track by division.id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpEditBWCreditNoteForm.division_id.$error" ng-if="erpEditBWCreditNoteForm.division_id.$dirty || erpEditBWCreditNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <div ng-if="{{$division_id}} > 0">
						<input type="hidden" id="division_id" ng-model="editBWCreditNote.division_id" name="division_id" value="{{$division_id}}">
                    </div>
                    <!--/Branch -->
                    
                    <!--Customer -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="customer_id">Customer<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="customer_id"
                            id="customer_id"
							ng-required="true"
                            ng-model="editBWCreditNote.customer_id.selectedOption"
							ng-change="funGetInvoiceNumbers(editBWCreditNote.customer_id.selectedOption.id)"
                            ng-options="customerList.name for customerList in customerListData track by customerList.id">
                            <option value="">Select Customer</option>
                        </select>
                        <span ng-messages="erpEditBWCreditNoteForm.customer_id.$error" ng-if="erpEditBWCreditNoteForm.customer_id.$dirty || erpEditBWCreditNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Customer is required</span>
                        </span>
                    </div>                    
                    <!--/Customer -->
                    <!--Customer -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="customer_id">Invoice Number<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="invoice_id"
                            id="invoice_id"
							ng-required="true"
                            ng-model="editBWCreditNote.invoice_id.selectedOption"
                            ng-options="customerInvoiceList.name for customerInvoiceList in customerInvoiceNumberList track by customerInvoiceList.id">
                            <option value="">Select Invoice Number</option>
                        </select>
                        <span ng-messages="erpEditBWCreditNoteForm.invoice_id.$error" ng-if="erpEditBWCreditNoteForm.invoice_id.$dirty || erpEditBWCreditNoteForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Invoice Number is required</span>
                        </span>
                    </div>                    
                    <!--/Customer -->
		    
		    <!--Parent Product Category-->
		    <div class="col-xs-6 form-group view-record">																
			<label for="product_category_id">Department<em class="asteriskRed">*</em></label>	
			<select
			    class="form-control"
			    name="product_category_id"
			    id="product_category_id"
			    ng-model="editBWCreditNote.product_category_id"
			    ng-options="item.name for item in parentCategoryList track by item.id"
			    ng-required='true'>
			    <option value="">Select Department</option>
			</select>
			<span ng-messages="erpEditBWCreditNoteForm.product_category_id.$error" ng-if="erpEditBWCreditNoteForm.product_category_id.$dirty || erpEditBWCreditNoteForm.$submitted" role="alert">
			    <span ng-message="required" class="error">Department is required</span>
			</span>
		    </div>
		    <!--/Parent Product Category-->
		    
                    <!-- Payment Amount Made-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="credit_note_amount">Amount<em class="asteriskRed">*</em></label>
                        <input type="text"
                            id="credit_note_amount"
                            ng-model="editBWCreditNote.credit_note_amount"
                            ng-required="true"
                            name="credit_note_amount"
                            class="form-control bgWhite"
                            placeholder="Amount">
						<span ng-messages="erpEditBWCreditNoteForm.credit_note_amount.$error" ng-if="erpEditBWCreditNoteForm.credit_note_amount.$dirty || erpEditBWCreditNoteForm.$submitted" role="alert">
							<span ng-message="required" class="error">Amount is required</span>
						</span>
                    </div>
                    <!-- /Payment Amount Made--->
                    
                    <!--Remarks-->
                    <div class="col-xs-6 form-group view-record">
						<label for="credit_note_remark">Remark<em class="asteriskRed">*</em></label>
						<textarea row="2"
							   class="form-control"
							   id="credit_note_remark"
							   ng-model="editBWCreditNote.credit_note_remark"
							   name="credit_note_remark"
							   ng-required="true"
							   placeholder="Remarks">
						</textarea>
						<span ng-messages="erpEditBWCreditNoteForm.credit_note_remark.$error" ng-if="erpEditBWCreditNoteForm.credit_note_remark.$dirty || erpEditBWCreditNoteForm.$submitted" role="alert">
							<span ng-message="required" class="error">Remark is required</span>
						</span>
					</div>
                    <!--/Remarks-->	
                    
                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" ng-model="editBWCreditNote.credit_note_id" name="credit_note_id" ng-value="editBWCreditNote.credit_note_id" id="credit_note_id">
                        <button type="submit" ng-disabled="erpEditBWCreditNoteForm.$invalid" class="btn btn-primary" ng-click="funUpdateBranchWiseCreditNote(divisionID)">Update</button>
                        <button type="button" class="btn btn-default" ng-click="backButton()">Cancel</button>
                    </div>
                    <!--Save Button-->
                    
				</div>			
					
		    </form>
		</div>	
    </div>	
</div>
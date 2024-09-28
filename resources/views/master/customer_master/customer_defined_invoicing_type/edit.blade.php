<div class="row" ng-show="{{defined('EDIT') && EDIT}}">
	<div class="panel panel-default" ng-if="editCustomerInvoicingFormDiv" >
		<div class="panel-body">
			<div class="row header1">
				<strong class="pull-left headerText">Edit Customer Defined Invoicing</strong>
			</div>
			<form name='erpEditCustomerInvoicingForm' id="erpEditCustomerInvoicingFormDiv" novalidate>

				<div class="row">
					<!--division Name-->
					<div class="col-xs-3">
						<label for="equipment_type_id" class="outer-lable">
							 <span class="filter-lable">Branch <em class="asteriskRed">*</em></span>
						</label>
						<select class="form-control"
								name="division_id"
								ng-model="editCustomerInvoicing.division_id.selectedOption"
								id="division_id"
								ng-required='true'
								ng-options="item.name for item in divisionsCodeList track by item.id" disabled>
								<option value="">Select Division Name</option>
						</select>
						<span ng-messages="erpEditCustomerInvoicingForm.division_id.$error" ng-if='erpEditCustomerInvoicingForm.division_id.$dirty || erpEditCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Division Name</span>
						</span>
					</div>
					<!--/division Name-->
					<div class="col-xs-3">
							<label for="equipment_type_id" class="outer-lable">
								 <span class="filter-lable">Customer Name <em class="asteriskRed">*</em></span>
							</label>
							<select class="form-control"
									name="customer_id"
									ng-model="editCustomerInvoicing.customer_id.selectedOption"
									id="customer_id"
									ng-required='true'
									ng-options="item.customer_name for item in custdata track by item.cust_id">
									<option value="">Select Customer Name</option>
							</select>
							<span ng-messages="erpEditCustomerInvoicingForm.customer_id.$error" ng-if='erpEditCustomerInvoicingForm.customer_id.$dirty || erpEditCustomerInvoicingForm.$submitted' role="alert">
								<span ng-message="required" class="error">Select Customer Name</span>
							</span>
						</div>

					<div class="col-xs-3">
						<label for="product_category_id" class="outer-lable">Department<em class="asteriskRed">*</em></label>
						<select class="form-control"
								name="product_category_id"
								id="product_category_id"
								ng-model="editCustomerInvoicing.product_category_id.selectedOption"
								ng-options="item.name for item in parentCategoryList track by item.id"
								ng-required='true' disabled>
							<option value="">Select Department</option>
						</select>
						<span ng-messages="erpEditCustomerInvoicingForm.product_category_id.$error" ng-if='erpEditCustomerInvoicingForm.product_category_id.$dirty || erpEditCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>
					</div>

					<!--Equipment Type-->
					<div class="col-xs-3">
						<label for="equipment_type_id" class="outer-lable">
							<span class="filter-lable">Customer Invoicing Type<em class="asteriskRed">*</em></span>
						</label>
						<select class="form-control"
								name="invoicing_type_id"
								ng-model="editCustomerInvoicing.invoicing_type_id.selectedOption"
								id="invoicing_type_id"
								ng-required='true'
								ng-options="item.name for item in invoicingTypes track by item.id">
							<option value="">Select Customer Invoicing Type</option>
						</select>
						<span ng-messages="erpEditCustomerInvoicingForm.invoicing_type_id.$error" ng-if='erpEditCustomerInvoicingForm.invoicing_type_id.$dirty || erpEditCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Customer Invoicing Type</span>
						</span>
					</div>
					<!--/Equipment Type-->
				</div><br/><br/>
				<div class="row">
					<!--Customer Billing Type-->
					<div class="col-xs-3">
						<label for="equipment_type_id" class="outer-lable">
							<span class="filter-lable">Customer Billing Type<em class="asteriskRed">*</em></span>
						</label>
						<select class="form-control"
								name="billing_type_id"
								ng-model="editCustomerInvoicing.billing_type_id.selectedOption"
								id="billing_type_id"
								ng-required='true'
								ng-options="item.name for item in billingTypes track by item.id">
							<option value="">Select Customer Billing Type</option>
						</select>
						<span ng-messages="erpEditCustomerInvoicingForm.billing_type_id.$error" ng-if='erpEditCustomerInvoicingForm.billing_type_id.$dirty || erpEditCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Customer Billing Type</span>
						</span>
					</div>
					<!--/Customer Billing Type-->

					<!--Customer Discount Type-->
					<div class="col-xs-3">
						<label for="equipment_type_id" class="outer-lable">
							<span class="filter-lable">Customer Discount Type<em class="asteriskRed">*</em></span>
						</label>
						<select class="form-control"
								name="discount_type_id"
								ng-model="editCustomerInvoicing.discount_type_id.selectedOption"
								id="discount_type_id"
								ng-required='true'
								ng-options="item.name for item in discountTypes track by item.id">
							<option value="">Select Customer Discount Type</option>
						</select>
						<span ng-messages="erpEditCustomerInvoicingForm.discount_type_id.$error" ng-if='erpEditCustomerInvoicingForm.discount_type_id.$dirty || erpEditCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Customer Discount Type</span>
						</span>
					</div>
					<!--/Customer Discount Type-->
					<!--Discount Value-->
					<div class="col-xs-3">
						<label for="equipment_type_id" class="outer-lable">
							<span class="filter-lable">Discount Value<em class="asteriskRed">*</em></span>
						</label>
						<input class="form-control"
								name="discount_value"
								ng-model="editCustomerInvoicing.discount_value"
								id="discount_value"
								ng-required='true'>
						</input>
						<span ng-messages="erpEditCustomerInvoicingForm.discount_value.$error" ng-if='erpEditCustomerInvoicingForm.discount_value.$dirty || erpEditCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Discount Value required.</span>
						</span>
					</div>
					<!--/Discount Value-->
					<!-- status-->
					<div class="col-xs-3">
						<label for="equipment_type_id" class="outer-lable">
							<span class="filter-lable">Status<em class="asteriskRed">*</em></span>
						</label>
						<select class="form-control"
								name="customer_invoicing_type_status"
								ng-model="editCustomerInvoicing.status.selectedOption"
								id="status"
								ng-required='true'
								ng-options="item.name for item in  status track by item.id">
							<option value="">Select Status</option>
						</select>
						<span ng-messages="erpEditCustomerInvoicingForm.customer_invoicing_type_status.$error" ng-if='erpEditCustomerInvoicingForm.customer_invoicing_type_status.$dirty || erpEditCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Status</span>
						</span>
					</div>
				</div>
				<div class="row">
					<!--Update button-->
					<div class="col-xs-3 pull-right">
						<label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" name="cdit_id" ng-value="editCustomerInvoicing.cdit_id" ng-model="editCustomerInvoicing.cdit_id">
						<span ng-if="{{defined('EDIT') && EDIT}}" >
							<button type="submit" title="Update" ng-disabled="erpEditCustomerInvoicingForm.$invalid" class='mT26 btn btn-primary  btn-sm' ng-click='funUpdateCustomerDefinedInvoice()'>Update</button>
						</span>
						<button title="Close" type='button' class='mT26 btn btn-default btn-sm' ng-click='addCustomerForm()'>Close</button>
					</div>
					<!--/Update button-->
				</div>
			</form>
		</div>
	</div>
</div>

<div class="row" ng-show="{{defined('ADD') && ADD}}">
	<div class="panel panel-default" ng-if="addCustomerInvoicingFormDiv">
		<div class="panel-body" ng-model="addCustomerInvoicingFormDiv">
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Customer Invoicing</strong></span>
			</div>
			<!--Add method form-->
			<form name='erpAddCustomerInvoicingForm' id="add_method_form" novalidate>
				<div class="row">
					<!--division Name-->
					<div class="col-xs-3">
						<label for="equipment_type_id" class="outer-lable">
							 <span class="filter-lable">Branch <em class="asteriskRed">*</em></span>
						</label>
						<select class="form-control"
								name="division_id"
								ng-model="addCustomerInvoicing.division_id"
								id="division_id"
								ng-required='true'
								ng-options="item.name for item in divisionsCodeList track by item.id">
								<option value="">Select Division Name</option>
						</select>
						<span ng-messages="erpAddCustomerInvoicingForm.division_id.$error" ng-if='erpAddCustomerInvoicingForm.division_id.$dirty || erpAddCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Division Name</span>
						</span>
					</div>
					<!--/division Name-->
					<!--Customer Name-->
					<div class="col-xs-3">
						<label for="equipment_type_id" class="outer-lable">
							 <span class="filter-lable">Customer Name <em class="asteriskRed">*</em></span>
						</label>
						<select class="form-control"
								name="customer_id"
								ng-model="addCustomerInvoicing.customer_id"
								id="customer_id"
								ng-required='true'
								ng-options="item.customer_name for item in custdata track by item.cust_id">
								<option value="">Select Customer Name</option>
						</select>
						<span ng-messages="erpAddCustomerInvoicingForm.customer_id.$error" ng-if='erpAddCustomerInvoicingForm.customer_id.$dirty || erpAddCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Customer Name</span>
						</span>
					</div>
					<!--/Customer Name-->

					<!--Parent Product Category-->
					<div class="col-xs-3">
						<label for="product_category_id" class="outer-lable">Department<em class="asteriskRed">*</em></label>
						<select class="form-control"
								name="product_category_id"
								id="product_category_id"
								ng-model="addCustomerInvoicing.product_category_id"
								ng-options="item.name for item in parentCategoryList track by item.id"
								ng-required='true'>
							<option value="">Select Department</option>
						</select>
						<span ng-messages="erpAddCustomerInvoicingForm.product_category_id.$error" ng-if='erpAddCustomerInvoicingForm.product_category_id.$dirty || erpAddCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>
					</div>
					<!--/Parent Product Category-->

					<!--Customer Invoicing Type-->
					<div class="col-xs-3">
						<label for="equipment_type_id" class="outer-lable">
							<span class="filter-lable">Customer Invoicing Type<em class="asteriskRed">*</em></span>
						</label>
						<select class="form-control"
								name="invoicing_type_id"
								ng-model="addCustomerInvoicing.invoicing_type_id"
								id="invoicing_type_id"
								ng-required='true'
								ng-options="item.name for item in invoicingTypes track by item.id">
							<option value="">Select Customer Invoicing Type</option>
						</select>
						<span ng-messages="erpAddCustomerInvoicingForm.invoicing_type_id.$error" ng-if='erpAddCustomerInvoicingForm.invoicing_type_id.$dirty || erpAddCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Customer Invoicing Type</span>
						</span>
					</div>
					<!--/Customer Invoicing Type-->
				</div><br/><br/>
				<div class="row">
					<!--Customer Billing Type-->
					<div class="col-xs-3">
						<label for="equipment_type_id" class="outer-lable">
							<span class="filter-lable">Customer Billing Type<em class="asteriskRed">*</em></span>
						</label>
						<select class="form-control"
								name="billing_type_id"
								ng-model="addCustomerInvoicing.billing_type_id"
								id="billing_type_id"
								ng-required='true'
								ng-options="item.name for item in billingTypes track by item.id">
							<option value="">Select Customer Billing Type</option>
						</select>
						<span ng-messages="erpAddCustomerInvoicingForm.billing_type_id.$error" ng-if='erpAddCustomerInvoicingForm.billing_type_id.$dirty || erpAddCustomerInvoicingForm.$submitted' role="alert">
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
								ng-model="addCustomerInvoicing.discount_type_id"
								id="discount_type_id"
								ng-required='true'
								ng-change="funGetDiscountType(addCustomerInvoicing.discount_type_id.id,'add')"

								ng-options="item.name for item in discountTypes track by item.id">
							<option value="">Select Customer Discount Type</option>
						</select>
						<span ng-messages="erpAddCustomerInvoicingForm.discount_type_id.$error" ng-if='erpAddCustomerInvoicingForm.discount_type_id.$dirty || erpAddCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Customer Discount Type</span>
						</span>
					</div>
					<!--/Customer Discount Type-->
					<!--Discount Value-->
					<div class="col-xs-3" ng-if="DiscountTypeYes">
						<label for="equipment_type_id" class="outer-lable">
							<span class="filter-lable">Discount Value<em class="asteriskRed">*</em></span>
						</label>
						<input class="form-control"
								name="discount_value"
								ng-model="addCustomerInvoicing.discount_value"
								id="discount_value"

								ng-required='true' Placeholder = "Discount Value">
						</input>
						<span ng-messages="erpAddCustomerInvoicingForm.discount_value.$error" ng-if='erpAddCustomerInvoicingForm.discount_value.$dirty || erpAddCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Discount Value required.</span>
						</span>
					</div>
					<div class="col-xs-3" ng-if="DiscountTypeNo">
						<label for="equipment_type_id" class="outer-lable">
							<span class="filter-lable">Discount Value</span>
						</label>
						<input class="form-control"
								name="discount_value"
								ng-model="addCustomerInvoicing.discount_value"
								id="discount_value_read_only"
								readonly
								ng-required='false' Placeholder = "Discount Value">
						</input>
						<span ng-messages="erpAddCustomerInvoicingForm.discount_value.$error" ng-if='erpAddCustomerInvoicingForm.discount_value.$dirty || erpAddCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Discount Value required.</span>
						</span>
					</div>
					<!--/Discount Value-->
					
					<!--TAT Editable Status-->
					<div class="col-xs-2 form-group" ng-init="addTestParameter.test_parameter_invoicing=true">
					    <label for="tat_editable">TAT Editable</label>
					    <div class="checkbox">
						<label for="tat_editable">
						    <input type="checkbox" ng-model="addCustomerInvoicing.tat_editable" name="tat_editable" value="1" id="tat_editable">Check for TAT Editable
						</label>
					    </div>
					</div>
					<!--/Parameter Invoicing-->
					
					<!--Status-->
					<div class="col-xs-3">
						<label for="equipment_type_id" class="outer-lable">
							<span class="filter-lable">Status<em class="asteriskRed">*</em></span>
						</label>
						<select class="form-control"
								name="customer_invoicing_type_status"
								ng-model="addCustomerInvoicing.status"
								id="status"
								ng-required='true'
								ng-options="item.name for item in  status track by item.id">
							<option value="">Select Status</option>
						</select>
						<span ng-messages="erpAddCustomerInvoicingForm.customer_invoicing_type_status.$error" ng-if='erpAddCustomerInvoicingForm.customer_invoicing_type_status.$dirty || erpAddCustomerInvoicingForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Status</span>
						</span>
					</div>
					<!--/Status-->
					

				</div>
				<div class="row">
					<!--save button-->
					<div class="col-xs-3 pull-right">
						<label for="submit">{{ csrf_field() }}</label>
						<span ng-if="{{defined('ADD') && ADD}}">
							<button title="Save" ng-disabled="erpAddCustomerInvoicingForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funAddCustomerDefinedInvoicing()'>Save</button>
						<button title="Reset"  type="button" class="mT26 btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>

						</span>
					</div>
				<!--/save button-->
				</div>
			</form>
			<!--Add method form-->
		</div>
	</div>
</div>

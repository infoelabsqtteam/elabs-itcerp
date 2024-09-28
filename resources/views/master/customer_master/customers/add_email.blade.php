<div class="row"  ng-hide="addEmailFormDiv">
    <div class="panel panel-default" >
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Add Customer Email :<b> [[customerData.customer_name]]</b></strong></span>
                </div>
					<span class="pull-right pull-custom">
							<button type="button" class="btn btn-primary" ng-click="backButton()">Back</button>
					</span>	
                <div role="new" class="navbar-form navbar-right"></div>
            </div>               
            <form name="erpCustomerEmailAddressesForm" id="erpCustomerEmailAddressesForm" method="POST" novalidate  >
                <div class="row">
					<div class="col-xs-3 form-group">
						<label for="customer_id">Customer Email<em class="asteriskRed">*</em></label>
						<input
							type="email"
							class="form-control"
							name="customer_email"
                            id="customer_email"
							ng-required="true"
                            ng-model="addCustomerEmails.customer_email"
							placeholder="Customer Email">
						<span ng-messages="erpCustomerEmailAddressesForm.customer_email.$error" 
							ng-if='erpCustomerEmailAddressesForm.customer_email.$dirty  || erpCustomerEmailAddressesForm.$submitted' role="alert">
								<span ng-message="required" class="error">Customer email is required</span>
						</span>
    				</div>
					
                    <div class="col-xs-3 form-group">
                        <label for="setting_group_id">Customer Email Type<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="customer_email_type"
                            id="customer_email_type"
                            ng-model="addCustomerEmails.customer_email_type"
							ng-options="item.name for item in emailTypes track by item.id ">
							<option value="">Select Customer Email Type</option>
							
                        </select>
						<span ng-messages="erpCustomerEmailAddressesForm.customer_email_type.$error" 
							ng-if='erpCustomerEmailAddressesForm.customer_email_type.$dirty  || erpCustomerEmailAddressesForm.$submitted' role="alert">
								<span ng-message="required" class="error">Customer email type is required</span>
						</span>	
                    </div>
                   
                    <div class="col-xs-3 form-group">
                        <label for="setting_group_id">Customer Email Status<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="customer_email_status"
                            id="customer_email_status"
                            ng-model="addCustomerEmails.customer_email_status"
							ng-options="item.name for item in emailStatusTypes track by item.id">
							<option value="">Select Customer Email Status</option>
							
                        </select>
						<span ng-messages="erpCustomerEmailAddressesForm.customer_email_status.$error" 
							ng-if='erpCustomerEmailAddressesForm.customer_email_status.$dirty  || erpCustomerEmailAddressesForm.$submitted' role="alert">
								<span ng-message="required" class="error">Customer email status is required</span>
						</span>	
                    </div>
                  
                    <!--Save Button-->
                    <div class="col-xs-3 form-group text-left mT26">
						<label for="submit">{{ csrf_field() }}</label>
						<button type="submit"  class="btn btn-primary" ng-disabled="erpCustomerEmailAddressesForm.$invalid" ng-click="funSaveCustomerEmailAddresses()">Save</button>
						<button type="button" class="btn btn-default" ng-click="resetButton()">Reset</button>
                    </div>
                    <!--Save Button "-->
					<input type="hidden" name="customer_id" id="customer_id" value="[[customerID]]">
					<input type="hidden" name="division_id" id="division_id" value="[[divisionID]]">
                </div>                    
            </form>
        </div>
    </div>
</div>
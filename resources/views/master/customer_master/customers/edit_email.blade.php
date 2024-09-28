<div class="row"  ng-if="editEmailFormDiv">
    <div class="panel panel-default" >
        <div class="panel-body">           
            <div class="row header-form">
		<div role="new" class="navbar-form navbar-left">            
		    <span class="pull-left"><strong id="form_title">Edit Customer Email : <b>[[editCustomerEmails.customer_name]]</b></strong></span>
		</div>
		<div role="new" class="navbar-form navbar-right">
		    <button type="button" class="btn btn-primary" ng-click="backButton()">Back</button>
		</div>
            </div>               
            <form name="erpCustomerEmailEditAddressesForm" id="erpCustomerEmailEditAddressesForm" method="POST" novalidate  >
                <div class="row">
		    <div class="col-xs-3 form-group">
			<label for="customer_id">Customer Email<em class="asteriskRed">*</em></label>
			<input
			    type="email"
			    class="form-control"
			    name="customer_email"
			    id="customer_email"
			    ng-required="true"
			    ng-model="editCustomerEmails.customer_email"
			    placeholder="Customer Email">
			<span ng-messages="erpCustomerEmailEditAddressesForm.customer_email.$error" ng-if='erpCustomerEmailEditAddressesForm.customer_email.$dirty  || erpCustomerEmailEditAddressesForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Customer email is required</span>
			</span>
		    </div>
					
                    <div class="col-xs-3 form-group">
			<label for="setting_group_id">Customer Email Type<em class="asteriskRed">*</em></label>
			<select
			    class="form-control"
			    name="customer_email_type"
			    id="customer_email_type"
			    ng-model="editCustomerEmails.customer_email_type.selectedOption"
			    ng-options="item.name for item in emailTypes track by item.id">
			    <option value="">Select Customer Email Type</option>
			</select>
			<span ng-messages="erpCustomerEmailEditAddressesForm.customer_email_type.$error" ng-if='erpCustomerEmailEditAddressesForm.customer_email_type.$dirty  || erpCustomerEmailEditAddressesForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Customer email type is required</span>
			</span>	
                    </div>
                   
		    <div class="col-xs-3 form-group">
			<label for="setting_group_id">Customer Email Status<em class="asteriskRed">*</em></label>
			<select
			    class="form-control"
			    name="customer_email_status"
			    id="customer_email_status"
			    ng-model="editCustomerEmails.customer_email_status.selectedOption"
			    ng-options="item.name for item in emailStatusTypes track by item.id">
			    <option value="">Select Customer Email Status</option>
			</select>
			<span ng-messages="erpCustomerEmailEditAddressesForm.customer_email_status.$error" ng-if='erpCustomerEmailEditAddressesForm.customer_email_status.$dirty  || erpCustomerEmailEditAddressesForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Customer email status is required</span>
			</span>	
		    </div>					

                    <!--Save Button-->
		    <div class="col-xs-3 form-group text-left mT26">
			<label for="submit">{{ csrf_field() }}</label>
			<input type="hidden" name="customer_id" id="customer_id" ng-model="editCustomerEmails.customer_id" ng-value="editCustomerEmails.customer_id">
			<input type="hidden" name="customer_email_id" id="customer_email_id" ng-model="editCustomerEmails.customer_email_id" ng-value="editCustomerEmails.customer_email_id">
			<button type="submit"  class="btn btn-primary" ng-disabled="erpCustomerEmailEditAddressesForm.$invalid" ng-click="funUpdateCustomerEmailAddresses(customerID,customerEmailID)">Update</button>
			<button type="button" class="btn btn-default" ng-click="closeButton()">Close</button>
		    </div>
                    <!--Save Button "-->
					
                </div>                    
            </form>
        </div>
    </div>
</div>
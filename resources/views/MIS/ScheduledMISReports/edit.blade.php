<div class="row" ng-hide="isViewEditScheduledMisReportForm">
    <div class="panel panel-default">
	<div class="panel-body">
	    
	    <!--Header-->
	    <div class="row header1">
		<strong class="pull-left headerText">Edit Scheduled Mail : <span ng-bind="editScheduledMisReport.smrd_scheduled_edit_title"></span></strong>
	    </div>
	    <!--/Header-->
	    
	    <!--Edit Scheduled Mail Form-->
	    <form method="POST" name="erpEditScheduledMisReportForm" id="erpEditScheduledMisReportForm" novalidate>		
		<div class="row">			
		    
		    <!--Branch-->
		    <div class="col-xs-2 form-group">
			<label for="smrd_division_id">Branch<em class="asteriskRed">*</em></label>
			<select
			    class="form-control"
			    ng-model="editScheduledMisReport.smrd_division_id"
			    id="edit_smrd_division_id"
			    name="smrd_division_id"
			    ng-required='true'
			    ng-options="item.name for item in divisionsCodeList track by item.id">
			    <option value="">Select Branch</option>
			</select>
			<span ng-messages="erpEditScheduledMisReportForm.smrd_division_id.$error" ng-if='erpEditScheduledMisReportForm.smrd_division_id.$dirty || erpEditScheduledMisReportForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Branch is required</span>
			</span>
		    </div>
		    <!--/Branch-->
		    
		    <!--Department / Parent Product Category-->
		    <div class="col-xs-2 form-group">																
			<label for="smrd_product_category_id">Department<em class="asteriskRed">*</em></label>	
			<select
			    class="form-control"
			    name="smrd_product_category_id"
			    id="edit_smrd_product_category_id"
			    ng-required='true'
			    ng-model="editScheduledMisReport.smrd_product_category_id"
			    ng-options="item.name for item in parentCategoryList track by item.id">
			    <option value="">Select Department</option>
			</select>
			<span ng-messages="erpEditScheduledMisReportForm.smrd_product_category_id.$error" ng-if='erpEditScheduledMisReportForm.smrd_product_category_id.$dirty || erpEditScheduledMisReportForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Department is required</span>
			</span>
		    </div>
		    <!--/Department / Parent Product Category-->
		    
		    <!--MIS Report Types-->
		    <div class="col-xs-4 form-group">                        
			<label for="smrd_mis_report_id">Select MIS Report Type<em class="asteriskRed">*</em></label>
			<select
			    class="form-control"
			    name="smrd_mis_report_id"
			    id="edit_smrd_mis_report_id"
			    ng-required='true'
			    ng-model="editScheduledMisReport.smrd_mis_report_id"
			    ng-options="MISReportTypes.name for MISReportTypes in MISReportTypesList track by MISReportTypes.id">
			    <option value="">Select Report Type</option>
			</select>
			<span ng-messages="erpEditScheduledMisReportForm.smrd_mis_report_id.$error" ng-if='erpEditScheduledMisReportForm.smrd_mis_report_id.$dirty || erpEditScheduledMisReportForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Report Type is required</span>
			</span>
		    </div>                      
		    <!--/MIS Report Types-->
		    
		    <!--Email Address-->
		    <div class="col-xs-4 form-group">
			<label for="smrd_mis_report_id">Add Email Address</label>
			<div class="col-xs-12 form-group">
			    <div class="col-xs-7 pL0">
				<input type="email" class="form-control" id="editPriSecEmailField" ng-model="editScheduledMisReport.editPriSecEmailField" placeholder="Email Address">
			    </div>
			    <div class="col-xs-4 pL0 font10">
				<input type="checkbox" id="editEmailTypeField" ng-model="editScheduledMisReport.editEmailTypeField">&nbsp;Seconday Email
			    </div>
			    <div class="col-xs-1 pL0">
				<input type="button" ng-disabled="!editScheduledMisReport.editPriSecEmailField" class="btn btn-success btn-sm" id="editMoreEmailFieldBtn" ng-click="funAddMoreEmailField(editScheduledMisReport.editPriSecEmailField,editScheduledMisReport.editEmailTypeField)" ng-model="editScheduledMisReport.editMoreEmailFieldBtn" value="GO">
			    </div>
			</div>
		    </div>
		    <!--/Email Address-->
		    
		    <!--Primary Email Addresses-->
		    <div class="col-xs-6 form-group" id="primary_email_addresses_div" ng-if="primaryEmailAddressList.length">
			<div class="col-xs-12 pL0"><label for="primary_email_addresses">Primary Email Addresses</label></div>
			<div class="col-xs-12" id="primary_email_addresses">
			    <div class="col-xs-6" ng-repeat="(key,value) in primaryEmailAddressList track by $index">
				<div class="col-xs-10 form-group">
				    <input readonly type="text" class="form-control bgWhite" id="edit_smrd_to_email_address_[[key]][[$index]]" name="smrd_to_email_address[]" value="[[value]]">
				</div>
				<div class="col-xs-2 form-group">
				    <button type="button" ng-click="funRemoveEmailField(value,1)" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
				</div>
			    </div>
			</div>
		    </div>
		    <!--/Primary Email Addresses-->
		    
		    <!--Seconday Email Addresses-->
		    <div class="col-xs-6 form-group" id="secondary_email_addresses_div" ng-if="secondayEmailAddressList.length">
			<div class="col-xs-12 pL0"><label for="secondary_email_addresses">Seconday Email Addresses</label></div>
			<div class="col-xs-12" id="secondary_email_addresses">
			    <div class="col-xs-6" ng-repeat="(key,value) in secondayEmailAddressList track by $index">
				<div class="col-xs-10 form-group">
				    <input readonly type="text" class="form-control bgWhite" id="edit_smrd_from_email_address_[[key]][[$index]]" name="smrd_from_email_address[]" value="[[value]]">
				</div>
				<div class="col-xs-2 form-group">
				    <button type="button" ng-click="funRemoveEmailField(value,2)" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
				</div>
			    </div>
			</div>
		    </div>
		    <!--/Seconday Email Addresses-->
		    
		    <!--Button-->
		    <div class="mT26 col-xs-12">
			<div class="pull-right">
			    <label for="csrf_field">{{ csrf_field() }}</label>	
			    <button type="submit" title="Save" ng-disabled="erpEditScheduledMisReportForm.$invalid || !primaryEmailAddressList.length" id="add_button" class="btn btn-primary" ng-click="funUpdateScheduledMisReport()">Update</button>
			    <button type="button" title="Reset" class="btn btn-default" ng-click="backButton(true)">Close</button>
			</div>
		    </div>
		    <!--/Button-->		    
		</div>		    
	    </form>
	    <!--Edit Scheduled Mail Form-->
	</div>
    </div>
</div>
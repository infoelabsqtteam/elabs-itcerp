<div class="row" ng-hide="isViewAddDiv">
    <div class="panel panel-default">
	<div class="panel-body">
	    
	    <!--Header-->
	    <div class="row header1">
		<strong class="pull-left headerText">Add Central PO</strong>
	    </div>
	    <!--/Header-->
	    
	    <!--Add Central PO Form-->
	    <form method="POST" name="erpAddCentralPOForm" enctype="multipart/form-data" id="erpAddCentralPOForm" novalidate>		
		<div class="row">			
		    
		    <!--PO No-->
		    <div class="col-xs-3 form-group">   
			<label for="cpo_no">PO No.<em class="asteriskRed">*</em></label>						   
			<input
			    type="text"
			    class="form-control" 
			    name="cpo_no"
			    id="cpo_no"
			    ng-model="centralPODtl.cpo_no" 
			    ng-required='true'
			    placeholder="PO No.">
			<span ng-messages="erpAddCentralPOForm.cpo_no.$error" ng-if='erpAddCentralPOForm.cpo_no.$dirty || erpAddCentralPOForm.$submitted' role="alert">
			    <span ng-message="required" class="error">PO No. is required</span>
			</span>
		    </div>				
		    <!--/PO No-->
		    
		    <!--Customer-->
                    <div class="col-xs-3 form-group">
                        <label for="cpo_customer_id">Customer Name<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="cpo_customer_id"
                            id="cpo_customer_id"
			    ng-required="true"
			    ng-change="funGetCityOnCustomerChange(centralPODtl.cpo_customer_id.id);"
                            ng-model="centralPODtl.cpo_customer_id"
                            ng-options="customerList.name for customerList in customerListData track by customerList.id">
                            <option value="">Select Customer Name</option>
                        </select>
			<span ng-messages="erpAddCentralPOForm.cpo_customer_id.$error" ng-if='erpAddCentralPOForm.cpo_customer_id.$dirty || erpAddCentralPOForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Customer Name is required</span>
			</span>
                    </div>                    
                    <!--/Customer-->
		    
		    <!--Customer City-->
                    <div class="col-xs-3 form-group">
                        <label for="cpo_customer_city">Customer City<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="cpo_customer_city"
                            id="cpo_customer_city"
			    ng-required="true"
                            ng-model="centralPODtl.cpo_customer_city"
                            ng-options="customerCity.name for customerCity in customerCityList track by customerCity.id">
                            <option value="">Select Customer City</option>
                        </select>
			<span ng-messages="erpAddCentralPOForm.cpo_customer_city.$error" ng-if='erpAddCentralPOForm.cpo_customer_city.$dirty || erpAddCentralPOForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Customer City is required</span>
			</span>
                    </div>                    
                    <!--/Customer City-->
		    
		    <!--Sample Name-->
		    <div class="col-xs-3 form-group">   
			<label for="cpo_sample_name">Sample Name<em class="asteriskRed">*</em></label>						   
			<input
			    type="text"
			    class="form-control" 
			    name="cpo_sample_name"
			    id="cpo_sample_name"
			    ng-model="centralPODtl.cpo_sample_name" 
			    ng-required='true'
			    placeholder="Sample Name">
			<span ng-messages="erpAddCentralPOForm.cpo_sample_name.$error" ng-if='erpAddCentralPOForm.cpo_sample_name.$dirty || erpAddCentralPOForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Sample Name is required</span>
			</span>
		    </div>
		    <!--/Sample Name-->
		    
		    <!--PO Date-->
		    <div class="col-xs-3 form-group">                        
			<label for="cpo_date">PO Date<em class="asteriskRed">*</em></label>
			<div class="input-group date" data-provide="datepicker">
			    <input
				type="text"
				class="bgwhite form-control"
				ng-model="centralPODtl.cpo_date"
				name="cpo_date"
				id="cpo_date"
				placeholder="PO Date"
				ng-required="true" />
			    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
			    <span ng-messages="erpAddCentralPOForm.cpo_date.$error" ng-if='erpAddCentralPOForm.cpo_date.$dirty || erpAddCentralPOForm.$submitted' role="alert">
				<span ng-message="required" class="error">PO Date is required</span>
			    </span>
			</div>
		    </div>		    
		    <!--/PO Date-->
		    
		    <!--PO File Name-->
		    <div class="col-xs-3 form-group">   
			<label for="cpo_file_name">PO File Name<em class="asteriskRed">*</em></label>						   
			<input
			    type="file"
			    class="btn btn-default col-xs-12 text-left" 
			    name="cpo_file_name"
			    ng-files="getTheFiles($files)"
			    id="cpo_file_name"
			    ng-model="centralPODtl.cpo_file_name">
			<span ng-messages="erpAddCentralPOForm.cpo_file_name.$error" ng-if='erpAddCentralPOForm.cpo_file_name.$dirty || erpAddCentralPOForm.$submitted' role="alert">
			    <span ng-message="required" class="error">PO File Name is required</span>
			</span>
		    </div>				
		    <!--/PO File Name-->
		    
		    <!--cpo_amount-->
		    <div class="col-xs-3 form-group">   
			<label for="cpo_amount">PO Amount<em class="asteriskRed">*</em></label>						   
			<input
			    type="text"
			    class="form-control" 
			    name="cpo_amount"
			    id="cpo_amount"
			    ng-model="centralPODtl.cpo_amount" 
			    ng-required='true'
			    placeholder="PO Amount">
			<span ng-messages="erpAddCentralPOForm.cpo_amount.$error" ng-if='erpAddCentralPOForm.cpo_amount.$dirty || erpAddCentralPOForm.$submitted' role="alert">
			    <span ng-message="required" class="error">PO Amount is required</span>
			</span>
		    </div>
		    <!--/cpo_amount-->
		    
		    <!--PO Status-->
		    <div class="col-xs-3 form-group">   
			<label for="company_name">PO Status<em class="asteriskRed">*</em></label>						   
			<select
			    class="form-control"
			    id="cpo_status"
			    name="cpo_status"
			    ng-model="centralPODtl.cpo_status"
			    ng-options="activeInactionList.name for activeInactionList in activeInactionSelectboxList track by activeInactionList.id"
			    ng-required='true'>
			    <option value="">Select PO Status</option>
			</select>
			<span ng-messages="erpAddCentralPOForm.cpo_status.$error" ng-if='erpAddCentralPOForm.cpo_status.$dirty || erpAddCentralPOForm.$submitted' role="alert">
			    <span ng-message="required" class="error">PO Status is required</span>
			</span>
		    </div>				
		    <!--/PO Status-->
		    
		    <!--Button-->
		    <div class="col-xs-12 form-group">
			<div class="pull-right">
			    <label for="csrf_field">{{ csrf_field() }}</label>	
			    <button type="submit" title="Save" ng-disabled="erpAddCentralPOForm.$invalid" id="add_button" class="btn btn-primary" ng-click="funAddCentralContentDtl()">Save</button>
			    <button type="button" title="Reset" class="btn btn-default" ng-click="resetForm()">Reset</button>
			</div>
		    </div>
		    <!--/Button-->
		    
		</div>		    
	    </form>
	    <!--Add Central PO Form-->
	</div>
    </div>
</div>
<div class="row" ng-hide="isViewAddDiv">
    <div class="panel panel-default">
	<div class="panel-body">
	    
	    <!--Header-->
	    <div class="row header1">
		<strong class="pull-left headerText">Add Central STP</strong>
	    </div>
	    <!--/Header-->
	    
	    <!--Add Central STP Form-->
	    <form method="POST" name="erpAddCentralSTPForm" enctype="multipart/form-data" id="erpAddCentralSTPForm" novalidate>		
		<div class="row">			
		    
		    <!--STP No-->
		    <div class="col-xs-3 form-group">   
			<label for="cstp_no">STP No.<em class="asteriskRed">*</em></label>						   
			<input
			    type="text"
			    class="form-control" 
			    name="cstp_no"
			    id="cstp_no"
			    ng-model="centralSTPDtl.cstp_no" 
			    ng-required='true'
			    placeholder="STP No.">
			<span ng-messages="erpAddCentralSTPForm.cstp_no.$error" ng-if='erpAddCentralSTPForm.cstp_no.$dirty || erpAddCentralSTPForm.$submitted' role="alert">
			    <span ng-message="required" class="error">STP No. is required</span>
			</span>
		    </div>				
		    <!--/STP No-->
		    
		    <!--Customer-->
                    <div class="col-xs-3 form-group">
                        <label for="cstp_customer_id">Customer Name<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="cstp_customer_id"
                            id="cstp_customer_id"
			    ng-required="true"
			    ng-change="funGetCityOnCustomerChange(centralSTPDtl.cstp_customer_id.id);"
                            ng-model="centralSTPDtl.cstp_customer_id"
                            ng-options="customerList.name for customerList in customerListData track by customerList.id">
                            <option value="">Select Customer Name</option>
                        </select>
			<span ng-messages="erpAddCentralSTPForm.cstp_customer_id.$error" ng-if='erpAddCentralSTPForm.cstp_customer_id.$dirty || erpAddCentralSTPForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Customer Name is required</span>
			</span>
                    </div>                    
                    <!--/Customer-->
		    
		    <!--Customer City-->
                    <div class="col-xs-3 form-group">
                        <label for="cstp_customer_city">Customer City<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="cstp_customer_city"
                            id="cstp_customer_city"
			    ng-required="true"
                            ng-model="centralSTPDtl.cstp_customer_city"
                            ng-options="customerCity.name for customerCity in customerCityList track by customerCity.id">
                            <option value="">Select Customer City</option>
                        </select>
			<span ng-messages="erpAddCentralSTPForm.cstp_customer_city.$error" ng-if='erpAddCentralSTPForm.cstp_customer_city.$dirty || erpAddCentralSTPForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Customer City is required</span>
			</span>
                    </div>                    
                    <!--/Customer City-->
		    
		    <!--Sample Name-->
		    <div class="col-xs-3 form-group">   
			<label for="cstp_sample_name">Sample Name<em class="asteriskRed">*</em></label>						   
			<input
			    type="text"
			    class="form-control" 
			    name="cstp_sample_name"
			    id="cstp_sample_name"
			    ng-model="centralSTPDtl.cstp_sample_name" 
			    ng-required='true'
			    placeholder="Sample Name">
			<span ng-messages="erpAddCentralSTPForm.cstp_sample_name.$error" ng-if='erpAddCentralSTPForm.cstp_sample_name.$dirty || erpAddCentralSTPForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Sample Name is required</span>
			</span>
		    </div>				
		    <!--/Sample Name-->
		    
		    <!--STP Date-->
		    <div class="col-xs-3 form-group">                        
			<label for="cstp_date">STP Date<em class="asteriskRed">*</em></label>
			<div class="input-group date" data-provide="datepicker">
			    <input
				type="text"
				class="bgwhite form-control"
				ng-model="centralSTPDtl.cstp_date"
				name="cstp_date"
				id="cstp_date"
				placeholder="STP Date"
				ng-required="true" />
			    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
			    <span ng-messages="erpAddCentralSTPForm.cstp_date.$error" ng-if='erpAddCentralSTPForm.cstp_date.$dirty || erpAddCentralSTPForm.$submitted' role="alert">
				<span ng-message="required" class="error">STP Date is required</span>
			    </span>
			</div>
		    </div>		    
		    <!--/STP Date-->
		    
		    <!--STP File Name-->
		    <div class="col-xs-3 form-group">   
			<label for="cstp_file_name">STP File Name<em class="asteriskRed">*</em></label>						   
			<input
			    type="file"
			    class="btn btn-default col-xs-12 text-left" 
			    name="cstp_file_name"
			    ng-files="getTheFiles($files)"
			    id="cstp_file_name"
			    ng-model="centralSTPDtl.cstp_file_name">
			<span ng-messages="erpAddCentralSTPForm.cstp_file_name.$error" ng-if='erpAddCentralSTPForm.cstp_file_name.$dirty || erpAddCentralSTPForm.$submitted' role="alert">
			    <span ng-message="required" class="error">STP File Name is required</span>
			</span>
		    </div>				
		    <!--/STP File Name-->
		    
		    <!--STP Status-->
		    <div class="col-xs-3 form-group">   
			<label for="company_name">STP Status<em class="asteriskRed">*</em></label>						   
			<select
			    class="form-control"
			    id="cstp_status"
			    name="cstp_status"
			    ng-model="centralSTPDtl.cstp_status"
			    ng-options="activeInactionList.name for activeInactionList in activeInactionSelectboxList track by activeInactionList.id"
			    ng-required='true'>
			    <option value="">Select STP Status</option>
			</select>
			<span ng-messages="erpAddCentralSTPForm.cstp_status.$error" ng-if='erpAddCentralSTPForm.cstp_status.$dirty || erpAddCentralSTPForm.$submitted' role="alert">
			    <span ng-message="required" class="error">STP Status is required</span>
			</span>
		    </div>				
		    <!--/STP Status-->
		    
		    <!--Button-->
		    <div class="col-xs-3 form-group mT26">
			<div class="pull-left">
			    <label for="csrf_field">{{ csrf_field() }}</label>	
			    <button type="submit" title="Save" ng-disabled="erpAddCentralSTPForm.$invalid" id="add_button" class="btn btn-primary" ng-click="funAddCentralContentDtl()">Save</button>
			    <button type="button" title="Reset" class="btn btn-default" ng-click="resetForm()">Reset</button>
			</div>
		    </div>
		    <!--/Button-->
		    
		</div>		    
	    </form>
	    <!--Add Central STP Form-->
	</div>
    </div>
</div>
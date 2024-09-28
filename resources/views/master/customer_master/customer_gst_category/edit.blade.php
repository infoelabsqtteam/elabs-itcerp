<div class="row" ng-hide="isViewEditCustomerGstCategoryForm">
    <div class="panel panel-default">
	<div class="panel-body">
	    
	    <!--Header-->
	    <div class="row header1">
	       <strong class="pull-left headerText">Edit Customer GST Category : <span ng-bind="editCustomerGstCategory.cgc_name"></span></strong>
	    </div>
	    <!--/Header-->
	    
	    <!--Add Customer GST Form-->
	    <form method="POST" name="erpEditCustomerGstCategoryForm" id="erpEditCustomerGstCategoryForm" novalidate>		
		<div class="row">			
		    <!--GST Category Name-->
		    <div class="col-xs-4 form-group">   
			<label for="company_name">GST Category Name<em class="asteriskRed">*</em></label>						   
			<input
                           type="text"
                           class="form-control" 
                           name="cgc_name"
                           id="cgc_name"
                           ng-model="editCustomerGstCategory.cgc_name" 
                           ng-required='true'
                           placeholder="GST Category Name">
			<span ng-messages="erpEditCustomerGstCategoryForm.cgc_name.$error" ng-if='erpEditCustomerGstCategoryForm.cgc_name.$dirty || erpEditCustomerGstCategoryForm.$submitted' role="alert">
			    <span ng-message="required" class="error">GST Category name is required</span>
			</span>
		    </div>				
		    <!--GST Category Name-->
		    
		    <!--GST Category Status-->
		    <div class="col-xs-4 form-group">   
			<label for="company_name">GST Category Status<em class="asteriskRed">*</em></label>						   
			<select
                           class="form-control"
                           id="cgc_status"
                           name="cgc_status"
                           ng-model="editCustomerGstCategory.cgc_status"
                           ng-options="activeInactionList.name for activeInactionList in activeInactionSelectboxList track by activeInactionList.id"
                           ng-required='true'>
                           <option value="">Select GST Category Status</option>
			</select>
			<span ng-messages="erpEditCustomerGstCategoryForm.cgc_status.$error" ng-if='erpEditCustomerGstCategoryForm.cgc_status.$dirty || erpEditCustomerGstCategoryForm.$submitted' role="alert">
			    <span ng-message="required" class="error">GST Category Status is required</span>
			</span>
		    </div>				
		    <!--GST Category Status-->
		    
		    <!--Button-->
		    <div class="mT26 col-xs-4">
			<div class="pull-left">
                           <label for="csrf_field">{{ csrf_field() }}</label>
                           <input type="hidden"  id="cgc_id" name="cgc_id" ng-model="editCustomerGstCategory.cgc_id" ng-value="editCustomerGstCategory.cgc_id">
                           <button type="submit" title="Save" ng-disabled="erpEditCustomerGstCategoryForm.$invalid" id="add_button" class="btn btn-primary" ng-click="funUpdateCustomerGstCategories()">Update</button>
                           <button type="button" title="Reset" class="btn btn-default" ng-click="backButton(true)">Back</button>
			</div>
		    </div>
		    <!--/Button-->		    
		</div>		    
	    </form>
	    <!--Add Customer GST Form-->
	</div>
    </div>
</div>
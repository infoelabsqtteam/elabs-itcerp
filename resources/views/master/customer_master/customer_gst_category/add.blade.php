<div class="row" ng-hide="isViewAddCustomerGstCategoryForm">
    <div class="panel panel-default">
	<div class="panel-body">
	    
	    <!--Header-->
	    <div class="row header1">
		<strong class="pull-left headerText">Add Customer GST Category</strong>
	    </div>
	    <!--/Header-->
	    
	    <!--Add Customer GST Form-->
	    <form method="POST" name='erpAddCustomerGstCategoryForm' id="erpAddCustomerGstCategoryForm" novalidate>		
		<div class="row">			
		    <!--GST Category Name-->
		    <div class="col-xs-4 form-group">   
			<label for="company_name">GST Category Name<em class="asteriskRed">*</em></label>						   
			<input
			    type="text"
			    class="form-control" 
			    name="cgc_name"
			    id="cgc_name"
			    ng-model="cusomerGstCategory.cgc_name" 
			    ng-required='true'
			    placeholder="GST Category Name">
			<span ng-messages="erpAddCustomerGstCategoryForm.cgc_name.$error" ng-if='erpAddCustomerGstCategoryForm.cgc_name.$dirty || erpAddCustomerGstCategoryForm.$submitted' role="alert">
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
			    ng-model="cusomerGstCategory.cgc_status"
			    ng-options="activeInactionList.name for activeInactionList in activeInactionSelectboxList track by activeInactionList.id"
			    ng-required='true'>
			    <option value="">Select GST Category Status</option>
			</select>
			<span ng-messages="erpAddCustomerGstCategoryForm.cgc_status.$error" ng-if='erpAddCustomerGstCategoryForm.cgc_status.$dirty || erpAddCustomerGstCategoryForm.$submitted' role="alert">
			    <span ng-message="required" class="error">GST Category Status is required</span>
			</span>
		    </div>				
		    <!--GST Category Status-->
		    
		    <!--Button-->
		    <div class="mT26 col-xs-4">
			<div class="pull-left">
			    <label for="csrf_field">{{ csrf_field() }}</label>	
			    <button type="submit" title="Save" ng-disabled="erpAddCustomerGstCategoryForm.$invalid" id="add_button" class="btn btn-primary" ng-click="funAddCustomerGstCategories()">Save</button>
			    <button type="button" title="Reset" class="btn btn-default" ng-click="resetForm()">Reset</button>
			</div>
		    </div>
		    <!--/Button-->		    
		</div>		    
	    </form>
	    <!--Add Customer GST Form-->
	</div>
    </div>
</div>
	<div ng-hide="currentProductTestDetail" id="currentProTestAltDetail">	
	<div class="row header1">
		<strong ng-hide="addAltMethodLable" class="pull-left headerText">Add Product Test Parameter Alternative Methods</strong>
		<strong ng-hide="editAltMethodLable" class="pull-left headerText">Edit Product Test Parameter Alternative Methods</strong>	
		<div ng-hide="">
			<button ng-hide="okAltHideBtn" title="Back" class="okBtn btn btn-default btn-sm" ng-click="returnToParameterList(currentTestId)">Back</button>					
		</div>
	</div>
	<div class="row recordDetails productDetails">
		<div class="col-xs-3">
		   <div class="">
			<label>Test Code:</label>
				<span class="color-green" ng-bind="alt_test_code" ng-model="alt_test_code"></span>
		   </div>
		</div>
		<div class="col-xs-3">
		   <div class="">
			<label>Test Product Name:</label>
				<span class="color-green" ng-bind="alt_product_name" ng-model="alt_product_name"></span>
		   </div>
		</div>
		<div class="col-xs-3">
		   <div class="">
			<label>Test Standard:</label>
				<span class="color-green" ng-bind="altStdName" ng-model="altStdName"></span>
		   </div>
		</div>
		<div class="col-xs-3">
		   <div class="">
			<label>Test Parameter Name:</label>
				<span class="color-green" ng-bind-html="alt_test_parameter_name" ng-model="alt_test_parameter_name"></span>
		   </div>
		</div>
	</div>
	<!-- ************************************** add parameter form  end**************************** -->	
	@include('master.standard_wise_product_test.product_test_parameter_alternate_methods.add')
	<!-- ************************************** add parameter form  end**************************** -->	

	<!-- ************************************** edit parameter form  start**************************** -->
	@include('master.standard_wise_product_test.product_test_parameter_alternate_methods.edit')
	<!-- ************************************** edit parameter form  end**************************** -->	
	</div>
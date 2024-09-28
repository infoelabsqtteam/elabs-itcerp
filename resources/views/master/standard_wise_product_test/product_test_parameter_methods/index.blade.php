	<div ng-hide="currentProductDetail" id="currentProDetailDiv">
	    <div class="row header1">
	        <strong ng-hide="addLable" class="pull-left headerText">Add Standard Wise Product Test Parameters</strong>
	        <strong ng-hide="editLable" class="pull-left headerText">Edit Standard Wise Product Test Parameters</strong>
	        <div ng-hide="paraBtns">
	            <button ng-hide="okHideBtn" class="okBtn btn btn-default btn-sm" ng-click="okRecord(currentTestId)" title="Back">Back</button>
	        </div>
	    </div>
	    <div class="row recordDetails productDetails bgWhite">
	        <div class="col-xs-4">
	            <label>Test Code:</label>
	            <span class="color-green" ng-bind="test_code_text" ng-model="test_code_text"></span>
	        </div>
	        <div class="col-xs-4">
	            <label>Product Category:</label>
	            <span class="color-green" ng-bind="product_category_text" ng-model="product_category_text"></span>
	        </div>
	        <div class="col-xs-4">
	            <label>Product Name:</label>
	            <span class="color-green" ng-bind="product_text" ng-model="product_text"></span>
	        </div>
	        <div class="col-xs-4">
	            <label>Test Standard Name:</label>
	            <span class="color-green" ng-bind="test_standard_text" ng-model="test_standard_text"></span>
	        </div>
	        <div class="col-xs-4">
	            <label>Wef:</label>
	            <span class="color-green" ng-bind="wef_text" ng-model="wef_text"></span>
	        </div>
	        <div class="col-xs-4">
	            <label>Upto:</label>
	            <span class="color-green" ng-bind="upto_text" ng-model="upto_text"></span>
	        </div>
	    </div>
	    <!-- ************************************** add parameter form  end**************************** -->
	    @include('master.standard_wise_product_test.product_test_parameter_methods.add')
	    <!-- ************************************** add parameter form  end**************************** -->

	    <!-- ************************************** edit parameter form  end**************************** -->
	    @include('master.standard_wise_product_test.product_test_parameter_methods.edit')
	    <!-- ************************************** edit parameter form  end**************************** -->

	</div>

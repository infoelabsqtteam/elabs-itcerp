<div class="row" ng-hide="IsNewStabilityOrder" id="erp_create_stability_order_form_div">
    <div class="panel panel-default">		           
	<div class="panel-body">
	
	    <div class="row header-form">
		<span class="pull-left headerText"><strong>Place New Stability Order</strong></span>
		<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></span>
	    </div>
		
	    <!--Create Order Form-->
	    <form method="POST" role="form" id="erpCreateStabilityOrderCSForm" name="erpCreateStabilityOrderCSForm" novalidate>		
	    
		<!--add customer form-->
		@include('sales.stability_orders.addCustomer')
		<!--add customer form-->
		
		<!--add sample form-->
		@include('sales.stability_orders.addSample')
		<!--add sample form-->
		
		<div class="order_detail">				
		    <!--Save Button-->
		    <div class="col-xs-12 form-group text-right mT10">
			<label for="submit">{{ csrf_field() }}</label>					
			<button type="button" ng-disabled="erpCreateStabilityOrderCSForm.$invalid" class="btn btn-primary" ng-click="funSaveCustomerSampleOfStabilityOrder()">Save</button>
			<button type="button" class="btn btn-default" ng-click="resetForm()">Reset</button>
		    </div>
		    <!--Save Button-->
		</div>
		    
	    </form>
	    <!--Create Order Form-->
		
	</div>	
    </div>
	
    <!----- include state tree view------->
    <!--include('sales.stability_orders.addStateCityTreeViewPopup')-->
    <!----- include state tree view------->
    
    <!----- include country tree view------->
    @include('sales.stability_orders.addCountryStateTreeViewPopup')
    <!----- include country tree view------->

    <!--category Tree-->
    @include('sales.stability_orders.productCategoryTreePopup')
    <!--/category Tree-->
</div>
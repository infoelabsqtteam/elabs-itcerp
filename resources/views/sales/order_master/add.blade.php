<div class="row" ng-hide="IsNewOrder" id="erp_create_order_form_div">
    <div class="panel panel-default">		           
	<div class="panel-body">
	
	    <div class="row header-form">
		<span class="pull-left headerText"><strong>Place New Order</strong></span>
		<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></span>
	    </div>
		
	    <!--Create Order Form-->
	    <form method="POST" role="form" id="erpCreateOrderForm" name="erpCreateOrderForm" novalidate>		
	    
		<!--add customer form-->
		@include('sales.order_master.addCustomer')
		<!--add customer form-->
		
		<!--add sample form-->
		@include('sales.order_master.addSample')
		<!--add sample form-->
		
		<!--add extra Field form-->
		@include('sales.order_master.addExtraFields')
		<!--add extra Field form-->
		
		<!--add extra detail form-->
		@include('sales.order_master.addExtraDetail')
		<!--add extra detail -->
		
		<!--add sample form-->
		@include('sales.order_master.addSampleParameters')
		<!--add sample form-->
		
		<div class="order_detail" ng-show ="isSampleId">				
		    <!--Save Button-->
		    <div class="col-xs-12 form-group text-right mT10">
			<label for="submit">{{ csrf_field() }}</label>					
			<button type="button" ng-disabled="orderCustomer.sample_priority_id.sample_priority_id == 0  || erpCreateOrderForm.$invalid" class="btn btn-primary" ng-click="funConfirmSaveMore(divisionID)">Save more</button>
			<button type="button" ng-disabled="orderCustomer.sample_priority_id.sample_priority_id == 0  || erpCreateOrderForm.$invalid" class="btn btn-primary" ng-click="funPlaceAndSaveOrder(divisionID,'0')">Save</button>
			<button type="button" class="btn btn-default" ng-click="resetForm()">Reset</button>
		    </div>
		    <!--Save Button-->
		</div>	
	    </form>
	    <!--Create Order Form-->			
	</div>	
    </div>
    
    <!----- include state tree view------->
    <!--include('sales.order_master.addStateCityTreeViewPopup')-->
	  @include('sales.order_master.addCountryStateTreeViewPopup')
    <!----- include state tree view------->
	    
    <!--get Alternative Test Parameter Form-->
    @include('sales.order_master.addAlternative')
    <!--/get Alternative Test Parameter Form-->
			    
    <!--add sample form-->
    @include('sales.order_master.addSampleParametersPopup')
    <!--add sample form-->	
    
    <!--category Tree-->
    @include('sales.order_master.productCategoryTreePopup')
    <!--/category Tree-->
    
    <!--add Internal Transfer sample form-->
    @include('sales.order_master.addInternalTransferSamplePopup')
    <!--/add Internal Transfer sample form-->
    
</div>
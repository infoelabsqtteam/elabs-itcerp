<div class="row" ng-hide="IsNewOrder" id="erp_create_order_form_div">
    <div class="panel panel-default">		           
	<div class="panel-body">
	
	    <div class="row header-form">
		<span class="pull-left headerText"><strong>Place New Order</strong></span>
	    </div>
		
	    <!--Create Order Form-->
	    <form method="POST" role="form" id="erpCreateOrderForm" name="erpCreateOrderForm" novalidate>		
	    
		<!--add customer form-->
		@include('sales.stability_orders.notifcations.addCustomer')
		<!--add customer form-->
		
		<!--add sample form-->
		@include('sales.stability_orders.notifcations.addSample')
		<!--add sample form-->
		
		<!--add extra detail form-->
		@include('sales.stability_orders.notifcations.addExtraDetail')
		<!--add extra detail -->
		
		<!--add sample form-->
		@include('sales.stability_orders.notifcations.addSampleParameters')
		<!--add sample form-->
		
		<div class="order_detail">				
		    <!--Save Button-->
		    <div class="col-xs-12 form-group text-right mT10">
			<label for="submit">{{ csrf_field() }}</label>
			<input type="hidden" name="stb_order_hdr_id" id="stb_order_hdr_id" ng-value="updateOrder.stb_order_hdr_id" ng-model="updateOrder.stb_order_hdr_id">
			<input type="hidden" name="stb_order_hdr_dtl_id" id="stb_order_hdr_dtl_id" ng-value="updateOrder.stb_order_hdr_dtl_id" ng-model="updateOrder.stb_order_hdr_dtl_id">
			<input type="hidden" name="stb_stability_type_id" id="stb_stability_type_id" ng-value="updateOrder.stb_stability_type_id" ng-model="updateOrder.stb_stability_type_id">
			<button type="button" ng-disabled="updateOrder.stb_sample_priority_id.sample_priority_id == 0  || erpCreateOrderForm.$invalid" class="btn btn-primary" ng-click="funConfirmPlaceAndSaveOrderMessage(stbOrderHdrID,stbOrderHdrDtlID,stbStabilityTypeID,'{{config('messages.message.confirmBoxMsg')}}')">Create Order</button>
		    </div>
		    <!--Save Button-->
		</div>	
	    </form>
	    <!--Create Order Form-->			
	</div>	
    </div>
    
    <!----- include state tree view------->
    @include('sales.stability_orders.notifcations.addStateCityTreeViewPopup')
    <!----- include state tree view------->
	    
    <!--get Alternative Test Parameter Form-->
    @include('sales.stability_orders.notifcations.addAlternative')
    <!--/get Alternative Test Parameter Form-->	
    
    <!--category Tree-->
    @include('sales.stability_orders.notifcations.productCategoryTreePopup')
    <!--/category Tree-->
    
</div>
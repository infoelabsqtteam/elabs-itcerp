<div class="row" ng-hide="IsUpdateOrder" id="IsUpdateOrder">
    <div class="panel panel-default">		           
	<div class="panel-body">		
	    <div class="row header-form">
		<span class="pull-left headerText" ng-click="funEditOrder(updateOrder.order_id)" title="Refresh"><strong>Update Order <span ng-bind="updateOrder.order_no"></span></strong></span>
		<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></span>
	    </div>
	    <!--Create Order Form-->
	    <form method="POST" role="form" id="erpUpdateOrderForm" name="erpUpdateOrderForm" novalidate>		
						    
		<!--edit customer form-->
		@include('sales.order_master.editCustomer')
		<!--edit customer form-->
		
		<!--edit sample form-->
		@include('sales.order_master.editSample')
		<!--edit sample form-->
		
		<!--edit extra Field form-->
		@include('sales.order_master.editExtraFields')
		<!--edit extra Field form-->
		
		<!--edit extra detail form-->
		@include('sales.order_master.editExtraDetail')
		<!--/edit extra detail form-->
		
		<!--edit Sample Parameters-->
		@include('sales.order_master.editSampleParameters')
		<!-- /edit Sample Parameters-->
		
		<!--Save Button-->
		<div class="col-xs-12 form-group text-right mT10">
		    <label for="submit">{{ csrf_field() }}</label>					
		    <input type="hidden" name="order_id" ng-value="updateOrder.order_id" ng-model="updateOrder.order_id">
		    <input type="hidden" name="product_id" ng-value="updateOrder.product_id" ng-model="updateOrder.product_id">
		    <button type="button" ng-disabled="erpUpdateOrderForm.$invalid" class="btn btn-primary" ng-click="funUpdateOrder(divisionID)">Update</button>
		    <button type="button" class="btn btn-default" ng-click="backButton()">Close</button>
		</div>
		<!--Save Button-->			
	    </form>
	    <!--Create Order Form-->
	</div>	
    </div>
	
    <!----- include state tree view------->
    <!--include('sales.order_master.editStateCityTreeViewPopup')-->
	 @include('sales.order_master.editCountryStateTreeViewPopup')

    <!----- include state tree view------->
    
    <!--get Alternative Test Parameter Form-->
    @include('sales.order_master.editAlternative')
    <!--/get Alternative Test Parameter Form-->
		    
    <!--add sample form-->
    @include('sales.order_master.editSampleParametersPopup')
    <!--add sample form-->	
    
    <!--category Tree-->
    @include('sales.order_master.productCategoryTreePopup')
    <!--/category Tree-->	
</div>

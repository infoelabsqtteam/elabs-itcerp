<!--Save As Order Form-->
<div class="row" ng-hide="IsSaveAsOrder">
    <div class="panel panel-default">		           
	<div class="panel-body">		
	    <div class="row header-form">
		<span class="pull-left headerText"><strong>Save As New Order</strong></span>
		<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></span>
	    </div>
	    <form method="POST" role="form" id="erpSaveAsOrderForm" name="erpSaveAsOrderForm" novalidate>		
						    
		<!--save As customer form-->
		@include('sales.order_master.saveAsCustomer')
		<!--save As  customer form-->
		
		<!--save As  sample form-->
		@include('sales.order_master.saveAsSample')
		<!--add sample form-->
		
		<!--save As  extra detail form-->
		@include('sales.order_master.saveAsExtraDetail')
		<!--/save As extra detail form-->
		
		<!--save As Sample Parameters-->
		@include('sales.order_master.saveAsSampleParameters')
		<!-- /save As Sample Parameters-->
		
		<!--Save Button-->
		<div class="col-xs-12 form-group text-right mT10">
		    <label for="submit">{{ csrf_field() }}</label>					
		    <button type="button" ng-disabled="erpSaveAsOrderForm.$invalid" class="btn btn-primary" ng-click="funConfirmSaveAsMore(divisionID,saveAsOrder.order_id)">Save</button>
		    <button type="button" class="btn btn-default" ng-click="backButton()">Close</button>
		</div>
		<!--Save Button-->
	    </form>
	</div>	
    </div>
    
    <!----- include state tree view------->
    @include('sales.order_master.saveAsStateCityTreeViewPopup')
    <!----- include state tree view------->
    
    <!--get Alternative Test Parameter Form-->
    @include('sales.order_master.saveAsAlternative')
    <!--/get Alternative Test Parameter Form-->
    
    <!--add sample form-->
    @include('sales.order_master.saveAsSampleParametersPopup')
    <!--add sample form-->	
    
    <!--category Tree-->
    @include('sales.order_master.productCategoryTreePopup')
    <!--/category Tree-->
</div>
<!--Save As Order Form-->
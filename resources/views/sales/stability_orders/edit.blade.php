<div class="row" ng-hide="IsUpdateStabilityOrder" id="IsUpdateStabilityOrder">
    <div class="panel panel-default">		           
	<div class="panel-body">		
	    
	    <div class="row header-form">
		<span class="pull-left headerText" ng-click="funEditCustomerSampleOfStabilityOrder(updateStabilityOrder.stb_order_hdr_id)" title="Refresh"><strong>Update Stability Order <span ng-bind="updateStabilityOrder.stb_prototype_no"></span></strong></span>
		<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></span>
	    </div>
	    
	    <!--Customer/Sample Content-->
	    <div class="row order-section fontbd">
		<span class="pull-left">Customer Detail</span>
		<span class="pull-right"><button type="button" class="btn btn-default" ng-click="toggleButton('stbEditCustomerSample','button-editcustomersample-id')"><i id="button-editcustomersample-id" class="fa fa-angle-double-up font16"></i></button></span>
	    </div>
	    <div class="mT10" id="stbEditCustomerSample" style="display:none;">	
		<!--Edit Stability Order Form-->
		<form method="POST" role="form" id="erpUpdateStabilityOrderCSForm" name="erpUpdateStabilityOrderCSForm" novalidate>
		
		    <!--add customer form-->
		    @include('sales.stability_orders.editCustomer')
		    <!--add customer form-->
		    
		    <!--add sample form-->
		    @include('sales.stability_orders.editSample')
		    <!--add sample form-->
		    
		    <!--Save Button-->
		    <div class="text-right toggleDivClass">
			<label for="submit">{{ csrf_field() }}</label>					
			<input type="hidden" name="stb_order_hdr_id" ng-value="updateStabilityOrder.stb_order_hdr_id" ng-model="updateStabilityOrder.stb_order_hdr_id">
			<input type="hidden" name="stb_product_id" ng-value="updateStabilityOrder.stb_product_id" ng-model="updateStabilityOrder.stb_product_id">
			<span ng-if="updateStabilityOrder.stb_status == 0 && updateStabilityOrder.stb_order_hdr_detail_status == 0"><button type="button" ng-disabled="erpUpdateStabilityOrderCSForm.$invalid" class="btn btn-primary" ng-click="funUpdateCustomerSampleOfStabilityOrder()">Update</button></span>
			<button type="button" class="btn btn-default" ng-click="toggleButton('stbEditCustomerSample','button-editcustomersample-id')">Close</button>
		    </div>
		    <!--Save Button-->			
		</form>
		<!--Edit Stability Order Form-->
	    </div>
	    <!--/Customer/Sample Content-->
	    
	    <!--Prototype Content-->
	    <div class="row order-section mT10 fontbd" style="margin-top:10px!important;">
		<span class="pull-left">Prototype Detail</span>
		<span class="pull-right"><button type="button" class="btn btn-default" ng-click="toggleButton('stbPrototypeAddEditDetailFormDiv','button-addeditprototype-id')"><i id="button-addeditprototype-id" class="fa fa-angle-double-down font16"></i></button></span>
	    </div>
	    <div class="togglePrototypeAddEditDetailDivClass mT10" id="stbPrototypeAddEditDetailFormDiv">
	    
		<!--View Prototype Stability Order Detail-->
		<div class="mT10" id="stbPrototypeDetailFromDiv" ng-hide="IsListPrototypeFormDiv">
		    
		    <!--Prototype Stability Order Detail-->
		    @include('sales.stability_orders.viewPrototypes')
		    <!--Prototype Stability Order Detail-->
		    
		</div>
		<!--View Prototype Stability Order Detail-->
		
		<!--Create Prototype Stability Order Form-->
		<div class="mT10" id="stbPrototypeAddFormDiv" ng-hide="IsAddPrototypeFormDiv || updateStabilityOrder.stb_status == 1">
		    
		    <form method="POST" role="form" id="erpCreateStabilityOrderPrototypeForm" name="erpCreateStabilityOrderPrototypeForm" novalidate>	
			
			<!--add extra detail form-->
			@include('sales.stability_orders.addPrototype')
			<!--add extra detail -->
			
			<div class="order_detail">				
			    <!--Save Button-->
			    <div class="col-xs-12 form-group text-right mT10">
				<label for="submit">{{ csrf_field() }}</label>
				<input type="hidden" name="stb_order_hdr_id" ng-value="updateStabilityOrder.stb_order_hdr_id" ng-model="updateStabilityOrder.stb_order_hdr_id">
				<span ng-if="updateStabilityOrder.stb_status == 0"><button type="button" ng-disabled="erpCreateStabilityOrderPrototypeForm.$invalid || allAddSelectedStabilityConditionArray.length <= 0 || allDefaultSelectedstabilityConditionArray.length <= 0" class="btn btn-primary" ng-click="funSavePrototypeOfStabilityOrder()">Add Prototype</button></span>
				 </div>
			    <!--Save Button-->
			</div>
			    
		    </form>
		</div>
		<!--/Create Prototype Stability Order Form-->
		
		<!--Edit Prototype Stability Order Form-->
		<div class="mT10" id="stbPrototypeEditFormDiv" ng-hide="IsEditPrototypeFormDiv">
		    
		    <form method="POST" role="form" id="erpUpdateStabilityOrderPrototypeForm" name="erpUpdateStabilityOrderPrototypeForm" novalidate>
			
			<!--Prototype Stability Order Detail-->
			@include('sales.stability_orders.editPrototype')
			<!--Prototype Stability Order Detail-->
			
			<div class="order_detail">				
			    <!--Save Button-->
			    <div class="col-xs-12 form-group text-right mT10">
				<label for="submit">{{ csrf_field() }}</label>
				<input type="hidden" name="stb_order_hdr_id" ng-value="orderEditStabilityPrototype.stb_order_hdr_id" ng-model="orderEditStabilityPrototype.stb_order_hdr_id">
				<input type="hidden" name="stb_order_hdr_dtl_id" ng-value="orderEditStabilityPrototype.stb_order_hdr_dtl_id" ng-model="orderEditStabilityPrototype.stb_order_hdr_dtl_id">
				<span ng-if="orderEditStabilityPrototype.stb_order_hdr_detail_status == 0"><button type="button" ng-disabled="erpUpdateStabilityOrderPrototypeForm.$invalid || allEditSelectedStabilityConditionArray.length <= 0 || allEditDefaultTestParametersArray.length <= 0" class="btn btn-primary" ng-click="funUpdatePrototypeOfStabilityOrder();">Update Prototype</button></span>
				<button type="button" class="btn btn-default" ng-click="closeButton('add')">Close</button>
			    </div>
			    <!--Save Button-->
			</div>
			    
		    </form>
		</div>
		<!--Edit Prototype Stability Order Form-->
		    
	    </div>
	    <!--Prototype Content-->
	</div>	
    </div>
	
    <!--Generate Stability Test Format Report Popup-->
    @include('sales.stability_orders.generateBranchWiseSTFReportCriteriaPopup')
    <!--/Generate Stability Test Format Report Popup-->
	
    <!----- include state tree view------->
    <!--include('sales.stability_orders.editStateCityTreeViewPopup')-->
    <!----- include state tree view------->
	 
    <!----- include country tree view------->
    @include('sales.stability_orders.editCountryStateTreeViewPopup')
    <!----- include country tree view------->
	 
    <!--category Tree-->
    @include('sales.stability_orders.productCategoryTreePopup')
    <!--/category Tree-->	
</div>

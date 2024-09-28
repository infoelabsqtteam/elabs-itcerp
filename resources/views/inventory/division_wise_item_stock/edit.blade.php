<div class="row" ng-hide="editItemStockFormDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Edit Store : <span ng-bind="editBranchWiseStore.store_name"></span></strong></span>
                </div>            
                <div role="new" class="navbar-form navbar-right">
                    <div style="margin: -4px; padding-right: 9px;">
                        <button ng-click="backButton();" class="btn btn-primary btn-sm">Back</button>
                    </div>
                </div>
            </div>               
            <form name="erpEditBranchWiseItemStockForm" id="erpEditBranchWiseItemStockForm" method="POST" novalidate>
                <div class="row">
				
					<!--Division for admin -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-2 form-group">
                        <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                        <select
							class="form-control"
                            name="division_id"
                            id="division_id"
                            ng-model="editBranchWiseItemStock.divisions.selectedOption"
                            ng-options="division.name for division in divisionsCodeList track by division.division_id"
							ng-change="funGetBranchWiseStores(editBranchWiseItemStock.divisions.division_id)">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpEditBranchWiseItemStockForm.item_unit.$error" ng-if='erpEditBranchWiseItemStockForm.item_unit.$dirty  || erpEditBranchWiseItemStockForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
					<!--/Division for admin -->
					
                    <!--Store Name for admin-->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-2 form-group">
                        <label for="store_id">Store Name<em class="asteriskRed">*</em></label>
						<select
							class="form-control"
                            name="store_id"
                            id="store_id"
                            ng-model="editBranchWiseItemStock.store_id.selectedOption"
                            ng-options="storeData.store_name for storeData in storeDataList track by storeData.store_id"
							ng-required='true'>
                            <option value="">Select Store Name</option>
                        </select>
                        <span ng-messages="erpEditBranchWiseItemStockForm.store_id.$error" ng-if="erpEditBranchWiseItemStockForm.store_id.$dirty || erpEditBranchWiseItemStockForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Store name is required</span>
                        </span>
                    </div>
                    <!--/Store Name for admin-->					
					
					<!--Division for Employee -->
                    <div ng-if="{{$division_id}} > 0">
                        <input type="hidden" value="{{$division_id}}" name="division_id" ng-model="editBranchWiseItemStock.division_id" id="division_id">
                    </div>
                    <!--/Division for Employee-->
					
					<!--Store Name for Employee-->
                    <div ng-if="{{$division_id}} > 0" class="col-xs-2 form-group">
                        <label for="store_id">Store Name<em class="asteriskRed">*</em></label>
						<select
							ng-init="funGetBranchWiseStores({{$division_id}})"
							class="form-control"
                            name="store_id"
                            id="store_id"
                            ng-model="editBranchWiseItemStock.store_id.selectedOption"
                            ng-options="storeData.store_name for storeData in storeDataList track by storeData.store_id"
							ng-required='true'>
                            <option value="">Select Store Name</option>
                        </select>
                        <span ng-messages="erpEditBranchWiseItemStockForm.store_id.$error" ng-if="erpEditBranchWiseItemStockForm.store_id.$dirty || erpEditBranchWiseItemStockForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Store name is required</span>
                        </span>
                    </div>
                    <!--/Store Name for Employee-->
					
					<!--Item Name-->
                    <div class="col-xs-2 form-group">
                        <label for="item_id">Item Name<em class="asteriskRed">*</em></label>
						<select							
							class="form-control"
                            name="item_id"
                            id="item_id"
                            ng-model="editBranchWiseItemStock.item_id.selectedOption"
                            ng-options="itemData.item_name for itemData in itemDataList track by itemData.item_id"
							ng-required='true'>
                            <option value="">Select Item Name</option>
                        </select>
                        <span ng-messages="erpEditBranchWiseItemStockForm.item_id.$error" ng-if="erpEditBranchWiseItemStockForm.item_id.$dirty  || erpEditBranchWiseItemStockForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Item name is required</span>
                        </span>
                    </div>
                    <!--/Item Name-->
					
					<!--Openning Balance-->
                    <div class="col-xs-2 form-group">
                        <label for="openning_balance">Openning Balance<em class="asteriskRed">*</em></label>						   
                        <input type="text"
							class="form-control" 
                            ng-model="editBranchWiseItemStock.openning_balance"
                            name="openning_balance" 
                            id="openning_balance"
                            ng-required='true'
                            placeholder="Openning Balance" />
                        <span ng-messages="erpEditBranchWiseItemStockForm.openning_balance.$error" ng-if="erpEditBranchWiseItemStockForm.openning_balance.$dirty  || erpEditBranchWiseItemStockForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Openning Balance is required</span>
                        </span>
                    </div>
                    <!--/Openning Balance-->
					
					<!-- Openning Balance date.-->
                    <div class="col-xs-2 form-group">
                        <label for="order_date">Openning Balance Date<em class="asteriskRed">*</em></label>
                        <div class="input-group date" data-provide="datepicker">
                            <input readonly type="text"
								id="openning_balance_date"
								ng-model="editBranchWiseItemStock.openning_balance_date"							
								name="openning_balance_date"
								class="form-control bgWhite"
								placeholder="Openning Balance Date"
								ng-required="true">
							<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                        </div>
						<span ng-messages="erpEditBranchWiseItemStockForm.openning_balance_date.$error" ng-if="erpEditBranchWiseItemStockForm.openning_balance_date.$dirty || erpEditBranchWiseItemStockForm.$submitted" role="alert">
							<span ng-message="required" class="error">Openning Balance date is required</span>
						</span>
                    </div>
                    <!-- /Openning Balance date.-->
					                    
                    <!--Update Button-->
                    <div class="col-xs-2 form-group text-right mT20">
						<label for="submit">{{ csrf_field() }}</label>
                        <input type="hidden" ng-model="editBranchWiseItemStock.division_wise_item_stock_id" name="division_wise_item_stock_id" ng-value="editBranchWiseItemStock.division_wise_item_stock_id" id="store_id">
						<button type="submit" ng-disabled="erpEditBranchWiseItemStockForm.$invalid" class="btn btn-primary" ng-click="funUpdateBranchWiseItemStock(divisionID)">Update</button>
						<button type="button" class="btn btn-default" ng-click="backButton()">Back</button>
                    </div>
                    <!--Update Button-->
                        
                </div>                    
            </form>
        </div>
    </div>
</div>
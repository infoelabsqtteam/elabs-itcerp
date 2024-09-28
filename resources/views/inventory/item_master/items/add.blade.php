<div class="row mT25" ng-hide="addItemFormDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Add New Item</strong></span>
                </div>            
                <div role="new" class="navbar-form navbar-right">
                    <div style="margin: -5px; padding-right: 9px;">
                        <button ng-click="navigateItemPage();" class="btn btn-default btn-sm">Back</button>
                    </div>
                </div>
            </div>               
            <form name="erpAddItemForm" id="erpAddItemForm" method="post" enctype="multipart/form-data" novalidate>
                <div class="row">
                    
                    <!--Item Category-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_cat_id">Item Category<em class="asteriskRed">*</em></label>						   
                        <select class="form-control"
                            name="item_cat_id"
                            id="item_cat_id"
                            ng-model="itemMaster.item_cat_id"
                            ng-options="item.name for item in itemCategoryList track by item.id">
                            <option value="">Select item category</option>
                        </select>
                        <span ng-messages="erpAddItemForm.item_cat_id.$error" ng-if='erpAddItemForm.item_cat_id.$dirty  || erpAddItemForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Item category code is required</span>
                        </span>
                    </div>
                    <!--/Item Category-->
                    
                    <!--Item Code-->
                    <div class="col-xs-6 form-group view-record">
						<span class="generate"><a href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
                        <label for="item_code">Item Code<em class="asteriskRed">*</em></label>						   
                        <input type="text"
                            class="form-control" readonly
							ng-model="item_code"
							ng-bind="item_code"
                            name="item_code" 
                            id="item_code"
                            ng-required='true'
                            placeholder="Item Code" />
                        <span ng-messages="erpAddItemForm.item_code.$error" 
                        ng-if='erpAddItemForm.item_code.$dirty  || erpAddItemForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Item code is required</span>
                        </span>
                    </div>
                    <!--/Item Code-->
                    
                    <!--Item Name-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_name">Item Name<em class="asteriskRed">*</em></label>						   
                        <input type="text" class="form-control" 
                            ng-model="itemMaster.item_name"
                            name="item_name" 
                            id="item_name"
                            ng-required='true'
                            placeholder="Item Name" />
                        <span ng-messages="erpAddItemForm.item_name.$error" 
                        ng-if='erpAddItemForm.item_name.$dirty  || erpAddItemForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Item name is required</span>
                        </span>
                    </div>
                    <!--/Item Name-->
                    
                    <!--Item Unit -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_unit">Item Unit<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="item_unit"
                            id="item_unit"
                            ng-model="itemMaster.item_unit"
                            ng-options="units.unit_name for units in itemUnitList track by units.unit_id">
                            <option value="">Select item Unit</option>
                        </select>
                        <span ng-messages="erpAddItemForm.item_unit.$error" ng-if='erpAddItemForm.item_unit.$dirty  || erpAddItemForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Item Unit is required</span>
                        </span>
                    </div>
                    <!--/Item Unit -->
                    
                    <!--Item Barcode -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_barcode">Item Barcode<em class="asteriskRed">*</em></label>						   
                        <input type="text"
                            class="form-control" 
                            ng-model="itemMaster.item_barcode"
                            name="item_barcode" 
                            id="item_barcode"
                            ng-required='true'
                            placeholder="Item Barcode" />
                        <span ng-messages="erpAddItemForm.item_barcode.$error" 
                        ng-if='erpAddItemForm.item_barcode.$dirty  || erpAddItemForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Item barcode is required</span>
                        </span>
                    </div>
                    <!--/Item Barcode -->
                    
                    <!--Item Description -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_description">Item Description<em class="asteriskRed">*</em></label>
                        <textarea rows="2" type="text" class="form-control" 
                            ng-model="itemMaster.item_description" 
                            name="item_description" 
                            id="item_description"
                            ng-required='true'
                            placeholder="Item Description" /></textarea>
                        <span ng-messages="erpAddItemForm.item_description.$error" 
                         ng-if='erpAddItemForm.item_description.$dirty  || erpAddItemForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Item description is required</span>
                        </span>
                    </div>
                    <!--/Item Description -->
                    
                    <!--Item Long Description -->   
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_long_description">Long Description</label>
                        <textarea rows="2"
                            class="form-control" 
                            ng-model="itemMaster.item_long_description" 
                            name="item_long_description" 
                            id="item_long_description"
                            placeholder="Long Description" />
                        </textarea>				
                    </div>
                    <!--/Item Long Description -->
                    
                    <!--Item Technical Description -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_technical_description">Technical Description</label>
                        <textarea rows="2"
                            class="form-control" 
                            ng-model="itemMaster.item_technical_description"  
                            name="item_technical_description" 
                            id="item_technical_description"
                            placeholder="Technical Description" />
                        </textarea>
                    </div>
                    <!--/Item Technical Description -->
                    
                    <!--Item Specification Description -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_specification">Item Specification</label>
                        <textarea rows="2"
                            class="form-control" 
                            ng-model="itemMaster.item_specification" 
                            name="item_specification" 
                            id="item_specification"
                            placeholder="Item Specification" />
                        </textarea>
                    </div>
                    <!--/Item Specification Description -->
                    
                    <!--Item Shelf Life Days-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="shelf_life_days">Item Shelf Life(Days)</label>
                            <input type="text"
                            class="form-control" 
                            ng-model="itemMaster.shelf_life_days" 
                            name="shelf_life_days" 
                            id="shelf_life_days"
                            placeholder="Shelf Life Days" />
                    </div>
                    <!--/Item Shelf Life Days-->
                    
                    <!--Perishable-->
                    <div class="col-xs-6 form-group view-record">
                        <input type="checkbox"
                            ng-model="itemMaster.is_perishable"  
                            name="is_perishable" 
                            id="is_perishable"
                            placeholder="Is Perishable" />
                        <label for="is_perishable">Perishable</label>                        
                    </div>
                    <!--/Perishable-->
					                    
                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right">
						<label for="submit">{{ csrf_field() }}</label>
						<button type="submit" ng-disabled="erpAddItemForm.$invalid" class="btn btn-primary" ng-click="addItem()">Save</button>
						<button  type='button' id='reset_button' class=' mT26 btn btn-default' ng-click='resetForm()' title="Reset"> Reset </button>
                    </div>
                    <!--Save Button-->
                        
                </div>                    
            </form>
        </div>
    </div>
</div>    
<div class="row" ng-hide="editItemFormDiv">
    <div class="panel panel-default">    
        <div class="panel-body">        
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Edit Item : [[itemName]]</strong></span>
                </div>            
               <!-- <div role="new" class="navbar-form navbar-right">
                    <div style="margin: -5px; padding-right: 9px;">
                        <button ng-click="navigateItemPage();" class="btn btn-primary btn-sm">Back</button>
                    </div>
                </div>-->
            </div>
            <form name="erpEditItemForm" id="erpEditItemForm" novalidate>                
                <div class="row">
                
                    <!--Item Category-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_cat_id">Item Category<em class="asteriskRed">*</em></label>						   
                        <select class="form-control"
                            name="item_cat_id"
                            id="item_cat_id"
                            ng-model="itemMasterEdit.item_cat_id.selectedOption"
                            ng-options="item.name for item in itemCategoryList track by item.id">
                            <option value="">Select item category</option>
                        </select>
                        <span ng-messages="erpEditItemForm.item_cat_id.$error" ng-if='erpEditItemForm.item_cat_id.$dirty  || erpEditItemForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Item category code is required</span>
                        </span>
                    </div>
                    <!--/Item Category-->
                    
                    <!--Item Code-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_code">Item Code</label>						   
                        <input readonly type="text"
                            class="form-control bgWhite" 
                            ng-model="itemMasterEdit.item_code"
                            name="item_code" 
                            id="item_code"
                            ng-required='true'
                            placeholder="Item Code" />
                        <span ng-messages="erpEditItemForm.item_code.$error" ng-if='erpEditItemForm.item_code.$dirty  || erpEditItemForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Item code is required</span>
                        </span>
                    </div>
                    <!--/Item Code-->
                    
                    <!--Item Name-->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_name">Item Name<em class="asteriskRed">*</em></label>						   
                        <input type="text" class="form-control" 
                            ng-model="itemMasterEdit.item_name"
                            name="item_name" 
                            id="item_name"
                            ng-required='true'
                            placeholder="Item Name" />
                        <span ng-messages="erpEditItemForm.item_name.$error" ng-if='erpEditItemForm.item_name.$dirty  || erpEditItemForm.$submitted' role="alert">
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
                            ng-model="itemMasterEdit.item_unit.selectedOption"
                            ng-options="units.unit_name for units in itemUnitList track by units.unit_id">
                            <option value="">Select Item Unit</option>
                        </select>
                        <span ng-messages="erpEditItemForm.item_unit.$error" ng-if='erpEditItemForm.item_unit.$dirty  || erpEditItemForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Item Unit is required</span>
                        </span>
                    </div>
                    <!--/Item Unit -->
                    
                    <!--Item Barcode -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_barcode">Item Barcode<em class="asteriskRed">*</em></label>						   
                        <input type="text"
                            class="form-control" 
                            ng-model="itemMasterEdit.item_barcode"
                            name="item_barcode" 
                            id="item_barcode"
                            ng-required='true'
                            placeholder="Item Barcode" />
                        <span ng-messages="erpEditItemForm.item_barcode.$error" 
                        ng-if='erpEditItemForm.item_barcode.$dirty  || erpEditItemForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Item barcode is required</span>
                        </span>
                    </div>
                    <!--/Item Barcode -->
                    
                    <!--Item Description -->
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_description">Item Description<em class="asteriskRed">*</em></label>
                        <textarea rows="2" type="text" class="form-control" 
                            ng-model="itemMasterEdit.item_description" 
                            name="item_description" 
                            id="item_description"
                            ng-required='true'
                            placeholder="Item Description" /></textarea>
                        <span ng-messages="erpEditItemForm.item_description.$error" 
                         ng-if='erpEditItemForm.item_description.$dirty  || erpEditItemForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Item description is required</span>
                        </span>
                    </div>
                    <!--/Item Description -->
                    
                    <!--Item Long Description -->   
                    <div class="col-xs-6 form-group view-record">
                        <label for="item_long_description">Long Description</label>
                        <textarea rows="2"
                            class="form-control" 
                            ng-model="itemMasterEdit.item_long_description" 
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
                            ng-model="itemMasterEdit.item_technical_description"  
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
                            ng-model="itemMasterEdit.item_specification" 
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
                            ng-model="itemMasterEdit.shelf_life_days" 
                            name="shelf_life_days" 
                            id="shelf_life_days"
                            placeholder="Shelf Life Days" />
                    </div>
                    <!--/Item Shelf Life Days-->
                    
                    <!--Perishable-->
                    <div class="col-xs-6 form-group view-record">
                        <input type="checkbox"
                            ng-model="itemMasterEdit.is_perishable"  
                            name="is_perishable" 
                            id="is_perishable"
                            placeholder="Is Perishable"
                            ng-checked="itemMasterEdit.is_perishable == 1" />
                        <label for="is_perishable">Perishable</label>                        
                    </div>
                    <!--/Perishable-->
                    
                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right">
                        <label for="submit">{{ csrf_field() }}</label>
                        <input type="hidden" name="item_id" ng-model="itemMasterEdit.item_id" ng-value="itemMasterEdit.item_id">
                        <button type="submit" ng-disabled="erpEditItemForm.$invalid" class="btn btn-primary" ng-click="funUpdateItem()">Update</button>
                        <button type="button" ng-click="navigateItemPage();" title="Close" class="btn btn-default">Close</button>

                    </div>
                    <!--Save Button-->
                </div>
            </form>
        </div>
    </div>
</div>
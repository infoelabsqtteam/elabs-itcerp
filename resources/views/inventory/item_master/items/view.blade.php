<div class="row" ng-hide="viewItemFormDiv">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">View Item : [[itemName]]</strong></span>
                </div>            
                <div role="new" class="navbar-form navbar-right">
                    <div style="margin: -5px; padding-right: 9px;">
                        <button ng-click="navigateItemPage();" class="btn btn-primary btn-sm">Back</button>
                    </div>
                </div>
            </div>
            <form name="erpEditItemForm" id="erpEditItemForm" novalidate>                
                <div class="row">
                
                    <div class="col-xs-12 form-group view-record">                        
                        <img ng-if="[[ItemImage]] != ''" id="item_image_[[itemId]]" class="img-thumbnail" title="[[itemName]]" alt="[[itemName]]" ng-src="{{url('/public/images/items')}}/[[itemId]]/[[ItemImage]]">
                        <img ng-if="[[ItemImage]] == ''" id="item_image_[[itemId]]" class="img-thumbnail" title="[[itemName]]" alt="[[itemName]]" ng-src="{{url('/public/images/default-item.jpeg')}}">
                    </div>
                    
                    <div class="col-xs-6 form-group view-record">
                        <span for="item_cat_id"><span class="leftspan">Item Category : </span>[[itemCategory ? itemCategory : '-']]</span>
                    </div>
                        
                    <div class="col-xs-6 form-group view-record">
                        <span for="item_code"><span class="leftspan">Item Code : </span>[[itemCode ? itemCode : '-']]</span>
                    </div>
                        
                    <div class="col-xs-6 form-group view-record">
                        <span for="item_name"><span class="leftspan">Item Name : </span>[[itemName ? itemName : '-']]</span>
                    </div>
                        
                    <div class="col-xs-6 form-group view-record">
                        <span for="item_unit"><span class="leftspan">Item Unit: </span>[[itemUnit ? itemUnit : '-']]</span>
                    </div>
                        
                    <div class="col-xs-6 form-group view-record">              
                        <span for="item_barcode"><span class="leftspan">Item Barcode : </span>[[itemBarcode ? itemBarcode : '-']]</span>
                    </div>
                        
                    <div class="col-xs-6 form-group view-record">
                        <span for="item_description"><span class="leftspan">Description: </span>
						<span id="itemlimitedText-[[itemId]]">
							[[ itemDescription | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
							<a href="javascript:;" ng-click="toggleDescription('item',itemId)" ng-show="itemDescription.length > 150" class="readMore">read more...</a>
						</span>
						<span id="itemfullText-[[itemId]]" style="display:none;" >
							[[ itemDescription]] 
							<a href="javascript:;" ng-click="toggleDescription('item',itemId)" class="readMore">read less...</a>
						</span>
                    </div>
                        
                    <div class="col-xs-6 form-group view-record">
                        <span for="item_long_description"><span class="leftspan">Long Description: </span>[[itemLongDescription ? itemLongDescription : '-']]</span>			
                    </div>
                        
                    <div class="col-xs-6 form-group view-record">
                        <span for="item_long_description"><span class="leftspan">Technical Description: </span>[[itemTechDescription ? itemTechDescription : '-']]</span>
                    </div>
                        
                    <div class="col-xs-6 form-group view-record">
                        <span for="item_specification"><span class="leftspan">Item Specification: </span>[[itemSpecification ? itemSpecification : '-']]</span>
                    </div>                 
                        
                    <div class="col-xs-6 form-group view-record">
                        <span for="shelf_life_days"><span class="leftspan">Item Shelf Life Days: </span>[[itemShelfLifeDays ? itemShelfLifeDays : '-']]</span>
                    </div>
                    
                    <div class="col-xs-6 form-group view-record">
                        <span for="item_unit"><span class="leftspan">Perishable: </span>[[itemPerishable ? itemPerishable : '-']]</span>                        
                    </div>
                        
                    <div class="col-xs-6 form-group view-record">
                        <span for="shelf_life_days"><span class="leftspan">Item Added On: </span>[[itemCreatedAt ? itemCreatedAt : '-']]</span>
                    </div>
                    
                    <div class="col-xs-12 form-group text-right">
                        <label for="submit">{{ csrf_field() }}</label>
                        <button title="Edit" class="btn btn-primary btn-sm" ng-click="funEditItem(itemId)">Edit Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
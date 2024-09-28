<div class="row" style="background: #ccc;padding: 5;margin: 0;width:100%;">Customer Detail</div>

<div class="row mT10">	
    
    <!--Customer Code-->
    <div class="col-xs-3 form-group">
        <label for="product_id">Customer</label> : <span ng-bind="viewOrder.customer_name"></span>        
    </div>
    <!--/Customer Code-->
    
    <!--Customer Location-->
    <div class="col-xs-3 form-group">
        <label for="customer_location_id">Customer Location</label> : <span ng-bind="viewOrder.city_name"></span>            
    </div>
    <!--Customer Location-->
    
    <!--Customer MFG LIC Number-->
    <div class="col-xs-3 form-group">
        <label for="mfg_lic_no">Mfg. Lic No.</label> : <span ng-bind="viewOrder.mfg_lic_no"></span>
    </div>
    <!--/Customer MFG LIC Number-->	
    
    <!--Sale Executive-->
    <div class="col-xs-3 form-group">
        <label for="sale_executive">Sale Executive</label> : <span ng-bind="viewOrder.sale_executive_name"></span>
    </div>
    <!--/Sale Executive-->
</div>

<div class="row"> 
    
    <!--Discount Type-->
    <div class="col-xs-3 form-group">
        <label for="discount_type">Discount Type</label> : <span ng-bind="viewOrder.discount_type"></span>        
    </div>
    <!--/Discount Type-->
    
    <!--Discount Percentage-->
    <div class="col-xs-3 form-group">
        <label for="discount_percentage">Discount Percentage</label> : <span ng-bind="viewOrder.discount_value"></span>        
    </div>
    <!--/Discount Percentage-->
    
</div>
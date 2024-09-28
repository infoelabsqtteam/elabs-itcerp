<div class="row order-section">Customer Detail</div>

<div class="row mT10">	
    
    <!--Customer Code-->
    <div class="col-xs-3 form-group">
        <label for="customer_id">Customer</label> : <span ng-bind="viewOrderReport.customer_name"></span>        
    </div>
    <!--/Customer Code-->
    
    <!--Customer Location-->
    <div class="col-xs-3 form-group">
        <label for="customer_location_id">Customer Location</label> : <span ng-bind="viewOrderReport.city_name"></span>            
    </div>
    <!--Customer Location-->
    
    <!--Customer MFG LIC Number-->
    <div class="col-xs-3 form-group">
        <label for="mfg_lic_no">Mfg. Lic No.</label> : <span ng-bind="viewOrderReport.mfg_lic_no"></span>
    </div>
    <!--/Customer MFG LIC Number-->	
    
    <!--Sale Executive-->
    <div class="col-xs-3 form-group">
        <label for="sale_executive">Sale Executive</label> : <span ng-bind="viewOrderReport.sale_executive_name"></span>
    </div>
    <!--/Sale Executive-->
</div>

<div class="row"> 
    
    <!--Discount Type-->
    <div class="col-xs-3 form-group">
        <label for="discount_type">Discount Type</label> : <span ng-bind="viewOrderReport.discount_type"></span>        
    </div>
    <!--/Discount Type-->
    
    <!--Discount Percentage-->
    <div class="col-xs-3 form-group">
        <label for="discount_percentage">Discount Percentage</label> : <span ng-bind="viewOrderReport.discount_value"></span>        
    </div>
    <!--/Discount Percentage-->
    
     <!--Discount Percentage-->
    <div class="col-xs-6 form-group">
        <label for="invoicing_type">Invoicing Type</label> : <span ng-bind="viewOrderReport.invoicing_type">[[viewOrderReport.invoicing_type_id]]</span>        
    </div>
    <!--/Discount Percentage-->
   
</div>
<div  style="background: #ccc;padding: 5;margin: 0;width:100%;"><strong>Customer Detail</strong></div>
    <div class="row mT10">	
        
        <!--Customer Code-->
        <div class="col-xs-4 form-group">
            <label for="customer_id">Customer</label> : <span ng-bind="printOrderReport.customer_name"></span>        
        </div>
        <!--/Customer Code-->
        
        <!--Customer Location-->
        <div class="col-xs-4 form-group">
            <label for="customer_location_id">Customer Location</label> : <span ng-bind="printOrderReport.city_name"></span>            
        </div>
        <!--Customer Location-->
        
        <!--Customer MFG LIC Number-->
        <div class="col-xs-4 form-group">
            <label for="mfg_lic_no">Mfg. Lic No.</label> : <span ng-bind="printOrderReport.mfg_lic_no"></span>
        </div>
        <!--/Customer MFG LIC Number-->	
    </div>

    <div class="row"> 
         <!--Sale Executive-->
        <div class="col-xs-4 form-group">
            <label for="sale_executive">Sale Executive</label> : <span ng-bind="printOrderReport.sale_executive_name"></span>
        </div>
        <!--/Sale Executive-->
        <!--Discount Type-->
        <div class="col-xs-4 form-group">
            <label for="discount_type">Discount Type</label> : <span ng-bind="printOrderReport.discount_type"></span>        
        </div>
        <!--/Discount Type-->
        
        <!--Discount Percentage-->
        <div class="col-xs-4 form-group">
            <label for="discount_percentage">Discount Percentage</label> : <span ng-bind="printOrderReport.discount_value"></span>        
        </div>
        <!--/Discount Percentage-->
        
    </div>
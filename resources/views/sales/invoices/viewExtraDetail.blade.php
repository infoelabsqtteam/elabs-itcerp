<div class="row order-section" ng-if="viewOrderReport.extraDetail"> Extra Detail </div>

<div class="order_detail" ng-if="viewOrderReport.extraDetail">
    <div class="row">
        
        <!--Hold Detail-->
        <div class="col-xs-12 mT10" ng-if="viewOrderReport.hold_order">
            <div class="col-xs-4 form-group">
                <label for="edit_hold_type"><strong>Hold Detail:</strong></label>
            </div>
            <div class="col-xs-8 form-group" ng-show="orderHoldEditFlag">
                <label>[[viewOrderReport.hold_reason ? viewOrderReport.hold_reason : '']]</label>
            </div>
        </div>
        <!--/Hold Detail-->
        
        <!--PO Detail-->
        <div class="col-xs-12" ng-if="viewOrderReport.po_no || viewOrderReport.po_date">
            <div class="col-xs-4 form-group">
                <label for="po_no">PO No.&nbsp; : &nbsp;</label>
                <span> [[viewOrderReport.po_no ? viewOrderReport.po_no : '']]</span>
            </div>
            <div class="col-xs-4 form-group">
                <label for="po_date">PO Date &nbsp;: &nbsp;</label><span class="">[[viewOrderReport.po_date ? viewOrderReport.po_date : '' ]]</span>
            </div>   
        </div>
        <!--/PO Detail-->
        
        <!--Sample Type-->
        <div class="col-xs-12" ng-if="viewOrderReport.order_sample_type">
            <div class="col-xs-4 form-group">
                <label for="sample_type"><strong>Sample Type Detail:</strong></label>
            </div>
            <div class="col-xs-8 form-group" >
                <label for="order_sample_type">
                    <span ng-if="viewOrderReport.order_sample_type == 1"></span>&nbsp;Inter Laboratory
                    <span ng-if="viewOrderReport.order_sample_type == 2"></span>&nbsp;Compensatory
                </label>
            </div>   
        </div>
        <!--/Sample Type-->
        
        <!--Different Reporting & Invoicing Needed-->
        <div class="col-xs-12 form-group mT10" ng-if ="viewOrderReport.reportingCustomerName || viewOrderReport.invoicingCustomerName" >
            <div class="col-xs-4 form-group">                    
                <label for="invoicing_needed">Different Invoicing & Reporting Address :</label>
            </div>
            <div class="col-xs-8">
                <div class="col-xs-6 form-group" style="margin-left: -7px;">
                    <label for="reporting_to">Reporting To :&nbsp;</label>
                    <span>[[viewOrderReport.reportingCustomerName ? viewOrderReport.reportingCustomerName : '']]</span>
                </div>
                <div class="col-xs-6 form-group">
                    <label for="invoicing_to">Invoicing To :&nbsp;</label>
                    <span>[[viewOrderReport.invoicingCustomerName ? viewOrderReport.invoicingCustomerName : '']]</span>
                </div>               
            </div>   
        </div>
        <!--/Different Reporting & Invoicing Needed-->
        
    </div>
</div>

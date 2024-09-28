<div class="row" ng-hide="IsEditInvoiceDetail">

    <!--erpFilterInvoicesForm-->
    <form class="form-inline" method="POST" role="form" id="erpEditInvoicesForm" name="erpEditInvoicesForm" novalidate>

        <!--Invoice Header-->
        <div class="col-xs-12 pdng-20">
            <div class="text-right"><button type="button" class="btn btn-primary" ng-click="backButton([[backTypeValue]])">Back</button></div>
            <div class="col-sm-12 col-xs-12 header-content" ng-bind-html="headerContent"></div>
        </div>
        <!--/Invoice Header-->

        <!--Invoice Body-->
        <div class="col-xs-12 mT10">
            <div class="col-md-12" ng-if="invoiceTemplateTypeEditDiv == '1'">
                @include('sales.invoices.editInvoiceWithoutStateWise')
            </div>
        </div>
        <!--/Invoice Body-->

        <!--Invoice Footer-->
        <div class="col-xs-12 form-group pT30 pB30">
            <input type="hidden" name="invoice_id" ng-value="invoiceId" ng-model="invoiceId">
            <button type="button" ng-disabled="erpEditInvoicesForm.$invalid" class="btn btn-primary pull-right" ng-click="funConfirmUpdateInvoiceDetail([[invoiceId]],1,1)">Update</button>
            <button type="button" class="invisible pull-right">&nbsp;</button>
            <button type="button" ng-click="funSelectViewInvoiceType(invoiceId,invoiceNo,1)" title="View Invoice" class="btn btn-default btn-sm pull-right">View Invoice</button>
            <button type="button" class="invisible pull-right">&nbsp;</button>
            <button type="button" class="btn btn-default pull-right" ng-click="backButton([[backTypeValue]])">Back</button>
        </div>
        <!--/Invoice Footer-->

    </form>
    <!--/erpFilterInvoicesForm-->
</div>

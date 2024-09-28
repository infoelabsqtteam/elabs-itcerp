<div id="isViewInvoiceReport" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width:1200px!important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Order No. :<span ng-bind="viewOrderReport.order_no"></span><span ng-if="viewOrderReport.order_reinvoiced_count">(Re-Invoicing)</span></h4>
            </div>
            <div class="modal-body custom-nr-scroll">
                <form method="POST" role="form" id="erpViewTestParamResultReportForm" name="erpViewTestParamResultReportForm" novalidate>

                    <!--add customer form-->
                    @include('sales.invoices.viewCustomer')
                    <!--add customer form-->

                    <!--add sample form-->
                    @include('sales.invoices.viewSample')
                    <!--add sample form-->

                    <!--view order extra detail -->
                    @include('sales.invoices.viewExtraDetail')
                    <!--/view order extra detail-->

                    <!--add sample form-->
                    @include('sales.invoices.viewSampleParameters')
                    <!--add sample form-->

                    <!--Back Button-->
                    <div class="text-right">
                        <label for="submit">{{ csrf_field() }}</label>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                    <!--Back Button-->

                </form>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-hide="IsViewInvoiceOrders">
    
    <form method="POST" role="form" id="generateInvoiceForm" name="generateInvoiceForm" novalidate>
        
        <!--header-->
        <div class="header">        
            <div role="new" class="navbar-form navbar-left width50">            
                <strong id="form_title">Reports for Invoicing<span ng-if="billingTypeOrderList.length">([[billingTypeOrderList.length]])</span></strong>
                <strong class="txt-right font10">(Note : Bold Order No. indicates Re-Invoiced Orders)</strong>
            </div>            
            <div role="new" class="navbar-form navbar-right">
                <div class="custom-row-inner">
                    <input type="text" placeholder="Search" ng-model="searchInvoiceOrders" class="form-control ng-pristine ng-untouched ng-valid">
                </div>
            </div>
        </div>
        <!--/header-->
        
        <!--order Listing-->
        <div id="no-more-tables">
            <table class="col-sm-12 table-striped table-condensed cf">
                <thead class="cf">
                    <tr>
                        <th>
                            <label ng-click="sortBy('order_no')" class="sortlabel">S.No.</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_no'" class="sortorder reverse"></span>
                        </th>
                        <th>
                            <label ng-if="hasGenerateInvoiceButton == 1 && billingTypeOrderList.length" ng-click="sortBy('order_no')" class="sortlabel checkbox width10"><input type="checkbox" ng-model="selectedAll" id="selectedAll" ng-click="toggleAll()">All</label>
                        </th>
                        <th>
                            <label ng-click="sortBy('order_no')" class="sortlabel">Order No.</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_no'" class="sortorder reverse"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('report_no')" class="sortlabel">Report No.</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'report_no'" class="sortorder reverse"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('order_date')" class="sortlabel">Order Date</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_date'" class="sortorder reverse ng-hide"></span>
                        </th>                                                              
                        <th>
                            <label ng-click="sortBy('report_date')" class="sortlabel">Report Date</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'report_date'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('batch_no')" class="sortlabel">Batch No.</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'batch_no'" class="sortorder reverse"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('defined_test_standard_name')" class="sortlabel">Analysis Type</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'defined_test_standard_name'" class="sortorder reverse"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('order_reinvoiced_count')" class="sortlabel">Re-Generation Count</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_reinvoiced_count'" class="sortorder reverse"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('olpd_cpo_no')" class="sortlabel">Connected PO No.</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'olpd_cpo_no'" class="sortorder reverse"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('invocingto_customer_name')" class="sortlabel">Invoiced To</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'invocingto_customer_name'" class="sortorder reverse"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('discount_type')" class="sortlabel">Discount Type</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'discount_type'" class="sortorder reverse"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('discount_value')" class="sortlabel">Discount Value</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'discount_value'" class="sortorder reverse"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('sample_description')" class="sortlabel">Sample Description</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'sample_description'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('cgtst_name')" class="sortlabel">GST</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'cgtst_name'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('totalAmount')" class="sortlabel">Total Amount</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'totalAmount'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('netDiscount')" class="sortlabel">Net Discount</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'netDiscount'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('extra_amount')" class="sortlabel">Extra Amount</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'extra_amount'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('netAmount')" class="sortlabel">Net Amount</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'netAmount'" class="sortorder reverse ng-hide"></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="billingTypeOrderListObj in billingTypeOrderList | filter:searchInvoiceOrders track by $index">
                        <td data-title="Serial No." class="ng-binding">[[$index + 1]]</td>
                        <td data-title="OrderNo" class="ng-binding"><input style="width: 20px!important;" class="orderCheckBox" type="checkbox" ng-model="selected" id="invoicing_orders[[billingTypeOrderListObj.order_id]]" name="order_id[]" ng-value="billingTypeOrderListObj.order_id" ng-click="funCheckAtLeastOneIsChecked(billingTypeOrderListObj.order_id)" ng-if="billingTypeOrderListObj.netAmount !='0.00'"></td>
                        <td data-title="OrderNo" class="ng-binding [[billingTypeOrderListObj.order_reinvoiced_class]]"><a href="javascript:;" title="Order Detail" ng-click="funShowReport(billingTypeOrderListObj.order_id)">[[billingTypeOrderListObj.order_no]]</a></td>
                        <td data-title="ReportNo" class="ng-binding">[[billingTypeOrderListObj.report_no]]</td>
                        <td data-title="Order Date" class="ng-binding">[[billingTypeOrderListObj.order_date | date : 'dd/MM/yyyy']] </td>
                        <td data-title="Order Date" class="ng-binding">[[billingTypeOrderListObj.report_date | date : 'dd/MM/yyyy']]</td>
                        <td data-title="ReportNo" class="ng-binding">[[billingTypeOrderListObj.batch_no]]</td>
                        <td data-title="Defined Test Standard Name" class="ng-binding">[[billingTypeOrderListObj.defined_test_standard_name]]</td>
                        <td data-title="Reinvoiced Count" class="ng-binding">[[billingTypeOrderListObj.order_reinvoiced_count]]</td>
                        <td data-title="Reinvoiced Count" class="ng-binding"><a href="[[billingTypeOrderListObj.olpd_cpo_file_link]]" title="Connected PO Detail" target="_blank" ng-bind="billingTypeOrderListObj.olpd_cpo_no"></a></td>
                        <td data-title="ReportNo" class="ng-binding">[[billingTypeOrderListObj.invocingto_customer_name]]</td>
                        <td data-title="ReportNo" class="ng-binding">[[billingTypeOrderListObj.discount_type]]</td>
                        <td data-title="ReportNo" class="ng-binding">[[billingTypeOrderListObj.discount_value ? billingTypeOrderListObj.discount_value : '-']]</td>
                        <td data-title="Sample Description" class="ng-binding">[[billingTypeOrderListObj.sample_description]]</td>
                        <td data-title="Sample Description" class="ng-binding">[[billingTypeOrderListObj.cgtst_name]]</td>
                        <td data-title="Total Amount" class="ng-binding">[[billingTypeOrderListObj.totalAmount]]</td>
                        <td data-title="Net Discount" class="ng-binding">[[billingTypeOrderListObj.netDiscount]]</td>
                        <td data-title="Net Discount" class="ng-binding">[[billingTypeOrderListObj.extra_amount ? billingTypeOrderListObj.extra_amount : '0.00']]</td>
                        <td data-title="Net Amount" class="ng-binding">[[billingTypeOrderListObj.netAmount]]</td>
                    </tr>
                    <tr ng-show="!billingTypeOrderList.length"><td colspan="19">No report found.</td></tr>
                </tbody>
                <tfoot ng-if="billingTypeOrderList.length">
                    <tr style="text-align:right;">
                        <td colspan="19">
                            <input type="hidden" name="division_id" ng-model="currenDivisionID" ng-value="currenDivisionID">
                            <input type="hidden" name="product_category_id" ng-model="currentProductCategoryID" ng-value="currentProductCategoryID">
                            <input type="hidden" name="billing_type_id" ng-model="currenBillingTypeID" ng-value="currenBillingTypeID">
                            <input type="hidden" name="customer_id" ng-model="currentCustomerID" ng-value="currentCustomerID">
                            <button ng-disabled="generateInvoiceBtn" ng-click="funConfirmInvoiceGenerateMessage('Are you sure you want to generate Invoice?')" class="btn btn-primary btn-sm">Generate Invoice</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!--order Listing-->
    </form>
</div>
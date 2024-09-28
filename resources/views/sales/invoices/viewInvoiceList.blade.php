<div class="row" ng-hide="IsViewInvoicesList">
    
    <!--header-->
    <div class="header">        
        <div role="new" class="navbar-form navbar-left">            
            <div><strong id="form_title">Invoice Listing<span ng-if="customerInvoicesList.length">([[customerInvoicesList.length]])</span></strong></div>            
        </div>            
        <div role="new" class="navbar-form navbar-right">
            <div class="custom-row-inner">
                <input type="text" placeholder="Search" ng-model="searchInvoiceListing" class="form-control ng-pristine ng-untouched ng-valid">
            </div>
        </div>
    </div>
    <!--/header-->
    
    <!--Customer Invoice Listing-->
    <div id="no-more-tables">
        <table class="col-sm-12 table-striped table-condensed cf">
            <thead class="cf">
                <tr>                            
                    <th>
                        <label ng-click="sortBy('invoice_no')" class="sortlabel">Invoice No </label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoice_no'" class="sortorder reverse"></span>
                    </th>
                    <th>
                        <label ng-click="sortBy('customer_id')" class="sortlabel">Customer Name</label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'customer_id'" class="sortorder reverse ng-hide"></span>
                    </th>                                                              
                    <th>
                        <label ng-click="sortBy('net_total_amount')" class="sortlabel">Net Amount</label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'net_total_amount'" class="sortorder reverse ng-hide"></span>
                    </th>
                    <th>
                        <label ng-click="sortBy('invoice_date')" class="sortlabel">Invoice Date </label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoice_date'" class="sortorder reverse ng-hide"></span>
                    </th>  
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="customerInvoicesListObj in customerInvoicesList | filter:searchInvoiceListing | orderBy:predicate:reverse">
                    <td data-title="invoice_no" class="ng-binding">[[customerInvoicesListObj.invoice_no]]</td>                                
                    <td data-title="customer_name" class="ng-binding">[[customerInvoicesListObj.customer_name]]</td>
                    <td data-title="net_total_amount" class="ng-binding">[[customerInvoicesListObj.net_total_amount]]</td>
                    <td data-title="invoice_date" class="ng-binding">[[customerInvoicesListObj.invoice_date | date : 'dd/MM/yyyy']]</td>
                    <td>
                        <button ng-click="funViewInvoice(customerInvoicesListObj.invoice_id,2)" class="btn btn-primary btn-sm">View Invoice</button>
                    </td>                
                </tr>                        
                <tr ng-show="!customerInvoicesList.length"><td colspan="8">No Invoice found.</td></tr>
            </tbody>
        </table>
    </div>
    <!--/Customer Invoice Listing-->       
</div>  
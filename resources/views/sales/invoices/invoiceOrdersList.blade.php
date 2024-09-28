<div class="row" ng-hide="viewInvoiceOrderDetail" id="viewInvoiceOrderDetail">
    
    <!--Form Start-->
    <form class="form-inline" method="POST" role="form" name="erpRelatedInvoiceReportForm" action="{{url('/sales/invoices/download-related-invoice-reports-document')}}"  target= "blank" novalidate>
    
	<!--search-->
	<div class="row header">        
	    <div class="pull-left mT5">   
		<strong class="headerText" title="Refresh">Invoice Number<span ng-if="invoiceNumber">&nbsp;:&nbsp;[[invoiceNumber]]</span><span ng-if="orderData.length">&nbsp;:&nbsp;Total Reports([[orderData.length]])</span></strong>
	    </div>            
	    <div class="navbar-form navbar-right" role="search">
		<div class="nav-custom">
		    <div style="float: left;color:#000;position: relative;">
			<button type="button" ng-disabled="!orderData.length" class="form-control btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download">Download</button>
			<div class="dropdown-menu" style="padding-right: 190px!important;">
			    <label for="submit">{{ csrf_field() }}</label>
			    <input type="submit" name="generate_related_invoice_orders_documents" value="Excel" class="dropdown-item">
			</div>
			<input type="hidden" ng-model="downloadContentList" name="downloadContentList" value="[[downloadContentList]]">
			<input type="text" ng-keypress="funEnterPressHandler($event)" placeholder="Search" ng-model="keyword" class="form-control ng-pristine ng-untouched ng-valid autoFocus">
			<button type="button" class="btn btn-primary" ng-click="backButton([[backTypeValue]])">Back</button>
		    </div>
		</div>			
	    </div>
	</div>
	<!--/search-->    
	    
	<!--display record--> 
	<div class="row" id="no-more-tables">
	    <div class="col-sm-12">
		<table class="col-sm-12 table-striped table-condensed cf">
		    <thead class="cf">
			<tr>                            
			    <th>
				<label ng-click="sortBy('division_name')" class="sortlabel">Division Name</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'division_name'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('department_name')" class="sortlabel">Department Name</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'division_name'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('customer_name')" class="sortlabel">Customer Name</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'customer_name'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('place')" class="sortlabel">Place</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'place'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('order_date')" class="sortlabel">Order Date</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'order_date'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('sample_description')" class="sortlabel">Sample Description</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'sample_description'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('sample_priority')" class="sortlabel">Sample Priority</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'sample_priority'" class="sortorder reverse ng-hide"></span>
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
				<label ng-click="sortBy('invoice_no')" class="sortlabel">Invoice No </label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'invoice_no'" class="sortorder reverse"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('order_amount')" class="sortlabel">Amount</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'order_amount'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('order_discount')" class="sortlabel">Discount</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'order_discount'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('dispatch_date')" class="sortlabel">Dispatched Date</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'dispatch_date'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('dispatched_no')" class="sortlabel">Dispatched No.</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'dispatched_no'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('dispatcher')" class="sortlabel">Dispatcher</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'dispatcher'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('status')" class="sortlabel">Status</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'status'" class="sortorder reverse ng-hide"></span>
			    </th>    
			</tr>
		    </thead>
		    <tbody>
			<tr dir-paginate="orderObj in orderData | filter:keyword | itemsPerPage:200 | orderBy:predicate:reverse">
			    <td data-title="Division name" class="ng-binding">[[orderObj.division_name]]</td>
			    <td data-title="Department name" class="ng-binding">[[orderObj.department_name]]</td>
			    <td data-title="Customer Name" class="ng-binding">[[orderObj.customer_name]]</td>
			    <td data-title="Customer City" class="ng-binding">[[orderObj.place]]</td>
			    <td data-title="Order Date" class="ng-binding">[[orderObj.order_date]]</td>
			    <td data-title="Sample Description" class="ng-binding">[[orderObj.sample_description]]</td>
			    <td data-title="Sample Priority" class="ng-binding">[[ orderObj.sample_priority]]</td>
			    <td data-title="Order No" class="ng-binding">[[orderObj.order_no]]</td>
			    <td data-title="Order No" class="ng-binding">[[orderObj.report_no]]</td>
			    <td data-title="Invoice No" class="ng-binding">[[orderObj.invoice_no]]</td>
			    <td data-title="Order amount" class="ng-binding">[[orderObj.order_amount]]</td>
			    <td data-title="Order discount" class="ng-binding">[[orderObj.order_discount]]</td>
			    <td data-title="Dispatched Date" class="ng-binding">[[orderObj.dispatch_date]]</td>
			    <td data-title="Dispatched No" class="ng-binding">[[orderObj.dispatched_no]]</td>
			    <td data-title="Dispatch By" class="ng-binding">[[orderObj.dispatcher]]</td>
			    <td data-title="order status" class="ng-binding">[[orderObj.status]]</td>
			</tr>                        
			<tr ng-if="!orderData.length"><td colspan="14">No order found.</td></tr>
		    </tbody>
		    <tfoot ng-if="orderData.length">
			<tr><td colspan="14"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td></tr>
		    </tfoot>
		</table>
	    </div>
	</div>
    </form>
    <!--Form Ends-->
</div>
<div class="row" ng-hide="IsListStabilityOrder" id="IsListStabilityOrder">
    
    <!--erpFilterOrderForm-->
    <form class="form-inline" method="POST" role="form" id="erpFilterStabilityOrderForm" name="erpFilterStabilityOrderForm" action="{{ url('sales/stability-orders/generate-branch-wise-order-pdf') }}" novalidate>
    
	<!--search-->
	<div class="row header">        
	    <div role="new" class="navbar-form navbar-left">            
		<strong id="form_title" title="Refresh" ng-click="funRefreshStabilityMultisearch()">Stability Order Listing<span ng-if="orderData.length">([[orderData.length]])</span></strong>
	    </div>            
	    <div role="new" class="navbar-form navbar-right">
		<div class="nav-custom">
		    <input type="text" placeholder="Search" ng-model="filterStabiltyOrders.keyword" name="keyword" ng-keypress="funEnterPressHandler($event)" ng-change="funFilterStabilityOrderOnSearch(filterStabiltyOrders.keyword)" class="form-control ng-pristine ng-untouched ng-valid autoFocus">
		    <span ng-if="{{defined('ADD') && ADD}}">
			<button type="button" ng-click="openNewOrderForm()" class="btn btn-primary" id="add_new_order" type="button"> [[buttonText]] </button>
		    </span>
		</div>
	    </div>
	</div>
	<!--/search-->    
	    
	<!--display record--> 
	<div class="row" id="no-more-tables">    
	    <!--Filter By--> 
	    <div class="panel panel-default filterForm" style="margin-top: 0px;">
		<div class="panel-body">					               
		    <div class="row">
			<div ng-if="{{$division_id}} == 0" class="col-xs-2 form-group">
			    <label for="from">Branch</label>
			    <select class="form-control width200" ng-model="filterStabiltyOrders.division_id" name="division_id" ng-options="division.name for division in divisionsCodeList track by division.id">
				<option value="">All Branch</option>
			    </select>
			</div>
			<div ng-if="{{$division_id}} > 0">
			    <input type="hidden" value="{{$division_id}}" name="division_id">
			</div>
			<div class="col-xs-2 form-group">                        
			    <label for="from">Filter From</label>
			    <div class="input-group date" data-provide="datepicker">
				<input autocomplete="off" type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterStabiltyOrders.stability_order_date_from" name="stability_order_date_from" id="order_date_from" placeholder="Date From" />
				<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
			    </div>
			</div>								
			<div class="col-xs-2 form-group">                        
			    <label for="to">Filter To</label>
			    <div class="input-group date" data-provide="datepicker">
				<input autocomplete="off" type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterStabiltyOrders.stability_order_date_to" name="stability_order_date_to" id="order_date_to" placeholder="Date To" />
				<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
			    </div>
			</div>							
			<div class="col-xs-2 form-group mT30">
			     <label for="submit">{{ csrf_field() }}</label>
			     <button type="button" title="Filter" ng-disabled="erpFilterOrderForm.$invalid" class="btn btn-primary" ng-click="funFilterStabilityOrder()"><i class="fa fa-search" aria-hidden="true"></i></button>
			     <button type="button" ng-disabled="!orderData.length" class="btn btn-primary dropdown dropdown-toggle" data-toggle="dropdown" title="Download">
				<i aria-hidden="true" class="fa fa-print"></i></button>
				<div class="dropdown-menu">
				    <input type="submit"  formtarget="_blank" name="generate_stability_order_documents" value="Excel" class="dropdown-item">
				    <input type="submit"  formtarget="_blank" name="generate_stability_order_documents" value="PDF" class="dropdown-item">				  
				</div>
				<button type="button" title="Refresh" ng-disabled="erpFilterOrderForm.$invalid" class="btn btn-default" ng-click="funRefreshStabilityMultisearch()"><i aria-hidden="true" class="fa fa-refresh"></i></button>
			</div>
		    </div>
		</div>
	    </div>	
	    <!--/Filter By-->
	    
	    <!--Listing of Orders-->
	    <div class="col-sm-12 tableRecord">
		<table class="col-sm-12 table-striped table-condensed cf">
		    <thead class="cf">
			<tr>                            
			    <th>
				<label ng-click="sortBy('prototype_no')" class="sortlabel">Prototype No </label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'prototype_no'" class="sortorder reverse"></span>
			    </th>
			    <th ng-if="{{$division_id}} == 0">
				<label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
				<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
			    </th>
			    <th>
				<label ng-click="sortBy('customer_name')" class="sortlabel">Customer Name</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'customer_name'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('city_name')" class="sortlabel">Place</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'city_name'" class="sortorder reverse ng-hide"></span>
			    </th>    
			    <th>
				<label ng-click="sortBy('prototype_date')" class="sortlabel">Prototype Date </label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'prototype_date'" class="sortorder reverse ng-hide"></span>
			    </th>
			   
			    <th>
				<label ng-click="sortBy('sample_description')" class="sortlabel">Sample Description</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'sample_description'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('batch_no')" class="sortlabel">Batch No.</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'batch_no'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th class="width8">
				<label ng-click="sortBy('sample_priority_name')" class="sortlabel">Sample Priority</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'sample_priority_name'" class="sortorder reverse ng-hide"></span>
			    </th> 
			    <th class="width8">
				<label ng-click="sortBy('remarks')" class="sortlabel">Remarks</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'remarks'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th class="width8">
				<label ng-click="sortBy('stb_status')" class="sortlabel">Booked Status</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'stb_status'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th class="width8">
				<label ng-click="sortBy('createdByName')" class="sortlabel">Created By</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'createdByName'" class="sortorder reverse ng-hide"></span>
			    </th>						
			    <th class="width10">Action
				<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="funOpenStabilityMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
			    </th>
			</tr>
		    </thead>
		    <tbody>
			<tr ng-hide="multiSearchTr">
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getStabilityMultiSearch(filterStabiltyOrders.search_prototype_no)" name="search_prototype_no" ng-model="filterStabiltyOrders.search_prototype_no" class="multiSearch form-control" placeholder="Prototype No"></td>
			    <td class="width10" ng-if="{{$division_id}} == 0"></td>
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getStabilityMultiSearch(filterStabiltyOrders.search_customer_name)" name="search_customer_name" ng-model="filterStabiltyOrders.search_customer_name" class="multiSearch form-control" placeholder="Customer Name"></td>
			    <td></td>
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getStabilityMultiSearch(filterStabiltyOrders.search_prototype_date)" name="search_prototype_date" ng-model="filterStabiltyOrders.search_prototype_date" class="multiSearch form-control" placeholder="Prototype Date"></td>
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getStabilityMultiSearch(filterStabiltyOrders.search_sample_description)" name="search_sample_description" ng-model="filterStabiltyOrders.search_sample_description" class="multiSearch form-control" placeholder="Sample Description"></td>
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getStabilityMultiSearch(filterStabiltyOrders.search_batch_no)" name="search_batch_no" ng-model="filterStabiltyOrders.search_batch_no" class="multiSearch form-control" placeholder="Batch No."></td>
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getStabilityMultiSearch(filterStabiltyOrders.search_sample_priority_name)" name="search_sample_priority_name" ng-model="filterStabiltyOrders.search_sample_priority_name" class="multiSearch form-control" placeholder="Sample Priority"></td>
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getStabilityMultiSearch(filterStabiltyOrders.search_remarks)" name="search_remarks" ng-model="filterStabiltyOrders.search_remarks" class="multiSearch form-control" placeholder="Remarks"></td>
			    <td></td>
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getStabilityMultiSearch(filterStabiltyOrders.search_created_by)" name="search_created_by" ng-model="filterStabiltyOrders.search_created_by" class="multiSearch form-control" placeholder="Created By"></td>
			    <td class="width10">
				<button type="button" ng-click="funRefreshStabilityMultisearch(divisionID)" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
				<button type="button" ng-click="funCloseStabilityMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
			    </td>
			</tr>                        
			<tr dir-paginate="orderObj in orderData | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
			    <td data-title="Order No" class="ng-binding"><a class="no-anchor" title="[[orderObj.product_name]]">[[orderObj.stb_prototype_no]]<span ng-if="orderObj.stb_order_hdr_id == newOrderActive" class="won"><sup>NEW</sup></span></a></td>
			    <td ng-if="{{$division_id}} == 0" data-title="Division Name ">[[orderObj.division_name]] </td>
			    <td data-title="Customer Name" class="ng-binding">[[orderObj.customer_name]]</td>
			    <td data-title="Customer City" class="ng-binding">[[orderObj.customer_city]]</td>
			    <td data-title="Order Date" class="ng-binding">[[orderObj.stb_prototype_date]]</td>
			    <td data-title="Sample Description" class="ng-binding"> [[ orderObj.sample_description]] </td>
			    <td data-title="Batch No" class="ng-binding"> [[ orderObj.stb_batch_no]] </td>
			    <td data-title="Remarks" class="ng-binding">[[orderObj.sample_priority_name ? orderObj.sample_priority_name : '-']]</td>      
			    <td data-title="Remarks" class="ng-binding">
				<span id="samplelimitedText-[[orderObj.stb_prototype_no]]">
				    [[ orderObj.stb_remarks | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
				    <a href="javascript:;" ng-click="toggleDescription('sample',orderObj.stb_prototype_no)" ng-show="orderObj.stb_remarks.length > 150" class="readMore">read more...</a>
				</span>
				<span id="samplefullText-[[orderObj.stb_prototype_no]]" style="display:none;" >
				    [[ orderObj.stb_remarks]] 
				    <a href="javascript:;" ng-click="toggleDescription('sample',orderObj.stb_prototype_no)" class="readMore">read less...</a>
				</span>
			    </td>
			    <td data-title="Booked Status" class="ng-binding">[[orderObj.stb_status | yesOrNo]]</td>
			    <td data-title="Created By" class="ng-binding">[[orderObj.createdByName]]</td>						
			    <td class="width10">
				<!--View-->
				<div class="report_btn_div" ng-if="{{defined('VIEW') && VIEW}}">
				    <span ng-if="{{defined('IS_ADMIN') && IS_ADMIN  || defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER}}">
					<button type="button" ng-click="funEditCustomerSampleOfStabilityOrder(orderObj.stb_order_hdr_id)" title="Edit/View Stability Order" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-eye" aria-hidden="true"></i></button>
				    </span>                            
				</div>
				<!--View-->
				<!--Delete-->
				<div class="report_btn_div" ng-if="{{defined('DELETE') && DELETE}}">
				    <button type="button" ng-click="funConfirmDeleteMessage(orderObj.stb_order_hdr_id)" title="Delete Stability Order" class="btn btn-danger btn-sm report_btn_span"> <i class="fa fa-trash-o" aria-hidden="true"></i></button>
				</div>
				<!--/Delete-->
			    </td>                
			</tr>                        
			<tr ng-if="!orderData.length"><td colspan="12" align="left">No record found.</td></tr>
		    </tbody>
		    <tfoot ng-if="orderData.length">
			<tr>
			    <td colspan="12"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div>
			    </td>
			</tr>
		    </tfoot>
		</table>
	    </div>
	    <!--/Listing of Orders-->
	</div>
    </form>
</div>
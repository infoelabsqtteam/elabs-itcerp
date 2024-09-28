<div class="row" ng-hide="IsViewList" id="IsViewList">
    
    <!--erpFilterTrfForm-->
    <form class="form-inline" method="POST" role="form" id="erpFilterTrfForm" name="erpFilterTrfForm" action="{{ url('sales/trfs/generate-branch-wise-trf-pdf') }}" novalidate>
    
	<!--search-->
	<div class="row header">        
	    <div role="new" class="navbar-form navbar-left">            
		<strong id="form_title" title="Refresh" ng-click="funRefreshTrfsList()">TRF Listing<span ng-if="trfData.length">([[trfData.length]])</span></strong>
	    </div>            
	    <div role="new" class="navbar-form navbar-right">
		<div class="nav-custom">
		    <input type="text" placeholder="Search" ng-model="filterTrfs.keyword" name="keyword" ng-keypress="funEnterPressHandler($event)" ng-change="funFilterTRfSearchBox(filterTrfs.keyword)" class="form-control ng-pristine ng-untouched ng-valid autoFocus">
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
			    <select class="form-control width200" ng-model="filterTrfs.division_id" name="division_id" ng-options="division.name for division in divisionsCodeList track by division.id">
				<option value="">All Branch</option>
			    </select>
			</div>
			<div ng-if="{{$division_id}} > 0">
			    <input type="hidden" value="{{$division_id}}" name="division_id">
			</div>
			<div class="col-xs-2 form-group">
			    <label for="from">Select Status</label>
			    <select class="form-control width200" ng-model="filterTrfs.trf_status" name="trf_status">
				<option value="">All Status</option>
				<option value="0">Pending</option>
				<option value="1">Booked</option>
			    </select>
			</div>
			<div class="col-xs-2 form-group">                        
			    <label for="from">Filter From</label>
			    <div class="input-group date" data-provide="datepicker">
				<input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterTrfs.trf_date_from" name="trf_date_from" id="trf_date_from" placeholder="Date From" />
				<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
			    </div>
			</div>								
			<div class="col-xs-2 form-group">                        
			    <label for="to">Filter To</label>
			    <div class="input-group date" data-provide="datepicker">
				<input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterTrfs.trf_date_to" name="trf_date_to" id="trf_date_to" placeholder="Date To" />
				<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
			    </div>
			</div>							
			<div class="col-xs-2 form-group mT30">
			    <label for="submit">{{ csrf_field() }}</label>
			    <button type="button" title="Filter" ng-disabled="erpFilterTrfForm.$invalid" class="btn btn-primary" ng-click="funFilterTrfByStatus()"><i class="fa fa-search" aria-hidden="true"></i></button>
			    <button type="button" ng-disabled="!trfData.length" class="btn btn-primary dropdown dropdown-toggle" data-toggle="dropdown" title="Download"><i aria-hidden="true" class="fa fa-print"></i></button>
			    <div class="dropdown-menu">
				<input type="submit" formtarget="_blank" name="generate_trf_documents" value="Excel" class="dropdown-item">				  
			    </div>
			    <button type="button" title="Refresh" ng-disabled="erpFilterTrfForm.$invalid" class="btn btn-default" ng-click="funRefreshTrfsList()"><i aria-hidden="true" class="fa fa-refresh"></i></button>
			</div>
		    </div>
		</div>
	    </div>	
	    <!--/Filter By-->
	    
	    <!--Listing of TRFs-->
	    <div class="col-sm-12 tableRecord">
		<table class="col-sm-12 table-striped table-condensed cf">
		    <thead class="cf">
			<tr>                            
			    <th>
				<label ng-click="sortBy('trf_no')" class="sortlabel">TRF No. </label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'trf_no'" class="sortorder reverse"></span>
			    </th>
			    <th ng-if="{{$division_id}} == 0">
				<label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
				<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
			    </th>
			    <th>
				<label class="sortlabel" ng-click="sortBy('trf_product_category_name')">Department</label>
				<span class="sortorder" ng-show="predicate === 'trf_product_category_name'" ng-class="{reverse:reverse}"></span>						
			    </th>
			    <th>
				<label ng-click="sortBy('trf_customer_name')" class="sortlabel">Customer Name</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'trf_customer_name'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('trf_customer_city')" class="sortlabel">Place</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'trf_customer_city'" class="sortorder reverse ng-hide"></span>
			    </th>    
			    <th>
				<label ng-click="sortBy('trf_date')" class="sortlabel">TRF Date </label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'trf_date'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('trf_sample_name')" class="sortlabel">Sample Name</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'trf_sample_name'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('trf_batch_no')" class="sortlabel">Batch No.</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'trf_batch_no'" class="sortorder reverse ng-hide"></span>
			    </th>
			    <th>
				<label ng-click="sortBy('sample_no')" class="sortlabel">Sample No.</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'sample_no'" class="sortorder reverse"></span>
			    </th>
			    <th class="width8">
				<label ng-click="sortBy('trf_status_name')" class="sortlabel">Status</label>
				<span ng-class="{reverse:reverse}" ng-show="predicate === 'trf_status_name'" class="sortorder reverse ng-hide"></span>
			    </th>					
			    <th class="width10">Action
				<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
			    </th>
			</tr>
		    </thead>
		    <tbody>
			<tr ng-hide="multiSearchTr">
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterTrfs.search_trf_no)" name="search_trf_no" ng-model="filterTrfs.search_trf_no" class="multiSearch form-control" placeholder="TRF No"></td>
			    <td class="width10" ng-if="{{$division_id}} == 0"></td>
			    <td></td>
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterTrfs.search_trf_customer_name)" name="search_trf_customer_name" ng-model="filterTrfs.search_trf_customer_name" class="multiSearch form-control" placeholder="Customer Name"></td>
			    <td></td>
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterTrfs.search_trf_date)" name="search_trf_date" ng-model="filterTrfs.search_trf_date" class="multiSearch form-control" placeholder="TRF Date"></td>
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterTrfs.search_trf_sample_name)" name="search_trf_sample_name" ng-model="filterTrfs.search_trf_sample_name" class="multiSearch form-control" placeholder="Sample Name"></td>
			    <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterTrfs.search_trf_batch_no)" name="search_trf_batch_no" ng-model="filterTrfs.search_trf_batch_no" class="multiSearch form-control" placeholder="Batch No."></td>		    
			    <td></td>
			    <td></td>
			    <td class="width10">
				<button type="button" ng-click="refreshMultisearch(divisionID)" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
				<button type="button" ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
			    </td>
			</tr>                        
			<tr dir-paginate="trfObj in trfData | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
			    <td data-title="TRF No" class="ng-binding">[[trfObj.trf_no]]</td>
			    <td ng-if="{{$division_id}} == 0" data-title="Division Name ">[[trfObj.trf_division_name]] </td>
			    <td data-title="Department Name" class="ng-binding">[[trfObj.trf_product_category_name]]</td>
			    <td data-title="Customer Name" class="ng-binding">[[trfObj.trf_customer_name]]</td>
			    <td data-title="Customer City" class="ng-binding">[[trfObj.trf_customer_city]]</td>
			    <td data-title="TRF Date" class="ng-binding">[[trfObj.trf_date]]</td>
			    <td data-title="Sample Name" class="ng-binding"> [[ trfObj.trf_sample_name]] </td>
			    <td data-title="Batch No" class="ng-binding"> [[ trfObj.trf_batch_no]] </td>
			    <td data-title="Sample No" class="ng-binding">[[trfObj.sample_no]]</td>
			    <td data-title="TRF status name" class="ng-binding"><span ng-if="trfObj.trf_status == 0" style = "color:red">Pending</span><span ng-if="trfObj.trf_status == 1" style = "color:green">Booked</span></td>	
			    <td class="width10">
				<div class="report_btn_div">
				    <button type="button" ng-click="funViewTrfDetail(trfObj.trf_id)" title="View TRF" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-eye" aria-hidden="true"></i></button>
				</div>
			    </td>                
			</tr>                        
			<tr ng-if="!trfData.length"><td colspan="10">No record found.</td></tr>
		    </tbody>
		    <tfoot ng-if="trfData.length">
			<tr><td colspan="10"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td></tr>
		    </tfoot>
		</table>
	    </div>
	    <!--/Listing of TRFs-->
	</div>
    </form>
</div>
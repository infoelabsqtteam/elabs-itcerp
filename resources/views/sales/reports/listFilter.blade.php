<!--Filter By--> 
<div class="row panel panel-default filterForm" style="margin-top: 0px;" ng-hide="IsViewReportFilter">
    <div class="panel-body">					               
	<div class="row">
	    <div ng-if="{{$division_id}} == 0" class="col-xs-2 form-group">
		<label for="from">Branch</label>
		<select class="form-control width200" ng-model="filterReport.divisions" name="division_id" ng-options="division.name for division in divisionsCodeList track by division.id">
		    <option value="">All Branch</option>
		</select>
	    </div>
	    <div ng-if="{{$division_id}} > 0">
		<input type="hidden" id="division_id" ng-model="filterReport.divisions" name="division_id" value="{{$division_id}}">
	    </div>
	    <div class="col-xs-2 form-group">                        
		<label for="from">Booking Date From</label>
		<div class="input-group date" data-provide="datepicker">
		    <input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterReport.order_date_from" name="order_date_from" id="order_date_from" placeholder="Booking Date From" />
		    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
		</div>
	    </div>								
	    <div class="col-xs-2 form-group">                        
		<label for="to">Booking Date  To</label>
		<div class="input-group date" data-provide="datepicker">
		    <input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterReport.order_date_to" name="order_date_to" id="order_date_to" placeholder="Booking Date To" />
		    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
		</div>
	    </div>
	    <div class="col-xs-2 form-group" ng-if="{{!defined('IS_DISPATCHER') && !defined('IS_TESTER')}}">                        
		<label for="from">Expected Due Date From</label>
		<div class="input-group date" data-provide="datepicker">
		    <input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterReport.expected_due_date_from" name="expected_due_date_from" id="expected_due_date_from" placeholder="Expected Due Date From" />
		    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
		</div>
	    </div>								
	    <div class="col-xs-2 form-group" ng-if="{{!defined('IS_DISPATCHER') && !defined('IS_TESTER')}}">                  
		<label for="to">Expected Due Date  To</label>
		<div class="input-group date" data-provide="datepicker">
		    <input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterReport.expected_due_date_to" name="expected_due_date_to" id="expected_due_date_to" placeholder="Expected Due Date To" />
		    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
		</div>
	    </div>
	    <div class="col-xs-2 form-group" ng-if="{{defined('IS_ADMIN') || defined('IS_TESTER')}}">                        
		<label for="dept_due_date_from">Department Due Date From</label>
		<div class="input-group date" data-provide="datepicker">
		    <input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterReport.dept_due_date_from" name="dept_due_date_from" id="dept_due_date_from" placeholder="Department Due Date From" />
		    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
		</div>
	    </div>
	</div>
	    
	<div class="row">
	
	    <div class="col-xs-2 form-group" ng-if="{{defined('IS_ADMIN') || defined('IS_TESTER')}}">                        
		<label for="dept_due_date_to">Department Due Date  To</label>
		<div class="input-group date" data-provide="datepicker">
		    <input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterReport.dept_due_date_to" name="dept_due_date_to" id="dept_due_date_to" placeholder="Department Due Date To" />
		    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
		</div>
	    </div>
	    <div class="col-xs-1 form-group mT30">    
		<div class="checkbox">
		    <label><input type="checkbox" ng-model="filterReport.order_search_all" name="order_search_all" id="order_search_all" value="all">&nbsp;All</label>
		</div>
	    </div>
	    <div ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}" class="col-xs-1 form-group mT30">    
		<div class="checkbox">
		    <label><input title="Dispatch Pendency" type="checkbox" ng-model="filterReport.order_search_dispatch_pendency" name="order_search_dispatch_pendency" id="order_search_dispatch_pendency" value="dispatch_pendency">&nbsp;Dispatched</label>
		</div>
	    </div>
	    <div class="col-xs-2 form-group mT25">
		<label for="submit">{{ csrf_field() }}</label>                             
		<button type="button" title="Filter" ng-disabled="erpFilterReportForm.$invalid" class="btn btn-primary" ng-click="funFilterReportByStatus()"><i class="fa fa-search" aria-hidden="true"></i></button>
		<button type="button" ng-disabled="!getBranchWiseReports.length" class="btn btn-primary dropdown dropdown-toggle" data-toggle="dropdown" title="Download">
		    <i aria-hidden="true" class="fa fa-print"></i></button>
		    <div class="dropdown-menu">
			<input type="submit"  formtarget="_blank" name="generate_report_documents" value="Excel"
			class="dropdown-item">
			<input type="submit"  formtarget="_blank" name="generate_report_documents" value="PDF"
			class="dropdown-item">
		    </div>
		<button type="button" title="Refresh" ng-disabled="erpFilterReportForm.$invalid" class="btn btn-default" ng-click="funRefreshReportByStatus()"><i aria-hidden="true" class="fa fa-refresh"></i></button>
	    </div>
	       
	</div>
    </div>
</div>	
<!--/Filter By-->
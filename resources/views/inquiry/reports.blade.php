@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/inquiryController.js') !!}"></script>	
<div class="container" ng-controller="inquiryController">
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
		
	<div>
		<div class="row header" id="filterInquiryReports" ng-hide="filterInquiryReports">
				<strong class="pull-left headerText">Generate Inquiry Report</strong>	
				<div class="navbar-form navbar-right" role="search">
					<div class="nav-custom">
						<input type="text" ng-hide="searchInquiryInput" class="form-control ng-pristine ng-untouched ng-valid"  placeholder="Search" ng-model="searchInquiry">
						<button style="display:none;" title="Filter" type="button" class="btn btn-primary btn-md" ng-hide="openFilter" ng-click="openFilterForm();">Filter</button>
					</div>
				</div>
		</div>
		<div class="row header" ng-hide="headerFollowupsReports">
				<strong class="pull-left headerText">Inquiry Followup Reports</strong>	
				<div class="navbar-form navbar-right" role="search">
					<div class="nav-custom">
						<input type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchfollowups">
					</div>
				</div>
		</div>
		<div class="row" id="IsViewReportFilter" ng-hide="IsViewReportFilter">
			<div class="panel" style="background-color: rgba(235, 235, 235, 0.33) !important;">
				<div class="panel-body">
					<!-- **************************************inquiry report form start**************************** -->
					<div ng-model="addProductFormDiv">
						<form name='inquiryReportForm' novalidate>
							<div class="row">
							<div class="col-xs-12">
								<div class="col-xs-2">
									<label for="inquiry_date_from">Followup Date From<em class="asteriskRed">*</em> </label>
									<div class="input-group date" data-provide="datepicker">
										<input type="text" class="bgwhite form-control" 
											ng-model="report.inquiry_date_from"
											name="inquiry_date_from" 
											id="inquiry_date_from"
											ng-required="true"
											placeholder="Date From" />
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</div>
									</div>
									<span ng-messages="inquiryReportForm.inquiry_date_from.$error" 
									ng-if='inquiryReportForm.inquiry_date_from.$dirty  || inquiryReportForm.$submitted' role="alert">
									<span ng-message="required" class="error">Date fields is required</span>
									</span>
								</div>
								<div class="col-xs-2">
									<label for="customer_id">Select Customer<input class="mL5" ng-model="report.checkAllCustomer" ng-click="checkAll('customer')" type="checkbox" id="select_customer"></label>							
									<select class="form-control" 
											id="customerSelect" 
											name="customer_id[]" 
											multiple="multiple"
											ng-change="selectClick('customer')"
											ng-model="report.customer_id"
											ng-options="item.customer_name for item in customerList track by item.customer_id ">
									</select>
									<span class="textGreen">press ctrl to select multiple<span>
								</div>				
								
								<div class="col-xs-2">
									<label for="customer_id">Select Employee<input class="mL5" ng-model="report.checkAllEmployee" ng-click="checkAll('employee')" type="checkbox" id="select_employee"></label>							
									<select class="form-control" 
											id="employeeSelect"
											name="employee_id[]" 
											multiple="multiple" 
											ng-change="selectClick('employee')"
											ng-model="report.employee_id" 
											ng-options="item.name for item in employeeList track by item.id ">
									</select>
									<span class="textGreen">press ctrl to select multiple</span>
								</div>
								<div class="col-xs-2">
									<label for="status">Select Status<input class="mL5" ng-model="report.checkAllStatus" ng-click="checkAll('status')" type="checkbox" id="select_status"></label>
									<select class="form-control" 
											id="statusSelect"
											name="status[]" 
											multiple="multiple"
											ng-change="selectClick('status')"
											ng-model="report.statusDropdown"
											ng-options="item.name for item in statusDropdown.Options track by item.id ">
									</select>
									<span class="textGreen">press ctrl to select multiple</span>	
								</div>
								<div class="col-xs-2">
									<label for="status">Select Mode<input class="mL5" ng-model="report.checkAllMode" ng-click="checkAll('mode')" type="checkbox" id="select_mode"></label>
									<select class="form-control" 
											id="modeSelect"
											name="mode[]" 
											multiple="multiple"
											ng-change="selectClick('mode')"
											ng-model="report.followupsMode"
											ng-options="item.name for item in followupsMode.followupdsModeTypeOptions track by item.id ">
									</select>
									<span class="textGreen">press ctrl to select multiple</span>	
								</div>
								<div class="col-xs-2">
									<label>Inquiry Date</label>
									<div class="input-group date" data-provide="datepicker">
										<input type="text" class="bgwhite form-control" 
											ng-model="report.inquiry_date" 
											name="inquiry_date" 
											placeholder="Inquiry Date" />
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</div>
									</div>
								</div>
								<div class="col-xs-2">
								   <button type='button' class='mL5 pull-right mT26 btn btn-default' ng-click='resetReportForm()' > Refresh </button>
								   <button type='submit' ng-disabled="inquiryReportForm.$invalid" class=' pull-right mT26 btn btn-primary' ng-click='generateInquiryReport()' > Generate </button>
								   <div ng-hide="loadersmall" class="loadersmall"></div>
								</div>	
						</div>	
								<div class="inquiryDate col-xs-2">
									<label for="inquiry_date_to">Followup Date To</label>
									<div class="input-group date" data-provide="datepicker">
										<input type="text" class="bgwhite form-control" 
											ng-model="report.inquiry_date_to"
											name="inquiry_date_to"
											placeholder="Date To" />
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</div>
									</div>
								</div>
						</div>
						</form>	
					</div>
					<!-- **********************************inquiry report form  start**************************** -->
				</div>	
			</div>
		</div>
    <!-----------------------report filter end----------------------------->
     <div class="row" id="inquiryReportList" ng-hide="inquiryList">
        <div id="no-more-tables">
			<div class="inq-header">Report
			 <button id="printButton"  class="pull-right btn btn-primary" ng-click="printInquiryReport('inquiryReportList')">Print</button>
			</div>
            <table class="col-sm-12 table-striped table-condensed cf mB15">
        		<thead class="cf">
        			<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('inquiry_no')">Inquiry No</label>
							<span class="sortorder" ng-show="predicate === 'inquiry_no'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('customer_name')">Customer Name</label>
							<span class="sortorder" ng-show="predicate === 'customer_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('name')">Followup By</label>
							<span class="sortorder" ng-show="predicate === 'name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="datetdWidth">
							<label class="sortlabel" ng-click="sortBy('inquiry_date')">Inquiry Date</label>
							<span class="sortorder" ng-show="predicate === 'inquiry_date'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="datetdWidth">
							<label class="sortlabel" ng-click="sortBy('next_followup_date')">Next Follow-up Date</label>
							<span class="sortorder" ng-show="predicate === 'next_followup_date'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('followup_detail')">Followup Detail</label>
							<span class="sortorder" ng-show="predicate === 'followup_detail'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('mode')">Mode</label>
							<span class="sortorder" ng-show="predicate === 'mode'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('inquiry_status')">Status</label>
							<span class="sortorder" ng-show="predicate === 'inquiry_status'" ng-class="{reverse:reverse}"></span>						
						</th>
        			</tr>
        		</thead>
        		<tbody>			
                    <tr dir-paginate="obj in inquiryReportdata | filter:searchInquiry | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 10 }} | orderBy:predicate:reverse">
        				<td data-title="Inquiry No">
							<a href="javascript:;" ng-click="generateInquiryFollowupsReport(obj.id)">[[obj.inquiry_no]]</a>
						</td>
        				<td data-title="Customer Name">[[obj.customer_name]]</td>
        				<td data-title="Followup By">[[obj.name]]</td>
        				<td data-title="Inquiry Date">[[obj.inquiry_date | date : 'dd-MM-yyyy']]</td>
        				<td data-title="Next Folloup">[[obj.next_followup_date | date : 'dd-MM-yyyy']]</td>
        				<td data-title="Followup Detail">
							<span id="reportlimitedText-[[obj.inquiry_no]]">
								[[ obj.followup_detail | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 200 }} ]]
								<a ng-click="toggleDescription('report',obj.inquiry_no)" ng-show="obj.followup_detail.length > 150" class="readMore">read more</a>
							</span>
							<span style="display:none;" id="reportfullText-[[obj.inquiry_no]]">
								[[ obj.followup_detail]] 
								<a ng-click="toggleDescription('report',obj.inquiry_no)" class="readMore">read less</a>
							</span>
						</td>
        				<td data-title="Mode">[[obj.mode]]</td>
        				<td class="text-upper" data-title="Followup Status" ><span class="[[obj.inquiry_status]]">[[obj.inquiry_status]]</span></td>							
        			</tr>					
 					<tr ng-hide="inquiryReportdata.length"  class="noRecord"><td colspan="7">No Record Found!</td></tr>
					<tr ng-show="inquiryReportdata.length" class="text-left">
						<td colspan="10">
							<div class="box-footer clearfix">
								<dir-pagination-controls></dir-pagination-controls>
							</div>		
						</td>
					</tr>
        		</tbody>
        	</table>	  
		</div>
		<div ng-hide="IsNotifySelector" class="notifyMe"></div>
	</div> 
	</div>
    <!-----------------------report filter end----------------------------->
	<div ng-hide="filterInquiryFollowups">
		<div class="row">
			<div id="no-more-tables">
				    <div class="inq-header">Inquiry Detail						
						<button title="Back"  type="button" class="pull-right btn btn-primary btn-md" ng-hide="closeFollowup" ng-click="closeFollowupReports(InquiryId);">Back</button>
					</div>
					<table class="col-sm-12 table-striped table-condensed cf">
						<thead class="cf">
							<tr>
								<th  class="datetdWidth">
									<label class="sortlabel" ng-click="sortBy('inquiry_no')">Inquiry No</label>
									<span class="sortorder" ng-show="predicate === 'inquiry_no'" ng-class="{reverse:reverse}"></span>						
								</th>
								<th  class="datetdWidth">
									<label class="sortlabel" ng-click="sortBy('inquiry_date')">Inquiry Date</label>
									<span class="sortorder" ng-show="predicate === 'inquiry_date'" ng-class="{reverse:reverse}"></span>						
								</th>
								<th  class="datetdWidth">
									<label class="sortlabel" ng-click="sortBy('next_followup_date')">Next Follow-up</label>
									<span class="sortorder" ng-show="predicate === 'next_followup_date'" ng-class="{reverse:reverse}"></span>						
								</th>
								<th>
									<label class="sortlabel" ng-click="sortBy('inquiry_detail')">Inquiry Detail</label>
									<span class="sortorder" ng-show="predicate === 'inquiry_detail'" ng-class="{reverse:reverse}"></span>						
								</th>
								<th>
									<label class="sortlabel" ng-click="sortBy('inquiry_status')">Status</label>
									<span class="sortorder" ng-show="predicate === 'inquiry_status'" ng-class="{reverse:reverse}"></span>						
								</th>
							</tr>
						</thead>								
						<tbody>
							<tr ng-repeat="obj in followupInquiryRepordata | orderBy:predicate:reverse"">
								<td data-title="Inquiry No">[[obj.inquiry_no]]</td>
								<td data-title="Date">[[obj.inquiry_date | date : 'dd-MM-yyyy']]</td>
								<td data-title="Next Folloup">[[obj.next_followup_date | date : 'dd-MM-yyyy']]</td>
								<td style="width:799px;"  data-title="Next Folloup">[[obj.inquiry_detail]]</td>
								<td class="text-upper" data-title="status"><span class="[[obj.inquiry_status]]">[[obj.inquiry_status]]</span></td>										
							</tr>
						</tbody>	
					</table>
					<div class="inq-header mT30">Follow-up Detail</div>
					<table class="mB15 col-sm-12 table-striped table-condensed cf">
						<thead class="cf">
							<tr>
								<th><label class="sortlabel">Follow By</label></th>
								<th><label class="sortlabel">Mode</label></th>
								<th><label class="sortlabel">Follow-Up Date</label></th>
								<th><label class="sortlabel">Next follow-up Date</label></th>
								<th><label class="sortlabel">Followup Detail</label></th>
								<th><label class="text-upper sortlabel">Status</label></th>
							</tr>
						</thead>								
						<tbody>
							<tr ng-repeat="obj in inquiryReportFolloupdata | filter:searchfollowups | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 10 }}">
								<td data-title="folloup mode">[[obj.employeename]]</td>
								<td data-title="next followup">[[obj.mode]]</td>
								<td data-title="next followup">[[obj.created_at | date : 'dd-MM-yyyy']]</td>
								<td data-title="next followup">[[obj.next_followup_date | date : 'dd-MM-yyyy']]</td>
								<td style="width:560px;" data-title="next followup">[[obj.followup_detail?obj.followup_detail:'-']]</td>
								<td class="text-upper" data-title="status"><span class="[[obj.inquiry_status]]">[[obj.status]]</span></td>											
							</tr>
							<tr ng-hide="inquiryReportFolloupdata.length"  class="noRecord"><td colspan="6">No Record Found!</td></tr>
						</tbody>	
					</table>	  
			</div>
		</div>  
	</div>  
    <!-----------------------report filter end----------------------------->
</div> 
@endsection
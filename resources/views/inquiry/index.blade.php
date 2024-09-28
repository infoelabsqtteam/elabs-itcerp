@extends('layouts.app')

@section('content')
	
<script type="text/javascript" src="{!! asset('public/ang/controller/inquiryController.js') !!}"></script>
<div class="container" ng-controller="inquiryController" ng-init="getInquiry()">

	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->

<div class="row header">
		<strong class="pull-left headerText">Inquiry Details</strong>	
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
			  <input type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchInquiry">
				<select class="form-control" name="status"
						ng-change="getStatusWiseListInquiry(statusDropdown.selectedOption)"
						ng-model="statusDropdown.selectedOption"
						ng-options="item.name for item in statusDropdown.Options track by item.id ">
					<option value="">All Inquiry Status</option>
				</select>
			  <a ng-if="{{defined('ADD') && ADD}}" href="javascript:;" title="Add New Record" class="btn btn-primary btn-md" ng-click="addNewInquiry()">Add New</a>
			  <a title="View Reports" class="btn btn-primary btn-md" href="{{url('/inquiry/reports')}}">Reports</a>
			</div>
		</div>
</div>
<div class="row">
        <div id="no-more-tables">	
            <table class="col-sm-12 table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
						<th><label>Inquiry No</label></th>
        				<th class="tdWidth"><label>Customer Name</label></th>
						<th class="tdWidth"><label>Followed By</label></th>
        				<th class="tdWidth"><label>Inquiry Date</label></th>
        				<th class="tdWidth"><label>Next follow-up</label></th>
        				<th><label>Detail</label></th>	
        				<th><label>Status</label></th>	
						<th><label>Created By</label></th>
						<th colspan="3"><label>Action</label></th>
        			</tr>
        		</thead>
        		<tbody>
                    <!--<tr ng-repeat="customerData in customerListData  track by $index">-->
                    <tr dir-paginate="obj in inquirydata | filter:searchInquiry | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 5 }}" >
						<td data-title="inquiry no">[[obj.inquiry_no]]</td>
        				<td class="tdWidth" data-title="date">[[obj.customer_name]]</td>
						<td data-title="employee name">[[obj.name]]</td>
        				<td class="tdWidth" data-title="date">[[obj.inquiry_date | date : 'dd-MM-yyyy']]</td>
        				<td data-title="next followup">[[obj.next_followup_date | date : 'dd-MM-yyyy']]</td>
        				<td data-title="detail">
							<span id="inquerylimitedText-[[obj.inquiry_no]]">
								[[ obj.inquiry_detail | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 200 }} ]]
								<a href="javascript:;" ng-click="toggleDescription('inquery',obj.inquiry_no)" ng-show="obj.inquiry_detail.length > 150" class="readMore">read more...</a>
							</span>
							<span style="display:none;" id="inqueryfullText-[[obj.inquiry_no]]">
								[[ obj.inquiry_detail]] 
								<a href="javascript:;" ng-click="toggleDescription('inquery',obj.inquiry_no)" class="readMore">read less...</a>
							</span>
						</td>
        				<td class="text-upper" data-title="status"><span class="[[obj.inquiry_status]]">[[obj.inquiry_status]]</span></td>
						<td class="tdWidth" data-title="Created By">[[obj.createdBy]]</td>
						<td>
							<a  href="javascript:;" type="button" class="btn-sm btn btn-primary " data-toggle="modal" data-target="#modalPopup" ng-click='renderInquiryFolloups([[obj.id]],[[obj.inquiry_no]],[[obj.inquiry_status]])'>FOLLOW UP</a>
					    </td>
                        <td>
							<a ng-if="{{defined('EDIT') && EDIT}}"  href="javascript:;" type="button" ng-show="[[obj.inquiry_status!='open']]"  title="Inquiry [[obj.inquiry_status]]!"  class="cursorNoDrop btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>							
							<a ng-if="{{defined('EDIT') && EDIT}}"  href="javascript:;" type="button" ng-show="[[obj.inquiry_status=='open']]" title="Edit" class="btn btn-primary btn-sm" ng-click="editInquiry([[obj.id]])"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>	
						</td>				
						<td>
						    <a ng-if="{{defined('DELETE') && DELETE}}"  href="javascript:;" type="button" title="Delete" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteInquiry([[obj.id]])"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
        			</tr>
					<tr ng-hide="inquirydata.length"  class="noRecord"><td colspan="8">No Record Found!</td></tr>
				</tbody>
        	</table>
			<div ng-if="inquirydataPaginate" class="text-left">
				<div class="box-footer clearfix">
					<dir-pagination-controls></dir-pagination-controls>
				</div>		
			</div>
		<!-- **************************************followup**************************** -->	
			<!-- Modal fullscreen to view detail start -->
			<div class="modal modal-fullscreen fade" data-backdrop="static" id="modalPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" ng-click="closeFollowupPopup(currentInquiryNumber)" >
						<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
					</button>
					<h2 class="modal-title" id="myModalLabel">Inquiry Details</h2>
				  </div>
				  <div class="modal-body">
											
				<!--display Messge Div-->
				@include('includes.alertMessagePopup')
				<!--/display Messge Div-->
														
				<div class="row">
							<div class="col-xs-6">
								Inquiry Number: <b ng-bind="currentInquiryNumber"></b>
							</div>
						</div>
						<hr>
							
						<div class="row header">
								<strong class="pull-left headerText" id="myModalLabe2">Follow ups List</strong>	
								<div class="navbar-form navbar-right">
									<div class="form-group">
										<div class="navbar-form navbar-right" role="search">
											<div class="nav-custom">
											   <input type="text" style="margin-top:-7px;" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchfollowups">
											   <a ng-if="{{defined('ADD') && ADD}}" href="javascript:;" type="button" style="margin-right: 3px;margin-top:-6px;" class="btn btn-primary addFolloupButton" ng-hide="currentInquiryStatus!='open'" ng-show="add_followup_btn" data-toggle="modal" data-id=[[currentInquiryID]] ng-click="addFollowupShow(currentInquiryID,currentInquiryNumber,currentInquiryStatus)">Add New</a>
											</div>
										</div>
									</div>
								</div>
						</div>										
						<div class="row">								
							<!-- show folloup list start here  -->
							<table class="col-sm-12 table-striped table-condensed cf">
								<thead class="cf">
									<tr>
										<th class="tdWidth"><label>Follow By</label></th>
										<th><label>Mode</label></th>
										<th style="width: 176px;"><label>Follow-Up Date</label></th>
										<th class="followup"><label>Next follow-up Date</label></th>
										<th class=""><label>Followup Detail</label></th>
										<th><label>Status</label></th>
										<th class="tdWidth"><label>Created By</label></th>
										<th colspan="3"><label>Action</label></th>
									</tr>
								</thead>
								<tbody>
									<tr dir-paginate="obj in inquiryFolloupdata | filter:searchfollowups | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 5 }}">
										<td data-title="Employee Name">[[obj.employeename]]</td>
										<td data-title="Mode">[[obj.mode]]</td>
										<td data-title="Created At">[[obj.created_at]]</td>
										<td data-title="Next Followup">[[obj.next_followup_date!='0000-00-00'?obj.next_followup_date:'-' | date : 'dd-MM-yyyy']]</td>
										<td data-title="Followup  Detail">
											<span id="followuplimitedText-[[obj.followup_id]]">
												[[ obj.followup_detail | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 200 }} ]]
												<a href="javascript:;" ng-click="toggleDescription('followup',obj.followup_id)" ng-show="obj.followup_detail.length > 150" class="readMore">read more...</a>
											</span>
											<span style="display:none;" id="followupfullText-[[obj.followup_id]]">
												[[ obj.followup_detail]] 
												<a href="javascript:;" ng-click="toggleDescription('followup',obj.followup_id)" class="readMore">read less...</a>
											</span>
										</td>
										<td class="text-upper" data-title="Status"><span class="[[obj.status]]">[[obj.status]]</span></td>											
										<td data-title="Created By">[[obj.createdBy]]</td>
										<td data-title="action">
											<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" type="button" ng-show="[[obj.inquiry_status!='open']]" title="Inquiry [[obj.inquiry_status]]!"  class="cursorNoDrop btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>			
											<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" type="button" ng-show="[[obj.inquiry_status=='open']]" title="Edit" class="btn btn-primary btn-sm" ng-click="editFollowupInquiry([[obj.followup_id]])"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>							
										</td>
										<td>	
											<a ng-if="{{defined('DELETE') && DELETE}}" type="button" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteInquiryFolloup([[obj.followup_id]],currentInquiryID,currentInquiryNumber,currentInquiryStatus)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
										</td>
									</tr>
									<tr ng-hide="inquiryFolloupdata.length"  class="noRecord"><td colspan="6">No Record Found!</td></tr>										
								</tbody>	
							</table>	
							<!-- show folloup list end here  -->
							<div ng-if="inquiryFolloupdataPaginate" class="text-left">										
									<div class="box-footer clearfix">
										<dir-pagination-controls></dir-pagination-controls>
									</div>			
							</div>
						</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-primary" ng-click="closeFollowupPopup(currentInquiryNumber)">Close</button>
				  </div>
				</div>
			  </div>
			</div>
			<!-- Modal fullscreen to view detail end -->							
		<!-- **************************************follow-up**************************** -->
		
		<!-- ************************************** Start add_inquiry Modal **************************** -->
		 @include('inquiry.inquiry.add')
		<!-- ************************************** ednd add_inquiry Modal **************************** -->
		
		<!-- **************************************edit Start edit_inquiry Modal **************************** -->
		 @include('inquiry.inquiry.edit')
		<!-- **************************************edit ednd edit_inquiry Modal **************************** -->
		
		<!-- **************************************edit Start edit_followup Modal ****************************--> 
		 @include('inquiry.followup.add')
		<!-- **************************************edit ednd edit_followup Modal **************************** -->
		
		<!-- **************************************edit Start edit_followup Modal ****************************--> 
		 @include('inquiry.followup.edit')
		<!-- **************************************edit ednd edit_followup Modal **************************** --> 
    </div>
</div>
</div>
@endsection
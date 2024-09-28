@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="customerController" ng-init="showUploadForm();">

	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--display Messge Div-->
	@include('includes.uploadMessage')
	<!--/display Messge Div-->
	
	<div id="uploadCustomerPreviewListing" >	
		<!--display Heading-->
		<div class="row header">
			<strong class="pull-left headerText">Customers</strong>	
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
				  <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchCustList">
				  <a title="Upload Record" class="btn btn-primary" ng-click="cancelUpload()">Back</a>
			</div></div>
		</div>	
		<!--/display Heading-->
		
		<!--display Listing of Customer-->
		<div class="row">
			<div id="no-more-tables" style="width: 1170px!important;overflow: hidden;overflow-x: scroll;">
				 <!-- show error message -->
				<table class="col-sm-12 table-striped table-condensed cf">
					<thead class="cf">
						<tr>
							<th ng-repeat="heading in cusomersListHeader">
								<label>[[heading]]</label>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="obj in cusomersListDataDisplay | filter: searchCustList">
							<td ng-repeat="(key, field) in obj">
							   <span class="color-green" ng-if="field == 'Success' && $index == '0'">[[field?field:'-']]</span>
							   <span class="color-cyan" ng-if="field == 'Duplicate customer code, email or phone number!' && $index == '0'">[[field?field:'-']]</span>
							   <span class="color-red" ng-if="field != 'Success' && field != 'Duplicate customer code, email or phone number!' && $index == '0'">[[field?field:'-']]</span>
							   <span ng-if="$index != '0'">[[field?field:'-']]</span>
							</td>
						</tr>
						<tr ng-show="numberOfSubmitedRecords.length">
							<td colspan="16">
								<button title="Save" type='button' class='btn btn-primary' ng-click='customerUploadCSV()'> Upload </button>
								<button title="Cancel" type='button' class='btn btn-default' ng-click='cancelUpload()'> Cancel </button>
							</td>
						</tr>
						<tr ng-hide="cusomersListDataDisplay.length" class="noRecord"><td colspan="16">No Record Found!</td></tr>
					</tbody>
				</table>	  
			</div>
		</div>
		<!--/display Listing of Customer-->
	</div>
		
		<!--display Add Form Popup-->
        @include('master.customer_master.customers.upload_form')
        <!--/display Add Form Popup-->

	<script type="text/javascript" src="{!! asset('public/ang/controller/customerController.js') !!}"></script>	
	<style>option {overflow: hidden;text-overflow: ellipsis;width: 250px;}</style>
	
</div>
@endsection
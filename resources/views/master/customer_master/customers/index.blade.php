@extends('layouts.app')

@section('content')

<div class="container" ng-controller="customerController" ng-init="funGetCustomers(limitFrom,limitTo);billingFunc();">

	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->

	<div class="row" ng-show="showCustomerEmailSection">
		@include('master.customer_master.customers.customer_emails')
	</div>

	<div ng-show="showCustomerSection">

		<!--customers list-->
		@include('master.customer_master.customers.list')
		<!--/customers list-->

		<!--customers add form-->
		@include('master.customer_master.customers.add')
		<!--/customers add form-->

		<!--customers edit form---->
		@include('master.customer_master.customers.edit')
		<!--/customers edit form---->

		<!--customers view form-->
		@include('master.customer_master.customers.view')
		<!--/customers view form-->

		<!--GST NUMBER POPUP-->
		@include('master.customer_master.customers.gstNumberPopUp')
		<!--/GST NUMBER POPUP-->

		<!--Edit GST NUMBER POPUP-->
		@include('master.customer_master.customers.editGstNumberPopUp')
		<!--/Edit GST NUMBER POPUP-->

	</div>

</div>

<style>option {overflow: hidden;text-overflow: ellipsis;width: 250px;}</style>
<script type="text/javascript" src="{!! asset('public/ang/controller/customerController.js') !!}"></script>
@endsection
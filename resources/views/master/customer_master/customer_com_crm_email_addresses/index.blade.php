@extends('layouts.app')

@section('content')

<div class="container" ng-controller="customerComCrmEmailAddressController" ng-init="funGetBranchWiseCustomerComCrms()">

	<!--*****display Messge Div* *******-->
	@include('includes.alertMessage')
	<!--****/display Messge Div********-->

	<!-- *****add Method form start****-->
	@include('master.customer_master.customer_com_crm_email_addresses.add')
	<!--*****/add Method form start***-->

	<!--***edit Method form start*****-->
	@include('master.customer_master.customer_com_crm_email_addresses.edit')
	<!--***/edit Method form start*****-->

	<!-- ***list Method start*********-->
	@include('master.customer_master.customer_com_crm_email_addresses.list')
	<!--***/list Method start********-->
</div>

<script type="text/javascript" src="{!! asset('public/ang/controller/customerComCrmEmailAddressController.js') !!}"></script>
@endsection

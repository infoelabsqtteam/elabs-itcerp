@extends('layouts.app')

@section('content')

<div class="container" ng-controller="customerExistAccountHoldUploadDtlController" ng-init="funGetUploadMasterList()">

	<!--*****display Messge Div* *******-->
	@include('includes.alertMessage')
	<!--****/display Messge Div********-->

	<!-- *****add Method form start****-->
	@include('master.customer_master.customer_exist_account_hold_upload.add')
	<!--*****/add Method form start***-->

	<!-- ***list Method start*********-->
	@include('master.customer_master.customer_exist_account_hold_upload.list')
	<!--***/list Method start********-->
</div>

<script type="text/javascript" src="{!! asset('public/ang/controller/customerExistAccountHoldUploadDtlController.js') !!}"></script>
@endsection

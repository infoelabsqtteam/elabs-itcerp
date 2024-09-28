@extends('layouts.app')

@section('content')

<div class="container" ng-controller="customerDefinedStructureController" ng-init="funGetCustomers();funGetParentCategory();funGetCustomersDefinedInvoicings();">

	<!--*****display Messge Div* *******-->
	@include('includes.alertMessage')
	<!--****/display Messge Div********-->

	<!-- *****add Method form start****-->
	@include('master.customer_master.customer_defined_structure.add')
	<!--*****/add Method form start***-->

	<!--***edit Method form start*****-->
	@include('master.customer_master.customer_defined_structure.edit')
	<!--***/edit Method form start*****-->

	<!-- ***list Method start*********-->
	@include('master.customer_master.customer_defined_structure.list')
	<!--***/list Method start********-->
</div>

<script type="text/javascript" src="{!! asset('public/ang/controller/customerDefinedStructureController.js') !!}"></script>
@endsection

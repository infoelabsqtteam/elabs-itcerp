@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="customerDefinedInvoicingTypeController" ng-init="funGetCustomers();funGetParentCategory();funGetCustomersDefinedInvoicings();">
		
	<!--*****display Messge Div*index.blade/opt/lampp/htdocs/itcerp/resources/views/master/customer_master/customer_defined_invoicing_type/index.blade.php.php *******-->	
	@include('includes.alertMessage')
	<!--****/display Messge Div********-->	

	<!-- *****add Method form start****-->
	@include('master.customer_master.customer_defined_invoicing_type.add')
	<!--*****/add Method form start***-->
	
	<!--***edit Method form start*****-->
	@include('master.customer_master.customer_defined_invoicing_type.edit')
	<!--***/edit Method form start*****-->
	
	<!-- ***list Method start*********-->
	@include('master.customer_master.customer_defined_invoicing_type.list')
	<!--***/list Method start********-->
</div>
	
<script type="text/javascript" src="{!! asset('public/ang/controller/customerDefinedInvoicingTypeController.js') !!}"></script>
@endsection
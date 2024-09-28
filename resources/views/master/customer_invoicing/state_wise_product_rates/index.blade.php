@extends('layouts.app')

@section('content')

<div class="container" ng-controller="invoicingTypeStateWiseProductsController" ng-init="funGetStateWiseProductRates(cirStateID,'1',1);funGetParentCategory();funGetStateAccToDept('1','1')">

	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->

	<!--Adding of store-->
	<div ng-show="{{defined('ADD') && ADD}}">
		@include('master.customer_invoicing.state_wise_product_rates.add')
	</div>
	<!--/Adding of store-->

	<!--Editing of store-->
	<div ng-show="{{defined('EDIT') && EDIT}}">
		@include('master.customer_invoicing.state_wise_product_rates.edit')
	</div>
	<!--/Editing of store-->

	<!--Display of Store Listing-->
	<div ng-show="{{defined('VIEW') && VIEW}}">
		@include('master.customer_invoicing.state_wise_product_rates.list')
	</div>
	<!--/Display of Store Listing-->

</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/invoicingTypeStateWiseProductsController.js') !!}"></script>
@endsection

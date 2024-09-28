@extends('layouts.app')

@section('content')

<div data="{{defined('VIEW') && VIEW}}" class="container" ng-controller="invoicingTypeCustomerWiseProductsController" ng-init="">

    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->

    <!--Adding-->
    @if(defined('ADD') && ADD)
    	@include('master.customer_invoicing.customer_wise_product_rates.add')
    @endif
    <!--/Adding-->

    <!--Editing -->
    @if(defined('EDIT') && EDIT)
    	@include('master.customer_invoicing.customer_wise_product_rates.edit')
    @endif
    <!--/Editing-->

    <!--Listing-->
    @if(defined('VIEW') && VIEW)
    	@include('master.customer_invoicing.customer_wise_product_rates.list')
    @endif
    <!--/Listing-->

    <!--tree view-->
    @if(defined('VIEW') && VIEW)
    	@include('master.customer_invoicing.customer_wise_product_rates.countryStateTreeViewPopup')
    @endif
    <!--/tree view-->

	<!--Editing of editFixedRate-->
    @if(defined('EDIT') && EDIT)
    	@include('master.customer_invoicing.customer_wise_product_rates.editFixedRate')
	@endif
    <!--/Editing of editFixedRate-->

</div>

<script type="text/javascript" src="{!! asset('public/ang/controller/invoicingTypeCustomerWiseProductsController.js') !!}"></script>
@endsection

@extends('layouts.app')

@section('content')

<div class="container" ng-controller="invoicingTypeCustomerWiseParametersController" ng-init="">

    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->

    <!--Adding of store-->
    <div ng-show="{{defined('ADD') && ADD}}">
        @include('master.customer_invoicing.customer_wise_parameter_rates.add')
    </div>
    <!--/Adding of store-->

    <!--Editing of store-->
    <div ng-show="{{defined('EDIT') && EDIT}}">
        @include('master.customer_invoicing.customer_wise_parameter_rates.edit')
    </div>
    <!--/Editing of store-->

    <!--Display of Store Listing-->
    <div ng-show="{{defined('VIEW') && VIEW}}">
        @include('master.customer_invoicing.customer_wise_parameter_rates.list')
    </div>
    <!--/Display of Store Listing-->

    <!--state tree view--
	@include('master.customer_invoicing.customer_wise_parameter_rates.stateCityTreeViewPopup')
	<!--/state tree view-->

    <!--country state tree view-->
    @include('master.customer_invoicing.customer_wise_parameter_rates.countryStateTreeViewPopup')
    <!--country state tree view-->
</div>

<script type="text/javascript" src="{!! asset('public/ang/controller/invoicingTypeCustomerWiseParametersController.js') !!}"></script>
@endsection

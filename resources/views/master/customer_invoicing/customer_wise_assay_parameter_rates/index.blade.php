@extends('layouts.app')

@section('content')

<div class="container" ng-controller="invoicingTypeCustomerWiseAssayParametersController" ng-init="funGetCustomerWiseAssayParameterList(0,2);">
	
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--Adding of store-->
    @if(defined('ADD') && ADD)
	@include('master.customer_invoicing.customer_wise_assay_parameter_rates.add')
    @endif
    <!--/Adding of store-->
    
    <!--Editing of store-->
    @if(defined('EDIT') && EDIT)
	@include('master.customer_invoicing.customer_wise_assay_parameter_rates.edit')
    @endif
    <!--/Editing of store-->
    
    <!--Display of Store Listing-->
    @if(defined('VIEW') && VIEW)
	@include('master.customer_invoicing.customer_wise_assay_parameter_rates.list')		
    @endif
    <!--/Display of Store Listing-->
    
    <!--state tree view-->
    @if(defined('VIEW') && VIEW)
	<!--include('master.customer_invoicing.customer_wise_assay_parameter_rates.assayStateCityTreeViewPopup')-->
   <!--country state tree view-->
    @include('master.customer_invoicing.customer_wise_assay_parameter_rates.countryStateTreeViewPopup')
	 <!--country state tree view-->
    @endif
    <!--/state tree view-->
    
    <!--Parameter Category tree view-->
    @if(defined('VIEW') && VIEW)
	@include('master.customer_invoicing.customer_wise_assay_parameter_rates.assayParameterCategoryTreePopup')
    @endif
    <!--/Parameter Category tree view-->
    
</div>
    
<script type="text/javascript" src="{!! asset('public/ang/controller/invoicingTypeCustomerWiseAssayParametersController.js') !!}"></script>
@endsection
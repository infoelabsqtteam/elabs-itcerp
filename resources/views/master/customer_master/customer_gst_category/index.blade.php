@extends('layouts.app')

@section('content')

<div class="container" ng-controller="customerGstCategoryController" ng-init="funGetCustomerGstCategory()">
    
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--Adding Form--> 
    @if(defined('ADD') && ADD)
	@include('master.customer_master.customer_gst_category.add')
    @endif
    <!--Adding Form-->
    
    <!--Editing Form--> 
    @if(defined('EDIT') && EDIT)
	@include('master.customer_master.customer_gst_category.edit')
    @endif
    <!--/Editing Form--> 
    
    <!--Viewing Form--> 
    @if(defined('VIEW') && VIEW)
	@include('master.customer_master.customer_gst_category.list')
    @endif
    <!--/Viewing Form--> 
   
</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/customerGstCategoryController.js') !!}"></script>
@endsection
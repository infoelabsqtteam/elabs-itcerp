@extends('layouts.app')

@section('content')

<div ng-controller="productMasterAliasController" class="container ng-scope" ng-init="">
    
	<!--display Messge Div funGetCustomerProductsList();funGetProductList(0);-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--adding of module form-->
	<div ng-show="{{defined('VIEW') && VIEW}}">
		@include('master.customer_invoicing.product_master_alias.list')
	</div>
	<!--/adding of module form-->
	
	<!--adding of module form-->
	<div ng-show="{{defined('ADD') && ADD}}">
		@include('master.customer_invoicing.product_master_alias.add')
	</div>
	<!--adding of module form-->
	
	<!--adding of module form-->
	<div ng-show="{{defined('EDIT') && EDIT}}">
		@include('master.customer_invoicing.product_master_alias.edit')
	</div>
	<!--adding of module form-->
	
	<!--adding of module form-->
	<div ng-show="{{defined('EDIT') && EDIT}}">
		@include('master.customer_invoicing.product_master_alias.productCategoryTreePopup')
	</div>
	<!--adding of module form-->
</div>
    
<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/productMasterAliasController.js') !!}"></script>
    
@endsection
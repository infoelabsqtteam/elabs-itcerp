@extends('layouts.app')

@section('content')

<div class="container" ng-controller="productController" ng-init="getProducts(seachCategoryId)">
	
	@include('includes.alertMessage')
	
	<div class="row">
		<!-- **************************************add Product form start**************************** -->
		@include('master.products.add')
		<!-- **********************************add product form  start**************************** -->
		
		<!-- **************************************add Product form start**************************** -->
		@include('master.products.upload')
		<!-- **********************************add product form  start**************************** -->
	   
	   <!-- **************************************edit Product form start**************************** -->
		@include('master.products.edit')
		<!-- **********************************edit product form  start**************************** -->
	</div>
		
	<!-- **************************************list Product form start**************************** -->
	@include('master.products.list')
	<!-- **********************************list product form  start**************************** -->

	<!-- **************************************add Product form start**************************** -->
	<div ng-if="productCategoriesTree.length">
		@include('master.products.productCategoryTreePopup')
	</div>
	<!-- **********************************add product form  start**************************** -->
</div>

<script type="text/javascript" src="{!! asset('public/ang/controller/productController.js') !!}"></script>
@endsection
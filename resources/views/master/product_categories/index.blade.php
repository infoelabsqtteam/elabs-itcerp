@extends('layouts.app')

@section('content')

<div class="container " ng-controller="productCategoryController" ng-init="getProductCategory();categoryFun()" >
@include('includes.alertMessage')	
		<div class="row">
			<!--**************************************add Product form start****************************-->
			@include('master.product_categories.add')
			@include('master.product_categories.upload')
			<!-- **********************************add product form  start**************************** -->
			
			<!-- **************************************edit Product form start**************************** -->
			@include('master.product_categories.edit')	
			<!-- **********************************edit product form  start**************************** -->
			
		</div>
		
		<!-- **************************************list Product form start*************************-->
		@include('master.product_categories.list')	
		<!-- **********************************list product form  start**************************** -->
		
		<!-- **************************************add Product form start**************************** -->
		<div ng-if="productCategoriesTree.length">
			@include('master.product_categories.productCategoryTreePopup')
		</div>
		<!-- **********************************add product form  start**************************** -->
</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/productCategoryController.js') !!}"></script>	

@endsection
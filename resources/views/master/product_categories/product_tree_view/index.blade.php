@extends('layouts.app')

@section('content')

<div class="container" ng-controller="productCategoryController" ng-init="getProductCategories();hideTreeForms();generateDefaultCode();showProductCatTreeViewPopUp(13)" >
@include('includes.alertMessage')	
		<div class="row">
			<!--**************************************add Product form start****************************-->
			@include('master.product_categories.product_tree_view.add')
			<!-- **********************************add product form  start**************************** -->
			
			<!-- **************************************edit Product form start**************************** -->
			@include('master.product_categories.product_tree_view.edit')	
			<!-- **********************************edit product form  start**************************** -->
			
		</div>
		
		<!-- **************************************list Product form start*************************-->
		@include('master.product_categories.product_tree_view.list')	
		<!-- **********************************list product form  start**************************** -->

</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/productCategoryController.js') !!}"></script>	

@endsection
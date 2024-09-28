@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/itemCategoryController.js') !!}"></script>	
<div class="container " ng-controller="itemCategoryController" ng-init="getItemCategory();categoryFun()" >
		<div class="row">	
			<div class="alert alert-success" role="alert" ng-hide="successMessage" ng-model="successMessage" ng-click="hideAlert()">
				<a class="close" title="close">×</a>
				<span ng-bind="successMsg"></span> 
			</div>
			<div class="alert alert-danger" role="alert"  ng-hide="errorMessage"  ng-model="errorMessage" ng-click="hideAlert()">
				<a  class="close" title="close">×</a>
				<span ng-bind="errorMsg"></span>
			</div> 
		</div>	
		<div class="row">
			<!-- **************************************add Item form start**************************** -->
			@include('inventory.item_master.item_categories.add')
			<!-- **********************************add item form  start**************************** -->
			
			<!-- **************************************edit Item form start**************************** -->
			 @include('inventory.item_master.item_categories.edit')
			<!-- **********************************edit item form  start**************************** -->	
		</div>
		<!-- **************************************edit Item form start**************************** -->
		 @include('inventory.item_master.item_categories.list')
		<!-- **********************************edit item form  start**************************** -->
</div>
@endsection
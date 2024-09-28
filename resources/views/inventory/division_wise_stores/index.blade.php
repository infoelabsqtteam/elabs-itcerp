@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="DivisionWiseStoresController" ng-init="funGetBranchWiseStores({{$division_id}})">
	
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--Adding of store-->
	<div ng-show="{{defined('ADD') && ADD}}">
		@include('inventory.division_wise_stores.add')
	</div>
	<!--/Adding of store-->
	
	<!--Editing of store-->
	<div ng-show="{{defined('EDIT') && EDIT}}">
		@include('inventory.division_wise_stores.edit')
	</div>
	<!--/Editing of store-->
	
	<!--Display of Store Listing-->
	<div ng-show="{{defined('VIEW') && VIEW}}">
		@include('inventory.division_wise_stores.list')
	</div>
	<!--/Display of Store Listing-->
	
</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/DivisionWiseStoresController.js') !!}"></script>
@endsection
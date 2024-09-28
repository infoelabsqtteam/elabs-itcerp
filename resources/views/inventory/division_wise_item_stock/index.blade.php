@extends('layouts.app')

@section('content')

<div class="container" ng-controller="DivisionWiseItemStocksController" ng-init="funGetBranchWiseItemStocks({{$division_id}})">
	
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--Adding of store-->
	<div ng-show="{{defined('ADD') && ADD}}">
		@include('inventory.division_wise_item_stock.add')
	</div>
	<!--/Adding of store-->
	
	<!--Editing of store-->
	<div ng-show="{{defined('EDIT') && EDIT}}">
		@include('inventory.division_wise_item_stock.edit')
	</div>
	<!--/Editing of store-->
	
	<!--Display of Store Listing-->
	<div ng-show="{{defined('VIEW') && VIEW}}">
		@include('inventory.division_wise_item_stock.list')
	</div>
	<!--/Display of Store Listing-->
	
</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/DivisionWiseItemStocksController.js') !!}"></script>
@endsection
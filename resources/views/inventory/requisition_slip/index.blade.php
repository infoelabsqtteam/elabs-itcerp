@extends('layouts.app')
@section('content')
	
<div class="container" ng-controller="requisitionController" ng-init="getRequisitions({{$division_id}});">
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--display Listing of Requisition-->
	@include('inventory.requisition_slip.list')
	<!--/display Listing of Requisition-->
		
	<!--display Add Form Popup-->
    @include('inventory.requisition_slip.add')
    <!--/display Add Form Popup-->
	
	<!--display Edit Form Popup-->
    @include('inventory.requisition_slip.edit')
    <!--/display edit Form Popup-->
	
	<script type="text/javascript" src="{!! asset('public/ang/controller/requisitionController.js') !!}"></script>	
</div>
@endsection
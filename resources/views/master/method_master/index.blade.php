@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="methodController" ng-init="getMethods(EquipmentTypeId)">
		
	<!--*****display Messge Div********-->	
	@include('includes.alertMessage')
	<!--****/display Messge Div********-->	

	<!-- *****add Method form start****-->
	@include('master.method_master.add')
	@include('master.method_master.upload')
	<!--*****/add Method form start***-->
	
	<!--***edit Method form start*****-->
	@include('master.method_master.edit')
	<!--***/edit Method form start*****-->
	
	<!-- ***list Method start*********-->
	@include('master.method_master.list')
	<!--***/list Method start********-->
</div>
	
<script type="text/javascript" src="{!! asset('public/ang/controller/methodController.js') !!}"></script>
@endsection
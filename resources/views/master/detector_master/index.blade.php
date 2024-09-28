@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="detectorController" ng-init="getAllDetectors(EquipmentTypeId)">
		
	<!--*****display Messge Div********-->	
	@include('includes.alertMessage')
	<!--****/display Messge Div********-->	

	<!-- *****add Method form start****-->
	@include('master.detector_master.add')
	@include('master.detector_master.upload')
	<!--*****/add Method form start***-->
	
	<!--***edit Method form start*****-->
	@include('master.detector_master.edit')
	<!--***/edit Method form start*****-->
	
	<!-- ***list Method start*********-->
	@include('master.detector_master.list')
	<!--***/list Method start********-->
</div>
	
<script type="text/javascript" src="{!! asset('public/ang/controller/detectorController.js') !!}"></script>
@endsection
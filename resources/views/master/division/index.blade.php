@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="divisionController" ng-init="getDivisions()">

	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
			
	<!---Adding of divisions--->
	@include('master.division.add')
	<!---/Adding of divisions--->
	
	<!---Editing of divisions--->
	@include('master.division.edit')
	<!---/Editing of divisions--->
	
	<!---Listing of divisions--->
	@include('master.division.list')
	<!---/Listing of divisions--->
		
	<!---Displaying of divisions--->
	@include('master.division.view')
	<!---/Displaying of divisions--->
</div>

<!--Incliding divisionController Js file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/divisionController.js') !!}"></script>

@endsection
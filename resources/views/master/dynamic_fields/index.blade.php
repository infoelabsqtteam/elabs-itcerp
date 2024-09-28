@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="dynamicFieldController" ng-init="getDynamicFields()">

	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
			
	<!---Adding of dynamic_fields--->
	@include('master.dynamic_fields.add')
	<!---/Adding of dynamic_fields--->
	
	<!---Editing of dynamic_fields--->
	@include('master.dynamic_fields.edit')
	<!---/Editing of dynamic_fields--->
	
	<!---Listing of dynamic_fields--->
	@include('master.dynamic_fields.list')
	<!---/Listing of dynamic_fields--->
		
	<!---Displaying of dynamic_fields--->
	@include('master.dynamic_fields.view')
	<!---/Displaying of dynamic_fields--->
</div>

<!--Incliding dynamicFieldController Js file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/dynamicFieldController.js') !!}"></script>

@endsection
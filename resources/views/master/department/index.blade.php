@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/departmentController.js') !!}"></script>	
<div class="container" ng-controller="departmentController" ng-init="getDepartments()">
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	@if(defined('ADD') && ADD)
	<!-------------------add form start here------------------------->
		@include('master.department.add')
	<!---------------------add form end------------------------------>
	@endif
		 
	<!-------------------edit form start here------------------------>
	@include('master.department.edit')
	<!-------------------edit form end here--------------------------->
	
	<!-------------------list form end here--------------------------->
	@include('master.department.list')
	<!-------------------list form end here--------------------------->

</div>
@endsection
@extends('layouts.app')
@section('content')

<script type="text/javascript" src="{!! asset('public/ang/controller/companyController.js') !!}"></script>
<div class="container" ng-controller="companyController" ng-init="getCompanies()">
	
	<!--display Messge Div-->
	    @include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--Editing of Company Form-->
		@include('master.company_master.edit')
	<!--/Editing of Company Form-->
	
	<!--listing of Company-->
		@include('master.company_master.list')
	<!--listing of Company-->
	
</div>
@endsection
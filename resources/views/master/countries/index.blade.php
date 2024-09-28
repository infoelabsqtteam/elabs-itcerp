@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/countryController.js') !!}"></script>	
<div class="container" ng-controller="countryController" ng-init="getCountriesList();">
@include('includes.alertMessage')

@if(defined('ADD') && ADD)
<div class="row">
    <div class="panel panel-default">
		<div class="panel-body">
			<!-- **************************************add countries form start**************************** -->
			@include('master.countries.add')
			<!-- **********************************add countries form  start**************************** -->
		    <!-- **************************************edit countries form start**************************** -->
			@include('master.countries.edit')
			<!-- **********************************edit countries form  start**************************** -->
		</div>	
	</div>
</div>
@endif
<!-- **************************************add countries form start**************************** -->
@include('master.countries.list')
<!-- **********************************add countries form  start**************************** -->
</div>
@endsection
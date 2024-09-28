@extends('layouts.app')

@section('content')
	
<script type="text/javascript" src="{!! asset('public/ang/controller/unitConversionsController.js') !!}"></script>	
<div class="container" ng-controller="unitConversionsController" ng-init="getUnitConversions()">
<!--display Messge Div-->
@include('includes.alertMessage')
<!--/display Messge Div-->
	<div class="row header">
		<strong class="pull-left headerText">Unit Conversions</strong>	
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
				<input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchUnitCon">
				<button title="Add New Record" type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#add_form">Add New</button>
			</div>
		</div>
	</div>
	
	<!-------------------add form start here--------------------------->
	@include('inventory.units.conversion.add')
	<!-------------------add form end here--------------------------->
	
	<!-------------------edit form start here--------------------------->
	@include('inventory.units.conversion.edit')
	<!-------------------edit form end here--------------------------->
	
	<!-------------------list form start here--------------------------->
	@include('inventory.units.conversion.list')
	<!-------------------list form end here--------------------------->
	
</div>
@endsection
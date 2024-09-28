@extends('layouts.app')
@section('content')
	
<div class="container" ng-controller="unitController" ng-init="getUnits()">

	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--add form start here-->
	@include('inventory.units.add')
	<!--/add form end here-->
	
	<!--Edit form start here-->
	@include('inventory.units.edit')
	<!--/Edit form end here-->
    
    <!--List start here-->
	@include('inventory.units.list')
	<!--/List end here-->
	
</div>

<!--Including unitController js file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/unitController.js') !!}"></script>
@endsection
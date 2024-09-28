@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/stabilityTypeController.js') !!}"></script>	
<div class="container" ng-controller="stabilityTypeController">
@include('includes.alertMessage')

@if(defined('ADD') && ADD)
<div class="row">
    <div class="panel panel-default">
		<div class="panel-body">
			<!-- **************************************add hold form**************************** -->
			@include('master.stability_type_master.add')
		    <!-- **************************************edit hold form**************************** -->
			@include('master.stability_type_master.edit')
		</div>	
	</div>
</div>
@endif
<!-- **************************************list hold master data**************************** -->
@include('master.stability_type_master.list')
</div>
@endsection
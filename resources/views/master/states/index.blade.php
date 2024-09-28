@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/stateController.js') !!}"></script>	
<div class="container" ng-controller="stateController" ng-init="getStates()">
@include('includes.alertMessage')

@if(defined('ADD') && ADD)
<div class="row">
    <div class="panel panel-default">
		<div class="panel-body">
			<!-- **************************************add State form start**************************** -->
			@include('master.states.add')
			<!-- **********************************add state form  start**************************** -->
		    <!-- **************************************edit State form start**************************** -->
			@include('master.states.edit')
			<!-- **********************************edit state form  start**************************** -->
		</div>	
	</div>
</div>
@endif
<!-- **************************************add State form start**************************** -->
@include('master.states.list')
<!-- **********************************add state form  start**************************** -->
</div>
@endsection
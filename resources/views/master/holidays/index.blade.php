@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/holidaysController.js') !!}"></script>	
<div class="container" ng-controller="holidaysController">
@include('includes.alertMessage')

@if(defined('ADD') && ADD)
<div class="row">
    <div class="panel panel-default">
		<div class="panel-body">
			<!-- **************************************add State form start**************************** -->
			@include('master.holidays.add')
			<!-- **********************************add state form  start**************************** -->
		    <!-- **************************************edit State form start**************************** -->
			@include('master.holidays.edit')
			<!-- **********************************edit state form  start**************************** -->
		</div>	
	</div>
</div>
@endif
<!-- **************************************add State form start**************************** -->
@include('master.holidays.list')
<!-- **********************************add state form  start**************************** -->
</div>
@endsection
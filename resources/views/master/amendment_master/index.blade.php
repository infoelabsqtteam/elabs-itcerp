@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/amendmentController.js') !!}"></script>	
<div class="container" ng-controller="amendmentController">
@include('includes.alertMessage')

@if(defined('ADD') && ADD)
<div class="row">
    <div class="panel panel-default">
		<div class="panel-body">
			<!-- **************************************add State form start**************************** -->
			@include('master.amendment_master.add')
			<!-- **********************************add state form  start**************************** -->
		    <!-- **************************************edit State form start**************************** -->
			@include('master.amendment_master.edit')
			<!-- **********************************edit state form  start**************************** -->
		</div>	
	</div>
</div>
@endif
<!-- **************************************add State form start**************************** -->
@include('master.amendment_master.list')
<!-- **********************************add state form  start**************************** -->
</div>
@endsection
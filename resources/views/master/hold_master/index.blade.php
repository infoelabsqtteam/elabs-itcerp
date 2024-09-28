@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/holdController.js') !!}"></script>	
<div class="container" ng-controller="holdController">
@include('includes.alertMessage')

@if(defined('ADD') && ADD)
<div class="row">
    <div class="panel panel-default">
		<div class="panel-body">
			<!-- **************************************add hold form**************************** -->
			@include('master.hold_master.add')
		    <!-- **************************************edit hold form**************************** -->
			@include('master.hold_master.edit')
		</div>	
	</div>
</div>
@endif
<!-- **************************************list hold master data**************************** -->
@include('master.hold_master.list')
</div>
@endsection
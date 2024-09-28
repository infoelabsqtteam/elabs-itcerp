@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/cityController.js') !!}"></script>	
<div class="container" ng-controller="cityController" ng-init="getCities(0)">
@include('includes.alertMessage')
@if(defined('ADD') && ADD)
<div class="row">
    <div class="panel panel-default">
		<div class="panel-body">
			<!-- **************************************add City form start**************************** -->
			@include('master.cities.add')
			<!-- **********************************add city form  start**************************** -->
		    <!-- **************************************edit City form start**************************** -->
			@include('master..cities.edit')
			<!-- **********************************edit city form  start**************************** -->
		</div>	
	</div>
</div>
@endif
 <!-- **************************************edit City form start**************************** -->
 @include('master..cities.list')
 <!-- **********************************edit city form  start**************************** -->

</div>
@endsection
@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/ownershipController.js') !!}"></script>	
<div class="container" ng-controller="ownershipController" ng-init="getOwnerships()">
@include('includes.alertMessage')
@if(defined('ADD') && ADD)
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">
					<!-- **************************************add Ownership form start************************* -->
					@include('master.customer_master.ownership_type.add')
					<!-- **********************************add ownership form  start**************************** -->
					<!-- **************************************edit Ownership form start************************ -->
					@include('master.customer_master.ownership_type.edit')
					<!-- **********************************edit ownership form  start**************************** -->
				</div>	
			</div>
		</div>
		@endif
		<!-- **************************************add Ownership form start************************* -->
		@include('master.customer_master.ownership_type.list')
		<!-- **********************************add ownership form  start**************************** -->
</div>
@endsection
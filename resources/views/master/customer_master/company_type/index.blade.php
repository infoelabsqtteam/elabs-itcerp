@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/companyTypeController.js') !!}"></script>	
<div class="container" ng-controller="companyTypeController" ng-init="getCompanyTypes()">
@include('includes.alertMessage')
@if(defined('ADD') && ADD)
<div class="row">
    <div class="panel panel-default">
		<div class="panel-body">
			<!-- **************************************add Company type form start**************************** -->
				@include('master.customer_master.company_type.add')
			<!-- **********************************add company type form  start**************************** -->
		    
			<!-- **************************************edit Company type form start**************************** -->
				@include('master.customer_master.company_type.edit')
			<!-- **********************************edit company type form start**************************** -->
		</div>	
	</div>
</div>
@endif
	
<!--listing page-->
@include('master.customer_master.company_type.list')
<!--listing page-->
</div>
@endsection
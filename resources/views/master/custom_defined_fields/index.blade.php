@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="customDefinedFieldsController" ng-init="getCustomDefinedList()">
		
	<!--*****display Messge Div********-->	
	@include('includes.alertMessage')
	<!--****/display Messge Div********-->	

	<!-- *****add Method form start****-->
        @if(defined('ADD') && ADD)
            @include('master.custom_defined_fields.add')
        @endif
	<!--*****/add Method form start***-->
	
	<!--***edit Method form start*****-->
        @if(defined('EDIT') && EDIT)
            @include('master.custom_defined_fields.edit')
        @endif
	<!--***/edit Method form start*****-->
	
	<!-- ***list Method start*********-->
        @if(defined('VIEW') && VIEW)
            @include('master.custom_defined_fields.list')
        @endif
	<!--***/list Method start********-->
</div>
	
<script type="text/javascript" src="{!! asset('public/ang/controller/customDefinedFieldsController.js') !!}"></script>
@endsection
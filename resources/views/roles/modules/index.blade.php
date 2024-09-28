@extends('layouts.app')

@section('content')
    
<div ng-controller="modulesController" class="container ng-scope" ng-init="funGetModuleList(0);">
    
    <!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--adding of module form-->
	<div ng-show="{{defined('ADD') && ADD}}">
    @include('roles.modules.add')
	</div>
    <!--adding of module form-->
	
	<!--adding of module form-->
	<div ng-show="{{defined('EDIT') && EDIT}}">
		@include('roles.modules.edit')
	</div>
    <!--adding of module form-->
	
	<!--adding of module form-->
    @include('roles.modules.list')
    <!--adding of module form-->
    
</div>
    
<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/modulesController.js') !!}"></script>
    
@endsection
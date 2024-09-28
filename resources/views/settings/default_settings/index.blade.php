@extends('layouts.app')

@section('content')
    
<div ng-controller="settingController" class="container" ng-init = "getDefaultSettingList();">
    <!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
    
    <!--order form-->
    @include('settings.default_settings.add')
    <!--/order form-->
    
    <!--order form-->
    @include('settings.default_settings.edit')
    <!--/order form-->
    
    <!--Order Listing div-->
    @include('settings.default_settings.list')
    <!--/Order Listing div-->
	    
</div>
    
<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/settingController.js') !!}"></script>
@endsection
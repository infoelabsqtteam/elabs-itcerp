@extends('layouts.app')

@section('content')

<div class="container" ng-controller="userSalesTargetDetailController" ng-init="funGetUserSalesTargetListing()">
    
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--Adding Form--> 
    @if(defined('ADD') && ADD)
	@include('master.employee.userSalesTargertMaster.add')
        @include('master.employee.userSalesTargertMaster.upload')
    @endif
    <!--Adding Form-->
    
    <!--Adding Form--> 
    @if(defined('EDIT') && EDIT)
	@include('master.employee.userSalesTargertMaster.edit')
    @endif
    <!--Adding Form-->
    
    <!--Viewing Form--> 
    @if(defined('VIEW') && VIEW)
	@include('master.employee.userSalesTargertMaster.list')
    @endif
    <!--/Viewing Form--> 
   
</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/userSalesTargetDetailController.js') !!}"></script>
@endsection
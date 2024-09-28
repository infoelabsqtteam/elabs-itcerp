@extends('layouts.app')

@section('content')

<!--container-->
<div class="container" ng-controller="employeeController" ng-init="funGetEmployees({{$division_id}})">
	
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--Add Employee-->
    @if(defined('ADD') && ADD)
        @include('master.employee.add')
    @endif
    <!--/Add Employee-->
    
    <!--Edit Employee-->
    @if(defined('EDIT') && EDIT)
        @include('master.employee.edit')
    @endif
    <!--/Edit Employee-->
    
    <!--listing-->
    @if(defined('VIEW') && VIEW)
        @include('master.employee.list')
    @endif
    <!--/listing-->
		
</div>
<!--container-->

<style>option {overflow: hidden;text-overflow: ellipsis;width: 250px;}</style>
<script type="text/javascript" src="{!! asset('public/ang/controller/employeeController.js') !!}"></script>	
@endsection
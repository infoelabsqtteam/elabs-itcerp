@extends('layouts.app')

@section('content')

<div class="container" ng-controller="scheduledMisReportDtlController" ng-init="funGetScheduledMisReport()">
    
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--Adding Form--> 
    @if(defined('ADD') && ADD)
	@include('MIS.ScheduledMISReports.add')
    @endif
    <!--Adding Form-->
    
    <!--Editing Form--> 
    @if(defined('EDIT') && EDIT)
	@include('MIS.ScheduledMISReports.edit')
    @endif
    <!--/Editing Form--> 
    
    <!--Viewing Form--> 
    @if(defined('VIEW') && VIEW)
	@include('MIS.ScheduledMISReports.list')
    @endif
    <!--/Viewing Form--> 
   
</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/scheduledMisReportDtlController.js') !!}"></script>
@endsection
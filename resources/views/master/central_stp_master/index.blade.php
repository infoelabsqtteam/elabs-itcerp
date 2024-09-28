@extends('layouts.app')

@section('content')

<div class="container" ng-controller="centralStpDtlController" ng-init="funGetCentralContentListing()">
    
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--Adding Form--> 
    @if(defined('ADD') && ADD)
	@include('master.central_stp_master.add')
    @endif
    <!--Adding Form-->
    
    <!--Viewing Form--> 
    @if(defined('VIEW') && VIEW)
	@include('master.central_stp_master.list')
    @endif
    <!--/Viewing Form--> 
   
</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/centralStpDtlController.js') !!}"></script>
@endsection
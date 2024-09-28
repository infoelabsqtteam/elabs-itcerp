@extends('layouts.app')

@section('content')
    
<div ng-controller="stabilityOrderPrototypesController" class="container ng-scope" ng-init="funGetStabilityOrdersList();">
    
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--Stability Order Listing div-->
    @if(defined('VIEW') && VIEW)
        @include('sales.stability_orders.list')
    @endif
    <!--/Stability Order Listing div-->
    
    <!--Stability Order Add div-->
    @if((defined('ADD') && ADD) && (defined('IS_ADMIN') || defined('IS_ORDER_BOOKER')))
        @include('sales.stability_orders.add')
    @endif
    <!--/Stability Order Add div-->
    
    <!--Stability Order Edit div-->
    @if((defined('EDIT') && EDIT) && (defined('IS_ADMIN') || defined('IS_ORDER_BOOKER')))
        @include('sales.stability_orders.edit')
    @endif
    <!--/Stability Order Edit div-->
    
    <!--Stability Order Notification div-->
    @if((defined('VIEW') && VIEW) && (defined('IS_ADMIN') || defined('IS_ORDER_BOOKER')))
        @include('sales.stability_orders.notification')
    @endif
    <!--/Stability Order Notification div-->
    
</div>
    
<!--including angular controller file ,minDate: 0,maxDate: 30-->
<script>$(function(){$('.stb_sampling_datepicker').datetimepicker({dateFormat: "dd-mm-yy" ,timeFormat: "hh:mm tt",maxDate: 0, hourMax: 24, numberOfMonths: 1});});</script>
<script>$(function(){$('.stb_prototype_datepicker').datepicker({dateFormat: "dd-mm-yy",maxDate: 0,numberOfMonths: 1});});</script>
<script type="text/javascript" src="{!! asset('public/ang/controller/stabilityOrderPrototypesController.js') !!}"></script>
@endsection
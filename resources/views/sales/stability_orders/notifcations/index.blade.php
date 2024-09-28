@extends('layouts.app')

@section('content')
    
<div ng-controller="stabilityOrdersController" class="container ng-scope" ng-init="funGetStabilityOrderNotificationList();">
    
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--Stability Order Listing div-->
    @if(defined('VIEW') && VIEW)
        @include('sales.stability_orders.notifcations.list')
    @endif
    <!--/Stability Order Listing div-->
    
    <!--Stability Order Listing div-->
    @if(defined('VIEW') && VIEW)
        @include('sales.stability_orders.notifcations.preview')
    @endif
    <!--/Stability Order Listing div-->
    
    <!--order form-->
    @if(defined('ADD') && ADD)
        @include('sales.stability_orders.notifcations.add')
    @endif
    <!--/order form-->
    
</div>
    
<!--including angular controller file ,minDate: 0,maxDate: 30-->
<script>$(function(){$('#sampling_date_add').datetimepicker({dateFormat: "dd-mm-yy" ,timeFormat: "hh:mm tt",maxDate: 0, hourMax: 24, numberOfMonths: 1});});</script>
<script>$(function(){$('#sampling_date_edit').datetimepicker({dateFormat: "dd-mm-yy" ,timeFormat: "hh:mm tt",maxDate: 0, hourMax: 24, numberOfMonths: 1});});</script>
<script>$(function(){$('#sampling_date_saveas').datetimepicker({dateFormat: "dd-mm-yy" ,timeFormat: "hh:mm tt",maxDate: 0, hourMax: 24, numberOfMonths: 1});});</script>
<script>$(function(){$('.order_date_add').datepicker({dateFormat: "dd-mm-yy",maxDate: 0,numberOfMonths: 1});});</script>
<script type="text/javascript" src="{!! asset('public/ang/controller/stabilityOrdersController.js') !!}"></script>
@endsection
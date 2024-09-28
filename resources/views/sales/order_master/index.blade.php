@extends('layouts.app')

@section('content')

<div id="container" ng-controller="ordersController" class="container ng-scope" division_id="{{$division_id}}" ng-init="">
    
    <!--display Messge Div funGetOrdersList({{$division_id}});-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--Order Listing div-->
    @include('sales.order_master.viewOrderLog')
    <!--/Order Listing div-->
    
    <!--Order Listing div-->
    @include('sales.order_master.list')
    <!--/Order Listing div-->
    
    <!--order form-->
    @include('sales.order_master.add')
    <!--/order form-->
    
    <!--order form-->
    @include('sales.order_master.edit')
    <!--/order form-->
    
    <!--order form-->
    @include('sales.order_master.saveas')
    <!--/order form-->
    
    <!--order form-->
    @include('sales.order_master.view')
    <!--/order form-->
    
    <!--order form-->
    @include('sales.order_master.upload')
    <!--/order form-->
    
    <!--previous Order PopUp form-->
    @include('sales.order_master.previousOrderPopUp')
    <!--/previous Order PopUp form-->
    
    <!--Cancelled Sample Input Popup-->
    @include('sales.order_master.order_cancellation.orderCancellationInputPopupWindow')
    <!--/Cancelled Sample Input Popup-->
    
    <!--Cancelled Sample Output Popup-->
    @include('sales.order_master.order_cancellation.orderCancellationOutputPopupWindow')
    <!--/Cancelled Sample Output Popup-->
    
</div>
    
<!--including angular controller file ,minDate: 0,maxDate: 30-->
<script>$(function(){$('#sampling_date_add').datetimepicker({dateFormat: "dd-mm-yy" ,timeFormat: "hh:mm tt",maxDate: 0, hourMax: 24, numberOfMonths: 1});});</script>
<script>$(function(){$('#sampling_date_edit').datetimepicker({dateFormat: "dd-mm-yy" ,timeFormat: "hh:mm tt",maxDate: 0, hourMax: 24, numberOfMonths: 1});});</script>
<script>$(function(){$('#sampling_date_saveas').datetimepicker({dateFormat: "dd-mm-yy" ,timeFormat: "hh:mm tt",maxDate: 0, hourMax: 24, numberOfMonths: 1});});</script>
<script>$(function(){$('.order_date_add_edit').datepicker({dateFormat: "dd-mm-yy",maxDate: 0,numberOfMonths: 1});});</script>
<script>$(function(){$('.order_date_add_saveas').datepicker({dateFormat: "dd-mm-yy",maxDate: 0,numberOfMonths: 1});});</script>
<script type="text/javascript" src="{!! asset('public/ang/controller/ordersController.js') !!}"></script>
@endsection
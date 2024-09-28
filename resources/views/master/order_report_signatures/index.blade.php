@extends('layouts.app')

@section('content')
    <div class="container" ng-controller="orderReportSignDtlController" ng-init="funListMaster();">
                
        <!--DISPLAY MESSGE DIV-->	
        @include('includes.alertMessage')
        <!--/DISPLAY MESSGE DIV-->	
    
        <!--ADD FORM START-->
        @if(defined('ADD') && ADD)
            @include('master.order_report_signatures.add')
        @endif
        <!--/ADD FORM START-->
        
        <!--EDIT FORM START-->
        @if(defined('EDIT') && EDIT)
            @include('master.order_report_signatures.edit')
        @endif
        <!--/EDIT FORM START-->
        
        <!--LIST START-->
        @if(defined('VIEW') && VIEW)
            @include('master.order_report_signatures.list')
        @endif
        <!--/LIST START-->
        
        <!--ORDER REPORT DETAIL CONTROLLER-->
        <script type="text/javascript" src="{!! asset('public/ang/controller/orderReportSignDtlController.js') !!}"></script>
        <!--/ORDER REPORT DETAIL CONTROLLER-->
    </div>
    
@endsection
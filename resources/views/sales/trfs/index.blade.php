@extends('layouts.app')

@section('content')
    
<div ng-controller="trfHdrController" class="container ng-scope" ng-init="funGetTrfsList();">
    
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--Order Listing div-->
    @if(defined('VIEW') && VIEW)
        @include('sales.trfs.list')
    @endif
    <!--/Order Listing div-->
    
    <!--order form-->
    @if(defined('VIEW') && VIEW)
        @include('sales.trfs.view')
    @endif
    <!--/order form-->
    
</div>

<script type="text/javascript" src="{!! asset('public/ang/controller/trfHdrController.js') !!}"></script>
@endsection
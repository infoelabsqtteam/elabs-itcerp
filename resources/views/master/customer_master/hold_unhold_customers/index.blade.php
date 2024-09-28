@extends('layouts.app')

@section('content')

    <div class="container" ng-controller="orderHoldUnholdController" ng-init="funGetHoldCustomers(limitFrom,limitTo);">

        <!--display Messge Div-->
        @include('includes.alertMessage')
        <!--/display Messge Div-->
        
        <div ng-show="showCustomerSection">
        
            <!--customers list-->
            @include('master.customer_master.hold_unhold_customers.list')
            <!--/customers list-->
            
        </div>
        
    </div>
    
    <style>option {overflow: hidden;text-overflow: ellipsis;width: 250px;}</style>
    <script type="text/javascript" src="{!! asset('public/ang/controller/OrderHoldUnholdController.js') !!}"></script>
	
@endsection
@extends('layouts.app')

@section('content')
    
<div ng-controller="paymentReceivedHdrsController" class="container ng-scope" ng-init="funGetBranchWisePaymentReceived({{$division_id}})">
    
    <!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--adding of module form-->
    @include('payments.payments_received.list')
    <!--adding of module form-->
	
	<!--adding of module form-->
	<div ng-show="{{defined('ADD') && ADD}}">
    @include('payments.payments_received.add')
	</div>
    <!--adding of module form-->
	
	<!--adding of module form-->
	<div ng-show="{{defined('EDIT') && EDIT}}">
		@include('payments.payments_received.edit')
	</div>
    <!--adding of module form-->
	    
</div>
    
<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/paymentReceivedHdrsController.js') !!}"></script>
    
@endsection
@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="orderReportHeaderType" ng-init="getOrderReportHdrTypeList();">

	<!--*****display Messge Div********-->	
	@include('includes.alertMessage')
	<!--****/display Messge Div********-->	

	<!-- *****add Method form start****-->
        @if(defined('ADD') && ADD)
            @include('master.order_report_header_types.add')
        @endif
        <!--*****/add Method form start***-->
	
	<!--***edit Method form start*****-->
        @if(defined('EDIT') && EDIT)
            @include('master.order_report_header_types.edit')
        @endif
	<!--***/edit Method form start*****-->
	
	<!-- ***list Method start*********-->
        @if(defined('VIEW') && VIEW)
            @include('master.order_report_header_types.list')
        @endif
	<!--***/list Method start********-->
</div>
	
<script type="text/javascript" src="{!! asset('public/ang/controller/orderReportHeaderType.js') !!}"></script>
@endsection
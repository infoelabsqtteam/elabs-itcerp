@extends('layouts.app')

@section('content')

<div ng-controller="samplesController" class="container" ng-init="funGetBranchWiseSamples({{$division_id}},'0')">

    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->

    <!--order form-->
    @if(defined('ADD') && ADD)
        @include('sales.samples.add')
    @endif
    <!--/order form-->

    <!--order form-->
    @if(defined('EDIT') && EDIT)
        @include('sales.samples.edit')
    @endif
    <!--/order form-->

    <!--Order Listing div-->
    @if(defined('VIEW') && VIEW)
        @include('sales.samples.list')
    @endif
    <!--/Order Listing div-->

    <!--tree view-->
    @if(defined('VIEW') && VIEW)
    	@include('sales.samples.countryStateTreeViewPopup')
    @endif
    <!--/tree view-->

</div>

<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/samplesController.js') !!}"></script>
@endsection
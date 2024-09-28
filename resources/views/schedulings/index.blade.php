@extends('layouts.app')

@section('content')

<div ng-controller="schedulingsController" class="container" ng-init="funGetSchedulingUnholdJobs()">

    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->

    <!--Listing sample which is just unhold be the User-->
    @include('schedulings.listUnholdOrders')
    <!--/Listing sample which is just unhold be the User-->

    <!--display assign job-->
    @include('schedulings.list')
    <!--/display assign job-->
</div>

<!--including angular controller file-->
<style>label {width: 100% !important;}</style>
<script type="text/javascript" src="{!! asset('public/ang/controller/schedulingsController.js') !!}"></script>
@endsection
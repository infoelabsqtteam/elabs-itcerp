@extends('layouts.app')

@section('content')

<div ng-controller="schedulingsController" class="container" ng-init="funGetDivisionWiseAssignedJobs({{$division_id}})">

    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->

    <!--display assign job-->
    @include('schedulings.jobListing')
    <!--/display assign job-->

    <!--including angular controller file-->
    <script type="text/javascript" src="{!! asset('public/ang/controller/schedulingsController.js') !!}"></script>
    <!--/including angular controller file-->

</div>

@endsection

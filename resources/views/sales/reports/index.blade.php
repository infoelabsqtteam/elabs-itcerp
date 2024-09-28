@extends('layouts.app')

@section('content')
    
<div ng-controller="reportsController" class="container ng-scope" ng-init="funGetBranchWiseReportList({{ $division_id }});">
    
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--display records-->
    @include('sales.reports.list')
    <!--/display records-->
    
    <!--View test report-->
    @include('sales.reports.viewTestReport')
    <!--/View test report-->
    
    <!--display report to add Test report result by tester-->
    @include('sales.reports.addReportByTester')
    <!--/display report to add Test report result by tester-->
    
    <!-----view report by section incharge---->
    @include('sales.reports.addReportBySectionIncharge')
    <!-----/view report by section incharge----->
    
    <!--add report by report reporter/reviewer-->
    @include('sales.reports.addReportByReporterAndReviwer')
    <!--/add report by report reporter/reviewer-->
    
    <!-----view report by Finalizer and QA----->
    @include('sales.reports.addReportByFinalizerAndQA')
    <!-----/view report by Finalizer and QA----->
    
</div>
    
<!--including angular controller file-->
<style>label {width: 100% !important;}</style>
<script type="text/javascript" src="{!! asset('public/ang/controller/reportsController.js') !!}"></script>    
@endsection
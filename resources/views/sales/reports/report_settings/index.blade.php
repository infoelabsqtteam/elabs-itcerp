@extends('layouts.app')

@section('content')

<div class="container" ng-controller="reportSettingController" ng-init="getCustomerDifinedTestReportFields(divisionID,productCategoryID)">

    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <div ng-show="{{defined('VIEW') && VIEW}}">
        @include('sales.reports.report_settings.reportColumnSetting')
    </div>
    
</div>

<link href="{!! asset('public/css/jquery-sorting-ui.css') !!}" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="{!! asset('public/ang/controller/reportSettingController.js') !!}"></script>
@endsection
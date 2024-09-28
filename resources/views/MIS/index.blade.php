@extends('layouts.app')

@section('content')

<div ng-controller="MISReportsController" class="container ng-scope">

    @if(!empty($errors) && $errors->any())
        <div class="alert alert-danger closeMessageAlert">{{$errors->first()}}</div>
    @endif
    @if(Session::has('successMsg'))
        <div id="successMsg" role="alert" class="alert alert-success closeMessageAlert">{{ Session::get('successMsg') }}</div>
    @endif
    @if(Session::has('errorMsg'))
        <div id="errorMsg" role="alert" class="alert alert-danger closeMessageAlert">{{ Session::get('errorMsg') }}</div>
    @endif

    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->

    <!--mis_report_form div-->
    @include('MIS.mis_report_form')
    <!--/mis_report_form div-->

    <!--daily_booking_detail div-->
    @include('MIS.DailyBookingDetail.daily_booking_detail')
    <!--/daily_booking_detail div-->

    <!--date_wise_sample_count div-->
    @include('MIS.PartyWiseSampleCount.date_wise_sample_count')
    <!--/date_wise_sample_count div-->

    <!--month_wise_sample_count div-->
    @include('MIS.PartyWiseSampleCount.month_wise_sample_count')
    <!--/month_wise_sample_count div-->

    <!--marketing_executive_name_wise_sample_count div-->
    @include('MIS.MarketingExecutiveWise.marketing_executive_name_wise_sample_count')
    <!--/marketing_executive_name_wise_sample_count div-->

    <!--marketing_executive_place_wise_sample_count div-->
    @include('MIS.MarketingExecutiveWise.marketing_executive_place_wise_sample_count')
    <!--/marketing_executive_place_wise_sample_count div-->

    <!--tat_report div-->
    @include('MIS.tatReport.tat_report')
    <!--/tat_report div-->

    <!--user_wise_performance_detail div-->
    @include('MIS.UserWisePerformanceDetail.user_wise_performance_detail')
    <!--/user_wise_performance_detail div-->

    <!--user_wise_performance_detail div-->
    @include('MIS.UserWisePerformanceDetail.analyst_wise_performance_summary')
    <!--/user_wise_performance_detail div-->

    <!--sample_log_status div-->
    @include('MIS.sampleLogStatus.sample_log_status')
    <!--/sample_log_status div-->

    <!--sample_log_status div-->
    <!--include('MIS.parameterWiseScheduling.parameter_wise_scheduling')-->
    <!--/sample_log_status div-->

    <!--tat_report div-->
    @include('MIS.salesReport.sales_report')
    <!--/tat_report div-->

    <!--daily invoice details div-->
    @include('MIS.DailyInvoiceDetail.daily_invoice_detail')
    <!--/daily invoice details div div-->

    <!--instrument wise performance detail div-->
    @include('MIS.InstrumentWisePerformanceDetail.instrument_wise_performance_detail')
    <!--/instrument wise performance detail div-->

    <!--Booking Cancellation detail div-->
    @include('MIS.BookingCancellationDetail.booking_cancellation_detail')
    <!--/Booking Cancellation detail div-->

    <!--Booking Amendment detail div-->
    @include('MIS.BookingAmendmentDetail.booking_amendment_detail')
    <!--Booking Amendment detail div-->

    <!--Daily Sales Detail div-->
    @include('MIS.DailySalesDetail.daily_sales_detail')
    <!--Daily Sales Detail div-->

    <!--Delay Status Detail div-->
    @include('MIS.DelayStatusDetail.delay_status_detail')
    <!--/Delay Status Detail div-->

    <!--Account Sales Detail div-->
    @include('MIS.AccountSalesDetail.account_sales_detail')
    <!--/Account Sales Detail div-->

    <!--Employee Sales Target Detail div-->
    @include('MIS.EmployeeSalesTargetDetail.employee_sales_target_detail')
    <!--/Employee Sales Target Detail div-->

    <!--User Sales Target Detail div-->
    @include('MIS.UserSalesTargetDetail.user_sales_target_detail')
    <!--/User Sales Target Detail div-->

    <!--Client Approval Process Detail div-->
    @include('MIS.ClientApprovalProcessDtl.client_approval_process_detail')
    <!--/Client Approval Process Detail div-->

    <!--ITC Std. Price Vs Sample Price Div-->
    @include('MIS.ItcPriceSamplePriceVariationPrices.index')
    <!--/ITC Std. Price Vs Sample Price Div-->

</div>

<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/MISReportsController.js') !!}"></script>
@endsection

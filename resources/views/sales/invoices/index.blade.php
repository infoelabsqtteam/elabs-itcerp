@extends('layouts.app')

@section('content')
    
<div ng-controller="invoicesController" class="container ng-scope" ng-init="funViewDivisionWiseInvoices({{$division_id}});">
    
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <!--display Invoice Listing-->
    @include('sales.invoices.invoiceListing')
    <!--/display Invoice Listing-->
    
    <!---- display invoice orders list-->
    @include('sales.invoices.invoiceOrdersList')
    <!---- /display invoice orders list-->
    
    <!--Invoice generation form div-->
    <div class="row" ng-hide="IsViewInvoiceGenForm">
        
        <!--display Orders for Invoicing--> 
        @include('sales.invoices.invoiceGenerate')
        <!--/display Orders for Invoicing-->
        
        <!--display Orders for Invoicing--> 
        @include('sales.invoices.viewOrdersInvoice')
        <!--/display Orders for Invoicing-->
        
        <!--display Invoices List--> 
        @include('sales.invoices.viewInvoiceList')
        <!--/display Invoices List-->        
        
    </div>
    <!--/Invoice generation form div-->
    
    <!--View Invoices Detail--> 
    @include('sales.invoices.viewInvoice')
    <!--/View Invoices Detail-->

    <!--View Invoices Detail--> 
    @include('sales.invoices.editInvoice')
    <!--/View Invoices Detail-->
	
    <!--display report popup-->
    @include('sales.invoices.viewInvoiceReport')
    <!--/display report popup-->
    
</div>
    
<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/invoicesController.js') !!}"></script>
<script type="text/javascript" src="{!! asset('public/js/kendo.all.min.js') !!}"></script>
@endsection
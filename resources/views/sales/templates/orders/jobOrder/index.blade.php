<div class="row" id="ViewOrderFoodSection">
    
    <!--statistics-->
    @include('sales.templates.orders.jobOrder.statistics')
    <!--/statistics-->
    
    <!--header-->
    @include('sales.templates.orders.jobOrder.header')
    <!--/header-->
    
    <!--content-->
    @include('sales.templates.orders.jobOrder.content')
    <!--/content-->
    
    <!--footer-->
    @include('sales.templates.orders.jobOrder.footer')
    <!--/footer-->
    
    <script type="text/javascript" src="{!! asset('public/js/kendo.all.min.js') !!}"></script>
</div>
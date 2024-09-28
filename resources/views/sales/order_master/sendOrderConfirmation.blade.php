@if(!empty($viewData['order']['product_category_id']))
    @if($viewData['order']['product_category_id'] == '1')
        @if($viewData['order']['division_id'] == '1')
            @include('sales.order_master.order_confirmation.orderConfirmationFood')
        @else
            @include('sales.order_master.order_confirmation.orderConfirmationFoodWithoutPartWise')
        @endif
    @elseif($viewData['order']['product_category_id'] == '2')
       @include('sales.order_master.order_confirmation.orderConfirmationPharma')
    @elseif($viewData['order']['product_category_id'] == '3')
       @include('sales.order_master.order_confirmation.orderConfirmationWater')
    @elseif($viewData['order']['product_category_id'] == '4')
       @include('sales.order_master.order_confirmation.orderConfirmationHelmet')
    @elseif($viewData['order']['product_category_id'] == '5')
       @include('sales.order_master.order_confirmation.orderConfirmationAyurvedic')
    @elseif($viewData['order']['product_category_id'] == '6')
       @include('sales.order_master.order_confirmation.orderConfirmationBuilding')
    @elseif($viewData['order']['product_category_id'] == '7')
        @include('sales.order_master.order_confirmation.orderConfirmationTextile')
    @elseif($viewData['order']['product_category_id'] == '8')        
        @if($viewData['order']['division_id'] == '1')
            @if(!empty($viewData['generalParameters']))
                @include('sales.order_master.order_confirmation.orderConfirmationEnvironmentWater')
            @else
                @include('sales.order_master.order_confirmation.orderConfirmationEnvironment')
            @endif
        @else
            @include('sales.order_master.order_confirmation.orderConfirmationEnvironmentWithoutPartWise')
        @endif
    @elseif($viewData['order']['product_category_id'] == '405')
        @include('sales.order_master.order_confirmation.generateReportCosmeticWithForm50Format')
    @else
        @include('sales.order_master.order_confirmation.generateReportDefault')
    @endif
@endif
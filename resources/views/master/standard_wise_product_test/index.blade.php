@extends('layouts.app')

@section('content')

<div class="container" ng-controller="standardWiseProductTestController" ng-init="">

    <!--Alert success and error msgs getStandatdProductTest(0);inputTypeInt();hideProTestParametersListView();--->
    @include('includes.alertMessage')
    <!--/Alert success and error msgs--->

    <div ng-show="{{defined('ADD') && ADD}}">
        <div class="row" ng-hide="productAndParameter">
            <div class="panel panel-default" style="opacity: 1;">
                <div class="panel-body">
                    <!-- **********************************product_tests form  start**************************** -->
                    @include('master.standard_wise_product_test.product_tests.index')
                    <!-- **********************************product_tests form  end**************************** -->

                    <!-- **********************************product_test_parameters form  start**************************** -->
                    @include('master.standard_wise_product_test.product_test_parameter_methods.index')
                    <!-- **********************************product_test_parameters form  end**************************** -->
                </div>
            </div>
        </div>
    </div>

    <div class="row" ng-model="testProductAltMethodForm" ng-hide="testProductAltMethodForm">
        <div class="panel panel-default">
            <div class="panel-body">
                <!-- **********************************product_test_parameters atlernative method form  start**************************** -->
                @include('master.standard_wise_product_test.product_test_parameter_alternate_methods.index')
                <!-- **********************************product_test_parameters atlernative method form  end**************************** -->
            </div>
        </div>
    </div>

    <!-- **********************************product tests table start**************************** -->
    <div class="productTable" ng-model="proTable" ng-hide="proTable">
        @include('master.standard_wise_product_test.product_tests.list')
    </div>
    <!-- **********************************/product tests table end**************************** -->

    <!-- **********************************product tests table start**************************** -->
    <div class="productTable" ng-model="viewProductTestParametersDiv" ng-hide="viewProductTestParametersDiv">
        @include('master.standard_wise_product_test.product_tests.view')
    </div>
    <!-- **********************************/product tests table end**************************** -->

    <!-- **********************************product test parameter methods table start**************************** -->
    <div class="paraneterTable" ng-model="paraTable" ng-hide="paraTable">
        @include('master.standard_wise_product_test.product_test_parameter_methods.list')
    </div>
    <!-- **********************************product test parameter methods table end**************************** -->

    <!-- **********************************product test parameters alternate method table  start**************************** -->
    <div class="alternativeMethodsTable" ng-model="altMethodTable" ng-hide="altMethodTable">
        @include('master.standard_wise_product_test.product_test_parameter_alternate_methods.list')
    </div>
    <!-- **********************************/product test parameters alternate method table  end**************************** -->

    <!-- **************************************add Product form start**************************** -->
    <div ng-if="productCategoriesTree.length">
        @include('master.standard_wise_product_test.product_tests.productCategoryTreePopup')
    </div>
    <!-- **********************************add product form  start**************************** -->

    <!-- **************************************add parameter form start************************* -->
    <div ng-if="parameterCategoriesTree.length">
        @include('master.standard_wise_product_test.product_test_parameter_methods.parameterCategoryTreePopup')
    </div>
    <!-- **********************************add parameter form  start**************************** -->

    <!--treeview js-->
    <script type="text/javascript" src="{!! asset('public/js/treeview.js') !!}"></script>
    <link href="{!! asset('public/css/treeview.css') !!}" rel="stylesheet" type="text/css" />
    <!--/treeview js-->

    <script type="text/javascript" src="{!! asset('public/ang/controller/standardWiseProductTestController.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('public/js/jquery.maskedinput.js') !!}"></script>

</div>
@endsection

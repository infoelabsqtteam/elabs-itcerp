@extends('layouts.app')

@section('content')

<div class="container" ng-controller="testParametersController">

    <!--display Messge Div-->
    @include('includes.multipleMessage')
    <!--/display Messge Div-->

    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->

    <!-------------------add form start here--------------------------->
    @if(defined('ADD') && ADD)
    	@include('master.test_parameters.add')
    @endif
    <!-------------------add form end here--------------------------->

    <!-------------------add form start here--------------------------->
    @if(defined('ADD') && ADD)
    	@include('master.test_parameters.upload')
    @endif
    <!-------------------add form end here--------------------------->

    <!-------------------edit form start here--------------------------->
    @if(defined('EDIT') && EDIT)
    	@include('master.test_parameters.edit')
    @endif
    <!-------------------edit form end here--------------------------->

    <!-------------------list form start here--------------------------->
    @if(defined('VIEW') && VIEW)
    	@include('master.test_parameters.list')
    @endif
    <!-------------------list form end here--------------------------->

    <!-- **************************************add Product form start**************************** -->
    <div ng-if="{{defined('ADD') && ADD}} && parameterCategoriesTree.length">
        @include('master.test_parameters.parameterCategoryTreePopup')
    </div>
    <!-- **********************************add product form  start**************************** -->

	<!-- testParametersController-->
	<script type="text/javascript" src="{!! asset('public/ang/controller/testParametersController.js') !!}"></script>
	<!-- testParametersController-->

	<style>#ui-tinymce-0_ifr,#ui-tinymce-1_ifr,#ui-tinymce-2_ifr,#ui-tinymce-3_ifr {height: 50px !important;}</style>

</div>
@endsection

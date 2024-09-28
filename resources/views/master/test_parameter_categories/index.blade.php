@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="testParameterCategoryController" ng-init="funGetParameterCategoryList();fungetParentCategory()">
	
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->

	<!-------------------add form start here--------------------------->
	@if(defined('ADD') && ADD)
		@include('master.test_parameter_categories.add')
		@include('master.test_parameter_categories.upload')
	@endif
	<!-------------------add form end here--------------------------->

	<!-------------------edit form start here--------------------------->
	@include('master.test_parameter_categories.edit')
	<!-------------------edit form end here--------------------------->
		
	<!-------------------list form start here--------------------------->
	@include('master.test_parameter_categories.list')
	<!-------------------list form start here--------------------------->
	
	<!-- ****categoryTree***************** -->
	<div ng-if="parameterCategoriesTree.length">
		@include('master.test_parameter_categories.parameterCategoryTreePopup')
	</div>
	<!-- *****categoryTree***************** -->
	
</div>
	
<script type="text/javascript" src="{!! asset('public/ang/controller/testParameterCategoryController.js') !!}"></script>
@endsection
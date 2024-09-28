@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="testParameterCategoryController" ng-init="getParameterCategories();fungetParentCategory();hideTreeForms();generateDefaultCode();showParameterCatTreeViewPopUp(14)">
	
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->

	<!-------------------add form start here--------------------------->
		@include('master.test_parameter_categories.parameter_tree_view.add')
	<!-------------------add form end here--------------------------->

	<!-------------------edit form start here--------------------------->
	@include('master.test_parameter_categories.parameter_tree_view.edit')
	<!-------------------edit form end here--------------------------->
		
	<!-------------------list form start here--------------------------->
	@include('master.test_parameter_categories.parameter_tree_view.list')
	<!-------------------list form start here--------------------------->
		
</div>
	
<script type="text/javascript" src="{!! asset('public/ang/controller/testParameterCategoryController.js') !!}"></script>
@endsection
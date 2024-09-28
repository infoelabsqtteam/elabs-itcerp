@extends('layouts.app')

@section('content')

<div class="container" ng-controller="testStandardController" ng-init="funGetTestStandard(GlobalProductCategoryId)">
    
    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
	
	<!-------------------add form start here--------------------------->
	@if(defined('ADD') && ADD)
		@include('master.test_standards.add')
		@include('master.test_standards.upload')
	@endif
	<!-------------------add form end here--------------------------->

	<!-------------------edit form start here--------------------------->
	@include('master.test_standards.edit')
	<!-------------------edit form end here--------------------------->
  	
	<!-------------------list start here--------------------------->
	@include('master.test_standards.list')
	<!-------------------list  end here--------------------------->

</div>
    
<script type="text/javascript" src="{!! asset('public/ang/controller/testStandardController.js') !!}"></script>	
@endsection
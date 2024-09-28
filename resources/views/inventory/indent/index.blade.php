@extends('layouts.app')
@section('content')	
<div class="container" ng-controller="indentController" ng-init="getIndents({{$division_id}});">
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--display Add Form Popup-->
    @include('inventory.indent.list')
    <!--/display Add Form Popup-->
	
	<!--display Add Form Popup-->
    @include('inventory.indent.add')
    <!--/display Add Form Popup-->
	
	<!--display Edit Form Popup-->
    @include('inventory.indent.edit')
    <!--/display edit Form Popup-->

</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/indentController.js') !!}"></script>
@endsection
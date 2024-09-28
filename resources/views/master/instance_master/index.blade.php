@extends('layouts.app')

@section('content')

<div class="container" ng-controller="instanceController" ng-init="funListMaster()">

	<!--*****display Messge Div********-->
	@include('includes.alertMessage')
	<!--****/display Messge Div********-->

	<!-- *****add Method form start****-->
	<div ng-show="{{defined('ADD') && ADD}}">
		@include('master.instance_master.add')
	</div>
	<!--*****/add Method form start***-->

	<!--***edit Method form start*****-->
	<div ng-show="{{defined('EDIT') && EDIT}}">
		@include('master.instance_master.edit')
	</div>
	<!--***/edit Method form start*****-->

	<!-- ***list Method start*********-->
	<div ng-show="{{defined('EDIT') && EDIT}}">
		@include('master.instance_master.list')
	</div>
	<!--***/list Method start********-->
</div>

<script type="text/javascript" src="{!! asset('public/ang/controller/instanceController.js') !!}"></script>
@endsection
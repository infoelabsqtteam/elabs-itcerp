@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="defaultRemarkController" ng-init="getBranchWiseDefaultRemarks()">
		
	<!--*****display Messge Div********-->	
	@include('includes.alertMessage')
	<!--****/display Messge Div********-->	

	<!-- *****add Method form start****-->
	@include('master.default_remarks.add')
	<!--*****/add Method form start***-->
	
	<!--***edit Method form start*****-->
	@include('master.default_remarks.edit')
	<!--***/edit Method form start*****-->
	
	<!-- ***list Method start*********-->
	@include('master.default_remarks.list')
	<!--***/list Method start********-->
</div>
	
<script type="text/javascript" src="{!! asset('public/ang/controller/defaultRemarkController.js') !!}"></script>
@endsection
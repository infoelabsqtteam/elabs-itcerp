@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="templateController" ng-init="getTemplatesList();">

	<!--*****display Messge Div********-->	
	@include('includes.alertMessage')
	<!--****/display Messge Div********-->	

	<!-- *****add Method form start****-->
        @if(defined('ADD') && ADD)
            @include('master.templates.report_templates.add')
        @endif
        <!--*****/add Method form start***-->
	
	<!--***edit Method form start*****-->
        @if(defined('EDIT') && EDIT)
            @include('master.templates.report_templates.edit')
        @endif
	<!--***/edit Method form start*****-->
	
	<!-- ***list Method start*********-->
        @if(defined('VIEW') && VIEW)
            @include('master.templates.report_templates.list')
        @endif
	<!--***/list Method start********-->
</div>
	
<script type="text/javascript" src="{!! asset('public/ang/controller/templateController.js') !!}"></script>
@endsection
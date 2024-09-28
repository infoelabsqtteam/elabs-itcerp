@extends('layouts.app')

@section('content')
    
<div ng-controller="schedulingsController" class="container">

    <!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
    
    <!--display assign job-->
    @include('schedulings.jobPrint.jobSheetPrint')
    <!--/display assign job-->
</div>
    
<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/schedulingsController.js') !!}"></script>
<style><!--.hght956{ height:956px !important; }.page-break{display:none;}--></style>
@endsection
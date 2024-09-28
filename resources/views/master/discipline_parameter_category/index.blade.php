@extends('layouts.app')

@section('content')
	
    <div class="container" ng-controller="orderReportDisciplineParameterDtlController" ng-init="funListMaster();">
                
        <!--DISPLAY MESSGE DIV-->	
        @include('includes.alertMessage')
        <!--/DISPLAY MESSGE DIV-->	
    
        <!--ADD FORM START-->
        @if(defined('ADD') && ADD)
            @include('master.discipline_parameter_category.add')
        @endif
        <!--/ADD FORM START-->
        
        <!--EDIT FORM START-->
        @if(defined('EDIT') && EDIT)
            @include('master.discipline_parameter_category.edit')
        @endif
        <!--/EDIT FORM START-->
        
        <!--LIST START-->
        @if(defined('VIEW') && VIEW)
            @include('master.discipline_parameter_category.list')
        @endif
        <!--/LIST START-->
        
        <!--DISCIPLINE CONTROLLER-->
        <script type="text/javascript" src="{!! asset('public/ang/controller/orderReportDisciplineParameterDtlController.js') !!}"></script>
        <!--/DISCIPLINE CONTROLLER-->
    </div>
    
@endsection
@extends('layouts.app')

@section('content')
	
    <div class="container" ng-controller="orderReportDisciplineController" ng-init="funListMaster();generateDefaultCode();">
                
        <!--DISPLAY MESSGE DIV-->	
        @include('includes.alertMessage')
        <!--/DISPLAY MESSGE DIV-->	
    
        <!--ADD FORM START-->
        @if(defined('ADD') && ADD)
            @include('master.discipline_master.add')
        @endif
        <!--/ADD FORM START-->
        
        <!--EDIT FORM START-->
        @if(defined('EDIT') && EDIT)
            @include('master.discipline_master.edit')
        @endif
        <!--/EDIT FORM START-->
        
        <!--LIST START-->
        @if(defined('VIEW') && VIEW)
            @include('master.discipline_master.list')
        @endif
        <!--/LIST START-->
        
        <!--DISCIPLINE CONTROLLER-->
        <script type="text/javascript" src="{!! asset('public/ang/controller/orderReportDisciplineController.js') !!}"></script>
        <!--/DISCIPLINE CONTROLLER-->
    </div>
    
@endsection
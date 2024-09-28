@extends('layouts.app')

@section('content')
    <div class="container" ng-controller="OrderDefinedTestStandardController" ng-init="funListMaster();">
                
        <!--DISPLAY MESSGE DIV-->	
        @include('includes.alertMessage')
        <!--/DISPLAY MESSGE DIV-->	
    
        <!--ADD FORM START-->
        @if(defined('ADD') && ADD)
            @include('master.defined_test_standard_master.add')
        @endif
        <!--/ADD FORM START-->
        
        <!--EDIT FORM START-->
        @if(defined('EDIT') && EDIT)
            @include('master.defined_test_standard_master.edit')
        @endif
        <!--/EDIT FORM START-->
        
        <!--LIST START-->
        @if(defined('VIEW') && VIEW)
            @include('master.defined_test_standard_master.list')
        @endif
        <!--/LIST START-->
        
        <!--Defined test standard CONTROLLER-->
        <script type="text/javascript" src="{!! asset('public/ang/controller/OrderDefinedTestStandardController.js') !!}"></script>
        <!--/Defined test standard CONTROLLER-->
    </div>
    
@endsection
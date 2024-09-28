@extends('layouts.app')

@section('content')
	
    <div class="container" ng-controller="OrderReportGroupController" ng-init="funListMaster();generateDefaultCode();">
                
        <!--DISPLAY MESSGE DIV-->	
        @include('includes.alertMessage')
        <!--/DISPLAY MESSGE DIV-->	
    
        <!--ADD FORM START-->
        @if(defined('ADD') && ADD)
            @include('master.group_master.add')
        @endif
        <!--/ADD FORM START-->
        
        <!--EDIT FORM START-->
        @if(defined('EDIT') && EDIT)
            @include('master.group_master.edit')
        @endif
        <!--/EDIT FORM START-->
        
        <!--LIST START-->
        @if(defined('VIEW') && VIEW)
            @include('master.group_master.list')
        @endif
        <!--/LIST START-->
        
        <!--DISCIPLINE CONTROLLER-->
        <script type="text/javascript" src="{!! asset('public/ang/controller/OrderReportGroupController.js') !!}"></script>
        <!--/DISCIPLINE CONTROLLER-->
    </div>
    
@endsection
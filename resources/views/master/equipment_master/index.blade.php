@extends('layouts.app')

@section('content')

<div class="container" ng-controller="equipmentController" ng-init="getEquipments()">

    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->
    
    <div ng-show="{{defined('ADD') && ADD}}">
        @include('master.equipment_master.add')
    </div>
    
    <div ng-show="{{defined('ADD') && ADD}}">
        @include('master.equipment_master.upload')
    </div>
    
    <div ng-show="{{defined('EDIT') && EDIT}}">
        @include('master.equipment_master.edit')
    </div>
    
    <div ng-show="{{defined('VIEW') && VIEW}}">
        @include('master.equipment_master.list')
    </div>
    
    <div ng-show="{{defined('VIEW') && VIEW}}">
        @include('master.equipment_master.sort')
    </div>
    
</div>

<link href="{!! asset('public/css/jquery-sorting-ui.css') !!}" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="{!! asset('public/ang/controller/equipmentController.js') !!}"></script>
@endsection
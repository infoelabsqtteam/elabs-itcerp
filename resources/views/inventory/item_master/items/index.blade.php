@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="itemController" ng-init="funGetItems()">
	
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--Display of Item Listing-->
	@include('inventory.item_master.items.list')
	<!--/Display of Item Listing-->

	<!--**************************************add Item form****************************-->
	@include('inventory.item_master.items.add')
	<!--**********************************/add item form****************************-->
	
	<!--**************************************add Item form****************************-->
	@include('inventory.item_master.items.edit')
	<!--**********************************/add item form****************************-->
	
	<!--**************************************view Item form****************************-->
	@include('inventory.item_master.items.view')
	<!--**********************************/view item form****************************-->
	
</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/itemController.js') !!}"></script>
<style>
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
.img-wrap {
    position: relative;
    display: inline-block;
    font-size: 0;
}
.img-wrap .close {
    background-color: #fff;
    border-radius: 50%;
    color: #000;
    cursor: pointer;
    font-size: 15px;
    font-weight: bold;
    line-height: 10px;
    opacity: 0.2;
    position: absolute;
    right: 0;
    text-align: center;
    top: 0;
    z-index: 100;
}
.img-wrap:hover .close {
    opacity: 1;
}
</style>
@endsection
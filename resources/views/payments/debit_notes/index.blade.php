@extends('layouts.app')

@section('content')

<div ng-controller="debitNotesController" class="container ng-scope" ng-init="funGetBranchWiseDebitNotes({{$division_id}})">

    <!--display Messge Div-->
    @include('includes.alertMessage')
    <!--/display Messge Div-->

    <!--adding of module form-->
    @include('payments.debit_notes.list')
    <!--adding of module form-->

    <!--adding of module form-->
    <div ng-show="{{defined('ADD') && ADD}}">
        @include('payments.debit_notes.add')
    </div>
    <!--adding of module form-->

    <!--adding of module form-->
    <div ng-show="{{defined('EDIT') && EDIT}}">
        @include('payments.debit_notes.edit')
    </div>
    <!--adding of module form-->
    <!--view of module form-->
    <div ng-show="{{defined('VIEW') && VIEW}}">
        @include('payments.debit_notes.view')
    </div>
    <!--view of module form-->

    <!--tree view-->
    @if(defined('VIEW') && VIEW)
    	@include('payments.debit_notes.countryStateTreeViewPopup')
    @endif
    <!--/tree view-->

</div>

<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/debitNotesController.js') !!}"></script>
<script>
    $("#debit_note_date").datepicker({
        dateFormat: 'dd-mm-yy'
        , minDate: 0
    });

</script>
@endsection

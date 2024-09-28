@extends('layouts.app')

@section('content')

<div ng-controller="creditNotesController" class="container ng-scope" ng-init="funGetBranchWiseCreditNotes({{$division_id}})">
    
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--adding of module form-->
	@include('payments.credit_notes.list')
	<!--adding of module form-->
	
	<!--adding of module form-->
	<div ng-show="{{defined('ADD') && ADD}}">
		@include('payments.credit_notes.add')
	</div>
	<!--adding of module form-->
	
	<!--adding of module form-->
	<div ng-show="{{defined('EDIT') && EDIT}}">
		@include('payments.credit_notes.edit')
	</div>
	<!--adding of module form-->
	
	<!--view credit note-->
	<div ng-show="{{defined('VIEW') && VIEW}}">
		@include('payments.credit_notes.view')
	</div>
	<!--view credit note-->

	<!--tree view-->
    @if(defined('VIEW') && VIEW)
    	@include('payments.debit_notes.countryStateTreeViewPopup')
    @endif
    <!--/tree view-->
	
</div>
    
<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/creditNotesController.js') !!}"></script>
<script>
	$( "#credit_note_date" ).datepicker({ dateFormat:'dd-mm-yy',minDate: 0});
</script>
@endsection
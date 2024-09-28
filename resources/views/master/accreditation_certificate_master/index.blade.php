@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="orderAccreditationCertificateMasterController" ng-init="getCertificatesList();">

	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
			
	<!---Adding of divisions-->
   	<div ng-show="{{defined('ADD') && ADD}}">
		@include('master.accreditation_certificate_master.add')
	</div>
	<!---/Adding of divisions-->
	
	<!---Editing of divisions-->
	<div ng-show="{{defined('EDIT') && EDIT}}">
		@include('master.accreditation_certificate_master.edit')
	<!---/Editing of divisions-->
	</div>
	<!---Listing of divisions-->
	<div ng-show="{{defined('VIEW') && VIEW}}">
		@include('master.accreditation_certificate_master.list')
	</div>
	<!---/Listing of divisions-->

</div>

<!--Incliding divisionController Js file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/orderAccreditationCertificateMasterController.js') !!}"></script>
@endsection
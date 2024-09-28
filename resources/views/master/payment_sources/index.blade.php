@extends('layouts.app')
@section('content')
<script type="text/javascript" src="{!! asset('public/ang/controller/paymentSourcesController.js') !!}"></script>	
<div class="container" ng-controller="paymentSourcesController" ng-init="getPaymentSources()">
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
		
<!-------------------add form start here--------------------------->
	@if(defined('ADD') && ADD)
		@include('master.payment_sources.add')
	@endif
<!---------------------add form end-------------------------------->

<!-------------------edit form start here-------------------------->
	@include('master.payment_sources.edit')
<!-------------------edit form end here---------------------------->

<!-------------------list start here-------------------------->
	@include('master.payment_sources.list')	
<!-------------------list end here---------------------------->
	

</div>
@endsection
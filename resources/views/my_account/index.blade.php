@extends('layouts.app')
@section('content')

<!--container-->
<div class="container" ng-controller="accountSettingController" ng-init="funGetAccountDetails()">
	
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
        
        @if(Session::has('successMsg'))
            <div id="successMsg" role="alert" class="alert alert-success closeMessagegAlert">{{ Session::get('successMsg') }}</div>
        @endif
        @if(Session::has('errorMsg'))
            <div id="errorMsg" role="alert" class="alert alert-danger closeMessagegAlert">{{ Session::get('errorMsg') }}</div>
        @endif
	
	@if(Request::path()=='profile/my-account') 
		<!--account details-->
		@include('my_account.account_detail')
		<!--/account details-->
	@endif
	
	@if(Request::path()=='profile/change-password')
		<!--changePassword-->
		@include('my_account.change_password')
		<!--/changePassword-->
	@endif

</div>

<!--Including accountSettingController file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/accountSettingController.js') !!}"></script>	
@endsection
@extends('layouts.app')

@section('content')
    
<div class="container">
    <div class="col-md-8 col-md-offset-1">
        <div class="panel panel-default">
            @if(!empty($errors) && $errors->any())
                <div class="alert alert-danger">{{$errors->first()}}</div>
            @endif
            @if(Session::has('successMsg'))
                <div id="successMsg" role="alert" class="alert alert-success closeMessagegAlert">{{ Session::get('successMsg') }}</div>
            @endif
            @if(Session::has('errorMsg'))
                <div id="errorMsg" role="alert" class="alert alert-danger closeMessagegAlert">{{ Session::get('errorMsg') }}</div>
            @endif
            @if(Session::has('alertMsg'))
                <div id="alertMsg" role="alert" class="alert alert-info closeMessagegAlert">{{ Session::get('alertMsg') }}</div>
            @endif
            <h3 class="panel-heading mT10">Welcome <strong>{{ ucfirst(Auth::user()->name) }}!</strong></h3>                    
            <div class="panel-body"><h4 class="col-md-12">You are logged in!</h4></div>
        </div>    
    </div>
</div>
    
@include('dashboard.list')

@endsection
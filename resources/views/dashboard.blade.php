@extends('layouts.app')

@section('content')
    
<div class="container">
    <div class="row col-md-12">
        <div class="panel panel-default">
            @if(!empty($errors) && $errors->any())
                <div class="alert alert-danger closeMessageAlert">{{$errors->first()}}</div>
            @endif
            @if(Session::has('successMsg'))
                <div id="successMsg" role="alert" class="alert alert-success closeMessageAlert">{{ Session::get('successMsg') }}</div>
            @endif
            @if(Session::has('errorMsg'))
                <div id="errorMsg" role="alert" class="alert alert-danger closeMessageAlert">{{ Session::get('errorMsg') }}</div>
            @endif
            <div class="panel-heading mT10">Dashboard</div>                    
            <div class="panel-body">
                <span class="col-md-8">You are logged in!</span>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin', ['crumbs' => []])
@section('content')
    <div class="col-sm-12">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">{{__('Not Approved!')}}</h4>
            <p>{{__('Your Account is not approved yet! please contact support team.')}}</p>
        </div>
    </div>
@endsection

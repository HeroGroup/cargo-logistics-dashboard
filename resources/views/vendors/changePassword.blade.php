@extends('layouts.admin', ['crumbs' => [
    'Vendors' => route('vendors.index', 'all'),
    'Change Vendors Password' => route('vendors.changePassword', $vendor->id)]
, 'title' => __('Change Vendors Password')])
@section('content')
    <div class="card col-md-12 mx-auto">
        {!! Form::model($vendor, array('route' => array('vendors.updatePassword', $vendor), 'method' => 'PUT', 'class' => 'form theme-form')) !!}
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5>Change Password</h5>
                        @component('components.password-component')@endcomponent
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{route('vendors.index', 'all')}}" class="btn btn-light">Cancel</a>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

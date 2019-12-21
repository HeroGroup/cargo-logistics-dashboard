@extends('layouts.admin', ['crumbs' => [
    __('Drivers') => route('drivers.index', 'all'),
    __('Change Drivers Password') => route('drivers.changePassword', $driver->id)]
, 'title' => __('Change Driver Password')])
@section('content')
    <div class="card col-md-12 mx-auto">
        {!! Form::model($driver, array('route' => array('drivers.updatePassword', $driver), 'method' => 'PUT', 'class' => 'form theme-form')) !!}
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5>{{__('Change Password')}}</h5>
                        {{--<div class="form-group row">--}}
                            {{--<label for="current_password" class="col-sm-3 col-form-label">Current Password</label>--}}
                            {{--<div class="col-sm-9">--}}
                                {{--<input type="password" name="current_password" class="form-control btn-pill @if(\Illuminate\Support\Facades\Session::has('messageOldPassword')) is-invalid @endif" id="current_password" placeholder="Current Password" required>--}}
                                {{--@if(\Illuminate\Support\Facades\Session::has('messageOldPassword'))--}}
                                {{--<div class="help-block text-danger">{{ \Illuminate\Support\Facades\Session::get('messageOldPassword') }}</div>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label">{{__('New Password')}}</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" class="form-control btn-pill @error('password') is-invalid @enderror" id="password" placeholder="{{__('New Password')}}" required>
                                @error('password')
                                <div class="help-block text-danger">{{ $message }}</div>
                                @else
                                <div class="help-block text-info hint"><i class="fa fa-exclamation-circle"></i> <small>{{__('minimum 8 characters')}}</small></div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirmation" class="col-sm-3 col-form-label">{{__('Confirm New Password')}}</label>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" class="form-control btn-pill" placeholder="{{__('Confirm New Password')}}" id="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                <a href="{{route('drivers.index', 'all')}}" class="btn btn-light">{{__('Cancel')}}</a>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

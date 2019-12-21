@extends('layouts.admin', ['crumbs' => [
    __('Vendors') => route('vendors.index', 'all'),
    __('Accounts') => route('vendors.accounts', $vendorId),
    __('Create Account') => route('vendors.accounts.create', $vendorId)]
, 'title' => __('New Account')])
@section('content')
    <div class="card col-md-12 mx-auto">
        {{ Form::open(array('url' => route('vendors.accounts.store', $vendorId), 'method' => 'POST', 'class' => 'form theme-form')) }}
        @csrf
        <input type="hidden" name="vendor_id" value="{{$vendorId}}">
        <input type="hidden" name="user_type" value="branch">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5>{{__('Account Information')}}</h5>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Full Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control btn-pill @error('name') is-invalid @enderror" id="name" placeholder="{{__('Full Name')}}" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">{{__('Email Address')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="email" class="form-control btn-pill @error('email') is-invalid @enderror" id="email" placeholder="name@example.com" value="{{ old('email') }}" >
                            @error('email')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mobile" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Mobile')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="mobile" class="form-control btn-pill @error('mobile') is-invalid @enderror" id="mobile" placeholder="{{__('Mobile')}}" value="{{ old('mobile') }}" required size="8" >
                            @error('mobile')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <h5>Account Security</h5>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" name="password" class="form-control btn-pill @error('password') is-invalid @enderror" id="password" placeholder="{{__('Password')}}" required>
                            @error('password')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @else
                            <div class="help-block text-info" style="margin-left: 10px;"><i class="fa fa-exclamation-circle"></i> <small>{{__('minimum 8 characters')}}</small></div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password_confirmation" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Confirm Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" name="password_confirmation" class="form-control btn-pill" placeholder="{{__('Confirm Password')}}" id="password_confirmation" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            <a href="{{route('vendors.accounts', $vendorId)}}" class="btn btn-light">{{__('Cancel')}}</a>
        </div>
        {{ Form::close() }}
    </div>
@endsection

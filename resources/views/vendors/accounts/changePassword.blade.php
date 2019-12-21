@extends('layouts.admin', ['crumbs' => [
    __('Vendors') => route('vendors.index', 'all'),
    __('Accounts') => route('vendors.accounts', $vendorId),
    __('Change Password') => route('vendors.accounts.changePassword', ['vendor' => $vendorId, 'account' => $account->id])]
, 'title' => __('Change Account Password')])
@section('content')
    <div class="card col-md-12 mx-auto">
        {!! Form::model($account, array('route' => array('vendors.accounts.updatePassword', $vendorId, $account->id), 'method' => 'PUT', 'class' => 'form theme-form')) !!}
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5>{{__('Change Password')}}</h5>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('New Password')}}</label>
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
                        <label for="password_confirmation" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Confirm New Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" name="password_confirmation" class="form-control btn-pill" placeholder="{{__('Confirm New Password')}}" id="password_confirmation" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            <a href="{{route('vendors.accounts', $vendorId)}}" class="btn btn-light">{{__('Cancel')}}</a>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

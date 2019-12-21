@extends('layouts.admin', ['crumbs' => [
    __('Vendors') => route('vendors.index', 'all'),
    __('Accounts') => route('vendors.accounts', $vendorId),
    __('Edit Account') => route('vendors.accounts.edit', ['vendor' => $vendorId, 'account' => $account->id])]
, 'title' => __('Edit Account Information')])
@section('content')
    <div class="card col-md-12 mx-auto">
        {!! Form::model($account, array('route' => array('vendors.accounts.update', $vendorId, $account), 'method' => 'PUT', 'class' => 'form theme-form')) !!}
        <input type="hidden" name="vendor_id" value="{{$vendorId}}">
        <input type="hidden" name="user_type" value="branch">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h5>{{__('Account Information')}}</h5>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Full Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control btn-pill @error('name') is-invalid @enderror" id="name" value="{{$account->name}}" required>
                            @error('name')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">{{__('Email Address')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="email" class="form-control btn-pill @error('email') is-invalid @enderror" id="email" value="{{$account->email}}" >
                            @error('email')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mobile" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Mobile')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="mobile" class="form-control btn-pill @error('mobile') is-invalid @enderror" id="mobile" value="{{$account->mobile}}" required size="8">
                            @error('mobile')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
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

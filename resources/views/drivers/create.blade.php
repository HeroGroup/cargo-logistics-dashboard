@extends('layouts.admin', ['crumbs' => [
    __('Drivers') => route('drivers.index', 'all'),
    __('Create Driver') => route('drivers.create')]
, 'title' => __('Register Driver')])
@section('content')
    <div class="card col-md-12">
        {{--<div class="card-header">
            <h5>{{__('Register Driver')}}</h5>
        </div>--}}
        {{ Form::open(array('url' => route('drivers.store'), 'method' => 'POST', 'files' => 'true', 'class' => 'form theme-form')) }}
            {{--@csrf--}}
            <input type="hidden" name="vendor_id" value="{{auth()->user()->vendor_id}}" >
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5>{{__('Personal Information')}}</h5>
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
                            <label for="email" class="col-sm-3 col-form-label">{{__('Email address')}}</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" class="form-control btn-pill @error('email') is-invalid @enderror" id="email" placeholder="name@example.com" value="{{ old('email') }}">
                                @error('email')
                                <div class="help-block text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Mobile')}}</label>
                            <div class="col-sm-9">
                                <input type="text" name="phone" class="form-control btn-pill @error('phone') is-invalid @enderror" id="phone" placeholder="{{__('Mobile')}}" value="{{ old('phone') }}" required size="8" >
                                @error('phone')
                                <div class="help-block text-danger">{{ $message }}</div>
                                @else
                                <div class="help-block text-info hint"><i class="fa fa-exclamation-circle"></i> <small>{{__('This will be your username')}}</small></div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{__("Profile Photo")}}</label>
                            <div class="col-sm-9">
                                <input name="profile_photo" type="file" accept="image/*" class="form-control btn-pill" value="{{old('profile_photo')}}">
                                <div class="help-block text-info hint"><i class="fa fa-exclamation-circle"></i> <small>supported file types: all image files (max size 1000Kb)</small></div>
                            </div>
                        </div>

                        <hr>
                        <h5>{{__('Account Security')}}</h5>
                        <div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Password')}}</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" class="form-control btn-pill @error('password') is-invalid @enderror" id="password" placeholder="{{__('Password')}}" required>
                                @error('password')
                                <div class="help-block text-danger">{{ $message }}</div>
                                @else
                                <div class="help-block text-info hint"><i class="fa fa-exclamation-circle"></i> <small>{{__('minimum 8 characters')}}</small></div>
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

                <hr>
                <h5>{{__('Additional Information')}}</h5>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label for="driver_type" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Contract Type')}}</label>
                            <div class="col-sm-9">
                                {!! Form::select('driver_type', Config::get('enums.driver_types'), null, array('id' => 'driver_type', 'class' => 'form-control btn-pill', 'required', 'onchange' => 'changeDriverType()')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="commission" style="display: none;">
                    <div class="col">
                        <div class="form-group row">
                            <label for="commission" class="col-sm-3 col-form-label">{{__('Commission')}}</label>
                            <div class="col-sm-4">
                                <input type="text" name="fixed_commission" class="form-control btn-pill" placeholder="{{__('Fixed Commission')}}" id="fixed_commission" value="{{ old('fixed_commission') }}">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="commission_percent" class="form-control btn-pill" placeholder="{{__('Commission Percent')}}" id="commission_percent" value="{{ old('commission_percent') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label for="transport_mode" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('What mode of transport will you be using?')}}</label>
                            <div class="col-sm-9">
                                {!! Form::select('transport_mode', Config::get('enums.transport_mode'), null, array('class' => 'form-control btn-pill', 'required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{__("Driver's Licence Image")}}</label>
                            <div class="col-sm-9">
                                <input name="licence_file" type="file" accept="image/*" class="form-control btn-pill" value="{{old('licence_file')}}">
                                <div class="help-block text-info hint"><i class="fa fa-exclamation-circle"></i> <small>supported file types: all image files (max size 1000Kb)</small></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{__('Licence Expiration Date')}}</label>
                            <div class="col-sm-9">
                                <input name="licence_expiration_date" type="date" class="form-control btn-pill digits" value="{{ old('licence_expiration_date') }}">
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
<script src="{{asset('js/custom-js.js')}}"></script>
@endsection

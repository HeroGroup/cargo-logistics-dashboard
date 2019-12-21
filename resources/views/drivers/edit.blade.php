@extends('layouts.admin', ['crumbs' => [
    __('Drivers') => route('drivers.index', 'all'),
    __('Edit Driver') => route('drivers.edit', $driver->id)]
, 'title' => __('Edit Driver Information')])
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger col-sm-12">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card col-md-12 mx-auto">
        {!! Form::model($driver, array('route' => array('drivers.update', $driver), 'method' => 'PUT', 'files' => 'true', 'class' => 'form theme-form')) !!}
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5>{{__('Personal Information')}}</h5>
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Full Name')}}</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control btn-pill @error('name') is-invalid @enderror" id="name" placeholder="{{__('Full Name')}}" value="{{$driver->name}}" required>
                                @error('name')
                                <div class="help-block text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">{{__('Email address')}}</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" class="form-control btn-pill @error('email') is-invalid @enderror" id="email" placeholder="name@example.com" value="{{$driver->email}}">
                                @error('email')
                                <div class="help-block text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Mobile')}}</label>
                            <div class="col-sm-9">
                                <input type="text" name="phone" class="form-control btn-pill @error('phone') is-invalid @enderror" id="phone" placeholder="{{__('Mobile')}}" value="{{$driver->phone}}" required size="8" >
                                @error('phone')
                                <div class="help-block text-danger">{{ $message }}</div>
                                @else
                                <div class="help-block text-info hint"><i class="fa fa-exclamation-circle"></i> <small>{{__('This will be your username')}}</small></div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{__("Profile Photo")}}</label>
                            <div class="col-sm-5">
                                <input name="profile_photo" type="file" accept="image/*" class="form-control btn-pill">
                                <div class="help-block text-info hint"><i class="fa fa-exclamation-circle"></i> <small>supported file types: all image files (max size 1000Kb)</small></div>
                            </div>
                            @if($driver->profile_photo)
                                <div class="col-sm-4">
                                    <a href="{{$driver->profile_photo}}" target="_blank"><img class="driver-licence-image" src="{{$driver->profile_photo}}" height="50" ></a>
                                </div>
                            @endif
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
                                {!! Form::select('driver_type', Config::get('enums.driver_types'), $driver->driver_type, array('id' => 'driver_type', 'class' => 'form-control btn-pill', 'required', 'onchange' => 'changeDriverType()')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="commission" style="display: {{$driver->driver_type == 'salary_based' ? 'none' : 'block'}}">
                    <div class="col">
                        <div class="form-group row">
                            <label for="commission" class="col-sm-3 col-form-label">{{__('Commission')}}</label>
                            <div class="col-sm-4">
                                <input type="text" name="fixed_commission" class="form-control btn-pill" placeholder="{{__('Fixed Commission')}}" id="fixed_commission" value="{{$driver->fixed_commission}}">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="commission_percent" class="form-control btn-pill" placeholder="{{__('Commission Percent')}}" id="commission_percent" value="{{$driver->commission_percent}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label for="transport_mode" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('What mode of transport will you be using?')}}</label>
                            <div class="col-sm-9">
                                {!! Form::select('transport_mode', Config::get('enums.transport_mode'), $driver->transport_mode, array('class' => 'form-control btn-pill digits', 'required')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{__("Driver's Licence Image")}}</label>
                            <div class="col-sm-5">
                                <input name="licence_file" type="file" class="form-control btn-pill">
                            </div>
                            @if($driver->licence_file)
                            <div class="col-sm-4">
                                <a href="{{$driver->licence_file}}" target="_blank"><img class="driver-licence-image" src="{{$driver->licence_file}}" height="50" ></a>
                            </div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{__('Licence Expiration Date')}}</label>
                            <div class="col-sm-9">
                                <input name="licence_expiration_date" type="date" class="form-control btn-pill digits" value="{{ $driver->licence_expiration_date }}">
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <h5>{{__('Status')}}</h5>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{__("Current Status")}}</label>
                            <div class="col-sm-1">
                                <label class="custom-switch">
                                    <input type="checkbox" name="availability" id="availability" {{$driver->availability ? "checked" : ""}} onchange="changeAvailabilityText(this.checked)">
                                    <span class="custom-slider round"></span>
                                </label>
                            </div>
                            <label id="driver-availability" class="col-sm-2 col-form-label"> {{$driver->availability ? __("Online") : __("Offline")}}</label>
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
<script>
    function changeAvailabilityText(input) {
        let availability = $("#driver-availability");
        if(input)
            availability.html(" Online");
        else
            availability.html(" Offline");
    }
</script>
@endsection

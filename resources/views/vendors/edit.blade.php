@extends('layouts.admin', ['crumbs' => [
    'Vendors' => route('vendors.index', 'all'),
    'Edit Vendor' => route('vendors.edit', $vendor->id)]
, 'title' => __('Edit Vendor Information')])
@section('content')
    <div class="card col-md-12 mx-auto">
        {!! Form::model($vendor, array('route' => array('vendors.update', $vendor), 'method' => 'PUT', 'files' => 'true', 'class' => 'form theme-form')) !!}
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5>Company Information</h5>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label"><label class="text-danger" style="font-size: 16px;">*</label> Company Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control btn-pill @error('name') is-invalid @enderror" id="name" value="{{$vendor->name}}" required>
                            @error('name')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="logo" class="col-sm-3 col-form-label"><label class="text-danger" style="font-size: 16px;">*</label> Company Logo</label>
                        <div class="col-sm-6">
                            <input name="logo" type="file" accept="image/*" class="form-control btn-pill @error('logo') is-invalid @enderror" value="{{$vendor->logo}}" @if(!$vendor->logo) required @endif>
                            @error('logo')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @else
                            <div class="help-block text-info" style="margin-left: 10px;"><i class="fa fa-exclamation-circle"></i> <small>supported file types: all image files (max size 500Kb)</small></div>
                            @enderror
                        </div>
                        <div class="col-sm-3">
                            <a href="{{$vendor->logo}}" target="_blank"><img src="{{$vendor->logo}}" height="50" alt="{{$vendor->name}}"></a>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">Email address</label>
                        <div class="col-sm-9">
                            <input type="email" name="email" class="form-control btn-pill @error('email') is-invalid @enderror" id="email" value="{{$vendor->email}}">
                            @error('email')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phone" class="col-sm-3 col-form-label">Telephone</label>
                        <div class="col-sm-9">
                            <input type="text" name="phone" class="form-control btn-pill @error('phone') is-invalid @enderror" id="phone" value="{{$vendor->phone}}" size="8">
                            @error('phone')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="contact_person" class="col-sm-3 col-form-label"><label class="text-danger" style="font-size: 16px;">*</label> Contact Person</label>
                        <div class="col-sm-9">
                            <input type="text" name="contact_person" class="form-control btn-pill @error('contact_person') is-invalid @enderror" id="contact_person" value="{{$vendor->contact_person}}" required>
                            @error('contact_person')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="menu" class="col-sm-3 col-form-label">Menu</label>
                        <div class="col-sm-6">
                            <input name="menu" type="file" accept="application/pdf" class="form-control btn-pill @error('menu') is-invalid @enderror" value="{{old('menu')}}" >
                            @error('menu')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @else
                            <div class="help-block text-info" style="margin-left: 10px;"><i class="fa fa-exclamation-circle"></i> <small>supported file types: pdf, all image files (max size 1000Kb)</small></div>
                            @enderror
                        </div>
                        <div class="col-sm-3" style="padding-top: 10px;">
                        @if($vendor->menu)
                            <a href="{{$vendor->menu}}" target="_blank">View Menu</a>
                        @else
                            <span>No menu uploaded yet.</span>
                        @endif
                        </div>
                    </div>
                </div>
            </div>

            @component('components.address-edit', ['title' => __('Pickup Address'), 'data' => $vendor, 'countries' => $countries, 'areas' => $areas])@endcomponent

            @if (auth()->user()->user_type == 'admin')
            <hr>
            <h5>Fees</h5>
            <div class="row">
                <div class="col">
                    <div class="form-group row">
                        <label for="delivery_fee" class="col-sm-3 col-form-label">Delivery Fee (kwd)</label>
                        <div class="col-sm-3">
                            <input name="delivery_fee" type="number" step="0.001" class="form-control btn-pill digits" value="{{$vendor->delivery_fee}}">
                        </div>
                        <label for="service_charge" class="col-sm-2 col-form-label">Service Charge (kwd)</label>
                        <div class="col-sm-3">
                            <input name="service_charge" type="number" step="0.001" class="form-control btn-pill digits" value="{{$vendor->service_charge}}">
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <h5>Additional Information</h5>
                <div class="checkbox">
                    <input type="checkbox" class="text-info" name="access_admin_drivers" id="access_admin_drivers" {{ $vendor->access_admin_drivers ? 'checked' : '' }}>
                    <label for="access_admin_drivers">Vendor has access to all drivers</label>
                </div>

                <div class="checkbox">
                    <input type="checkbox" name="has_own_drivers" id="has_own_drivers" {{ $vendor->has_own_drivers ? 'checked' : '' }}>
                    <label for="has_own_drivers">Vendor has their own drivers</label>
                </div>
            @endif
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{route('vendors.index', 'all')}}" class="btn btn-light">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>

@endsection

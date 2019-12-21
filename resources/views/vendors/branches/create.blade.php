@extends('layouts.admin', ['crumbs' => [
    __('Vendors') => route('vendors.index', 'all'),
    __('Branches') => route('vendors.branches', $vendorId),
    __('Create Branch') => route('vendors.branches.create', $vendorId)]
, 'title' => __('New Branch')])
@section('content')
    <div class="card col-md-12 mx-auto">
        {{ Form::open(array('url' => route('vendors.branches.store', $vendorId), 'method' => 'POST', 'class' => 'form theme-form')) }}
        @csrf
        <input type="hidden" name="vendor_id" value="{{$vendorId}}">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5>{{__('Branch Information')}}</h5>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Branch Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control btn-pill @error('name') is-invalid @enderror" id="name" placeholder="{{__('Branch Name')}}" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mobile" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Mobile')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="mobile" class="form-control btn-pill @error('mobile') is-invalid @enderror" id="name" placeholder="{{__('Mobile')}}" value="{{ old('mobile') }}" required size="8" >
                            @error('mobile')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="contact_person" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Contact Person')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="contact_person" class="form-control btn-pill @error('contact_person') is-invalid @enderror" id="contact_person" placeholder="{{__('Contact Person')}}" value="{{ old('contact_person') }}" required>
                            @error('contact_person')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                @component('components.password-component')@endcomponent

                    @component('components.address', ['title' => __('Pickup Address'), 'countries' => $countries])@endcomponent
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            <a href="{{route('vendors.branches', $vendorId)}}" class="btn btn-light">{{__('Cancel')}}</a>
        </div>
        {{ Form::close() }}
    </div>

@endsection

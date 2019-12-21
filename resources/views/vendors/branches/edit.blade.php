@extends('layouts.admin', ['crumbs' => [
    __('Vendors') => route('vendors.index', 'all'),
    __('Branches') => route('vendors.branches', $vendorId),
    __('Edit Branch') => route('vendors.branches.edit', ['vendor' => $vendorId, 'branch' => $branch->id])]
, 'title' => __('Edit Branch Information')])
@section('content')
    <div class="card col-md-12 mx-auto">
        {!! Form::model($branch, array('route' => array('vendors.branches.update', $vendorId, $branch), 'method' => 'PUT', 'class' => 'form theme-form')) !!}
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

                    <h5>{{__('Company Information')}}</h5>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label"><label class="text-danger" style="font-size: 16px;">*</label> {{__('Branch Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control btn-pill @error('name') is-invalid @enderror" id="name" value="{{$branch->name}}" required>
                            @error('name')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mobile" class="col-sm-3 col-form-label"><label class="text-danger" style="font-size: 16px;">*</label> {{__('Mobile')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="mobile" class="form-control btn-pill @error('mobile') is-invalid @enderror" id="phone" value="{{$branch->mobile}}" required size="8">
                            @error('mobile')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @component('components.address-edit', ['title' => __('Pickup Address'), 'data' => $branch, 'countries' => $countries, 'areas' => $areas])@endcomponent
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            <a href="{{route('vendors.branches', $vendorId)}}" class="btn btn-light">{{__('Cancel')}}</a>
        </div>
        {!! Form::close() !!}
    </div>

@endsection

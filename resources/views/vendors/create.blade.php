@extends('layouts.admin', ['crumbs' => [
    'Vendors' => route('vendors.index', 'all'),
    'Create Vendor' => route('vendors.create')]
, 'title' => __('Register Vendor')])
@section('content')
    <div class="card col-md-12 mx-auto">
        {{ Form::open(array('url' => route('vendors.store'), 'method' => 'POST', 'files' => 'true', 'class' => 'form theme-form')) }}
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5>Company Information</h5>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Company Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control btn-pill @error('name') is-invalid @enderror" id="name" placeholder="Company Name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="logo" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Company Logo</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                            <input name="logo" type="file" accept="image/*" class="form-control btn-pill @error('logo') is-invalid @enderror" value="{{old('logo')}}" required>
                            @error('logo')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @else
                            <div class="help-block text-info hint"><i class="fa fa-exclamation-circle"></i> <small>supported file types: all image files (max size 500Kb)</small></div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">Email address</label>
                        <div class="col-sm-9">
                            <input type="email" name="email" class="form-control btn-pill @error('email') is-invalid @enderror" id="email" placeholder="name@example.com" value="{{ old('email') }}">
                            @error('email')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phone" class="col-sm-3 col-form-label">Telephone</label>
                        <div class="col-sm-9">
                            <input type="text" name="phone" class="form-control btn-pill @error('phone') is-invalid @enderror" id="phone" placeholder="Telephone" value="{{ old('phone') }}" size="8">
                            @error('phone')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mobile" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Mobile</label>
                        <div class="col-sm-9">
                            <input type="text" name="mobile" class="form-control btn-pill @error('mobile') is-invalid @enderror" id="phone" placeholder="Mobile" value="{{ old('mobile') }}" required size="8">
                            @error('mobile')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="contact_person" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Contact Person</label>
                        <div class="col-sm-9">
                            <input type="text" name="contact_person" class="form-control btn-pill @error('contact_person') is-invalid @enderror" id="contact_person" placeholder="Contact Person" value="{{ old('contact_person') }}" required>
                            @error('contact_person')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="menu" class="col-sm-3 col-form-label">Menu</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="MAX_FILE_SIZE" value="1024000" />
                            <input name="menu" type="file" accept="image/*,application/pdf" class="form-control btn-pill @error('menu') is-invalid @enderror" value="{{old('menu')}}" >
                            @error('menu')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @else
                            <div class="help-block text-info hint"><i class="fa fa-exclamation-circle"></i> <small>supported file types: pdf, all image files (max size 1000Kb)</small></div>
                            @enderror
                        </div>
                    </div>

                    <hr>
                    <h5>Account Security</h5>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Password</label>
                        <div class="col-sm-9">
                            <input type="password" name="password" class="form-control btn-pill @error('password') is-invalid @enderror" id="password" placeholder="Password" required>
                            @error('password')
                            <div class="help-block text-danger">{{ $message }}</div>
                            @else
                            <div class="help-block text-info hint"><i class="fa fa-exclamation-circle"></i> <small>minimum 8 characters</small></div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password_confirmation" class="col-sm-3 col-form-label"><span class="text-danger">*</span> Confirm Password</label>
                        <div class="col-sm-9">
                            <input type="password" name="password_confirmation" class="form-control btn-pill" placeholder="Confirm Password" id="password_confirmation" required>
                        </div>
                    </div>
                </div>
            </div>

            @component('components.address', ['title' => __('Pickup Address'), 'countries' => $countries])@endcomponent

            <hr>
            <h5>Subscription</h5>
            <div class="row">
                <div class="col">
                    <div class="form-group row">
                        <label for="subscribe_from" class="col-sm-3 col-form-label">From Date</label>
                        <div class="col-sm-3">
                            <input name="subscribe_from" type="date" class="form-control btn-pill digits" value="{{ old('subscribe_from') }}">
                        </div>
                        <label for="subscribe_to" class="col-sm-2 col-form-label">To Date</label>
                        <div class="col-sm-3">
                            <input name="subscribe_to" type="date" class="form-control btn-pill digits" value="{{ old('subscribe_to') }}">
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <h5>Fees</h5>
            <div class="row">
                <div class="col">
                    <div class="form-group row">
                        <label for="delivery_fee" class="col-sm-3 col-form-label">Delivery Fee (kwd)</label>
                        <div class="col-sm-3">
                            <input name="delivery_fee" type="number" step="0.001" class="form-control btn-pill digits" value="{{ old('delivery_fee') }}">
                        </div>
                        <label for="service_charge" class="col-sm-2 col-form-label">Service Charge (kwd)</label>
                        <div class="col-sm-3">
                            <input name="service_charge" type="number" step="0.001" class="form-control btn-pill digits" value="{{ old('service_charge') }}">
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <h5>Additional Information</h5>

            <div class="row">
                <div class="col">
                    <div class="checkbox">
                        <input type="checkbox" name="access_admin_drivers" id="access_admin_drivers">
                        <label for="access_admin_drivers">Vendor has access to all drivers</label>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" name="has_own_drivers" id="has_own_drivers">
                        <label for="has_own_drivers">Vendor has their own drivers</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{route('vendors.index', 'all')}}" class="btn btn-light">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>

<script>
    $(document).ready(function() {
        let today = new Date();
        $("input[name=subscribe_from]").val(today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2));
    });
</script>

@endsection

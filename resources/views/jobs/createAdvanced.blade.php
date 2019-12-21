@extends('layouts.admin', ['crumbs' => [
    __('Create Job') => route('jobs.create', 'advanced')]
, 'title' => __('New Job')])
@section('content')
    <div>
        <div style="display: inline-block;">
            <a href="{{route('jobs.create', 'simple')}}" class="btn btn-lg btn-secondary btn-square create-job">{{__('Simple Form')}}</a>
        </div>
        <div style="display: inline-block;">
            <a href="{{route('jobs.create', 'advanced')}}" class="btn btn-lg btn-secondary btn-square create-job">{{__('Advanced Form')}}</a>
        </div>
    </div>

    <div class="card col-md-12 mx-auto">
        <form method="post" action="{{route('jobs.store')}}" class="form theme-form" id="new-job-form">
            @csrf
            <input type="hidden" value="{{auth()->user()->vendor_id}}" name="vendor_id">
            <input type="hidden" value="{{session('branch')}}" name="vendor_branch_id">
            <div class="card-body">
                @csrf
                <div id="advanced-job" class="default-according">
                    <div class="card">
                        <div id="heading-pickup" class="card-header" style="background-color: lightgray;">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#pickup" aria-expanded="true" aria-controls="pickup">
                                    <span class="fa fa-fw fa-plus"></span> {{__('Pickup')}}
                                </button>
                            </h5>
                        </div>
                        <div id="pickup" class="collapse" aria-labelledby="heading-pickup" data-parent="#advanced-job">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="pickup_address" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Address')}}</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="pickup_address" class="form-control btn-pill @error('pickup_address') is-invalid @enderror" id="pickup_address" placeholder="{{__('Address')}}" value="{{ $owner->getAddress($owner->id) }}" required>
                                                @error('pickup_address')
                                                <div class="help-block text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-1">
                                                <span class="text-primary text-underline fa fa-fw fa-map-marker custom-map-icon" data-title="tooltip" title="{{__('CHOOSE ON MAP')}}" onclick="openPickupModal()"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="pickup_description" class="col-sm-3 col-form-label">{{__('Description')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="pickup_description" class="form-control btn-pill @error('pickup_description') is-invalid @enderror" id="pickup_description" placeholder="{{__('Description')}}" value="{{ $owner->pickup_description }}">
                                                @error('pickup_description')
                                                <div class="help-block text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="pickup_place_type" class="col-sm-3 col-form-label">{{__('Place Type')}}</label>
                                            <div class="col-sm-9">
                                                {!! Form::select('pickup_place_type', \CargoLogisticsModels\Setting::where('setting_key', 'LIKE', 'place_type')->pluck('setting_value', 'setting_value')->toArray(), $owner->place_type, array('id' => 'pickup_place_type', 'class' => 'form-control btn-pill', 'placeholder' => __('Select from list...'), 'value' => $owner->place_type )) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row" style="display: none;">
                                            <label for="pickup_latitude" class="col-sm-3 col-form-label">{{__('Latitude')}}</label>
                                            <div class="col-sm-3">
                                                <input type="text" name="pickup_latitude" class="form-control btn-pill" id="pickup_latitude" placeholder="0.00" value="{{ $owner->latitude }}" >
                                            </div>
                                            <label for="pickup_longitude" class="col-sm-2 col-form-label">{{__('Longitude')}}</label>
                                            <div class="col-sm-3">
                                                <input type="text" name="pickup_longitude" class="form-control btn-pill" id="pickup_longitude" placeholder="0.00" value="{{ $owner->longitude }}" >
                                            </div>
                                            <div class="col-sm-1">
                                                <span class="text-primary text-underline fa fa-fw fa-map-marker custom-map-icon" data-title="tooltip" title="{{__('CHOOSE ON MAP')}}" onclick="openPickupModal()"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="pickup_contact_person" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Contact Person')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="pickup_contact_person" class="form-control btn-pill @error('pickup_contact_person') is-invalid @enderror" id="pickup_contact_person" placeholder="{{__('Contact Person')}}" value="{{ $owner->contact_person }}" required>
                                                @error('pickup_contact_person')
                                                <div class="help-block text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="pickup_contact_phone" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Contact Phone')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="pickup_contact_phone" class="form-control btn-pill @error('pickup_contact_phone') is-invalid @enderror" id="pickup_contact_phone" placeholder="{{__('Contact Phone')}}" value="{{ $owner->mobile }}" required minlength="8" maxlength="8">
                                                @error('pickup_contact_phone')
                                                <div class="help-block text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div id="heading-destination" class="card-header" style="background-color: lightgray;">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#destination" aria-expanded="false" aria-controls="destination">
                                    <span class="fa fa-fw fa-minus"></span> {{__('Drop off')}}
                                </button>
                            </h5>
                        </div>
                        <div id="destination" class="collapse show" aria-labelledby="heading-destination" data-parent="#advanced-job">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="dropoff_address" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Address')}}</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="dropoff_address" class="form-control btn-pill @error('dropoff_address') is-invalid @enderror" id="dropoff_address" placeholder="{{__('Address')}}" value="{{ old('dropoff_address') }}" required>
                                                @error('dropoff_address')
                                                <div class="help-block text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-1">
                                                <span class="text-primary text-underline fa fa-fw fa-map-marker custom-map-icon" data-title="tooltip" title="{{__('CHOOSE ON MAP')}}" onclick="openDropoffModal()"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="dropoff_description" class="col-sm-3 col-form-label">{{__('Description')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="dropoff_description" class="form-control btn-pill @error('dropoff_description') is-invalid @enderror" id="dropoff_description" placeholder="{{__('Description')}}" value="{{ old('dropoff_description') }}">
                                                @error('dropoff_description')
                                                <div class="help-block text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="dropoff_place_type" class="col-sm-3 col-form-label">{{__('Place Type')}}</label>
                                            <div class="col-sm-9">
                                                {!! Form::select('dropoff_place_type', \CargoLogisticsModels\Setting::where('setting_key', 'LIKE', 'place_type')->pluck('setting_value', 'setting_value')->toArray(), null, array('id' => 'dropoff_place_type', 'class' => 'form-control btn-pill', 'placeholder' => __('Select from list...'), 'value' => old('dropoff_place_type') )) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row" style="display: none;">
                                            <label for="dropoff_latitude" class="col-sm-3 col-form-label">{{__('Latitude')}}</label>
                                            <div class="col-sm-3">
                                                <input type="text" name="dropoff_latitude" class="form-control btn-pill" id="dropoff_latitude" placeholder="0.00" value="{{ old('dropoff_latitude') }}" >
                                            </div>
                                            <label for="dropoff_longitude" class="col-sm-2 col-form-label">{{__('Longitude')}}</label>
                                            <div class="col-sm-3">
                                                <input type="text" name="dropoff_longitude" class="form-control btn-pill" id="dropoff_longitude" placeholder="0.00" value="{{ old('dropoff_longitude') }}" >
                                            </div>
                                            <div class="col-sm-1">
                                                <span class="text-primary text-underline fa fa-fw fa-map-marker custom-map-icon" data-title="tooltip" title="{{__('CHOOSE ON MAP')}}" onclick="openDropoffModal()"></span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="dropoff_contact_person" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Contact Person')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="dropoff_contact_person" class="form-control btn-pill @error('dropoff_contact_person') is-invalid @enderror" id="dropoff_contact_person" placeholder="{{__('Contact Person')}}" value="{{ old('dropoff_contact_person') }}" required>
                                                @error('dropoff_contact_person')
                                                <div class="help-block text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="dropoff_contact_phone" class="col-sm-3 col-form-label"><span class="text-danger">*</span> {{__('Contact Phone')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="dropoff_contact_phone" class="form-control btn-pill @error('dropoff_contact_phone') is-invalid @enderror" id="dropoff_contact_phone" placeholder="{{__('Contact Phone')}}" value="{{ old('dropoff_contact_phone') }}" required minlength="8" maxlength="8">
                                                @error('dropoff_contact_phone')
                                                <div class="help-block text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div id="heading-schedule" class="card-header" style="background-color: lightgray;">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#schedule" aria-expanded="false" aria-controls="schedule">
                                    <span class="fa fa-fw fa-plus"></span> {{__('Schedule')}}
                                </button>
                            </h5>
                        </div>
                        <div id="schedule" class="collapse" aria-labelledby="heading-schedule" data-parent="#advanced-job">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="pickup_date" class="col-sm-3 col-form-label">{{__('Pickup Date')}}</label>
                                            <div class="col-sm-3">
                                                <input type="date" name="pickup_date" class="form-control" id="pickup_date" value=" {{ old('pickup_date') }}">
                                            </div>
                                            <label for="pickup_time" class="col-sm-2 col-form-label">{{__('Pickup Time')}}</label>
                                            <div class="col-sm-3">
                                                <input type="time" name="pickup_time" class="form-control" id="pickup_time" value=" {{ old('pickup_time') }}">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label for="dropoff_date" class="col-sm-3 col-form-label">{{__('Drop Off Date')}}</label>
                                            <div class="col-sm-3">
                                                <input type="date" name="dropoff_date" class="form-control" id="dropoff_date" value=" {{ old('dropoff_date') }}">
                                            </div>
                                            <label for="dropoff_time" class="col-sm-2 col-form-label">{{__('Drop Off Time')}}</label>
                                            <div class="col-sm-3">
                                                <input type="time" name="dropoff_time" class="form-control" id="dropoff_time" value=" {{ old('dropoff_time') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div id="heading-items" class="card-header" style="background-color: lightgray;">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#items" aria-expanded="false" aria-controls="items">
                                    <span class="fa fa-fw fa-plus"></span> {{__('Items')}}
                                </button>
                            </h5>
                        </div>
                        <div id="items" class="collapse" aria-labelledby="heading-items" data-parent="#advanced-job">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col" id="item-inputs">
                                        <div class="form-group row">
                                            <div class="col-sm-2">
                                                <label for="quantity">Qnt.</label>
                                                <input type="number" class="form-control" name="quantity[0]">
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="item_description">Description</label>
                                                <input type="text" class="form-control" name="item_description[0]" onkeyup="updateDescription(this)">
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="item_price">Price</label>
                                                <input type="number" step="0.001" class="form-control" name="item_price[0]" >
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-primary btn-xs" style="margin-top: 35px;" onclick="addItem()">
                                                    <i class="fa fa-fw fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div id="heading-instructions" class="card-header" style="background-color: lightgray;">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#instructions" aria-expanded="false" aria-controls="instructions">
                                    <span class="fa fa-fw fa-plus"></span> {{__('Instructions')}}
                                </button>
                            </h5>
                        </div>
                        <div id="instructions" class="collapse" aria-labelledby="heading-instructions" data-parent="#advanced-job">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group row">
                                            <label for="instructions" class="col-sm-3 col-form-label">{{__('Instructions')}}</label>
                                            <div class="col-sm-9">
                                                <textarea cols="3" name="instructions" class="form-control" id="instructions" value="{{ old('instructions') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary" onclick="submitForm()">{{__('Submit')}}</button>
                <a href="{{route('dashboard')}}" class="btn btn-light">{{__('Cancel')}}</a>
            </div>
        </form>
    </div>

    <div id="map" style="display: none;"></div>

    <div class="modal fade" id="pickup-map-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Select Pickup Location on Map')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="pickup-map" style="height: 550px; width: 100%;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-air-light" data-dismiss="modal">{{__('Close Without Saving')}}</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="selectPickupLocation()">{{__('Save and Close')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dropoff-map-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Select Drop Off Location on Map')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="dropoff-map" style="height: 550px; width: 100%;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-air-light" data-dismiss="modal">{{__('Close Without Saving')}}</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="selectDropoffLocation()">{{__('Save and Close')}}</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $(".btn-link").attr("type", "button");
        });

        $(".btn-link").on('click', function() {
            if ($(this).children().hasClass('fa-plus')) {
                $(".btn-link span").removeClass('fa-minus').addClass('fa-plus');
                $(this).children().removeClass('fa-plus').addClass('fa-minus');
            } else {
                $(".btn-link span").removeClass('fa-minus').addClass('fa-plus');
                $(this).children().removeClass('fa-minus').addClass('fa-plus');
            }
        });

        function submitForm() {
            $("#new-job-form").submit();
        }

        let items = 0, newItem = false;
        function updateDescription(input) {
            let description = input.value;
            if (description.length > 0)
                newItem = true;
        }

        function addItem() {
            if (newItem) {
                items++;
                newItem = false;
                let newChild = "<div class='form-group row'>" +
                    "<div class='col-sm-2'>" +
                    "    <label for='quantity'>Qnt.</label>" +
                    "    <input type='number' class='form-control' name='quantity["+items+"]'>" +
                    "</div>" +
                    "<div class='col-sm-3'>" +
                    "    <label for='item_description'>Description</label>" +
                    "    <input type='text' class='form-control' name='item_description["+items+"]' onkeyup='updateDescription(this)'>" +
                    "</div>" +
                    "<div class='col-sm-3'>" +
                    "    <label for='item_price'>Price</label>" +
                    "    <input type='number' step='0.001' class='form-control' name='item_price["+items+"]' >" +
                    "</div>" +
                    "<div class='col-sm-1'>" +
                    "    <button type='button' class='btn btn-primary btn-xs' style='margin-top: 35px;' onclick='addItem()'>" +
                    "        <i class='fa fa-fw fa-plus'></i>'" +
                    "    </button>" +
                    "</div>" +
                    "</div>";
                $("#item-inputs").append(newChild);
            }
        }
    </script>
    <script type="text/javascript" src="{{asset('js/google-places.js')}}"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=.....&libraries=places&callback=initAutocomplete" type="text/javascript"></script>
@endsection

@extends('layouts.admin', ['crumbs' => [
    'Settings' => route('settings.index')],
    'title' => __('Areas')
])
@section('content')
    <div class="card col-md-12">
        <div class="card-body">
            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-countries-tab" data-toggle="pill" href="#pills-countries" role="tab" aria-controls="pills-countries" aria-selected="true">{{__('Countries')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-areas-tab" data-toggle="pill" href="#pills-areas" role="tab" aria-controls="pills-areas" aria-selected="false">{{__('Areas')}}</a>
                </li>
            </ul>
            <hr>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-countries" role="tabpanel" aria-labelledby="pills-countries-tab">
                    @component('components.areas.countries', ['countries' => $countries])@endcomponent
                </div>
                <div class="tab-pane fade" id="pills-areas" role="tabpanel" aria-labelledby="pills-areas-tab">
                    @component('components.areas.areas', ['countries' => $countries, 'areas' => $areas])@endcomponent
                </div>
            </div>
        </div>
    </div>

@endsection

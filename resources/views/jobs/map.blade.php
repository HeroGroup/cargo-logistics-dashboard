@extends('layouts.admin')
@section('content')
    <div id="map" style="overflow: hidden; top: 0; bottom: 0; width: 100%; position: absolute;"></div>
    <div id="side-menu" style="width: 250px; position: absolute; top: 0; left:0; bottom: 0; background-color: black; opacity: 0.8; overflow: scroll;">
        <div id="mainMenu">
            @component('components.map.mainMenu', ['onlineDrivers'=>$onlineDrivers, 'newJobs'=>$newJobs, 'startedJobs'=>($acceptedJobs+$startedJobs)])@endcomponent
        </div>
        <div id="jobs-menu">
            @component('components.map.jobs')@endcomponent
        </div>
        <div id="job-menu">
            @component('components.map.job')@endcomponent
        </div>
        <div id="drivers-menu">
            @component('components.map.drivers')@endcomponent
        </div>
        <div id="driver-menu">
            @component('components.map.driver')@endcomponent
        </div>
    </div>

    <div id="left-menu" style="height: 320px; width: 60px; right: 0; top: 80px; position: absolute; background-color: black; opacity: 0.8; ">
        @component('components.map.markerHelper', ['src' => 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png', 'title' => 'New'])@endcomponent
        @component('components.map.markerHelper', ['src' => 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png', 'title' => 'Accepted'])@endcomponent
        @component('components.map.markerHelper', ['src' => 'https://maps.google.com/mapfiles/ms/icons/pink-dot.png', 'title' => 'Started'])@endcomponent
        @component('components.map.markerHelper', ['src' => 'https://maps.google.com/mapfiles/ms/icons/green-dot.png', 'title' => 'Complete'])@endcomponent
        @component('components.map.markerHelper', ['src' => 'https://maps.google.com/mapfiles/ms/icons/red-dot.png', 'title' => 'Abandon'])@endcomponent
    </div>

    <div id="bottom-menu" style="height: 100px; position: fixed; left: 350px; right: 150px; bottom: 0; background-color: black; opacity: 0.8; color: #fff;">
        <div class="row text-center" style="padding-top: 30px;">
            <div class="col-md-3">
                <div>Delivered</div>
                <div style="font-size: 20px; font-weight: bold;">{{$completedJobs}}</div>
            </div>
            <div class="col-md-3">
                <div>Canceled</div>
                <div style="font-size: 20px; font-weight: bold;">{{$canceledJobs}}</div>
            </div>
            <div class="col-md-3">
                <div>Avg Delivery Time</div>
                <div style="font-size: 20px; font-weight: bold;">{{$avgDeliveryTime}} minutes</div>
            </div>
            <div class="col-md-3">
                <div>Drivers Online</div>
                <div style="font-size: 20px; font-weight: bold;">{{$onlineDrivers}}/{{$allDrivers}}</div>
            </div>
        </div>
    </div>

    <script src="{{asset('js/custom-map.js')}}"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnzOm5LfUi_94uz3nPjW-LExL14iqfLmU&callback=initMap" type="text/javascript"></script>
@endsection

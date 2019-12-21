@extends('layouts.admin', ['crumbs' => []])
@section('content')

        <div class="col-md-4 col-sm-12">
            <h6>{{__('Number of Deliveries')}}</h6>
            <div class="bg-info card browser-widget">
                <div class="media card-body">
                    {{--<div class="media-img">
                        <img  src="assets/images/dashboard/firefox.png" alt=""/>
                    </div>--}}
                    <i class="align-self-center mr-3"></i>
                    <div class="media-body align-self-center">
                        <div>
                            <p><b>{{__('Today')}} </b></p>
                            <h4><span class="counter">{{$totalDeliveries['day']}}</span></h4>
                        </div>
                        <div>
                            <p><b>{{__('Week')}} </b></p>
                            <h4><span class="counter">{{$totalDeliveries['week']}}</span></h4>
                        </div>
                        <div>
                            <p><b>{{__('Month')}} </b></p>
                            <h4><span class="counter">{{$totalDeliveries['month']}}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <h6>{{__('Avg Delivery Time (minutes)')}}</h6>
            <div class="bg-danger card browser-widget">
                <div class="media card-body">
                    {{--<div class="media-img">
                        <img  src="assets/images/dashboard/firefox.png" alt=""/>
                    </div>--}}
                    <i class="align-self-center mr-3"></i>
                    <div class="media-body align-self-center">
                        <div>
                            <p><b>{{__('Today')}} </b></p>
                            <h4><span class="counter">{{$deliveriesTimes['day']}}</span></h4>
                        </div>
                        <div>
                            <p><b>{{__('Week')}} </b></p>
                            <h4><span class="counter">{{$deliveriesTimes['week']}}</span></h4>
                        </div>
                        <div>
                            <p><b>{{__('Month')}} </b></p>
                            <h4><span class="counter">{{$deliveriesTimes['month']}}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <h6>{{__('Total Distance (Kilometers)')}}</h6>
            <div class="bg-success card browser-widget">
                <div class="media card-body">
                    {{--<div class="media-img">
                        <img  src="assets/images/dashboard/safari.png" alt=""/>
                    </div>--}}
                    <i class="align-self-center mr-3"></i>
                    <div class="media-body align-self-center">
                        <div>
                            <p><b>{{__('Today')}} </b></p>
                            <h4><span class="counter">{{$distance['day']}}</span></h4>
                        </div>
                        <div>
                            <p><b>{{__('Week')}} </b></p>
                            <h4><span class="counter">{{$distance['week']}}</span></h4>
                        </div>
                        <div>
                            <p><b>{{__('Month')}} </b></p>
                            <h4><span class="counter">{{$distance['month']}}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <hr>

        <div class="col-md-6 col-sm-12">
            <canvas id="myChart"></canvas>
        </div>
        <div class="col-md-6 col-sm-12">
            <canvas id="myLineChart"></canvas>
        </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>
        $.ajax({
            url: '{{route('charts')}}',
            method: 'GET'
        })
            .done(function(response) {
                var ctx = document.getElementById('myChart').getContext('2d');
                var chart = new Chart(ctx, {
                    // The type of chart we want to create
                    type: 'line',

                    // The data for our dataset
                    data: {
                        labels: response.data.dailyChart.labels,
                        datasets: [{
                            label: 'Daily Earnings',
                            backgroundColor: 'transparent',
                            borderColor: 'rgb(255, 99, 132)',
                            data: response.data.dailyChart.chartData
                        }]
                    },

                    // Configuration options go here
                    options: {}
                });

                var context = document.getElementById('myLineChart').getContext('2d');
                var myLineChart = new Chart(context, {
                    type: 'line',
                    data: {
                        labels: response.data.totalChart.labels,
                        datasets: [{
                            label: 'Total Earnings',
                            backgroundColor: 'transparent',
                            borderColor: 'rgb(64, 153, 255)',
                            data: response.data.totalChart.chartData
                        }]
                    },
                    options: {}
                });
            });
    </script>
@endsection

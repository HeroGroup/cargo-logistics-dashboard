@extends('layouts.admin', ['crumbs' => [
    __('Jobs') => route('jobs.index'),
    __('Job History') => route('jobs.history', $job)]
, 'title' => __('Job History'),
'subtitle' => 'From: ' . $job->pickup_address . "\n" . 'To: ' . $job->dropoff_address])
@section('content')
    <!-- Container-fluid starts -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <!-- cd-timeline Start -->
                        <section id="cd-timeline" class="cd-container">
                            @foreach($histories as $history)
                                <div class="cd-timeline-block">
                                    <div class="cd-timeline-img cd-picture {{Config::get('enums.job_history_bg.'.$history->status)}}">
                                        <i class="{{Config::get('enums.job_history_icon.'.$history->status)}}"></i>
                                    </div>
                                    <div class="cd-timeline-content">
                                        <h4>
                                            {{strtoupper(__($history->status))}}
                                            @if(in_array($history->status, ['accepted', 'assigned', 'started']))
                                                <span style="font-size: 14px;">  ({{$history->status}} {{__('in')}} <b>{{ $history->duration_minutes }}</b> {{__('minutes')}})</span>
                                            @endif
                                        </h4>
                                        @if($history->status == 'accepted' || $history->status == 'assigned')
                                            <p class="m-0">Driver: <b>{{$history->driver->name}}</b></p>
                                        @endif

                                        @if(in_array($history->status, ['accepted', 'assigned', 'started']))
                                            <p class="m-0">Distance to pickup: <b>{{$history->distance_to_pickup}} Km</b></p>
                                            <p class="m-0">Distance to Drop Off: <b>{{$history->distance_to_dropoff}} Km</b></p>
                                        @elseif($history->status == 'completed')
                                            <p class="m-0">Distance from driver Start to Drop Off: <b>{{\CargoLogisticsModels\JobHistory::where('job_id', '=', $job->id)->where('status', '=', 'started')->first()->distance_to_dropoff}} Km</b></p>
                                            <p class="m-0">Distance from origin to Drop Off: <b>{{$job->distance}} Km</b></p>
                                            <p class="m-0">Job duration from driver Accepted to completed: <b>{{\CargoLogisticsModels\JobHistory::where('job_id', '=', $job->id)->where('status', '=', 'started')->first()->duration_minutes+$history->duration_minutes}} minutes</b></p>
                                            <p class="m-0">Job duration from driver Start to completed: <b>{{$history->duration_minutes}} minutes</b></p>
                                            <a href="{{$history->job->proof}}" target="_blank">
                                                <img src="{{$history->job->proof}}" class="p-t-20 w-100" alt="" />
                                            </a>
                                        @endif
                                        <span class="cd-date">
                                            {{date('l jS F Y', strtotime($history->created_at))}}
                                            <br>
                                            {{date('h:i A', strtotime($history->created_at))}}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </section>
                        <!-- cd-timeline Ends -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends -->
<script src="{{asset('js/timeline-v-1/main.js')}}" ></script>
<script src="{{asset('js/modernizr.js')}}" ></script>
@endsection

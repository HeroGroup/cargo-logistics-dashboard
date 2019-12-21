@extends('layouts.admin', ['crumbs' => [
    __('Jobs') => route('jobs.index')]
, 'title' => __('Job Notifications')
, 'subtitle' => 'From: ' . $job->pickup_address . "\n" . 'To: ' . $job->dropoff_address])
@section('content')
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>{{__('Driver')}}</th>
                                <th>{{__('Notified at')}}</th>
                                <th>{{__('Distance at the moment')}}</th>
                                <th>{{__('Status')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $notification)
                                <tr>
                                    <td>{{$notification->driver->name}}</td>
                                    <td>{{$notification->created_at}}</td>
                                    <td>{{$notification->driver_distance}} Km</td>
                                    <td>{{$notification->status}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

@endsection

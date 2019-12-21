@extends('layouts.admin', ['crumbs' => [
    __('Jobs') => route('jobs.index')]
, 'title' => __('live jobs')])
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger col-sm-12">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                            <tr class="text-center">
                                <th>#ID</th>
                                @if(auth()->user()->user_type=='admin')<th scope="col">{{__('Vendor')}}</th>@endif
                                <th scope="col">{{__('Driver')}}</th>
                                <th scope="col">{{__('Pickup')}}</th>
                                <th scope="col">{{__('Drop off')}}</th>
                                <th scope="col">{{__('Scheduled Time')}}</th>
                                <th scope="col">{{__('Status')}}</th>
                                <th scope="col">{{__('Created')}}</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($jobs as $job)
                                <tr class="text-center">
                                    <td>#{{$job->unique_number}}</td>
                                    @if(auth()->user()->user_type=='admin')<td>{{$job->vendor->name}}</td>@endif
                                    <td>{{$job->driver->name}}</td>
                                    <td>{{$job->pickup_address}}</td>
                                    <td>{{$job->dropoff_address}}</td>
                                    <td>{{substr($job->pickup_time, 0, 5)}}</td>
                                    <td>{{strtoupper($job->status)}}</td>
                                    <td>{{$job->created_at}}</td>
                                    <td>
                                        @component('components.actionButtons', [
                                            'routeEdit' => route('jobs.edit', $job->id),
                                            'itemId' => $job->id,
                                            'routeDelete' => route('jobs.destroy', $job->id),
                                        ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

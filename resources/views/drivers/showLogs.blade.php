@extends('layouts.admin', ['crumbs' => [
    __('Drivers') => route('drivers.index', 'all'),
    __('Log History') => route('drivers.showDriversLog', $driver->id)]
, 'title' => __('Log History')])
@section('content')
<div class="row">
    <div class="col-sm-12 col-xl-12">
        <div class="card">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">Driver Name is <b>{{__('offline')}}</b> 2019-07-11 13:28</li>
                    <li class="list-group-item">Driver Name is <b>{{__('online')}}</b> 2019-07-11 13:24</li>
                    <li class="list-group-item">Driver Name <b>{{__('Logged out')}}</b> 2019-07-11 11:53</li>
                    <li class="list-group-item">Driver Name <b>{{__('Logged in')}}</b> 2019-07-11 11:40</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

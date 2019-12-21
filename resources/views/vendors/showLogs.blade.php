@extends('layouts.admin', ['crumbs' => [
    'Vendors' => route('vendors.index', 'all'),
    'Log History' => route('vendors.showVendorsLog', $vendor->id)]
, 'title' => 'Log History'])
@section('content')
<div class="row">
    <div class="col-sm-12 col-xl-12">
        <div class="card">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">Company Name is <b>offline</b> 2019-07-11 13:28</li>
                    <li class="list-group-item">Company Name is <b>online</b> 2019-07-11 13:24</li>
                    <li class="list-group-item">Company Name <b>Logged out</b> on 2019-07-11 11:53</li>
                    <li class="list-group-item">Company Name <b>Logged in</b> on 2019-07-11 11:40</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

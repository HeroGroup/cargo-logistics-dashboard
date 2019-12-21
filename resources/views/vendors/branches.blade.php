@extends('layouts.admin', ['crumbs' => [
    __('Vendors') => route('vendors.index', 'all'),
    __('Branches') => route('vendors.branches', $vendorId)]
, 'title' => __('List of Branches')])
@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="{{route('vendors.branches.create', $vendorId)}}">
                    <i class="fa fa-fw fa-plus"></i>
                    {{__('Create Branch')}}
                </a>
            </div>
            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">{{__('Branch Name')}}</th>
                                <th scope="col">{{__('Mobile')}}</th>
                                <th scope="col">{{__('Country')}}</th>
                                <th scope="col">{{__('Area')}}</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($branches as $branch)
                                <tr>
                                    <td>{{$branch->name}}</td>
                                    <td>{{$branch->mobile}}</td>
                                    <td>{{$branch->country->name}}</td>
                                    <td>{{$branch->area->name}}</td>
                                    <td>
                                        @component('components.actionButtons', [
                                            'routeEdit' => route('vendors.branches.edit', [$branch->vendor_id, $branch->id]),
                                            'itemId' => $branch->id,
                                            'routeDelete' => route('vendors.branches.destroy', [$branch->vendor_id, $branch->id]),
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

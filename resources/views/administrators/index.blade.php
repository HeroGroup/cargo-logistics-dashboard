@extends('layouts.admin', ['crumbs' => [
    __('Users') => route('administrators.index')]
,'title' => __('List of Admin Users')])
@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-6">
                        <a class="btn btn-primary" href="{{route('administrators.create')}}">
                            <i class="fa fa-fw fa-plus"></i>
                            {{__('Create New User')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{__('Full Name')}}</th>
                                <th scope="col">{{__('Email Address')}}</th>
                                <th scope="col">{{__('Mobile')}}</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $row = 1; ?>
                            @foreach($administrators as $administrator)
                                <tr>
                                    <th scope="row">{{$row}}</th>
                                    <td>{{$administrator->name}}</td>
                                    <td>{{$administrator->email}}</td>
                                    <td>{{$administrator->mobile}}</td>
                                    <td>
                                        @component('components.actionButtons', [
                                            'routeEdit' => route('administrators.edit', $administrator->id),
                                            'itemId' => $administrator->id,
                                            'routeDelete' => route('administrators.destroy', $administrator->id),
                                        ])
                                        @endcomponent
                                    </td>
                                </tr>
                                <?php $row++; ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

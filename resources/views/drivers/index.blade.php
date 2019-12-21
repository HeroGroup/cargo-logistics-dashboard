@extends('layouts.admin', ['crumbs' => [
    __('Drivers') => route('drivers.index', 'all')]
, 'title' => __('List of Drivers')])
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
            <div class="card-header">
                <div class="float-right">
                    <div class="table-warning text-center custom-badge">{{__('Unapproved')}}</div>
                    <div class="table-danger text-center custom-badge">{{__('Inactive')}}</div>
                </div>
            </div>

            @component('components.filter', ['type' => 'Driver', 'routeAdd' => route('drivers.create')])@endcomponent

            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{__('Profile Photo')}}</th>
                                    <th scope="col">{{__('Full Name')}}</th>
                                    <th scope="col">{{__('Mobile')}}</th>
                                    <th scope="col">{{__('Transportation Mode')}}</th>
                                    <th scope="col">{{__('Type')}}</th>
                                    <th scope="col">{{__('Status')}}</th>
                                    <th scope="col">{{__('Rate')}}</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $row = 1; ?>
                            @foreach($drivers as $driver)
                                <tr class="@if($driver->account_status == 'unapproved'){{'table-warning'}}@elseif($driver->account_status == 'inactive'){{'table-danger'}}@endif">
                                    <td scope="row">{{$row}}</td>
                                    <td><img src="{{$driver->profile_photo}}" alt="..." height="50"></td>
                                    <td>{{$driver->name}}</td>
                                    <td>{{$driver->phone}}</td>
                                    <td>{{$driver->transport_mode}}</td>
                                    <td>{{Config::get('enums.driver_types.'.$driver->driver_type)}}</td>
                                    <td> @if($driver->availability) <span class="text-success">{{strtoupper(__('ONLINE'))}}</span> @else <span class="text-muted">{{strtoupper(__('OFFLINE'))}}</span> @endif </td>
                                    <td>{{$driver->getRate->avg('rate')}}</td>
                                    <td>
                                        @if (auth()->user()->user_type == 'admin')
                                            @component('components.actionButtons', [
                                                'routeEdit' => route('drivers.edit', $driver->id),
                                                'routeChangePassword' => route('drivers.changePassword', $driver->id),
                                                'routeJobHistory' => route('drivers.jobHistory', $driver->id),
                                                'routeLogs' => route('drivers.showDriversLog', $driver->id),
                                                'itemId' => $driver->id,
                                                'routeDelete' => route('drivers.destroy', $driver->id),
                                            ])
                                            @endcomponent
                                        @else
                                            @component('components.actionButtons', [
                                                'routeEdit' => route('drivers.edit', $driver->id),
                                                'routeJobHistory' => route('drivers.jobHistory', $driver->id),
                                                'itemId' => $driver->id,
                                                'routeDelete' => route('drivers.destroy', $driver->id),
                                            ])
                                            @endcomponent
                                        @endif
                                    </td>
                                    <td>
                                        @if (((auth()->user()->user_type == 'admin') ||
                                             ((auth()->user()->user_type == 'vendor' || auth()->user()->user_type == 'branch') && auth()->user()->vendor->has_own_drivers)) &&
                                             ($driver->account_status != 'approved'))
                                            <a class="btn btn-outline-success-2x" href="{{route('drivers.approve', $driver->id)}}">{{__('Approve')}}</a>
                                        @endif
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
    <script>
        $(document).ready(function(){
            $("#filter").val("{{$status}}");
        });
        function refreshTable(selected) {
            window.location.href = "/drivers/"+selected+"/show";
        }
    </script>
@endsection

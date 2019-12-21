@extends('layouts.admin', ['crumbs' => [
    __('Jobs') => route('jobs.index')]
, 'title' => __('Jobs List')])
@section('content')
    <style>
        select.form-control:not([size]):not([multiple]) { height: 30px; }
        .font-small { font-size: 12px; height: 30px; }
        #toggle-filters {font-size: 16px; margin-left: 15px; display: none;}

        @media (max-width: 576px) {
            #toggle-filters {display: block;}
        }
    </style>
    @if ($errors->any())
        <div class="col-sm-12">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    {{--<i  class="fa fa-fw fa-filter text-primary"--}}
        {{--style="font-size: 20px; cursor:pointer;"--}}
        {{--data-toggle="tooltip" title="Filters"--}}
        {{--onclick="toggleCollapseFilters()">--}}
    {{--</i>--}}
    <a id="toggle-filters" href="#" onclick="toggleCollapseFilters()"><i class="fa fa-bars"></i> Toggle Filters</a>
    <div class="col-sm-12">
        <div class="card">
            <div id="filters" class="row" style="margin: 10px;">
                <div class="col-sm-2" style="margin-bottom: 10px;">
                    <select name="status_filter" id="status_filter" class="form-control font-small">
                        <option value="all">{{__('All Jobs')}}</option>
                        <option value="new">{{__('New')}}</option>
                        <option value="accepted">{{__('Accepted')}}</option>
                        <option value="completed">{{__('Completed')}}</option>
                        <option value="abandoned">{{__('Abandoned')}}</option>
                        <option value="canceled">{{__('Canceled')}}</option>
                    </select>
                </div>

                @if(auth()->user()->user_type == 'admin')
                    <div class="col-sm-2" style="margin-bottom: 10px;">
                        {!! Form::select('vendors_list', $vendors, null, array('id' => 'vendors_list', 'class' => 'form-control font-small', 'placeholder' => 'Select Vendor...', 'onchange' => 'changeVendorsList(this.value)')) !!}
                    </div>
                @elseif(auth()->user()->user_type == 'vendor')
                    <input type="hidden" name="vendors_list" id="vendors_list" value="{{auth()->user()->vendor_id}}">
                @endif

                @if(auth()->user()->user_type != 'branch')
                    <div class="col-sm-2" style="margin-bottom: 10px;">
                        {!! Form::select('branches_list', $branches_list, null, array('id' => 'branches_list', 'class' => 'form-control font-small', 'placeholder' => 'Select Branch...')) !!}
                    </div>
                @endif

                <div class="col-sm-2" style="margin-bottom: 10px;">
                    <select name="date_filter" id="date_filter" class="form-control font-small" onchange="listOnChange()">
                        <option value="today">{{__('Today')}}</option>
                        <option value="seven">{{__('Last 7 Days')}}</option>
                        <option value="thirty">{{__('Last 30 Days')}}</option>
                        <option value="custom">{{__('Custom Date')}}</option>
                    </select>
                </div>
                <div id="dates" class="col-sm-4" style="display: none;">
                    <div class="form-group row">
                        <div class="col-sm-6" style="margin-bottom: 5px;">
                            <input type="date" name="fromDate" id="from-date" class="form-control font-small" />
                        </div>
                        <div class="col-sm-6">
                            <input type="date" name="toDate" id="to-date" class="form-control font-small" />
                        </div>
                    </div>
                </div>

                <div class="col-sm-2">
                    <button class="btn btn-primary font-small w-100" onclick="refreshTable()">Apply</button>
                </div>
            </div>

            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                            <tr class="text-center">
                                <th>#ID</th>
                                @if(auth()->user()->user_type=='admin')<th scope="col">{{__('Vendor')}}</th>@endif
                                @if(auth()->user()->user_type!='branch')<th>Branch</th>@endif
                                <th scope="col">{{__('Driver')}}</th>
                                <th scope="col">{{__('Pickup')}}</th>
                                <th scope="col">{{__('Drop off')}}</th>
                                <th scope="col">{{__('Job Distance')}}</th>
                                <th scope="col">{{__('Distance from driver start to dropoff')}}</th>
                                <th scope="col">{{__('Scheduled Time')}}</th>
                                <th scope="col">{{__('Status')}}</th>
                                <th scope="col">{{__('Start to Complete Duration')}}</th>
                                <th scope="col">{{__('Created')}}</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($jobs as $job)
                                <tr class="text-center">
                                    <td>#{{$job->unique_number}}</td>
                                    @if(auth()->user()->user_type=='admin')<td>{{$job->vendor->name}}</td>@endif
                                    @if(auth()->user()->user_type!='branch')<td>{{$job->branch ? $job->branch->name : ''}}</td>@endif
                                    <td>
                                        @if($job->driver_id)
                                            {{$job->driver->name}}
                                        @elseif( /* if any of this conditions are true: user is admin , user is vendor or branch and access admin drivers , user is vendor or branch and has their own drivers */
                                            (auth()->user()->user_type == 'admin') ||
                                            ((auth()->user()->user_type == 'vendor' || auth()->user()->user_type == 'branch') && auth()->user()->vendor->access_admin_drivers) ||
                                            ((auth()->user()->user_type == 'vendor' || auth()->user()->user_type == 'branch') && auth()->user()->vendor->has_own_drivers)
                                        )
                                            <button type="button" class="btn btn-sm btn-outline-primary-2x" data-toggle="modal" data-target="#drivers-modal-{{$job->id}}">{{__('Drivers List')}}</button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-primary-2x" onclick="notifyAll('{{$job->id}}')">{{__('Notify Drivers')}}</button>
                                        @endif
                                    </td>
                                    <td>{{$job->pickup_address}}</td>
                                    <td>{{$job->dropoff_address}}</td>
                                    <td>{{$job->distance}} Km</td>
                                    <td>@if(in_array($job->status, ["started", "completed"])) {{\CargoLogisticsModels\JobHistory::where([['job_id', '=', $job->id],['status', '=', 'started']])->first()->distance_to_dropoff}} Km @endif</td>
                                    <td>{{$job->pickup_date}} {{substr($job->pickup_time, 0, 5)}}</td>
                                    <td>{{strtoupper($job->status)}}</td>
                                    <td>@if($job->status == "completed") {{ \CargoLogisticsModels\JobHistory::where([['job_id', '=', $job->id],['status', '=', 'started']])->first()->duration_minutes }} minutes @endif</td>
                                    <td>{{$job->created_at}}</td>
                                    <td>
                                        @component('components.actionButtons', [
                                            'routeEdit' => route('jobs.edit', $job->id),
                                            'routeJobHistory' => route('jobs.history', $job->id),
                                            'routeJobNotifications' => route('jobs.notifications', $job->id),
                                            'itemId' => $job->id,
                                            'routeDelete' => route('jobs.destroy', $job->id),
                                        ])
                                        @endcomponent
                                    </td>
                                    <td>
                                        <!-- Drivers Modal -->
                                        <div class="modal fade" id="drivers-modal-{{$job->id}}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{__('Drivers List')}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="{{$job->id}}">
                                                        <p id="job-description">{{"from \"" . $job->pickup_address . "\" To \"" . $job->dropoff_address . "\""}}</p>
                                                        <div class="text-right" style="margin: 5px;">
                                                            <button class="btn btn-sm btn-outline-info-2x" data-dismiss="modal" onclick="notifyAll('{{$job->id}}')">{{__('Notify all nearby drivers')}}</button>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-striped">
                                                                <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>{{__('Name')}}</th>
                                                                    <th>{{__('Distance to pickup')}}</th>
                                                                    <th></th>
                                                                    <th></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($job->drivers as $driver)
                                                                    <tr>
                                                                        <td class="text-success">
                                                                            @if(\CargoLogisticsModels\NotifyDriver::where([['job_id', $job->id],['driver_id', $driver['id']]])->count() > 0)
                                                                                {{__('NOTIFIED')}}
                                                                            @endif
                                                                        </td>
                                                                        <td>{{$driver['name']}}</td>
                                                                        <td>{{$driver['distance_to_pickup']}}</td>
                                                                        <td class="text-right"><button class="btn btn-outline-primary-2x" onclick="chooseDriver('{{$job->id}}','{{$driver['id']}}')">{{__('NOTIFY')}}</button></td>
                                                                        <td class="text-right">
                                                                            @if(
                                                                                (auth()->user()->user_type == 'admin') ||
                                                                                ((auth()->user()->user_type == 'vendor' || auth()->user()->user_type == 'branch') && auth()->user()->vendor->has_own_drivers))
                                                                                <button class="btn btn-outline-success-2x" onclick="assignDriver('{{$job->id}}','{{$driver['id']}}')">{{__('ASSIGN')}}</button>
                                                                            @endif
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
{{--127.0.0.1:8000/jobs/show/all/custom?fromDate=2019-22-08&toDate=2019-22-02--}}
    <script>
        $(document).ready(function(){
            $("#status_filter").val("{{$status}}");
            $("#vendors_list").val("{{$v}}");
            if ($("#vendors_list").val()) {
                changeVendorsList("{{$v}}");
            }

            $("#date_filter").val("{{$date}}");
            if("{{$date}}" === "custom") {
                $("#dates").show();
                $("#from-date").val("{{$fromDate}}");
                $("#to-date").val("{{$toDate}}");
                // let url = new URL(window.location.href);
                // document.getElementById("from-date").value = url.searchParams.get("fromDate");
                // document.getElementById("to-date").value = url.searchParams.get("toDate");
            } else {
                let today = new Date();
                $("input[type=date]").val(today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2));
            }
        });

        function refreshTable() {
            let url = "/jobs/show/"+$("#status_filter").val()+"/"+$("#date_filter").val()+"/"+$("#from-date").val()+"/"+$("#to-date").val();

            if ($("#vendors_list").val())
                url += "/"+$("#vendors_list").val();

            if ($("#branches_list").val())
                url += "/"+$("#branches_list").val();

            window.location.href = url;

        }

        function listOnChange() {
            if ($("#date_filter").val() === 'custom') {
                $("#dates").show("medium");
            } else {
                $("#dates").hide("medium");
            }
        }

        function chooseDriver(jobId, driverId) {
            // const jobId = $("input[name=id]").val();
            $.ajax({
                url: "{{route('jobs.notifySingleDriver')}}",
                type: "POST",
                data: {
                    _token: "{{csrf_token()}}",
                    job: jobId,
                    driver: driverId
                }
            }).done(function (response) {
                if (response.status) {
                    window.location.href = response.url;
                } else {
                    swal(response.message);
                }
            });
        }

        function assignDriver(jobId, driverId) {
            // const jobId = $("input[name=id]").val();
            $.ajax({
                url: "{{route('jobs.assignDriver')}}",
                type: "POST",
                data: {
                    _token: "{{csrf_token()}}",
                    job: jobId,
                    driver: driverId
                }
            }).done(function (response) {
                if (response.status) {
                    window.location.href = response.url;
                } else {
                    swal(response.message);
                }
            });
        }

        function toggleCollapseFilters() {
            let display = document.getElementById("filters").style.display;
            // $("#filters").css("display", display === 'none' ? 'flex' : 'none');
            if (display === 'none')
                $("#filters").show("medium");
            else
                $("#filters").hide("medium");
        }

        function notifyAll(job) {
            $.ajax({
                url: "notifyDriversApi/"+job,
                method: "post",
                data: {
                    "_token": "{{csrf_token()}}"
                }
            })
                .done(function (response) {
                    if (response === "success")
                        swal({"title": "{{__('Nearby drivers notified successfully.')}}" , "icon" : "success"});
                    else
                        swal({"title": "{{__('Unfortunately there are no nearby drivers right now!')}}" , "icon" : "warning"});
                });
        }

        function changeVendorsList(input) {
            if (input) {
                $.ajax({
                    url: "/getBranches/" + input,
                    method: "get",
                    data: {
                        vendor: input
                    }
                })
                    .done(function (response) {
                        $("#branches_list").html(response);

                        if ("{{$branch}}") $("#branches_list").val("{{$branch}}");
                    });
            } else {
                $("#branches_list").html("<option value=''>{{__('Select Branch...')}}</option>");
            }
        }
    </script>
@endsection

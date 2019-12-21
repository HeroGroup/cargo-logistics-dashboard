@extends('layouts.admin', ['crumbs' => [
    __('Drivers') => route('drivers.index', 'all'),
    __('Job History') => route('drivers.jobHistory', $driver)]
, 'title' => __('Job History')])
@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div style="margin: 10px">
                <div class="form-group row">
                    <div class="col-sm-2">
                        {!! Form::select('drivers', $drivers, $driver, array('id' => 'drivers', 'class' => 'form-control', 'onchange' => 'refreshTable(this.value)', 'placeholder' => 'Choose Driver...')) !!}
                    </div>

                    <div class="col-sm-2">
                        <select name="filter" id="filter" class="form-control" onchange="refreshTable(this.value);">
                            <option value="all">{{__("All Jobs")}}</option>
                            <option value="accepted">{{__('Accepted')}}</option>
                            <option value="completed">{{__('Completed')}}</option>
                            <option value="abandoned">{{__('Abandoned')}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">{{__('Job')}}</th>
                                <th scope="col">{{__('From')}}</th>
                                <th scope="col">{{__('To')}}</th>
                                <th scope="col">{{__('Status')}}</th>
                                <th scope="col">{{__('Duration')}} ({{__('minutes')}})</th>
                                <th scope="col">{{__('Time')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($histories as $history)
                                <tr>
                                    <td>#{{$history->job->unique_number}}</td>
                                    <td>{{$history->job->pickup_address}}</td>
                                    <td>{{$history->job->dropoff_address}}</td>
                                    <td>{{$history->status}}</td>
                                    <td>{{strtoupper($history->duration_minutes)}}</td>
                                    <td>{{date('l jS F Y h:i A', strtotime($history->created_at))}}</td>
                                </tr>
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
            $("#filter").val("{{$filter}}");
            $("#drivers").val("{{$driver->id}}");
        });
        function refreshTable(selected) {
            let driver = $("#drivers").val();
            let filter = $("#filter").val();
            window.location.href = "/drivers/"+driver+"/jobHistory/"+filter;
        }
    </script>
@endsection

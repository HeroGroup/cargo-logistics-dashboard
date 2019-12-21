@extends('layouts.admin', ['crumbs' => [
    __('Reports') => route('reports')]
, 'title' => __('Reports')])
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
<div class="col-sm-12 mx-auto">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="form-group row">
                        <label for="reportsList" class="col-sm-3 col-form-label">{{__('Report Type')}}</label>
                        <div class="col-sm-4">
                            {!! Form::select('reportsList', $reportsList, null, array('id' => 'reportsList', 'class' => 'form-control', 'placeholder' => 'Choose Report Type...', 'onchange' => 'changeReportType()')) !!}
                        </div>
                    </div>
                    <div id="drivers-list" style="display: none;">
                        <hr>
                        <div class="form-group row">
                            <label for="driversList" class="col-sm-3 col-form-label">{{__('Driver')}}</label>
                            <div class="col-sm-4">
                                {!! Form::select('driversList', $driversList, null, array('id' => 'driversList', 'class' => 'form-control', 'placeholder' => 'Choose Driver ...')) !!}
                            </div>
                        </div>
                    </div>
                    <div id="dates" style="display: none;">
                        <hr>
                        <div class="form-group row">
                            <label for="fromDate" class="col-sm-3 col-form-label">{{__('Start Date')}}</label>
                            <div class="col-sm-3">
                                <input type="date" name="fromDate" id="from-date" class="form-control" />
                            </div>
                            <label for="toDate" class="col-sm-2 col-form-label">{{__('End Date')}}</label>
                            <div class="col-sm-3">
                                <input type="date" name="toDate" id="to-date" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a class="btn btn-primary" href="#" onclick="getReport()">Export</a>
        </div>
    </div>
</div>
    <script>
        let requireDate = ['jobs.export', 'drivers.jobs.export', 'vendors.deliveryFees'];
        let requireDriver = ['drivers.jobs.export'];

        $(document).ready(function() {
            changeReportType();

            let today = new Date();
            $("input[type=date]").val(today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2));
        });

        function getReport() {
            event.preventDefault();
            let reportType = $("#reportsList").val();
            let fromDate = $("#from-date").val();
            let toDate = $("#to-date").val();
            let driver = $("#driversList").val();

            if (!reportType) {
                swal({title: 'Please select a report from list.', icon: 'warning'});
            } else if (requireDate.includes(reportType) && (!fromDate || !toDate)) {
                swal({title: 'You need to select start and end dates.', icon: 'warning'});
            } else if (requireDriver.includes(reportType) && !driver) {
                swal({title: 'You need to select a driver.', icon: 'warning'});
            } else {

                $.ajax({
                    url: '{{route('getReport')}}',
                    method: 'GET',
                    data: {
                        reportType,
                        driver,
                        fromDate,
                        toDate
                    }
                })
                    .done(function (response) {
                        window.location.href = response;
                    });
            }
        }

        function changeReportType(input) {
            let reportTypeValue = $("#reportsList").val();

            if (requireDate.includes(reportTypeValue)) {
                $("#dates").show();
            } else {
                $("#dates").hide();
            }

            if (requireDriver.includes(reportTypeValue)) {
                $("#drivers-list").show();
            } else {
                $("#drivers-list").hide();
            }
        }
    </script>
@endsection

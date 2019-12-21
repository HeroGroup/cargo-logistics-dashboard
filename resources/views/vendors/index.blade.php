@extends('layouts.admin', ['crumbs' => [
    'Vendors' => route('vendors.index', 'all')]
, 'title' => 'List of Vendors'])
@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="table-warning text-center custom-badge">Unapproved</div>
                    <div class="table-danger text-center custom-badge">Inactive</div>
                </div>
            </div>

            @component('components.filter', ['type' => 'Vendor', 'routeAdd' => route('vendors.create')])@endcomponent

            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Logo</th>
                                <th scope="col">Company Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Telephone</th>
                                <th scope="col">Mobile</th>
                                <th scope="col">Contact Person</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vendors as $vendor)
                                <tr class="@if($vendor->account_status == 'unapproved'){{'table-warning'}}@elseif($vendor->account_status == 'inactive'){{'table-danger'}}@endif">
                                    <td scope="row"><img src="{{$vendor->logo}}" alt="{{$vendor->name}}" height="50"></td>
                                    <td>{{$vendor->name}}</td>
                                    <td>{{$vendor->email}}</td>
                                    <td>{{$vendor->phone}}</td>
                                    <td>{{$vendor->mobile}}</td>
                                    <td>{{$vendor->contact_person}}</td>
                                    <td>
                                        @component('components.actionButtons', [
                                            'routeEdit' => route('vendors.edit', $vendor->id),
                                            'routeChangePassword' => route('vendors.changePassword', $vendor->id),
                                            'routeSubscriptions' => route('vendors.subscription', $vendor->id),
                                            'routeVendorAccounts' => route('vendors.accounts', $vendor->id),
                                            'routeVendorBranches' => route('vendors.branches', $vendor->id),
                                            'routeLogs' => route('vendors.showVendorsLog', $vendor->id),
                                        ])
                                        @endcomponent
                                    </td>
                                    <td>
                                        @if($vendor->account_status != 'approved')
                                            <a class="btn btn-outline-success-2x" href="{{route('vendors.approve', $vendor->id)}}">Approve</a>
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
    <script>
        $(document).ready(function(){
            $("#filter").val("{{$filter}}");
        });
        function refreshTable(selected) {
            window.location.href = "/vendors/"+selected+"/show";
        }
    </script>
@endsection

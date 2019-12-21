@extends('layouts.admin', ['crumbs' => [
    __('Vendors') => route('vendors.index', 'all'),
    __('Accounts') => route('vendors.accounts', $vendorId)]
, 'title' => __('List of Vendor Accounts')])
@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="{{route('vendors.accounts.create', $vendorId)}}">
                    <i class="fa fa-fw fa-plus"></i>
                    {{__('Create Account')}}
                </a>
            </div>
            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">{{__('Full Name')}}</th>
                                <th scope="col">{{__('Email')}}</th>
                                <th scope="col">{{__('Mobile')}}</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($accounts as $user)
                                <tr>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->mobile}}</td>
                                    <td>
                                        @component('components.actionButtons', [
                                            'routeEdit' => route('vendors.accounts.edit', [$user->vendor_id, $user->id]),
                                            'routeChangePassword' => route('vendors.accounts.changePassword', [$user->vendor_id, $user->id]),
                                            'routeAssignBranches' => route('vendors.accounts.assignBranches', [$user->vendor_id, $user->id]),
                                            'itemId' => $user->id,
                                            'routeDelete' => route('vendors.accounts.destroy', [$user->vendor_id, $user->id]),
                                        ])
                                        @endcomponent
                                    </td>
                                    <td>
                                        <div class="modal" id="assignBranchModal-{{$user->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{__('Assign Branches')}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <table class="table table-hover table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th>Branch</th>
                                                                <th></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($branches as $branch)
                                                                <tr>
                                                                    <td>
                                                                        @if(\CargoLogisticsModels\VendorBranchAccount::where('vendor_branch_id', '=', $branch->id)->where('user_id', '=', $user->id)->count() > 0)
                                                                            <i class="fa fa-fw fa-check-circle text-success"></i>
                                                                        @endif
                                                                        {{$branch->name}}
                                                                    </td>
                                                                    <td>
                                                                        @if(\CargoLogisticsModels\VendorBranchAccount::where('vendor_branch_id', '=', $branch->id)->where('user_id', '=', $user->id)->count() > 0)
                                                                            <button id="btn-revoke-brnach-{{$branch->id}}" class="btn btn-sm btn-danger" onclick="revokeBranch(this.id, '{{$user->id}}', '{{$branch->id}}')">Revoke</button>
                                                                        @else
                                                                            <button id="btn-assign-brnach-{{$branch->id}}" class="btn btn-sm btn-primary" onclick="assignBranch(this.id, '{{$user->id}}', '{{$branch->id}}')">Assign</button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-warning" data-dismiss="modal">{{__('Close')}}</button>
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


    <script>
        function openAssignBranchModal(userId) {
            $("#assignBranchModal-"+userId).modal("show");
        }

        function assignBranch(callerId, user, branch) {
            $.ajax({
                url: '{{route('vendors.accounts.branches.assign', $vendorId)}}',
                method: 'post',
                data: {
                    _token: '{{csrf_token()}}',
                    user: user,
                    branch: branch
                }
            }).done(function (response) {
                if (response.status === "success") {
                    let btn = $("#"+callerId);
                    btn.removeClass("btn-primary")
                        .addClass("btn-danger")
                        .html("Revoke")
                        .click(function() {
                            revokeBranch(callerId, user, branch)
                        });
                } else {
                    swal(response.message);
                }
            });
        }

        function revokeBranch(callerId, user, branch) {
            $.ajax({
                url: '{{route('vendors.accounts.branches.revoke', $vendorId)}}',
                method: 'post',
                data: {
                    _token: '{{csrf_token()}}',
                    user: user,
                    branch: branch
                }
            }).done(function (response) {
                if (response.status === "success") {
                    let btn = $("#"+callerId);
                    btn.removeClass("btn-danger")
                        .addClass("btn-primary")
                        .html("Assign")
                        .click(function() {
                            assignBranch(callerId, user, branch)
                        });
                } else {
                    swal(response.message);
                }
            });
        }
    </script>
@endsection

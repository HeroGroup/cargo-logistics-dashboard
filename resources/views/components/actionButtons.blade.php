<button type="button" class="btn btn-outline-secondary-2x dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('Actions')}}</button>
<div class="dropdown-menu">
    @if(isset($routeEdit))<a class="dropdown-item text-primary" href="{{ $routeEdit }}"><i class="fa fa-fw fa-edit"></i> {{__('Edit')}}</a>@endif
    @if(isset($routeChangePassword))<a class="dropdown-item text-danger" href="{{ $routeChangePassword }}"><i class="fa fa-fw fa-key"></i> {{__('Change Password')}}</a>@endif
    @if(isset($routeSubscriptions))<a class="dropdown-item text-info" href="{{ $routeSubscriptions }}"><i class="fa fa-fw fa-sign-in"></i> {{__('Subscription')}}</a>@endif
    @if(isset($routeVendorAccounts))<a class="dropdown-item text-warning" href="{{ $routeVendorAccounts }}"><i class="fa fa-fw fa-users"></i> {{__('Accounts')}}</a>@endif
    @if(isset($routeVendorBranches))<a class="dropdown-item text-dark" href="{{ $routeVendorBranches }}"><i class="fa fa-fw fa-share-alt"></i> {{__('Branches')}}</a>@endif
    @if(isset($routeAssignBranches))<a class="dropdown-item text-info" href="#" onclick="openAssignBranchModal('{{$itemId}}')"><i class="fa fa-fw fa-users"></i> {{__('Assign Branches')}}</a>@endif
    @if(isset($routeJobHistory))<a class="dropdown-item text-primary" href="{{ $routeJobHistory }}"><i class="fa fa-fw fa-list-alt"></i> {{__('Job History')}}</a>@endif
    @if(isset($routeJobNotifications))<a class="dropdown-item text-success" href="{{ $routeJobNotifications }}"><i class="fa fa-fw fa-list-alt"></i> {{__('Job Notifications')}}</a>@endif
    @if(isset($routeLogs))<a class="dropdown-item text-success" href="{{ $routeLogs }}"><i class="fa fa-fw fa-list"></i> {{__('Show Log')}}</a>@endif
    @if(isset($routeDelete))
        <a class="dropdown-item text-danger" href="#" onclick='event.preventDefault(); destroy({{$itemId}});' ><i class="fa fa-fw fa-trash"></i> {{__('Delete')}}</a>
        <form id="destroy-form-{{$itemId}}" method="post" action="{{$routeDelete}}" style="display:none">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
        </form>
    @endif
</div>

<script>
    function destroy(itemId) {
        swal({
            title: "{{__('Are you sure you want to delete this item?')}}",
            text: "{{__('Be aware that deletion process is non-returnable')}}",
            icon: "warning",
            buttons: ["{{__('Cancel')}}", "{{__('Delete')}}"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                document.getElementById('destroy-form-'+itemId).submit();
            }
        });
    }
</script>

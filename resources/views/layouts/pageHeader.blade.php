<div class="page-main-header">
    <div class="main-header-left">
        <div class="logo-wrapper">
            <a href="/">
                <img src="{{asset('images/logo-light.png')}}" class="image-dark" alt="Logo"/>
                <img src="{{asset('images/logo-light-dark-layout.png')}}" class="image-light" alt="Logo"/>
            </a>
        </div>
    </div>

    <div class="main-header-right row">
    <div class="vertical-mobile-sidebar">
        <i class="fa fa-bars sidebar-bar"></i>
    </div>
    <div class="nav-right col">
        <ul class="nav-menus">
            @if(auth()->user()->user_type == 'vendor' || auth()->user()->user_type == 'branch')
            {{--<li>--}}
                {{--<a href="{{route('vendors.edit', auth()->user()->vendor_id)}}" type="button" class="btn btn-outline-info btn-bill">Edit Vendor Info</a>--}}
            {{--</li>--}}
                <li>
                    <a href="{{route('jobs.create', 'simple')}}" class="text-dark" data-toggle="tooltip" title="{{__('Create Job')}}">
                        <img class="align-self-center pull-right mr-2" src="{{asset('images/dashboard/plus.png')}}" alt="header-job">
                    </a>
                </li>
            @endif
            <li>
                <a href="#!" onclick="javascript:toggleFullScreen()" class="text-dark">
                    <img class="align-self-center pull-right mr-2" src="{{asset('images/dashboard/browser.png')}}" alt="header-browser">
                </a>
            </li>
            <li class="onhover-dropdown">
                <a href="#!" class="txt-dark">
                    <img class="align-self-center pull-right mr-2" src="{{asset('images/dashboard/translate.png')}}" alt="header-translate">
                </a>
                <ul class="language-dropdown onhover-show-div p-20">
                    <li><a href="{{route('changeLocale', 'en')}}" data-lng="en"> English</a></li><br/>
                    <li><a href="{{route('changeLocale', 'ar')}}" data-lng="ar"> العربية </a></li>
                </ul>
            </li>
            @if(auth()->user()->user_type == 'branch')
                <li class="onhover-dropdown">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <h6 class="m-0 txt-dark f-16">
                                @if(session()->get('branch')){{\CargoLogisticsModels\VendorBranch::find(session()->get('branch'))->name}}@endif
                                <i class="fa fa-angle-down pull-right ml-2"></i>
                            </h6>
                        </div>
                    </div>
                    <ul class="profile-dropdown onhover-show-div p-20">
                        @foreach(\CargoLogisticsModels\VendorBranchAccount::where('user_id', '=', auth()->user()->id)->get() as $branch)
                            <li><a href="{{route('changeBranch', $branch->vendor_branch_id)}}">{{$branch->vendorBranch->name}}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endif
            <li class="onhover-dropdown">
                <div class="media  align-items-center">
                    <img class="align-self-center pull-right mr-2" src="{{asset('images/dashboard/user.png')}}" alt="header-user"/>
                    <div class="media-body">
                        <h6 class="m-0 txt-dark f-16">
                            {{auth()->user()->name}}
                            <i class="fa fa-angle-down pull-right ml-2"></i>
                        </h6>
                    </div>
                </div>
                <ul class="profile-dropdown onhover-show-div p-20">
                    <li>
                        <a href="{{route('getProfile')}}">
                            <i class="icon-user"></i>
                            {{__('Edit Profile')}}
                        </a>
                    </li>
                    @if(auth()->user()->user_type == 'vendor')
                    <li>
                        <a href="{{route('vendors.edit', auth()->user()->vendor_id)}}">
                            <i class="icon-user"></i>
                            {{__('Edit Vendor')}}
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="#">
                            <i class="icon-map"></i>
                            {{__('Map')}}
                        </a>
                    </li>
                    {{--<li>--}}
                        {{--<a href="{{route('administrators.edit', auth()->user()->id)}}">--}}
                            {{--<i class="icon-key"></i> Password--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    <li>
                        <a href="#" onclick="logout()">
                            <i class="icon-power-off"></i>
                            {{__('Logout')}}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="d-lg-none mobile-toggle">
            <i class="icon-more"></i>
        </div>
    </div>
</div>
</div>
<script>
    function logout() {
        swal({
            title: "{{__('Are you sure you want to Logout?')}}",
            icon: "warning",
            buttons: ["{{__('Cancel')}}", "{{__('Logout')}}"],
            dangerMode: true,
        }).then((willExit) => {
            if (willExit) {
                event.preventDefault();
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>

<div class="vertical-menu-main">
    <nav id="main-nav">
        <!-- Sample menu definition -->
        <ul id="main-menu" class="sm pixelstrap">
            <li>
                <div class="text-right mobile-back">
                    {{__('Back')}}<i class="fa fa-angle-right pl-2" aria-hidden="true"></i>
                </div>
            </li>
            <li><a href="/dashboard"><i class="icon-desktop font-primary"></i> {{__('Dashboard')}}</a></li>
            @if(auth()->user()->user_type == 'admin' || ((auth()->user()->user_type == 'vendor' || auth()->user()->user_type == 'branch') && auth()->user()->vendor->has_own_drivers))
                <li><a href="#"><i class="icon-files font-success"></i>{{__('Drivers')}}</a>
                    <ul>
                        <li><a href="{{route('drivers.index', 'all')}}">{{__('Drivers List')}}</a></li>
                        <li><a href="{{route('drivers.create')}}">{{__('Add New Driver')}}</a></li>
                    </ul>
                </li>
            @endif
            @if(auth()->user()->user_type == 'admin')
                <li><a href="#"><i class="icon-package font-info"></i>{{__('Vendors')}}</a>
                    <ul>
                        <li><a href="{{route('vendors.index', 'all')}}">{{__('Vendors List')}}</a></li>
                        <li><a href="{{route('vendors.create')}}">{{__('Add New Vendor')}}</a></li>
                        {{--<li><a href="#">{{__('History')}}</a></li>--}}
                    </ul>
                </li>
            @elseif(auth()->user()->user_type == 'vendor')
                <li><a href="#"><i class="icon-package font-info"></i>{{__('Accounts')}}</a>
                    <ul>
                        <li><a href="{{route('vendors.accounts', auth()->user()->vendor_id)}}">{{__('Accounts List')}}</a></li>
                        <li><a href="{{route('vendors.accounts.create', auth()->user()->vendor_id)}}">{{__('Add New Account')}}</a></li>
                    </ul>
                </li>
                <li><a href="#"><i class="icon-package font-info"></i>{{__('Branches')}}</a>
                    <ul>
                        <li><a href="{{route('vendors.branches', auth()->user()->vendor_id)}}">{{__('Branches List')}}</a></li>
                        <li><a href="{{route('vendors.branches.create', auth()->user()->vendor_id)}}">{{__('Add New Branch')}}</a></li>
                    </ul>
                </li>
            @endif

            <li><a href="#"><i class="icon-comment-alt font-danger"></i>{{__('Jobs')}}</a>
                <ul>
                    @if(auth()->user()->user_type != 'admin')<li><a href="{{route('jobs.create', 'simple')}}">{{__('Add Job')}}</a></li>@endif
                    <li><a href="{{route('jobs.map')}}">{{__('Maps')}}</a></li>
                    <li><a href="{{route('jobs.liveJobs')}}">{{__('Live Jobs')}}</a></li>
                    <li><a href="{{route('jobs.index')}}">{{__('All Jobs')}}</a></li>
                </ul>
            </li>
            <li><a href="{{route('reports')}}"><i class="icon-settings font-primary"></i>{{__('Reports')}}</a></li>
            @if(auth()->user()->user_type == 'admin')
            <li><a href="#"><i class="icon-headphone-alt font-secondary"></i>{{__('Settings')}}</a>
                <ul>
                    <li><a href="{{route('areas')}}">{{__('Areas')}}</a></li>
                    <li><a href="{{route('translations')}}">{{__('Translation')}}</a></li>
                    <li><a href="{{route('settings.index')}}">{{__('Settings')}}</a></li>
                    <li><a href="{{route('administrators.index')}}">{{__('Admin Users')}}</a></li>
                </ul>
            </li>
            <li><a href="#"><i class="icon-headphone-alt font-secondary"></i>{{__('Website')}}</a>
                <ul>
                    <li><a href="#">{{__('Website logo')}}</a></li>
                    <li><a href="#">{{__('Slideshow')}}</a></li>
                    <li><a href="#">{{__('About us')}}</a></li>
                </ul>
            </li>
            @endif
        </ul>
    </nav>
</div>

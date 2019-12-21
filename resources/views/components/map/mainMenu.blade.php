<div style="padding: 10px; margin:50px 0; width: 100%">
    <button class="btn btn-outline-secondary-2x" style="width: 100%;">Main Menu</button>
</div>

<style>
    .online-drivers-div {
        padding: 10px; width: 100%;
        @if(auth()->user()->user_type == 'admin' || ((auth()->user()->user_type == 'vendor' || auth()->user()->user_type == 'branch') && auth()->user()->vendor->has_own_drivers))

        @else
            display: none;
        @endif
    }
</style>

<div class="online-drivers-div">
    <button class="btn btn-outline-secondary-2x" style="width: 100%;" onclick="getOnlineDrivers()">Drivers Online (<span id="drivers-online">{{$onlineDrivers}}</span>)</button>
</div>

<div style="padding: 10px; width: 100%;">
    <button class="btn btn-outline-secondary-2x" style="width: 100%;" onclick="getJobs('new')">Unassigned (<span id="new-jobs">{{$newJobs}}</span>)</button>
</div>
<div style="padding: 10px; width: 100%">
    <button class="btn btn-outline-secondary-2x" style="width: 100%;" onclick="getJobs('started')">In Progress (<span id="started-jobs">{{$startedJobs}}</span>)</button>
</div>

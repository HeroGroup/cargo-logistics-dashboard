@component('components.map.mainMenuBtn')@endcomponent
<div style="padding: 10px; width: 100%">
    <button class="btn btn-outline-secondary-2x" style="width: 100%;" onclick="getOnlineDrivers()">Back</button>
</div>
<div style="padding: 10px; margin-top:50px; width: 100%">
    <button class="btn btn-outline-secondary-2x" style="width: 100%;" onclick="openCloseDetails('driver-detail')">Details</button>
</div>
<div id="driver-detail" style="display: none; background-color: silver; margin: 15px; padding: 10px; height: 80px;">
    Name: <span id="driver-name"></span><br>
    phone: <span id="driver-phone"></span><br>
    <span id="driver-jobs"></span> jobs
</div>

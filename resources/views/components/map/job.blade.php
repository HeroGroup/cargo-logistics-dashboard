@component('components.map.mainMenuBtn')@endcomponent
<div style="padding: 10px; width: 100%">
    <button class="btn btn-outline-secondary-2x" style="width: 100%;" onclick="getJobs($('#jobs-type').text()); changeSideMenu('jobs-menu')">Back</button>
</div>
<div style="padding: 10px; margin-top:50px; width: 100%">
    <button class="btn btn-outline-secondary-2x" style="width: 100%;" onclick="openCloseDetails('job-detail')">Job Details</button>
</div>
<span id="jobs-type" style="display: none;"></span>
<div id="job-detail" class="job-detail-box">
    <strong>Reference: </strong><span id="job-reference"></span><br><br>
    <strong>Vendor: </strong><span id="job-vendor"></span><br><br>
    <strong>Branch: </strong><span id="job-branch"></span><br><br>
    <strong>Origin: </strong><span id="job-origin"></span><br><br>
    <strong>Destination: </strong><span id="job-destination"></span><br><br>
    <strong>Distance: </strong><span id="job-distance"></span><br><br>
    <strong>Driver: </strong><span id="job-driver"></span><br><br>
    <strong>Created: </strong><span id="job-created"></span><br><br>
</div>

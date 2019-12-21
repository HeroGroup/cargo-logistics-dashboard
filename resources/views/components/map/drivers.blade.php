<style>
    .card {
        margin: 10px; padding: 10px;
    }
    .card:hover {
        cursor: pointer; opacity: 0.8;
    }
    .card .card-body {
        padding: 0px;
    }
    .profile-photo {
        background-color: darkgray; border-radius: 50px; height: 50px; width: 50px;
    }
    .information {
        border-left: 1px solid lightgray; height: 50px; display: inline-block; margin-left: 5px; padding-left: 5px;
    }
    .job-info {
        margin-left: 5px; padding-left: 5px;
    }
</style>
@component('components.map.mainMenuBtn')@endcomponent
<div id="drivers-list"></div>

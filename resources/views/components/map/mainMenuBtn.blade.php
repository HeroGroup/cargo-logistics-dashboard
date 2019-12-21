<div style="padding: 10px; margin-top:50px; width: 100%">
    <button class="btn btn-outline-secondary-2x" style="width: 100%;" onclick="mainMenuButtonClick()">Main Menu</button>
</div>
<script>
    function mainMenuButtonClick() {
        changeSideMenu('mainMenu');
        getJobs();
        getOnlineDrivers(false);
    }
</script>

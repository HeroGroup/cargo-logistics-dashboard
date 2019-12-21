let latitude = $("#latitude"), longitude = $("#longitude");
let mapCenter = {
        lat: latitude.val() > 0 ? parseFloat(latitude.val()) : 29.304031279419128,
        lng: longitude.val() > 0 ? parseFloat(longitude.val()) : 47.977630615234375
        // lat: 29.304031279419128,
        // lng: 47.977630615234375
    };
let mapOptions = {zoom: 13, center: mapCenter, mapTypeId: 'roadmap'};
let map, marker;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    marker = new google.maps.Marker({position: mapCenter, map: map, draggable: true});
}

function openMapModal() {
    $("#map-modal").modal("show");
    google.maps.event.trigger(map, "resize");
    map.setCenter(marker.getPosition());
}

function saveAndClose() {
    latitude.val(marker.getPosition().lat());
    longitude.val(marker.getPosition().lng());
}

// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

let latitude = $("#pickup_latitude"), longitude = $("#pickup_longitude");
let pickupMapCenter = {
    lat: latitude.val() > 0 ? parseFloat(latitude.val()) : 29.304031279419128,
    lng: longitude.val() > 0 ? parseFloat(longitude.val()) : 47.977630615234375
};
let mapCenter = { lat: 29.304031279419128, lng: 47.977630615234375 };

let pickupMap, dropoffMap, markers1 = [], markers2 = [];

function initAutocomplete() {
    pickupMap = new google.maps.Map(document.getElementById('pickup-map'), {
        center: pickupMapCenter,
        zoom: 13,
        mapTypeId: 'roadmap'
    });
    markers1.push(new google.maps.Marker({position: pickupMapCenter, map: pickupMap, draggable: true}));

    dropoffMap = new google.maps.Map(document.getElementById('dropoff-map'), {
        center: mapCenter,
        zoom: 13,
        mapTypeId: 'roadmap'
    });
    markers2.push(new google.maps.Marker({position: mapCenter, map: dropoffMap, draggable: true}));


    // Create the search box and link it to the UI element.
    let input1 = document.getElementById('pickup_address');
    let searchBox1 = new google.maps.places.SearchBox(input1);
    // pickupMap.controls[google.maps.ControlPosition.TOP_LEFT].push(input1);

    // Bias the SearchBox results towards current map's viewport.
    pickupMap.addListener('bounds_changed', function() {
        searchBox1.setBounds(pickupMap.getBounds());
    });


    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox1.addListener('places_changed', function() {
        let places = searchBox1.getPlaces();

        if (places.length === 0) {
            return;
        }

        // Clear out the old markers1.
        markers1.forEach(function(marker) {
            marker.setMap(null);
        });
        markers1 = [];

        // For each place, get the icon, name and location.
        let bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            let icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers1.push(new google.maps.Marker({
                map: pickupMap,
                icon: icon,
                title: place.name,
                position: place.geometry.location,
                draggable: true
            }));

            if (place.geometry.location) {
                // console.log("Lat: " + place.geometry.location.lat() + "\nLng: " + place.geometry.location.lng());
                $("#pickup_latitude").val(place.geometry.location.lat());
                $("#pickup_longitude").val(place.geometry.location.lng());
            }
            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        pickupMap.fitBounds(bounds);
    });



    // Create the search box and link it to the UI element.
    let input2 = document.getElementById('dropoff_address');
    let searchBox2 = new google.maps.places.SearchBox(input2);
    // dropoffMap.controls[google.maps.ControlPosition.TOP_LEFT].push(input2);

    // Bias the SearchBox results towards current map's viewport.
    dropoffMap.addListener('bounds_changed', function() {
        searchBox2.setBounds(dropoffMap.getBounds());
    });


    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox2.addListener('places_changed', function() {
        let places = searchBox2.getPlaces();

        if (places.length === 0) {
            return;
        }

        // Clear out the old markers2.
        markers2.forEach(function(marker) {
            marker.setMap(null);
        });
        markers2 = [];

        // For each place, get the icon, name and location.
        let bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            let icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers2.push(new google.maps.Marker({
                map: dropoffMap,
                icon: icon,
                title: place.name,
                position: place.geometry.location,
                draggable: true
            }));

            if (place.geometry.location) {
                // console.log("Lat: " + place.geometry.location.lat() + "\nLng: " + place.geometry.location.lng());
                $("#dropoff_latitude").val(place.geometry.location.lat());
                $("#dropoff_longitude").val(place.geometry.location.lng());
            }
            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        dropoffMap.fitBounds(bounds);
    });
}

function openPickupModal() {
    $("#pickup-map-modal").modal("show");
    google.maps.event.trigger(pickupMap, "resize");
    console.log(markers1);
    pickupMap.setCenter(markers1[0].getPosition());
}

function openDropoffModal() {
    $("#dropoff-map-modal").modal("show");
    google.maps.event.trigger(dropoffMap, "resize");
    dropoffMap.setCenter(markers2[0].getPosition());
}

function selectPickupLocation() {
    $("#pickup_latitude").val(markers1[0].getPosition().lat());
    $("#pickup_longitude").val(markers1[0].getPosition().lng());
}

function selectDropoffLocation() {
    $("#dropoff_latitude").val(markers2[0].getPosition().lat());
    $("#dropoff_longitude").val(markers2[0].getPosition().lng());
}




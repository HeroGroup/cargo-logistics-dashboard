let drivers = [], jobs = [];
let defaultLocation = {lat: 29.304031279419128, lng: 47.977630615234375};
let map, markers = [];
let directionsService, directionsDisplay, bounds;

function initMap() {
    let styledMapType = new google.maps.StyledMapType(
        [
            {elementType: 'geometry', stylers: [{color: '#ebe3cd'}]},
            {elementType: 'labels.text.fill', stylers: [{color: '#523735'}]},
            {elementType: 'labels.text.stroke', stylers: [{color: '#f5f1e6'}]},
            {
                featureType: 'administrative',
                elementType: 'geometry.stroke',
                stylers: [{color: '#c9b2a6'}]
            },
            {
                featureType: 'administrative.land_parcel',
                elementType: 'geometry.stroke',
                stylers: [{color: '#dcd2be'}]
            },
            {
                featureType: 'administrative.land_parcel',
                elementType: 'labels.text.fill',
                stylers: [{color: '#ae9e90'}]
            },
            {
                featureType: 'landscape.natural',
                elementType: 'geometry',
                stylers: [{color: '#dfd2ae'}]
            },
            {
                featureType: 'poi',
                elementType: 'geometry',
                stylers: [{color: '#dfd2ae'}]
            },
            {
                featureType: 'poi',
                elementType: 'labels.text.fill',
                stylers: [{color: '#93817c'}]
            },
            {
                featureType: 'poi.park',
                elementType: 'geometry.fill',
                stylers: [{color: '#a5b076'}]
            },
            {
                featureType: 'poi.park',
                elementType: 'labels.text.fill',
                stylers: [{color: '#447530'}]
            },
            {
                featureType: 'road',
                elementType: 'geometry',
                stylers: [{color: '#f5f1e6'}]
            },
            {
                featureType: 'road.arterial',
                elementType: 'geometry',
                stylers: [{color: '#fdfcf8'}]
            },
            {
                featureType: 'road.highway',
                elementType: 'geometry',
                stylers: [{color: '#f8c967'}]
            },
            {
                featureType: 'road.highway',
                elementType: 'geometry.stroke',
                stylers: [{color: '#e9bc62'}]
            },
            {
                featureType: 'road.highway.controlled_access',
                elementType: 'geometry',
                stylers: [{color: '#e98d58'}]
            },
            {
                featureType: 'road.highway.controlled_access',
                elementType: 'geometry.stroke',
                stylers: [{color: '#db8555'}]
            },
            {
                featureType: 'road.local',
                elementType: 'labels.text.fill',
                stylers: [{color: '#806b63'}]
            },
            {
                featureType: 'transit.line',
                elementType: 'geometry',
                stylers: [{color: '#dfd2ae'}]
            },
            {
                featureType: 'transit.line',
                elementType: 'labels.text.fill',
                stylers: [{color: '#8f7d77'}]
            },
            {
                featureType: 'transit.line',
                elementType: 'labels.text.stroke',
                stylers: [{color: '#ebe3cd'}]
            },
            {
                featureType: 'transit.station',
                elementType: 'geometry',
                stylers: [{color: '#dfd2ae'}]
            },
            {
                featureType: 'water',
                elementType: 'geometry.fill',
                stylers: [{color: '#b9d3ff'}]
            },
            {
                featureType: 'water',
                elementType: 'labels.text.fill',
                stylers: [{color: '#92998d'}]
            }
        ],
        {name: 'Styled Map'});

    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 29.269607, lng: 47.905204},
        zoom: 12
    });

    //Associate the styled map with the MapTypeId and set it to display.
    map.mapTypes.set('styled_map', styledMapType);
    map.setMapTypeId('styled_map');


    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();
    bounds = new google.maps.LatLngBounds();
}

$(document).ready(function() {
    hideAllMenus();
    $("#mainMenu").show();

    $.ajax({
        url: '/getJobs',
        method: 'GET',
    }).done(function(response) {
        jobs = response;
        getJobs();
    });

    $.ajax({
        url: '/getOnlineDrivers',
        method: 'GET',
    }).done(function(response) {
        drivers = response;
        $("#drivers-online").text(drivers.length);
        getOnlineDrivers(false);
    });
});

function getJobs(status=null) {
    let filteredJobs;
    if (status) {
        filteredJobs = jobs.filter(function (element) {
            if (status === 'started')
                return  element.status === 'accepted' || element.status === 'started';
            else
                return  element.status === status;
        });

        $("#"+status+"-jobs").text(filteredJobs.length);
    } else {
        filteredJobs = jobs;
    }

    clearMarkers();

    $("#jobs-list").html("");
    for(let i=0; i<filteredJobs.length; i++) {
        let destination = { lat: filteredJobs[i].dropoff_latitude , lng: filteredJobs[i].dropoff_longitude };
        let destinationMarker = addMarker(destination, map, filteredJobs[i].dropoff_address, filteredJobs[i].status);
        destinationMarker.addListener('click', function(input) {
            jobClick(filteredJobs[i]);
        });

        bounds.extend(destination);
        markers.push(destinationMarker);

        if (status) createJobItem(filteredJobs[i]);
    }
    map.fitBounds(bounds);
    if (status != null) changeSideMenu('jobs-menu');
}

function getOnlineDrivers(clear=true) {
    if (clear) clearMarkers();

    $("#drivers-list").html("");
    for(let i=0; i<drivers.length; i++) {
        let location = { lat: drivers[i].latitude , lng: drivers[i].longitude };
        let marker = addMarker(location, map, drivers[i].driver_name, 'driver');
        marker.addListener('click', function(input) {
            driverClick(drivers[i]);
        });

        createDriverItem(drivers[i]);

        bounds.extend(location);
        markers.push(marker);
    }
    map.fitBounds(bounds);
    if (clear) changeSideMenu('drivers-menu');
}

function showJobOnMap(origin, destination) {
    directionsDisplay.setMap(map);

    let request = {
        origin: origin,
        destination: destination,
        travelMode: 'DRIVING'
    };
    directionsService.route(request, function(result, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(result);
        }
    });
}

function addMarker(position, map, title, type='job') {
    let iconUrl = "https://maps.google.com/mapfiles/";
    switch (type) {
        case 'job':
            iconUrl += 'ms/icons/pink-dot.png';
            break;
        case 'driver':
            iconUrl += 'kml/pal4/icon15.png';
            break;
        case 'new':
            iconUrl += 'ms/icons/yellow-dot.png';
            break;
        case 'accepted':
            iconUrl += 'ms/icons/blue-dot.png';
            break;
        case 'started':
            iconUrl += 'ms/icons/pink-dot.png';
            break;
        case 'completed':
            iconUrl += 'ms/icons/green-dot.png';
            break;
        case 'abandoned':
            iconUrl += 'ms/icons/red-dot.png';
            break;
        default:
            iconUrl += 'ms/icons/orange-dot.png';
            break;
    }

    return new google.maps.Marker({
        position: position,
        map: map,
        title: title,
        icon: {
            url: iconUrl,
            scaledSize: new google.maps.Size(40, 40)
        }
    });
}

function clearMarkers() {
    // clear markers
    bounds = new google.maps.LatLngBounds();
    directionsDisplay.setMap(null);
    markers.forEach(function(marker) {
        marker.setMap(null);
    });
    markers = [];
}

function changeSideMenu(menu) {
    hideAllMenus();
    $("#"+menu).show();
}

function hideAllMenus() {
    $("#mainMenu").hide();
    $("#jobs-menu").hide();
    $("#job-menu").hide();
    $("#drivers-menu").hide();
    $("#driver-menu").hide();
}

function openCloseDetails(name) {
    let detailBox = $("#"+name);
    if (detailBox.css("display") === "none")
        detailBox.slideDown("medium");
    else
        detailBox.slideUp("medium");
}

function jobClick(job) {
    clearMarkers();
    let origin = { lat: job.pickup_latitude , lng: job.pickup_longitude };
    let destination = { lat: job.dropoff_latitude , lng: job.dropoff_longitude };
    showJobOnMap(origin, destination);

    if (job.driver_latitude && job.driver_longitude) {
        let driverMarker = addMarker({lat: job.driver_latitude, lng: job.driver_longitude}, map, job.driver_name, 'driver');
        markers.push(driverMarker);
    }
    changeSideMenu('job-menu');
    $("#job-vendor").text(job.vendor_name);
    $("#job-branch").text(job.branch_name);
    $("#job-origin").text(job.pickup_address);
    $("#job-destination").text(job.dropoff_address);
    $("#job-distance").text(job.distance + " Km");
    $("#job-reference").text(job.unique_number);
    $("#job-driver").text(job.driver_name);
    $("#job-created").text(job.created_at);
    $("#jobs-type").text(job.status);
}

function driverClick(item) {
    clearMarkers();
    let location = {lat: item.latitude, lng: item.longitude};
    marker = addMarker(location, map, item.name, 'driver');
    $("#driver-name").text(item.name);
    $("#driver-phone").text(item.phone);
    $("#driver-jobs").text(item.jobs);
    bounds.extend(location);
    map.fitBounds(bounds);
    changeSideMenu('driver-menu');
}


function createDriverItem(item) {
    let newChild = ""+
        "<div class='card' onclick='driverClick("+JSON.stringify(item)+")'>" +
        "<div class='card-body'>" +
        "<div class='profile-photo float-left'></div>" +
        "<div class='information'>" +
        "<span>"+item.name+"</span><br>" +
        "<span>"+item.jobs+" jobs</span>" +
        "</div>" +
        "</div>" +
        "</div>";

    $("#drivers-list").append(newChild);
}

function createJobItem(item) {
    let newChild = ""+
        "<div class='card' onclick='jobClick("+JSON.stringify(item)+")'>" +
        "<div class='card-body'>" +
        "<div class='job-info'>" +
        "<span>From: "+item.pickup_address+"</span><br>" +
        "<span>To: "+item.dropoff_address+"</span>" +
        "</div>" +
        "</div>" +
        "</div>";

    $("#jobs-list").append(newChild);
}

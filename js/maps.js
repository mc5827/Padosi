/*var map;
function initMap() {
var myLatLng = {lat: -25.363, lng: 131.044};

// Create a map object and specify the DOM element for display.
var map = new google.maps.Map(document.getElementById('map'), {
center: myLatLng,
scrollwheel: false,
zoom: 7
});

// Create a marker and set its position.
var marker = new google.maps.Marker({
map: map,
position: myLatLng,
title: 'Hello World!'
});
}*/
alert("Maps loaded");
var markers = [];
var marker;
function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -34.397, lng: 150.644},
    zoom: 6
  });
  var infoWindow = new google.maps.InfoWindow({map: map});

  // Try HTML5 geolocation.
  if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(position) {
    var pos = {
      lat: position.coords.latitude,
      lng: position.coords.longitude
    };

    infoWindow.setPosition(pos);
    infoWindow.setContent('Location found.');
    map.setCenter(pos);
  }, function() {
    handleLocationError(true, infoWindow, map.getCenter());
  });
  } else {
  // Browser doesn't support Geolocation
  handleLocationError(false, infoWindow, map.getCenter());
  }

  map.addListener('click', function(e) {
  deleteMarkers();
  placeMarkerAndPanTo(e.latLng, map);
  updateMarkerPosition(marker.getPosition());
  });



}
function updateMarkerPosition(latLng) {
  document.getElementById('info').innerHTML = [
  latLng.lat(),
  latLng.lng()
  ].join(', ');
}
function setMapOnAll(map) {
  for (var i = 0; i < markers.length; i++) {
  markers[i].setMap(map);
  }
}
function clearMarkers() {
  setMapOnAll(null);
}
function deleteMarkers() {
  clearMarkers();
  markers = [];
}

function placeMarkerAndPanTo(latLng, map) {
  marker = new google.maps.Marker({
  position: latLng,
  map: map
  });
  map.panTo(latLng);
  markers.push(marker);
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
                      'Error: The Geolocation service failed.' :
                      'Error: Your browser doesn\'t support geolocation.');
}

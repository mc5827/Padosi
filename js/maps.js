var areas = {
    bayridge: {
      center: {lat: 40.626164, lng:  -74.03295},
      population:20
    },
    sunset: {
      center: {lat: 40.645532, lng: -74.012385},
      population:20
    },
    harlem: {
      center: {lat: 40.811550, lng: -73.946477},
      population:20
    },
    canal: {
      center: {lat: 40.719447, lng: -74.001526},
      population:20
    },
    brooklyn: {
      center: {lat: 40.650002, lng: -73.949997},
      population:5000
    },
    manhattan: {
      center: {lat: 40.758896, lng: -73.985130},
      population:4000
    }
};
function getUserBlockHood() {
  alert("Getting the block of the user");
  var response;
  $.ajax({
        url: "php/getBlockHood.php",
          cache: false,
          type: "GET",
          crossDomain: true,
          async:false,
          success: function(data) {
                response = JSON.parse(data);
          },
          error: function(xhr) {
              alert(JSON.stringify(xhr));
          }
    });
    alert(response);
    return response;
}
function getUserListBlockHood(locationType,userLocation) {
    alert("Getting the block of the user");
    var response;
    $.ajax({
          url: "php/getuserListByBlockHood.php",
            cache: false,
            type: "GET",
            crossDomain: true,
            async:false,
            data:{
              "type" : encodeURIComponent(locationType),
              "block" : encodeURIComponent(userLocation['Block']),
              "hood" : encodeURIComponent(userLocation['Hood'])
            },
            success: function(data) {
                  response = JSON.parse(data);
                  alert(JSON.stringify(response));
            },
            error: function(xhr) {
                alert(JSON.stringify(xhr));
            }
      });
      //alert(response[category]);
      return response;
}
//marker = [];
/*function createMarkers(map,user,i) {
    alert(JSON.stringify(user));
    address = user['address'];
    points = user['location'].split(',');
    var position = new google.maps.LatLng(parseInt(points[0]),parseInt(points[1]));

    marker = new google.maps.Marker({
      position: position,
      map: map,
      title: address
    });
}*/
function initMap() {

  locationType = $('#selection :selected').text();
  if(category == 'Hood') {
      userLocation = getUserBlockHood();
  } else {
      userLocation = getUserBlockHood();
  }


  userList = getUserListBlockHood(locationType,userLocation);


  var bounds = new google.maps.LatLngBounds();
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 10,
    center: {lat: 40.730610, lng: -73.935242},
    mapTypeId: google.maps.MapTypeId.TERRAIN
  });

  //Highlighting hood
  for (var name in areas) {
      if(name == userLocation[locationType]) {
        alert(name);
        var cityCircle = new google.maps.Circle({
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#FF0000',
          fillOpacity: 0.35,
          map: map,
          center: areas[name].center,
          radius: Math.sqrt(areas[name].population) * 100
        });
    }
  }

  //Creating markers
  alert(userList.length);
  /*for(i=0;i<userList.length;i++) {
    createMarkers(map,userList[i],i);
  }*/

  for( i = 0; i < userList.length; i++ ) {
        alert(JSON.stringify(userList[i]));
        address = userList[i]['address'];
        points = userList[i]['location'].split(',');
        var position = new google.maps.LatLng(parseFloat(points[0]), parseFloat(points[1]));
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: userList[i]['address']
        });

        // Allow each marker to have an info window
        /*google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));*/

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
    });




}

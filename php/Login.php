<?php
$con = mysql_connect ( 'localhost', 'root', '' );
if (! $con) {
	die ( 'Could not connect: ' . mysql_error () );
}
mysql_select_db ( 'dbproject' );
$query = "SELECT distinct(hood) FROM location";
$result = mysql_query ( $query );
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">

<title>Padosi</title>

<!-- Bootstrap Core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="../css/sb-admin.css" rel="stylesheet">

<!-- Morris Charts CSS -->
<link href="../css/plugins/morris.css" rel="stylesheet">

<!-- Custom Fonts -->
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet"
	type="text/css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<script src="../External/jquery-1.11.3.min.js"></script>
<script src="../External/jquery-1.11.3.js"></script>
<script src="../External/bootstrap-table.min.js"></script>
<script src="../External/bootstrap-table.js"></script>
<script type="text/javascript" src="../External/bootstrap-filestyle.min.js"> </script>
<link rel="stylesheet" href="../External/bootstrap-table.css">
<link rel="stylesheet" href="../External/bootstrap-table.min.css">
<link href="../js/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEu5tkfIAZi8PEuY0udKP7cRhKe2csV0s&callback=initMap"
        async defer></script>
        <style type="text/css">
         .maps {
            height: 200px;
            width: 350px;
            margin:1%;
            background-color: #CCC;
          }
        </style>
<script language="javascript" type="text/javascript">

var markers = [];
var marker;
function initMap() {
  var mapFeed = new google.maps.Map(document.getElementById('mapFeed'), {
    center: {lat: -34.397, lng: 150.644},
    zoom: 10
  });
  var infoWindowFeed = new google.maps.InfoWindow({map: mapFeed});

  if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };

        infoWindowFeed.setPosition(pos);
        infoWindowFeed.setContent('Location found.');
        mapFeed.setCenter(pos);
      }, function() {
          handleLocationError(true, infoWindowFeed, mapFeed.getCenter());
        });
      } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindowFeed, mapFeed.getCenter());
      }

      mapFeed.addListener('click', function(e) {
        deleteMarkers();
        placeMarkerAndPanTo(e.latLng, mapFeed);
        updateMarkerPosition(marker.getPosition(),'input[name=feedLocation]');
      });

    }
    function updateMarkerPosition(latLng,element) {
      $(element).val(latLng.lat().toString()+','+latLng.lng().toString());
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


function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{
			try{
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}

		return xmlhttp;
    }

	function getBlock(hood) {

		var strURL="findBlock.php?hood="+hood;
		var req = getXMLHTTP();

		if (req) {

			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {
						document.getElementById('blockdiv').innerHTML=req.responseText;
					} else {
						alert("Problem while using XMLHTTP:\n" + req.statusText);
					}
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}

	function takePic(){
		var userName = document.getElementById('registerUserName').value;
		window.open('../webcam.html?userName='+userName,'_blank', 'toolbar=0,location=20,menubar=0');
		document.getElementById('photoTypeId').value='webcam';
			}

</script>

<style>
 #photoDiv div {
  display: inline;

  width: 30%;
}
</style>
</head>

<body>


	<div class="container">
		<div id="loginbox" style="margin-top: 50px;"
			class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="panel-title">Sign In</div>

				</div>

				<div style="padding-top: 30px" class="panel-body">

					<div style="display: none" id="login-alert"
						class="alert alert-danger col-sm-12"></div>

					<form id="loginform" class="form-horizontal" role="form" method="post" action="validateLogin.php">

						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon"><i
								class="glyphicon glyphicon-user"></i></span> <input
							id="login-username" type="text" class="form-control"
								name="registeredUsername" value="" placeholder="username or email">
						</div>

						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon"><i
								class="glyphicon glyphicon-lock"></i></span> <input
								id="login-password" type="password" class="form-control"
								name="regiseteredUserPassword" placeholder="password">
						</div>



						<div class="input-group">
							<div class="checkbox">
								<label> <input id="login-remember" type="checkbox"
									name="remember" value="1"> Remember me
								</label>
							</div>
						</div>


						<div style="margin-top: 10px" class="form-group">
							<!-- Button -->

							<div class="col-sm-12 controls">
<!-- 								<a id="btn-login" href="validateLogin.php" class="btn btn-success">Login </a> -->
								<button id="btn-login" type="submit" class="btn btn-success">
									<i class="icon-hand-right"></i> &nbsp Login
								</button>

							</div>
						</div>


						<div class="form-group">
							<div class="col-md-12 control">
								<div
									style="border-top: 1px solid #888; padding-top: 15px; font-size: 85%">
									Don't have an account! <a href="#"
										onClick="$('#loginbox').hide(); $('#signupbox').show();"> Sign
										Up Here </a>
								</div>
							</div>
						</div>
					</form>



				</div>
			</div>
		</div>
		<div id="signupbox" style="display: none; margin-top: 50px"
			class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="panel-title">Sign Up</div>
					<div
						style="float: right; font-size: 85%; position: relative; top: -10px">
						<a id="signinlink" href="#"
							onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign In</a>
					</div>
				</div>
				<div class="panel-body">
					<form method="post" id="signupform" class="form-horizontal" role="form" action="newUser.php" enctype="multipart/form-data">

						<div id="signupalert" style="display: none"
							class="alert alert-danger">
							<p>Error:</p>
							<span></span>
						</div>

						<div class="form-group">
							<label for="userName" class="col-md-3 control-label">UserName</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="userName" id="registerUserName"
									placeholder="Enter UserName">
							</div>
						</div>

						<div class="form-group">
							<label for="userPassword" class="col-md-3 control-label">Password</label>
							<div class="col-md-9">
								<input type="password" class="form-control" name="userPassword" id="registerPassword"
									placeholder="Enter Password">
							</div>
						</div>

						<div class="form-group">
							<label for="address" class="col-md-3 control-label">Address</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="address"
									placeholder="Enter Address ">
							</div>
						</div>

						<div class="form-group">
							<label for="hood" class="col-md-3 control-label">Hood</label>
							<div class="col-md-9">
								<select name="hood" class="form-control" onChange="getBlock(this.value)">
									<option value="">Select Hood</option>
										<?php while ($row=mysql_fetch_array($result)) { ?>
											<option value=<?php echo $row['hood']?>><?php echo $row['hood']?></option>
										<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="icode" class="col-md-3 control-label">Block</label>
							<div class="col-md-9" id="blockdiv">
								<select name="block" class="form-control">
									<option>Select Block</option>
       							</select>
							</div>
						</div>

						<div class="form-group">
							<label for="address" class="col-md-3 control-label">Zip Code</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="zipCode"
									placeholder="Enter Zip Code">
							</div>
						</div>

						<div class="form-group">
							<label for="userProfile" class="col-md-3 control-label">User Profile</label>
							<div class="col-md-9">
								<textarea class="form-control" rows="5" name="userProfile" placeholder="Enter Profile"></textarea>
							</div>
						</div>

						<div class="form-group" >
							<label for="userPhoto" class="col-md-3 control-label">User Photo</label>
							<div  class="col-md-9" id="photoDiv" >
							<div class="col-md-9" align="left">
							<input type="file" class="filestyle" data-input="false" name="fileImage" id="image_upload">
<!-- 								<input name="userfile" type="file" id="userfile"> " -->
							</div>
							<div class="col-md-9" >
								<!-- <a href="../webcam.html?userName=Mohit" rel="facebox">text</a> -->
								<button type="button" class="btn btn-default" onClick="takePic()" >
								<img alt="" src="../camera.jpg" height="20" width="100">
								</button>
							</div>
							</div>
						</div>

						<div class="form-group">
							<label for="address" class="col-md-3 control-label">Location</label>
							<div class="col-md-9">
								<div class="form-group">
                  							<div class="col-md-9">
                  								<input type="text" class="form-control" name="feedLocation"
                  									placeholder="Enter location">
                  							</div>
                  				</div>
								<div class="maps" id="mapFeed"></div>
							</div>
						</div>


						<div class="form-group">
							<!-- Button -->
							<div class="col-md-offset-3 col-md-9">
								<button id="btn-signup" type="submit" class="btn btn-info">
									<i class="icon-hand-right"></i> &nbsp Sign Up
								</button>
							</div>
						</div>
						<input type="hidden" name="photoType" id='photoTypeId' value='file'>
					</form>
				</div>
			</div>
		</div>
	</div>

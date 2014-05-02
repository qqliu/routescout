<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head>
<link href='http://fonts.googleapis.com/css?family=Lily+Script+One' rel='stylesheet' type='text/css'>
    <meta charset="utf-8">
    <title>RouteScout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="RouteScout">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="files/style.css" rel="stylesheet">
    <link href="files/bootstrap.css" rel="stylesheet">
    <link href="files/example-fluid-layout.css" rel="stylesheet">
    <link href="popups/popups.css" rel="stylesheet">
    <link rel="stylesheet" href="files/jquery-ui-1.10.4/themes/base/jquery-ui.css">
    
    <!-- Load any supplemental Javascript libraries here -->
    <script src="files/jquery.js"></script>
    <script src="files/bootstrap-transition.js"></script>
    <script src="files/bootstrap-alert.js"></script>
    <script src="files/bootstrap-modal.js"></script>
    <script src="files/bootstrap-dropdown.js"></script>
    <script src="files/bootstrap-scrollspy.js"></script>
    <script src="files/bootstrap-tab.js"></script>
    <script src="files/bootstrap-tooltip.js"></script>
    <script src="files/bootstrap-popover.js"></script>
    <script src="files/bootstrap-button.js"></script>
    <script src="files/bootstrap-collapse.js"></script>
    <script src="files/bootstrap-carousel.js"></script>
    <script src="files/bootstrap-typeahead.js"></script>
    <script type="text/javascript" src="files/jquery-ui-1.10.4/ui/jquery-ui.js"></script>
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDjX5a3zMFqvmcjySBDAbkf2fOfY1piELs&sensor=true&libraries=drawing"></script>
    <script>
      
    var map, adding, message, feature, messageId = 0;
    var markers = {};
    
    function toggleActive(button) {
	var active = $(button).hasClass("active");
	if (!active) {
	    $(button).addClass("active");
	    if ($(button).attr("id") == "report-button") {
		$("#tip-button").removeClass("active");
	    } else {
		$("#report-button").removeClass("active");
	    }
	} else {
	    map.setOptions({ draggableCursor: 'default' });
	    $(button).removeClass("active");
	    adding = undefined;
	}
    }
    
    var iconBase = 'popups/';
    var icons = {
      thumbstack: {
	icon: iconBase + 'thumbtack-blue.png'
      },
      caution: {
	icon: iconBase + 'caution.png'
      },
      star: {
	icon: iconBase + 'star-32.png'
      }
    };
    
    function addMarker(feature) {
      var marker = new google.maps.Marker({
	position: feature.position,
	icon: icons[feature.type].icon,
	map: feature.map
      });
      marker.info = new google.maps.InfoWindow({
        content: adding == "caution" ? "<b style='font-size: 16px; float:left;'>Caution</b><br /><div style='font-size:14px;'>" + message + "</div><br /><button onclick='deleteMarker(\"message" + messageId + "\");' style='float:left' class='message_delete' id= 'message" +
	    messageId + "'>Delete</button>": "<b style='font-size: 16px; float:left;'>Tip </b><br /><div style='font-size:14px;'>" + message + "</div><br /><button onclick='deleteMarker(\"message" + messageId + "\");' style='float:left' class='message_delete' id= 'message" + messageId + "'>Delete</button>"
      });
      
      marker.type = feature.type;
      markers[messageId] = marker;
      google.maps.event.addListener(marker, 'click', function() {
	  marker.info.open(map, this);
      });
      map.setOptions({ draggableCursor: 'default' });
      adding = undefined;
    }
    
    function deleteMarker(id) {
	markers[id.split("message")[1]].setMap(null);
	delete markers[id];
    };
    
    function initialize() {
        var myCenter, directionsDisplay, directionsDisplay2, directionsService, mapProp;
	
	myCenter = new google.maps.LatLng(42.3522, -71.0627);
        directionsDisplay = new google.maps.DirectionsRenderer();
	directionsDisplay2 = new google.maps.DirectionsRenderer();
        directionsService = new google.maps.DirectionsService();
        
        mapProp = {
          center: myCenter,
          zoom:14,
          mapTypeId:google.maps.MapTypeId.ROADMAP
          };
        map=new google.maps.Map(document.getElementById("googleMap")
          ,mapProp);
	
	map.setOptions({ draggableCursor: 'default' });
	
	//show popup
	$("#popup").dialog({autoOpen: false});
	
	google.maps.event.addListener(map, 'click', function(event) {
	    if (adding == "star") {
		feature = {position: event.latLng, type: "star", map: map};
		$("#popup-title").text("Add Tip");
		$("#popup").dialog("option", { position: [485 + event.pixel.x, 180 + event.pixel.y] });
		$("#popup").dialog('open');
	    } else if (adding == "caution") {
		feature = {position: event.latLng, type: "caution", map: map};
		$("#popup-title").text("Report Accident");
		$("#popup").dialog("option", { position: [485 + event.pixel.x, 180 + event.pixel.y] });
		$("#popup").dialog('open');
	    }
	});
	
	function addStarCaution() {
	  if (feature != undefined && feature != null) {
	      addMarker(feature);
	  }
	}
	
        directionsDisplay.setMap(map);
	directionsDisplay2.setMap(map);
        
        function Route() {
	  var start = $("#starting_loc").val();
	  var end = $("#destination_loc").val();
	  if(navigator.geolocation) {
	    navigator.geolocation.getCurrentPosition(function(position) {
	      start = start || new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
	      end = end || new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
	      map.setCenter(initialLocation);
	    }, function() {
	      start = start || "Boston, MA";
	    });
	  } else {
	      start = start || "Boston, MA";
	  }
	  var request = {
	    origin: start,
	    destination: end,
	    travelMode: google.maps.TravelMode.BICYCLING
	  };
	  directionsService.route(request, function(result, status) {
	    if (status == google.maps.DirectionsStatus.OK) {
	      directionsDisplay.setDirections(result);
	    } else { alert("couldn't get directions:"+status); }
	  });
        }
	
	function AnotherRoute() {
            var start = new google.maps.LatLng(42.3522, -71.0627);
            var end =new google.maps.LatLng(42.354, -71.067);
            var request = {
            origin: start,
            destination: end,
            travelMode: google.maps.TravelMode.WALKING
             };
             directionsService.route(request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
              directionsDisplay2.setDirections(result);
            } else { alert("couldn't get directions:"+status); }
            });
        }
	
	function showAllRoutes() {
	    Route();
	    AnotherRoute();
	}
      
      $("#report-button").click(function() {
          map.setOptions({ draggableCursor : "url(popups/caution.png) 16 30, default" });
          adding = "caution";
          toggleActive(this);
      });
      
      $("#tip-button").click(function() {
          map.setOptions({ draggableCursor: "url(popups/star-32.png) 16 30, default" });
          adding = "star";
          toggleActive(this);
      });

      $("#savedroutes").click(function() {
        $("#second").attr("src", "myroutes.php").show();
      });
      
      $("#route").click(function(e) {
        $("#second").fadeIn();
        if ($("#second").attr("src") == "myroutes.php") {
          $("#second").attr("src", "filter_routes.php");
        }
        e.preventDefault();
	showAllRoutes();
      });
      
      //install handler for submit button
      $('#popup-submit').click(function() {
	lines = $('#popup-textbox').val().split('\n');
	message = "";
	for(var i = 0; i < lines.length; i++){
	    message += "<span style='float:left;'>" + lines[i] + "</span><br />";
	}
	$("#popup").dialog('close');
	addStarCaution();
	$("#popup-textbox").val("");
	$("#report-button").removeClass("active");
	$("#tip-button").removeClass("active");
	messageId += 1;
      });
      
      $('.filters:checkbox').click(function(){
	if(!$(this).is(':checked')){
	    var id = $(this).attr("value");
	    for (var i in markers) {
		if (markers[i].type == id) {
		    markers[i].setVisible(false);
		}
	    }
	} else {
	    var id = $(this).attr("value");
	    for (var i in markers) {
		if (markers[i].type == id) {
		    markers[i].setVisible(true);
		}
	    }
	}
      });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>
    <style>
  a {
  color: #000000;
}
a:hover {
  color:#FFFFFF;
  text-decoration: none;
}
</style>
  <body background="parchment.jpg">
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">

      <a class="logout-saved" id="logout"href="#">Logout</a>
        <a class="logout-saved" id="savedroutes"href="#">Saved Routes</a>

        <div id="center-this-navbar">
        <div id="header">
            RouteScout         
            </div>
        </div>
 </div>
    </div>

    
    <div id="content">
    <div class="container-fluid span15">
      <div class="row-fluid">
        <div class="container-fluid span15">
          <div class="row-fluid">
  <div id="first" class="container well well-small span11" height="200">
     <div id="two">
    <div class="from-to" style="text-align:center">
        
        <form class="navbar-form" role="search">
        
        <div id = "test">
          <h3>From:</h3>
          <input type="text" class="form-control" placeholder="Search" name="srch-term" id="starting_loc">
        </div>
    
        <div id = "test">
          <h3>To:</h3>
          <input type="text" class="form-control" placeholder="Search" name="srch-term" id="destination_loc" >
        </div>
    
      </div>
    
    
    
    <div id="route-find" style="text-align:center">
      <button id="route" class="btn btn-large" >Search For Routes!</button>
    </div>
   </div>
  </div>

</div>
  <div class="row-fluid">
    <style>
    #first
    {
        overflow:hidden;
        background: url(texture-noise-beige-blur-lt.gif);
    }
    #second {
        overflow:hidden;
        background:url(texture-noise-beige-blur-lt.gif);
    }
</style>
<style>

.test {
  padding: 0px;
  margin: 0px;
}

</style>


<script type="text/javascript">
  $(document).ready(function() {
    //re-add class to force CSS
    $(".criteria-slider").slider({
      value: 50,
      change: function( event, ui ) {
        if (true) {
          $("#routes").animate({ opacity: 0 }, 400, "swing", function() {
            var tmp = $("#route-2").text();
            $("#route-2").text($("#route-1").text());
            $("#route-1").text(tmp);
            $("#routes").animate({ opacity: 1 });
          });
        }
      }
    }).addClass("criteria-slider");
    
    $("#routes button").width("100%");

    //document.getElementById("containerfluid").style.visibility="visible";
    //document.getElementById("navigation").style.visibility="hidden";
        
  });    
</script>
  <div id="second" name="second" class="container well span11" src="filter_routes.php" height="310" sandbox="allow-same-origin allow-scripts" style="display:none">
    <body>

<div id= "containerfluid" class="container-fluid">
<br />
<center>
<h2><u>Possible</u> <u>Routes</u></h2>
</center>
<div id="routes">
<ol>
<li><button id="route-1" class="btn btn-large" type="button">Cambridge St.</button></li>
<li><button id="route-2" class="btn btn-large">Newbury St.</button></li>
</ol>
</div>
<br />
<h4>Sort Routes By:</h4>
<table class="test">
  <tr>

      <td>Overall Safety</td>
      <td><div id="slider" class="criteria-slider"></td>
  </tr>
  <tr>
      <td>Fewest Accidents</td>
      <td><div id="slider" class="criteria-slider"></td>
  </tr>
  <tr>
      <td>Bike Lanes</td>
      <td><div id="slider" class="criteria-slider"></td>
  </tr>
  <tr>
      <td>Efficiency</td>
      <td><div id="slider" class="criteria-slider"></td>
  </tr>
  <tr>
      <td>Scenery</td>
      <td><div id="slider" class="criteria-slider"></td>
  </tr>
</table>

</div>
</body>





  </div>
</div>
</div>
</div>
</div>
<!--Navigation CODE-->
<style>
h1{
  margin-top: 30px;
margin-bottom: 20px;

}


.item{
 margin-top: 20px;
 margin-bottom: 20px;
 font-size: 18px;
}
#bottom-buttons{
position: relative;
top: -25px;
margin-top: 0px;
margin-left: 20px;
margin-right: 20px;
}

body {
    overflow-x:hidden;
    overflow-y:hidden;
}

.go-back {
  width:20px;
  height: 20px;

}

a {
  color: #000000;
}
a:hover {
  color: #000000;
  text-decoration: none;
}

</style>

<body>
<div id="navigation" style="display:none">
<div class = "go-back" style="padding: 10px;">
<a href="filter_routes.php"><img src="back-arrow.png"></a>
</div>

<h2 style="text-align:center"><u>Selected</u> <u>Route</u></h2>

<div style="padding-top: 0px; padding-left: 10px; padding-bottom: 10px; padding-right: 10px;">
  <ol id="list">
    <li class="item">Head north on Washington St toward Hayward Pl</li>
      <li class="item">Turn left onto Temple Pl</li>
      <li class="item">Turn left onto Tremont St</li>
  </ol>

</div>

<div id="bottom-buttons" class="row-fluid">
  <div class="span5 offset2">
<div id="route-find" style="text-align:center">
  <button id="savedButton" class="btn btn-large" data-toggle="modal" data-target="#saveModal">
  Save Route!
</button>

</div>
</div>
<div class="span6 offset7">
<div id="route-find" style="text-align:center">
  <button id="route" class="btn btn-large" ><a href="rateRoute.php" style="text-decoration: none">
    Rate this Route</a></button>
</div>
</div>
</div>
</div>
</body>


<script>
$("#savedButton").click(function() {
    $(this).after('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Succesfully Saved!</div>');
}); 

$('#route-1').click(function(e){
    //document.getElementById("navigation").style.visibility="visible";
    //document.getElementById("containerfluid").style.visibility="hidden";
    e.preventDefault();
    
    $("#containerfluid").hide();
    $("#navigation").show();
    
    return false;
});
</script>
<div hidden id="popup">
  <center>
  <p id="popup-title">Title</p>
    <textarea id="popup-textbox" cols="25" rows="5"></textarea>
    <p><a id="popup-submit" class="btn btn-success btn-large">Submit</a></p>
  </center>
</div>
<div class="container-fluid">
  <div class="span8" id="map">
  <div id="togglefeatures">
  <div id="toggle-label" class="toggle-button">Toggle Visibility:</div>
  <div class="toggle-button"><img class="toggle-img" src="files/icon_biking.png" />Bike Lanes <input class = "filters" type="checkbox" value = "lanes" checked></div>
  <div class="toggle-button"><img class="toggle-img" src="popups/star-32.png" />Tips <input class="filters" type="checkbox" value = "star" checked></div>
  <div class="toggle-button"><img class="toggle-img" src="popups/caution.png" />Accidents <input class="filters" type="checkbox" value="caution" checked></div>

        <div id="googleMap" style="width:700px;height:550px;"></div>
  <div id="green-buttons">
  <a id="report-button" class="popup-button btn btn-success btn-large">Report Accident</a>
  <a id="tip-button" class="popup-button btn btn-success btn-large">Add Tip</a>
  
    </div>

    </div><!--/.fluid-container-->
    </div>
</div></div>
</div>
</body></html>
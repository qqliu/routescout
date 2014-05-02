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
         map.setOptions({
             draggableCursor: 'default'
         });
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
         content: adding == "caution" ? "<b style='font-size: 16px; float:left;'>Caution</b><br /><div style='font-size:14px;'>" + message + "</div><br /><button onclick='deleteMarker(\"message" + messageId + "\");' style='float:left' class='message_delete' id= 'message" + messageId + "'>Delete</button>" : "<b style='font-size: 16px; float:left;'>Tip </b><br /><div style='font-size:14px;'>" + message + "</div><br /><button onclick='deleteMarker(\"message" + messageId + "\");' style='float:left' class='message_delete' id= 'message" + messageId + "'>Delete</button>"
     });
     marker.type = feature.type;
     markers[messageId] = marker;
     google.maps.event.addListener(marker, 'click', function() {
         marker.info.open(map, this);
     });
     map.setOptions({
         draggableCursor: 'default'
     });
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
         zoom: 14,
         mapTypeId: google.maps.MapTypeId.ROADMAP
     };
     map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
     map.setOptions({
         draggableCursor: 'default'
     });
     //show popup
     $("#popup").dialog({
         autoOpen: false
     });
     google.maps.event.addListener(map, 'click', function(event) {
         if (adding == "star") {
             feature = {
                 position: event.latLng,
                 type: "star",
                 map: map
             };
             $("#popup-title").text("Add Tip");
             $("#popup").dialog("option", {
                 position: [485 + event.pixel.x, 180 + event.pixel.y]
             });
             $("#popup").dialog('open');
         } else if (adding == "caution") {
             feature = {
                 position: event.latLng,
                 type: "caution",
                 map: map
             };
             $("#popup-title").text("Report Accident");
             $("#popup").dialog("option", {
                 position: [485 + event.pixel.x, 180 + event.pixel.y]
             });
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
         if (navigator.geolocation) {
             navigator.geolocation.getCurrentPosition(function(position) {
                 start = start || new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                 end = end || new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
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
             } else {
                 alert("couldn't get directions:" + status);
             }
         });
     }

     function AnotherRoute() {
         var start = new google.maps.LatLng(42.3522, -71.0627);
         var end = new google.maps.LatLng(42.354, -71.067);
         var request = {
             origin: start,
             destination: end,
             travelMode: google.maps.TravelMode.WALKING
         };
         directionsService.route(request, function(result, status) {
             if (status == google.maps.DirectionsStatus.OK) {
                 directionsDisplay2.setDirections(result);
             } else {
                 alert("couldn't get directions:" + status);
             }
         });
     }

     function showAllRoutes() {
         Route();
         AnotherRoute();
     }
     $("#report-button").click(function() {
         map.setOptions({
             draggableCursor: "url(popups/caution.png) 16 30, default"
         });
         adding = "caution";
         toggleActive(this);
     });
     $("#tip-button").click(function() {
         map.setOptions({
             draggableCursor: "url(popups/star-32.png) 16 30, default"
         });
         adding = "star";
         toggleActive(this);
     });
     $("#savedroutes").click(function() {
         $("#second").attr("src", "myroutes.php").show();
     });
     $("#route").click(function(e) {
     	 $("#rate-route").hide();
         $("#navigation").hide();
         $("#containerfluid").show();
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
         for (var i = 0; i < lines.length; i++) {
             message += "<span style='float:left;'>" + lines[i] + "</span><br />";
         }
         $("#popup").dialog('close');
         addStarCaution();
         $("#popup-textbox").val("");
         $("#report-button").removeClass("active");
         $("#tip-button").removeClass("active");
         messageId += 1;
     });
     $('.filters:checkbox').click(function() {
         if (!$(this).is(':checked')) {
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
 $(document).ready(function() {
     //re-add class to force CSS
     $(".criteria-slider").slider({
         value: 50,
         change: function(event, ui) {
             if (true) {
                 $("#routes").animate({
                     opacity: 0
                 }, 400, "swing", function() {
                     var tmp = $("#route-2").text();
                     $("#route-2").text($("#route-1").text());
                     $("#route-1").text(tmp);
                     $("#routes").animate({
                         opacity: 1
                     });
                 });
             }
         }
     }).addClass("criteria-slider");
     $("#routes button").width("100%");
     $("#savedButton").click(function() {
         $(this).after('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Succesfully Saved!</div>');
     });
     
     $('#route-1').click(function(e) {
         e.preventDefault();
         $("#containerfluid").hide();
         $("#rate-route").hide();
         $("#navigation").show();
         return false;
     });
     
     $('#back-to-routes').click(function(e) {
         e.preventDefault();
         $("#navigation").hide();
         $("#containerfluid").show();
         return false;
     });
     
     $('#back-to-nav').click(function(e) {
         e.preventDefault();
         $("#rate-route").hide();
         $("#navigation").show();
         return false;
     });
     
     $('#route-rate').click(function(e) {
         e.preventDefault();
         $("#navigation").hide();
         $("#containerfluid").hide();
         $("#rate-route").show();
         return false;
     });
		
	$("#route-save").click(function() {
	    $(this).after('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Succesfully Saved!</div>');
	}); 

 });
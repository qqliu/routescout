 var map, adding, message, feature, messageId = 0;
 var markers = {};
 var curResult;

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
		    curResult = result;
		    var possibleRoutes, c;
		    possibleRoutes = curResult.routes;
		    c = 0;
		    $("#routes").empty();
		    if (possibleRoutes.length > 0) {
			$("#routes").append("<ol></ol>");
			for (i in possibleRoutes) {
			    $($("#routes").find("ol")[0]).append('<li><button class="btn btn-large route-buttons" id="route-' + c + '" type="button">' +
						possibleRoutes[i].summary + '</button></li>');
			    c += 1;
			}
			$('.route-buttons').click(function(e) {
			    e.preventDefault();
			    $("#containerfluid").hide();
			    $("#rate-route").hide();
			    $("#saved-routes").hide();
			    $("#navigation").show();
			    var index = e.currentTarget.id.split("route-")[1];
			    var steps = curResult.routes[index].legs[0].steps;
			    $("#directions_list").empty();
			    if (steps.length > 0) {
				$("#directions_list").append("<ol id='list'></ol>");
				for (i in steps) {
				    $($("#directions_list").find("ol")[0]).append('<li class="item">' + steps[i].instructions + '</li>');
				}
			    }
			    $("#directions_list").append();
			    return false;
			});
		    }
		    directionsDisplay.setDirections(result);
             } else {
		    //alert("couldn't get directions:" + status);
             }
         });
     }

     function showAllRoutes() {
         Route();
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
     $("#route").click(function(e) {
     	 $("#rate-route").hide();
         $("#navigation").hide();
         $("#saved-routes").hide();
         $("#second").fadeIn();
         $("#containerfluid").show();
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
        $("#save-route-alert").show();
	    $('#save-route-alert').delay(500).fadeOut(400);
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
         $("#saved-routes").hide();
         $("#rate-route").show();
         return false;
     });
     
     $('#savedroutes').click(function(e) {
         e.preventDefault();
         $("#navigation").hide();
         $("#containerfluid").hide();
         $("#rate-route").hide();
         $("#second").fadeIn();
         $("#saved-routes").show();
         return false;
     });
     
	$("#route-save").click(function() {
	    $("#save-rate-alert").show();
	    $('#save-rate-alert').delay(500).fadeOut(400);
	}); 
	
	$(".stars").raty(); 
	
	$("#selectable").selectable({ disabled: true });

    //add x button to each selectable
    $("#selectable li").each(function() {
      $(this).append($('<span class="delete-button ui-icon ui-icon-close"></span>'));
      $(this).addClass("route");
    });
    
    //add x button handler
    $(".delete-button").click(function() {
      var parent = $(this).parent();
      parent.removeClass("route");
      parent.addClass("deleting");
      parent.fadeOut(700, function() {
        parent.remove();
      });
    });
 });
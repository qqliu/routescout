var MAX_SELECTABLE_TEXT = 20;  //must be > 3
 
 var map, adding, message, feature, messageId = 0;
 var markers = {};
 var curResult;
 var countryRestrict = { 'country': 'us' };
 var efficiency_rating = 0, scenery_rating = 0, safety_rating = 0;
 var savedRouteView = false;


 function populate_tips() {
    $.ajax('http://leoliu.scripts.mit.edu/routescout/db.php?op=get_all_tas&kind=0', {
       type : 'GET',
       success: function(res) {
          for (var i=0;i<res.data.length;i++) {
             A = parseFloat(res.data[i].x),
             k = parseFloat(res.data[i].y),
             position = new google.maps.LatLng(k, A);
             feature = {
                user: res.data[i].user,
                position: position,
                type: "star",
            };
            adding = "star";
            messageId = parseInt(res.data[i].id);
            message = res.data[i].comment;
            addMarker(feature);
        }
    }
});
 }

 function populate_accidents(res) {
    $.ajax('http://leoliu.scripts.mit.edu/routescout/db.php?op=get_all_tas&kind=1', {
       type : 'GET',
       success: function(res) {
          for (var i=0;i<res.data.length;i++) {
             A = parseFloat(res.data[i].x),
             k = parseFloat(res.data[i].y),
             position = new google.maps.LatLng(k, A);
             feature = {
                user: res.data[i].user,
                position: position,
                type: "caution",
            };
            adding = "caution";
            messageId = parseInt(res.data[i].id);
            message = res.data[i].comment;
            addMarker(feature);
        }
    }
});
 }

 function displayRoute(i, result, color) {
   rendererOptions = {
       draggable: false,
       suppressMarkers: true,
       suppressBicyclingLayer: true,
       polylineOptions: {
          strokeColor: color,
          strokeWeight:  4,
          strokeOpacity: 1.0
      }
  };
  var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
  directionsDisplay.setDirections(result);
  directionsDisplay.setRouteIndex(i);
  return directionsDisplay;
}

var colors = ["#D9853B", "#DF3D82", "#00FF00", "#003366", "#FF9900", "#993333", "#FFCC33", "#FFFF7A", "#CC6699", "#7D1935"];
var displayRoutes = [];
var savedRoutes = [];
var last_route = "";
var c = 0;

function toggleActive(button) {
   var active = $(button).hasClass("on");
   if (!active) {
        $(button).removeClass("off");
       $(button).addClass("on");
       if ($(button).attr("id") == "green-button1") {
           $("#green-button2").removeClass("on");
           $("#green-button2").addClass("off");
       } else {
           $("#green-button1").removeClass("on");
           $("#green-button1").addClass("off");
       }
   } else {
       map.setOptions({
           draggableCursor: 'default'
       });
       $(button).removeClass("on");
       $(button).addClass("off");
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

function refreshMarker(id) {
  m_id = id.split("message")[1];

   marker = markers[m_id];
     
   adding = marker.type;
    
    feature = {
        user: marker.user,
        position: marker.position,
        type: marker.type,
    };

    marker.setMap(null);
    delete markers[id];
 
  addMarker(feature);
  markers[m_id].info.open(map, markers[m_id]);
}

function addMarker(feature) {
   var marker = new google.maps.Marker({
       position: feature.position,
       icon: icons[feature.type].icon,
       map: map,
   });


   content = "";
   if (adding === "caution") {
      content += "<b style='font-size: 16px; float:left;'>Caution</b>";
  } else {
      content += "<b style='font-size: 16px; float:left;'>Tip</b>";
  }
  content += "<br /><div style='font-size:14px; text-align:left; min-width:50px; display: block;'>" + message + "</div>";

  content += "<div style='min-width:50px;padding:5px;margin:2px;padding-left:0px;margin-left:0px;'>";
  username = $("#user").text();
  if (username === feature.user && username != "") {
      content += "<button onclick='editMarker(\"message" + messageId + "\");' style='float:left' class='message_edit' id= 'message" + messageId + "'>Edit</button>";
      content += "<button onclick='deleteMarker(\"message" + messageId + "\");' style='float:left' class='message_delete' id= 'message" + messageId + "'>Delete</button>";
  } else {
      content += "<button onclick='flagMarker(\"message" + messageId + "\");' style='float:left' class='message_flag' id= 'message" + messageId + "'>Flag</button>";
  }

  content += "</div>";
  marker.message = message;
  marker.messageId = messageId;
  marker.user = username;

  marker.info = new google.maps.InfoWindow({
   content: content,
});
  marker.type = feature.type;
  markers[messageId] = marker;
  google.maps.event.addListener(marker, 'click', function() {
   marker.info.open(map, this);
});

  google.maps.event.addListener(marker, 'visible_changed', function() {
    if (!marker.getVisible()) {
      marker.info.close();
    }                  
  });

  map.setOptions({
   draggableCursor: 'default'
});
  adding = undefined;
}

function deleteMarker(id) {
  m_id = id.split("message")[1];
  markers[m_id].setMap(null);
  delete markers[id];

  data_obj = {
     op: "delete_ta",
     id: parseInt(m_id)
 };

 var text = $.ajax('db.php', {
   data : data_obj,
   type : 'POST',
   async: false
}).responseText;
   return text;
};

function save_tip_accident(message, feature) {
  if (feature.type.localeCompare("caution") == 0) {
     kind = 1;
 } else {
     kind = 0;
 }
 data_obj = {
     op: "save_ta",
     kind: kind,
     comment: message,
     x: feature.position.A,
     y: feature.position.k,
     flagged: 0,
 };

 return $.ajax('db.php', {
   data : data_obj,
   type : 'POST',
   async: false
}).responseText;
};


function editMarker(id) {
 m_id = id.split("message")[1];
 marker = markers[m_id];
 messageId = m_id;

 if (marker.type === "star") {
  $("#popup-title").text("Edit Tip");
  $("#popup-textbox").val(marker.message);
  $("#popup").dialog("option", {
     position: [485 + 350, 180 + 150]
 });
  $("#popup").dialog('open');
} else {
  $("#popup-title").text("Edit Accident Report");
  $("#popup-textbox").val(marker.message);
  $("#popup").dialog("option", {
     position: [485 + 350, 180 + 150]
 });
  $("#popup").dialog('open');
}
};

function flagMarker(id) {
   m_id = id.split("message")[1];

   data_obj = {
     op: "flag_ta",
     id: parseInt(m_id)
 };

 resp = $.ajax('db.php', {
   data : data_obj,
   type : 'POST',
   async: false
}).responseText;

 alert("Thank you. Flagged for an admin's attention.");
};


function edit_tip_or_accident(message, messageId) {
  data_obj = {
     op: "edit_ta",
     id: parseInt(messageId),
     comment: message,
 };
 $.ajax('db.php', {
   data : data_obj,
   type : 'POST',
   async: false,
});
 return message;
};

function get_saved_routes() {
 $.post( "db.php", { op: "get_saved_routes" })
 .done(function(res) {
    $("#selectable").empty();
    var saved_routes = res.data;
    for (i in saved_routes) {
        var displayName = saved_routes[i].name;
        var tooltip = '';
        if (displayName.length >= MAX_SELECTABLE_TEXT){
            displayName = displayName.substring(0, MAX_SELECTABLE_TEXT-3);
            displayName += "...";
            tooltip = 'title="' + saved_routes[i].name  +  '"';
        }
       $("#selectable").append('<li ' + tooltip + ' class="ui-widget-content" from = "' + saved_routes[i].from_loc + '" to = "'+ saved_routes[i].to_loc + '" index = "' + saved_routes[i].route_index + '" key = "' + saved_routes[i].route_key + '">' + displayName+ '</li>');
   }
                //add x button to each selectable
                $("#selectable li").each(function() {
                  $(this).append($('<span class="delete-button ui-icon ui-icon-close"></span>'));
                  $(this).addClass("route");
              });

                $("#selectable").selectable({
                   selecting: function(event, ui) {
                    var request = {
                     origin: ui.selecting.attributes.from.nodeValue,
                     destination: ui.selecting.attributes.to.nodeValue,
                     travelMode: google.maps.TravelMode.BICYCLING,
                     provideRouteAlternatives: true
                 };
                 var directionsService = new google.maps.DirectionsService();
                 directionsService.route(request, function(result, status) {
                     if (status == google.maps.DirectionsStatus.OK) {
                      $("#rate-route").hide();
                      $("#navigation").show();
                      $("#saved-routes").hide();
                      $("#containerfluid").hide();
                      $("#directions_list").empty();
                      var possibleRoutes = result.routes;
                      var curRoute = possibleRoutes[parseInt(ui.selecting.attributes.index.nodeValue)];
                      var steps = curRoute.legs[0].steps;
                      if (steps.length > 0) {
                          $("#directions_list").append("<ol id='list'></ol>");
                          for (i in steps) {
                              $($("#directions_list").find("ol")[0]).append('<li class="item">' + steps[i].instructions + '</li>');
                          }
                      }
                      $.post( "db.php", { op: "get_ratings_route", route_key: last_route[0] })
                      .done(function(res) {
                       var scores = [];
                       if (res.error == "") {
                           var rating = res.data;
                           if (rating != null) {
                               scores = [parseInt(rating.safety), parseInt(rating.efficiency), parseInt(rating.scenery)];
                           } else {
                               scores = [0, 0, 0];
                           }
                           $("#safety_rating").raty({score: scores[0], click: function(score, evt) {
                               clickFnc(this, score, evt) }
                           });
                           $("#efficiency_rating").raty({score: scores[1], click: function(score, evt) {
                               clickFnc(this, score, evt) }
                           });
                           $("#scenery_rating").raty({score: scores[2], click: function(score, evt) {
                               clickFnc(this, score, evt) }
                           });
                           var clickFnc = function(obj, score, evt) {
                               if ($(obj).attr('id') == "safety_rating") {
                                   safety_rating = score;
                               } else if ($(obj).attr('id') == "efficiency_rating") {
                                   efficiency_rating = score;
                               } else {
                                   scenery_rating = score;
                               }
                           };
                       } else {
                           console.log("ERROR: " + data.error);
                       }
                   });
 for (route in displayRoutes) {
   displayRoutes[route].setMap(null);
}

for (route in savedRoutes) {
   savedRoutes[route].setMap(null);
}
var newRoute = displayRoute(parseInt(ui.selecting.attributes.index.nodeValue), result, colors[c % colors.length]);
savedRoutes.push(newRoute);
newRoute.setMap(map);
savedRouteView = true;
}
});
}
});
});
}


function get_user_tips() {
 $.post( "db.php", { op: "get_user_tas", kind: 0 })
 .done(function(res) {
    $("#tips").empty();
    var comments = res.data;
    for (i in comments) { 
        var displayName = comments[i].comment;
        var tooltip = '';
        if (displayName.length >= MAX_SELECTABLE_TEXT){
            displayName = displayName.substring(0, MAX_SELECTABLE_TEXT-3);
            displayName += "...";
            tooltip = 'title="' + comments[i].comment  +  '"';
        }
      
       $("#tips").append('<li ' + tooltip + ' class="ui-widget-content">' + displayName + '</li>');
   }
});
};

function get_user_accidents() {
 $.post( "db.php", { op: "get_user_tas", kind: 1 })
 .done(function(res) {
$("#accidnts").empty();
    //$("#commentsDisplay").empty();
    var comments = res.data;
    for (i in comments) {  
        var displayName = comments[i].comment;
        var tooltip = '';
        if (displayName.length >= MAX_SELECTABLE_TEXT){
            displayName = displayName.substring(0, MAX_SELECTABLE_TEXT-3);
            displayName += "...";
            tooltip = 'title="' + comments[i].comment  +  '"';
        }
      
       $("#accidents").append('<li ' + tooltip + ' class="ui-widget-content">' + displayName + '</li>');
   }
});
};




function initialize() {
    var myCenter, directionsService,
    mapProp, starting, ending, rendererOptions;

    starting = document.getElementById('starting_loc');
    ending = document.getElementById('destination_loc');
    autocomplete_starting = new google.maps.places.Autocomplete(starting);
    autocomplete_ending = new google.maps.places.Autocomplete(ending);

    get_saved_routes();
    get_user_tips();
    get_user_accidents();

    google.maps.event.addListener(autocomplete_starting, 'place_changed', onPlaceChanged);
    //google.maps.event.addDomListener(document.getElementById('country'), 'change',
      //setAutocompleteCountry);
    //autocomplete_starting.bindTo('bounds', map);
    //autocomplete_ending.bindTo('bounds', map);

    myCenter = new google.maps.LatLng(42.3522, -71.0627);
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
     username = $("#user").text();
     map = map;
     if (adding == "star") {
       feature = {
          position: event.latLng,
          type: "star",
          user: username,
      };
      $("#popup-title").text("Add Tip");
      $("#popup").dialog("option", {
          position: [485 + event.pixel.x, 180 + event.pixel.y]
      });
      $("#popup-textbox").val("");
      $("#popup").dialog('open');
  } else if (adding == "caution") {
   feature = {
      position: event.latLng,
      type: "caution",
      user: username,
  };
  $("#popup-title").text("Report Accident");
  $("#popup").dialog("option", {
      position: [485 + event.pixel.x, 180 + event.pixel.y]
  });
  $("#popup-textbox").val("");
  $("#popup").dialog('open');
}
});

    function addStarCaution() {
       if (feature != undefined && feature != null) {
           addMarker(feature);
       }
   }
   var bikeLayer = new google.maps.BicyclingLayer();
   bikeLayer.setMap(map);

   var styles = [
   {
      stylers: [
      { hue: "#00ffe6" },
      { saturation: -20 }
      ]
  },{
      featureType: "road",
      elementType: "geometry",
      stylers: [
      { lightness: 100 },
      { visibility: "simplified" }
      ]
  },{
      featureType: "road",
      elementType: "labels",
      stylers: [
      { visibility: "off" }
      ]
  }
  ];

  map.setOptions({styles: styles});

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
       travelMode: google.maps.TravelMode.BICYCLING,
       provideRouteAlternatives: true
   };
   directionsService.route(request, function(result, status) {
       $("#loading").css("visibility", "hidden");
       if (status == google.maps.DirectionsStatus.OK) {
          curResult = result;
          var possibleRoutes;
          possibleRoutes = curResult.routes;
          for (route in displayRoutes) {
            displayRoutes[route].setMap(null);
        }

        for (route in savedRoutes) {
            savedRoutes[route].setMap(null);
        }
        displayRoutes = [];
        savedRoutes = [];
        c = 0;
        $("#routes").empty();
        if (possibleRoutes.length > 0) {

                //$("#noRouteFound").hide();
              //$("#noroute").hide();
              $("#routes").show();
              $("#routes").append("<ol></ol>");

              $("#noRouteFound").css("display","none");
              $("#noRouteFound").css("visibility","hidden");



              for (i in possibleRoutes) {
                     var name = possibleRoutes[i].summary;
                     var steps = possibleRoutes[i].legs[0].steps;
                     var routeKey = "";
                     for (i in steps) {
                        routeKey += steps[i].instructions;
                     }
                     var res = $.ajax({
                            type: 'POST',
                            url: "db.php",
                            data: {op: "get_average_ratings", route_key: routeKey},
                            async:false
                          }).responseText;
                       res = JSON.parse(res);
                       if (res.error == "") {
                            var safety = res.data.safety.safety;
                            var efficiency = res.data.efficiency.efficiency;
                            var scenery = res.data.scenery.scenery;
                            var prob = Math.random();
                            if (prob > 0.6) {
                              safety =  (((5)*Math.random()).toString()).substring(0,4);
                              efficiency = (((5)*Math.random()).toString()).substring(0,4);
                              scenery = (((5)*Math.random()).toString()).substring(0, 4); 
                            }
                            var routeButton = '<li><button style="border-width: 5px; border-color:' + colors[c] + '" class="button2 route-buttons" data-toggle="tooltip" data-placement="right" data-html="true" id="route-' + c + '" title = "';
                            if (safety != null && safety != "0") {
                                   routeButton = routeButton + 'Safety rating: ' + safety + '<br />'; 
                            }
                            
                            if (efficiency != null && efficiency != "0") {
                                   routeButton = routeButton + 'Efficiency rating: ' + efficiency + '<br />';
                            }
                            
                            if (scenery != null && scenery != "0") {
                                   routeButton = routeButton + 'Scenery rating: ' + scenery + '<br />';       
                            }
                            
                            if ((safety == null || safety == "0") && (efficiency == null || efficiency == "0") && (scenery != null || scenery != "0")) {
                                   routeButton = routeButton + 'No ratings available.';
                            }
                            routeButton += '">' + name + '</button></li>';
                            
                            $($("#routes").find("ol")[0]).append(routeButton);
                            displayRoutes.push(displayRoute(c, result, colors[c % colors.length]));
                            c += 1;
                            
                            $("[data-toggle=tooltip]").tooltip({content: function () {
                                       return $(this).prop('title');},
                                       position: {
                                       my: "center",
                                       at: "right+100",
                                       track: false,
                                       using: function(position, feedback) {
                                           $(this).css(position);                   
                                       }}});
                     }
                //document.getElementById("noRouteFound").style.display= "";
              //document.getElementById("noRouteFound").style.visibility= "hidden" ;
             }
             $('.route-buttons').click(function(e) {
                 e.preventDefault();
                 $("#containerfluid").hide();
                 $("#rate-route").hide();
                 $("#saved-routes").hide();
                 $("#navigation").show();
                 savedRouteView = false;
                 var index = e.currentTarget.id.split("route-")[1];
                 for (route in displayRoutes) {
                    if (route != index) {
                        displayRoutes[route].setMap(null);
                    }
                }
                for (route in savedRoutes) {
                 savedRoutes[route].setMap(null);
             }
             var steps = curResult.routes[index].legs[0].steps;
             var routeKey = "";
             $("#directions_list").empty();
             if (steps.length > 0) {
                $("#directions_list").append("<ol id='list'></ol>");
                for (i in steps) {
                    $($("#directions_list").find("ol")[0]).append('<li class="item">' + steps[i].instructions + '</li>');
                    routeKey += steps[i].instructions;
                }
            }
            last_route = [routeKey, curResult.routes[index].summary, request.origin, request.destination, index];
            $.post( "db.php", { op: "get_ratings_route", route_key: last_route[0] })
            .done(function(res) {
             var scores = [];
             if (res.error == "") {
                 var rating = res.data;
                 if (rating != null) {
                     scores = [parseInt(rating.safety), parseInt(rating.efficiency), parseInt(rating.scenery)];
                 } else {
                     scores = [0, 0, 0];
                 }
                 $("#safety_rating").raty({score: scores[0], click: function(score, evt) {
                     clickFnc(this, score, evt) }
                 });
                 $("#efficiency_rating").raty({score: scores[1], click: function(score, evt) {
                     clickFnc(this, score, evt) }
                 });
                 $("#scenery_rating").raty({score: scores[2], click: function(score, evt) {
                     clickFnc(this, score, evt) }
                 });
                 var clickFnc = function(obj, score, evt) {
                     if ($(obj).attr('id') == "safety_rating") {
                         safety_rating = score;
                     } else if ($(obj).attr('id') == "efficiency_rating") {
                         efficiency_rating = score;
                     } else {
                         scenery_rating = score;
                     }
                 };
             } else {
                 console.log("ERROR: " + data.error);
             }
         });
 return false;
});
}

for (route in displayRoutes) {
 displayRoutes[route].setMap(map);
}
} else {



  $("#routes").hide();
    $("#noRouteFound").show();
              //$("#noroute").show();
   //alert("coulxdn't get directions:" + status);
           //$("#noRouteFound").append('<div id="noroute" class="alert alert-danger alert-dismissable"></div>');
           $("#noRouteFound").css("display", "block");
           document.getElementById("noRouteFound").style.visibility= "visible" ;
           //document.getElementById('noroute').innerHTML+= "Sorry! No routes were found. Please try again.";
       }
   });
}

function toggleLanes(value) {
    if (value) {
       bikeLayer.setMap(map);
   } else {
       bikeLayer.setMap(null);
   }
}

     // When the user selects a city, get the place details for the city and
    // zoom the map in on the city.
    function onPlaceChanged() {
      var place = autocomplete_starting.getPlace();
      if (place.geometry) {
       map.panTo(place.geometry.location);
       map.setZoom(15);
   } else {
       document.getElementById('starting_loc').placeholder = 'Enter an address';
   }
}

function showAllRoutes() {
    $("#loading").css("visibility", "visible");
    Route();
}

$("#green-button1").click(function() {
   map.setOptions({
       draggableCursor: "url(popups/caution.png) 16 30, default"
   });
   adding = "caution";
   toggleActive("#green-button1");
});

$("#green-button2").click(function() {
   map.setOptions({
       draggableCursor: "url(popups/star-32.png) 16 30, default"
   });
   adding = "star";
   toggleActive("#green-button2");
});

$("#destination_loc").keypress(function(event) {
    if (event.which == 13) {
        $("#rate-route").hide();
         $("#navigation").hide();
         $("#saved-routes").hide();
         $("#second").fadeIn();
         $("#containerfluid").show();
         event.preventDefault();
         showAllRoutes();
    }});

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

        title = $("#popup-title").text();
        if (title.indexOf("Edit") > -1) {
            m = $('#popup-textbox').val();
            message = edit_tip_or_accident(m, messageId);
            str_id = 'message' + messageId;
            refreshMarker(str_id);

            $("#popup").dialog('close');
            $("#popup-textbox").val("");
          $("#report-button").removeClass("active");
          $("#tip-button").removeClass("active");
      } else {
          message = $('#popup-textbox').val();
          result = save_tip_accident(message, feature);
          $("#popup").dialog('close');
          addStarCaution();
          $("#popup-textbox").val("");
          $("#report-button").removeClass("active");
          $("#tip-button").removeClass("active");
          messageId += 1;
      }
      get_user_accidents();
      get_user_tips();
  });
     $('.filters').click(function() {
       if ($(this).hasClass('on2')) {
           var id = $(this).attr("id");
           for (var i in markers) {
               if (markers[i].type == id) {
                   markers[i].setVisible(false);
               }
           }
           if (id == "lanes") {
              toggleLanes(false);
          }
          $(this).removeClass('on2');
          $(this).addClass('off2');
      } else {
       var id = $(this).attr("id");
       for (var i in markers) {
           if (markers[i].type == id) {
               markers[i].setVisible(true);
           }
       }
       if (id == "lanes") {
          toggleLanes(true);
      }
       $(this).removeClass('off2');
       $(this).addClass('on2');
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

   $(document).tooltip();

   populate_tips();
   populate_accidents();

   $('.dropdown-menu').click(function(e) {
            e.stopPropagation(); //This will prevent the event from bubbling up and close the dropdown when you type/click on text boxes.
        });

     $("#routes button").width("100%");
     $("#savedButton").click(function() {
    $.post( "db.php", { op: "save_route", route_key: last_route[0], name: last_route[1], from_loc: last_route[2], to_loc: last_route[3], route_index: last_route[4] })
        .done(function( data ) {
        if (data.error != "") {
            $("#save-route-error").show();
            $('#save-route-error').delay(500).fadeOut(400);
        } else {
            $("#save-route-alert").show();
            $('#save-route-alert').delay(500).fadeOut(400);
        }
    });
        get_saved_routes();
     });

     $('#back-to-routes').click(function(e) {
     if (!savedRouteView) {
        e.preventDefault();
        $("#navigation").hide();
        $("#containerfluid").show();
        for (route in displayRoutes) {
            displayRoutes[route].setMap(map);
        }
     } else {
        $("#containerfluid").hide();
        $("#saved-routes").show();
        $("#navigation").hide();
        $("#tips").hide();
        $("#accidents").hide();
     }
        return false;
     });

     $('#back-saved-routes').click(function (e) {
        $("#saved-routes").hide();
        $("#containerfluid").show();
     });

     $('#back-to-nav').click(function(e) {
         e.preventDefault();
         $("#rate-route").hide();
         $("#navigation").show();
         return false;
     });

     $('#savedroutes').click(function(e) {
         e.preventDefault();
         $("#navigation").hide();
         $("#containerfluid").hide();
         $("#rate-route").hide();
         $("#second").fadeIn();
         $("#saved-routes").show();
         $("#tips").hide();
         $("#accidents").hide();
         $("#accidents").css("display", "none");
         $("#tips").css("display", "none");
         $("#selectable").show();
         return false;
     });

     $('#saved_routes_back_button').click(function(e) {
         e.preventDefault();
         $("#navigation").hide();
         $("#containerfluid").hide();
         $("#rate-route").hide();
         $("#tips").hide();
         $("#accidents").hide();
         $("#accidents").css("display", "none");
         $("#tips").css("display", "none");
         $("#selectable").show();
         return false;
     });

     $('#user_tips').click(function(e) {
         get_user_tips();
         e.preventDefault();
         $("#navigation").hide();
         $("#containerfluid").hide();
         $("#rate-route").hide();
         $("#second").fadeIn();
         $("#saved-routes").show();
         $("#selectable").hide();
         $("#tips").show();
         $("#accidents").hide();
         
         return false;
     });


$('#user_accidents').click(function(e) {
         e.preventDefault();
         $("#navigation").hide();
         $("#containerfluid").hide();
         $("#rate-route").hide();
         $("#second").fadeIn();
         $("#saved-routes").show();
         $("#selectable").hide();
         $("#tips").hide();
         $("#accidents").show();
         
         return false;
     });

$('#route-rateSaved').click(function(e) {
         e.preventDefault();
         $("#navigation").hide();
         $("#containerfluid").hide();
         $("#rate-route").hide();
         $("#second").fadeIn();
         $("#saved-routes").show();
         $("#commentsDisplay").hide();
         $("#accidentDisplay").hide();
         $("#selectable").show();
         return false;
     });

      $('#route-rate').click(function(e) {
	e.preventDefault();
	$("#navigation").hide();
	$("#containerfluid").hide();
	$("#saved-routes").hide();
	$("#rate-route").show();
        $.post( "db.php", { op: "get_ratings_route", route_key: last_route[0] })
 	    .done(function( res ) {
 	       var scores = [];
               if (res.error == "") {
                   var rating = res.data;
                   if (rating != null) {
                       scores = [parseInt(rating.safety), parseInt(rating.efficiency), parseInt(rating.scenery)];
                   } else {
                       scores = [0, 0, 0];
                   }
                   $("#safety_rating").raty({score: scores[0], click: function(score, evt) {
                       clickFnc(this, score, evt) }
                   });
                   $("#efficiency_rating").raty({score: scores[1], click: function(score, evt) {
                       clickFnc(this, score, evt) }
                   });
                   $("#scenery_rating").raty({score: scores[2], click: function(score, evt) {
                       clickFnc(this, score, evt) }
                   });
                   var clickFnc = function(obj, score, evt) {
                       if ($(obj).attr('id') == "safety_rating") {
                           safety_rating = score;
                       } else if ($(obj).attr('id') == "efficiency_rating") {
                           efficiency_rating = score;
                       } else {
                           scenery_rating = score;
                       }
                   };
               } else {
                   console.log("ERROR: " + data.error);
               }
 	 });
     });

    $("#route-save").click(function() {
        $.post( "db.php", { op: "update_ratings", route_key: last_route[0], safety: safety_rating, efficiency: efficiency_rating, scenery: scenery_rating})
        .done(function( data ) {
        if (data.error != "") {
            $("#save-rate-error").show();
            $('#save-rate-error').delay(500).fadeOut(400);
        } else {
            $("#save-rate-alert").show();
            $('#save-rate-alert').delay(500).fadeOut(400);
        }
       });
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

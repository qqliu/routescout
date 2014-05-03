<!DOCTYPE html>

<html lang="en">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link href='http://fonts.googleapis.com/css?family=Lily+Script+One' rel=
    'stylesheet' type='text/css'>
    <meta charset="utf-8">

    <title>RouteScout</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="RouteScout" name="description">
    <meta content="" name="author"><!-- Le styles -->
    <link href="files/style.css" rel="stylesheet">
    <link href="files/bootstrap.css" rel="stylesheet">
    <link href="files/example-fluid-layout.css" rel="stylesheet">
    <link href="files/jquery-ui-1.10.4/themes/base/jquery-ui.css" rel="stylesheet"><!-- Load any supplemental Javascript libraries here -->

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
    <script src="ratePlugin/jquery.raty.min.js"></script>
    <script src="files/jquery-ui-1.10.4/ui/jquery-ui.js" type=
    "text/javascript"></script>
    <script src=
    "http://maps.googleapis.com/maps/api/js?key=AIzaSyDjX5a3zMFqvmcjySBDAbkf2fOfY1piELs&amp;sensor=true&libraries=places"></script>
    <script src="files/index.js"></script>
</head>

<body background="parchment.jpg">
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <a class="logout-saved" id="logout">Logout</a>
            <a class="logout-saved" id="savedroutes">Saved Routes</a>

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
                        <div class="container well well-small span11" id=
                        "first">
                            <div id="two">
                                <div class="from-to" style="text-align:center">
                                    <form class="navbar-form">
                                        <div id="test">
                                            <h3>From:</h3><input class=
                                            "form-control" id="starting_loc"
                                            name="srch-term" placeholder=
                                            "Search" type="text">
                                        </div>

                                        <div id="test">
                                            <h3>To:</h3><input class=
                                            "form-control" id="destination_loc"
                                            name="srch-term" placeholder=
                                            "Search" type="text">
                                        </div>
                                    </form>
                                </div>

                                <div id="route-find" style="text-align:center">
                                    <button class="btn btn-large" id=
                                    "route">Search For Routes!</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid" id="bottom-window">
                        <div class="container well span11" id="second" style=
                        "display:none">
                            <div class="container-fluid" id="containerfluid">
                                <br>
                                <h2><span style="text-decoration: underline">Possible</span>
                                <span style="text-decoration: underline">Routes</span></h2><br>

                                <div id="routes"></div><br>

                                <h4>Sort Routes By:</h4>

                                <table class="test">
                                    <tr>
                                        <td>Overall Safety</td>

                                        <td>
                                            <div class="criteria-slider" id="slider"></div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Fewest Accidents</td>

                                        <td>
                                            <div class="criteria-slider" id="slider"></div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Bike Lanes</td>

                                        <td>
                                            <div class="criteria-slider" id="slider"></div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Efficiency</td>

                                        <td>
                                            <div class="criteria-slider" id="slider"></div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Scenery</td>

                                        <td>
                                            <div class="criteria-slider" id="slider"></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        
                        
                        
				        <div id="navigation" style="display:none">
				            <div class="go-back" style="padding: 10px;">
				                <a id="back-to-routes"><img src="back-arrow.png"></a>
				            </div>
				
				            <h2 style="text-align:center"><span style=
				            "text-decoration: underline">Selected</span> <span style=
				            "text-decoration: underline">Route</span></h2>
				
				            <div id="directions_list" style="padding-top: 0px; padding-left: 10px; padding-bottom: 10px; padding-right: 10px;"></div>
				
				            <div class="row-fluid" id="bottom-buttons">
				                    <div id="route-find" style="text-align:center; float: left;">
				                        <button class="btn btn-large" data-target="#saveModal"
				                        data-toggle="modal" id="savedButton">Save
				                        Route!</button>
				                        <div class='save-alert' id="save-route-alert" style="display:none;">Successfully Saved!</div>
				                    </div>
				
				                    <div id="route-find" style="text-align:center">
				                        <button class="btn btn-large" id="route-rate" type="button">Rate this
				                        Route</a></button>
				                    </div>
				            </div>
				        </div>
				        
				        <div id="rate-route" style="display:none">
				        	<div class = "go-back" style="padding: 10px;">
								<a id="back-to-nav"><img src="back-arrow.png"></a>
								</div>
								
								<h2 style="text-align:center"><u>Rate</u> <u>this</u> <u>Route!</u></h2>
								
								<div class="row-fluid">
								<div class="span4"><h3>Safety:</h3></div>
								  <div class="span4 offset3 ">
								<div class="stars"></div>
								  </div>
								 </div>
								
								
								 <div class="row-fluid">
								<div class="span4"><h3>Efficiency</h3></div>
								  <div class="span4 offset3">
								<div class="stars"></div>
								  </div>
								 </div>
								
								<div class="row-fluid">
								<div class="span4"><h3>Scenery</h3></div>
								  <div class="span4 offset2">
								<div class="stars"></div>
								  </div>
								 </div>
								
								
								<div id="bottom-buttons" class="row-fluid">
									<div id="route-find" style="text-align:center">
										<button id="route-save" type="button" class="btn btn-large">
										Rate!</button>
										<div class='save-alert' id="save-rate-alert" style="display:none;">Successfully Saved!</div> 
									</div>
								</div>
				        </div>
				        
				        <div id="saved-routes" style="display:none">
                    		<center>
								<br /><br />
								<h2><u>Saved</u> <u>Routes</u></h2>
								<br /><br />
								<ol id="selectable">
								  <li class="ui-widget-content">Main St. To Bridge St.</li>
								  <li class="ui-widget-content">Memorial Dr. to Stevens Creek</li>
								  <li class="ui-widget-content">Stevens Creek to First Ave</li>
								  <li class="ui-widget-content">Barnhart Ave to First Stevens Creek</li>
								</ol>
							
							</center>
                        </div>
                        
                       </div>
                    </div>
                </div>
            </div>
        </div>

 

        <div hidden="" id="popup">
            <p id="popup-title">Title</p>
            <textarea cols="25" id="popup-textbox" rows="5"></textarea>

            <p><a class="btn btn-success btn-large" id=
            "popup-submit">Submit</a></p><br>
        </div>

        <div class="container-fluid">
            <div class="span8" id="map">
                <div id="togglefeatures">
                    <div class="toggle-button" id="toggle-label">
                        Toggle Visibility:
                    </div>

                    <div class="toggle-button">
                        <img class="toggle-img" src="files/icon_biking.png">Bike Lanes <input checked class="filters" type="checkbox" value="lanes">
                    </div>

                    <div class="toggle-button">
                        <img class="toggle-img" src="popups/star-32.png">Tips<input checked class="filters" type="checkbox" value="star">
                    </div>

                    <div class="toggle-button">
                        <img class="toggle-img" src="popups/caution.png">Accidents <input checked class="filters" type="checkbox" value="caution">
                    </div>

                    <div id="googleMap" style="width:700px;height:550px;">
                    </div>

                    <div id="green-buttons">
                        <a class="popup-button btn btn-success btn-large" id=
                        "report-button">Report Accident</a> <a class=
                        "popup-button btn btn-success btn-large" id=
                        "tip-button">Add Tip</a>
                    </div>
                </div><!--/.fluid-container-->
            </div>
        </div>
    </div>
</body>
</html>
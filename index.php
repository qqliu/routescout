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
	<script src="files/index.js"></script>
  </head>
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
  <div id="second" name="second" class="container well span11" src="filter_routes.php" height="310" sandbox="allow-same-origin allow-scripts" style="display:none">
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

  </div>
</div>
</div>
</div>
</div>

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
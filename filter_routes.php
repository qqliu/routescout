<!DOCTYPE html>

<html>
<head>
<link href='http://fonts.googleapis.com/css?family=Lily+Script+One' rel='stylesheet' type='text/css'>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

<!-- Load style sheets -->
<link href="files/bootstrap.css" rel="stylesheet">
<link href="files/style.css" rel="stylesheet">
<link href="filter_routes/filter_routes.css" rel="stylesheet">
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
  });    
</script>
</head>

<body>
<div class="container-fluid">
<br />
<center>
<h2><u>Possible</u> <u>Routes</u></h2>
</center>
<div id="routes">
<ol>
<li><button id="route-1" class="btn btn-large" onclick="window.location.href='navigation.php'">Cambridge St.</button></li>
<li><button id="route-2" class="btn btn-large" onclick="window.location.href='navigation.php'">Newbury St.</button></li>
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

</html>

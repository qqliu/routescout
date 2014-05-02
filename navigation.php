<!DOCTYPE html>

<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

<!-- Load style sheets -->
<link href="files/style.css" rel="stylesheet">
<link href="files/bootstrap.css" rel="stylesheet">
<link href="popups/popups.css" rel="stylesheet">
<link href="test_iframe.css" rel="stylesheet">
<link rel="stylesheet" href="files/jquery-ui-1.10.4/themes/base/jquery-ui.css">
<link href='http://fonts.googleapis.com/css?family=Lily+Script+One' rel='stylesheet' type='text/css'>
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

</body>


<script>
$("#savedButton").click(function() {
    $(this).after('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Succesfully Saved!</div>');
}); 
</script>
<!--
<div id="route-find" style="text-align:center">
	<button id="route" class="btn btn-success btn-large" ><a href="filter_routes.php" style="text-decoration: none">
		Search For Routes!</a></button>
</div>

-->
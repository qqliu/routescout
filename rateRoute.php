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
<script src="ratePlugin/jquery.raty.min.js"></script>


<style>

a {
	color: #000000;
}
a:hover {
	color: #000000;
	text-decoration: none;
}

h1{
	margin-top: 30px;
margin-bottom: 20px;

}

h3 {
  margin-right: 25px;
margin-left:25px;
margin-right: 10px;
margin-bottom: 20px;


}
.item{
 margin-top: 20px;
 margin-bottom: 20px;
 font-size: 18px;
}
#bottom-buttons{
margin-top: -10px;
margin-left: 55px;
margin-right: 20px;
}
#stars{

}
body {
    overflow-x:hidden;
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
<a href="navigation.php"><img src="back-arrow.png"></a>
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
	

<div class="span6 offset7">
<div id="route-find" style="text-align:center">
	<button id="route" type="button" class="btn btn-large" onClick=" $('#save').css({'color': 'black'}); return false;"><a href="navigation.php" style="text-decoration: none">
		Rate!</a></button>
</div>
</div>
</div>
</body>

<script>
$(".stars").raty(
{
   //starOff  : 'star-off-big.png',
  //starOn   : 'star-on-big.png'
}
  );

$("#route").click(function() {
    $(this).after('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Succesfully Saved!</div>');
}); 


</script>

<!--
<div id="route-find" style="text-align:center">
	<button id="route" class="btn btn-success btn-large" ><a href="filter_routes.php" style="text-decoration: none">
		Search For Routes!</a></button>
</div>

-->
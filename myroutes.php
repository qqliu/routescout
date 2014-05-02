<!DOCTYPE html>

<html>
<head>
<link href='http://fonts.googleapis.com/css?family=Lily+Script+One' rel='stylesheet' type='text/css'>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

<!-- Load style sheets -->
<link href="files/bootstrap.css" rel="stylesheet">
<link href="files/style.css" rel="stylesheet">
<link href="myroutes/myroutes.css" rel="stylesheet">
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

<script type="text/javascript">
  $(document).ready(function() {
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
  
</script>
</head>

<body>
<center>
<br /><br />
<h2><u>Saved</u> <u>Routes</u></h2>
<br /><br />
<ol id="selectable">
  <li class="ui-widget-content">Main St. To Br...</li>
  <li class="ui-widget-content">Memorial Dr. t...</li>
  <li class="ui-widget-content">Stevens Creek ...</li>
  <li class="ui-widget-content">Barnhart Ave t...</li>
  <li class="ui-widget-content">Homestead Ave ...</li>
  <li class="ui-widget-content">Massachusetts ...</li>
  <li class="ui-widget-content">First Ave to D...</li>
  <li class="ui-widget-content">Blaney Rd to B...</li>
  <li class="ui-widget-content">Miller Ave to ...</li>
  <li class="ui-widget-content">Broadway to Ma...</li>
  <li class="ui-widget-content">Kendall Square...</li>
  <li class="ui-widget-content">Akamai to Chil...</li>
  <li class="ui-widget-content">In-N-Out Sunnl...</li>
  <li class="ui-widget-content">Harvard Squaro...</li>
  <li class="ui-widget-content">Vassar St to A...</li>
  <li class="ui-widget-content">San Jose, CA t...</li>
</ol>

</center>
</body>

</html>

<!-- TODO:
ADD A CLOSE HANDLER TO THE DIALOG
-->

<!DOCTYPE html>

<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

<!-- Load style sheets -->
<link href="files/style.css" rel="stylesheet">
<link href="files/bootstrap.css" rel="stylesheet">
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

<script type="text/javascript">
    //returns whether the mouse is over the object
    function isMouseOver(e, j_obj) {
      var left = j_obj.offset().left;
      var right = left + j_obj.width();
      var top = j_obj.offset().top;
      var bottom = top + j_obj.height();
      
      return (left < e.pageX && e.pageX < right && top < e.pageY && e.pageY < bottom);
    }

  $(document).ready(function() {
    var isReport;
  
    //show popup
    $("#popup").dialog({autoOpen: false});
  
    //install handlers for buttons
    
    $(".popup-button").click(function() {
      var active = $(this).hasClass("active");
      console.log( $(this).attr("id"));
      isReport = $(this).attr("id") == "report-button";
      console.log(isReport);
      
      if (!active) {
        //make active, change mouse
        $(this).addClass("active");
        $('#pin').show();
        $(document).on('mousemove', function(e){
          var onMap = isMouseOver(e, $("#map"));
          if (onMap) {
            $('#pin').css({
               left:  e.pageX - 15,
               top:   e.pageY - 15
            });
          }
        });
        $(document).on('click', function(e){
          console.log("click");
          var onMap = isMouseOver(e, $("#map"));
          if (onMap) {
            $('#pin').css({
               left:  e.pageX - 15,
               top:   e.pageY - 15
            });
            $(document).off('mousemove');
            $(document).off('click');
            $("#popup-title").text((isReport) ? "Report Accident" : "Add Tip");
            $("#popup").dialog("option", { position: [e.pageX, e.pageY] });
            $("#popup").dialog('open');
            $(".popup-button").removeClass("active");
          }
        });
      } else {
        //make inactive, restore mouse, hide popup
        $(this).removeClass("active");
        $('#pin').hide();
        $(document).off('mousemove');
        $(document).off('click');
      }
    });
    
    //install handler for submit button
    $('#popup-submit').click(function() {
      var pinPos = $('#pin').position();
      $('#pin').hide();
      //put the correct img there
      var imgSrc = isReport ? "popups/caution.png" : "popups/star-32.png";
      console.log(imgSrc);
      var imgClass = isReport ? "report-icon" : "tip-icon";
      
      var imgElem = $("<img>").attr("src", imgSrc).addClass(imgClass);
      //add mouseover
      
      $("#map").append(imgElem);
      imgElem.css('left', pinPos.left);
      imgElem.css('top', pinPos.top);
      $("#popup").dialog('close');
      $(document).off('click');
    });
    
    //install handler for close
  });
  

</script>
</head>

<body>

  <p><a id="report-button" class="popup-button btn btn-success btn-large">Report Accident</a></p>
  <p><a id="tip-button" class="popup-button btn btn-success btn-large">Add Tip</a></p>
  <div id="map" width="400px" style="border:1px solid black; height:400px; width:400px;"></div>
  <img hidden id="pin" src="popups/thumbtack-blue.png" />
  
  <div hidden id="popup">
    <center>
    <p id="popup-title">Title</p>
    <textarea id="popup-textbox" cols="25" rows="5">Enter your comments here...</textarea>
    <p><a id="popup-submit" class="btn btn-success btn-large">Submit</a></p>
    </center>
  </div>
</div>
</body>

</html>

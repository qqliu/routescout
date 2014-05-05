
<?php 
$path = $_SERVER['DOCUMENT_ROOT'];
$append = "/login/initconfig.php";
$path .= "/routescout/index.php";
include_once($path);


?>
<?php 


require("./login/initconfig.php"); 
$submitted_username = ''; 
if (isset($_POST['loginform'])) {
    $query = " 
    SELECT  
    username, 
    password, 
    salt, 
    email 
    FROM registerUsers 
    WHERE 
    username = :username 
    "; 
    $query_params = array( 
        ':username' => $_POST['username'] 
        ); 

    try{ 
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
    } 
    catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
    $login_ok = false; 
    $row = $stmt->fetch(); 
    if($row){ 
        $check_password = hash('sha256', $_POST['password'] . $row['salt']); 
        for($round = 0; $round < 65536; $round++){
            $check_password = hash('sha256', $check_password . $row['salt']);
        } 
        if($check_password === $row['password']){
            $login_ok = true;
        } 
    } 

    if($login_ok){ 
        unset($row['salt']); 
        unset($row['password']); 
        $_SESSION['user'] = $row;  
            //echo "Login succeeded!!!!"
            //header("Location: secret.php"); 
            //die("Redirecting to: secret.php"); 
    } 
    else{ 
        echo'<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        The username or password you entered is incorrect.
        </div>';
        $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
    } 
}
else if (isset($_POST['registerform'])) {
    // Ensure that the user fills out fields 
    if(empty($_POST['username'])) 

    { 
        echo'<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Please enter a username.
        </div>';
        
    } 
    if(empty($_POST['password'])) 
        { echo'<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    Please enter a password.
    </div>'; } 
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { echo'<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    Please enter a valid email address.
    </div>'; } 

        // Check if the username is already taken
    $query = " 
    SELECT 
    1 
    FROM registerUsers 
    WHERE 
    username = :username 
    "; 
    $query_params = array( ':username' => $_POST['username'] ); 
    try { 
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
    } 
    catch(PDOException $ex){ } 
    $row = $stmt->fetch(); 
    if($row){ echo'<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    This username is already in use.
    </div>'; } 
    $query = " 
    SELECT 
    1 
    FROM registerUsers 
    WHERE 
    email = :email 
    "; 
    $query_params = array( 
        ':email' => $_POST['email'] 
        ); 
    try { 
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
    } 
    catch(PDOException $ex){ } 
    $row = $stmt->fetch(); 
    if($row){ echo'<div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    This email address is already registered.
    </div>'; } 
    else{
        echo'<div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        You have successfully registered! Please login now to access saved routes, tips, comments, and more! 
        </div>';
    }

        // Add row to database 
    $query = " 
    INSERT INTO registerUsers ( 
        username, 
        password, 
        salt, 
        email 
        ) VALUES ( 
        :username, 
        :password, 
        :salt, 
        :email 
        ) 
"; 

        // Security measures
$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
$password = hash('sha256', $_POST['password'] . $salt); 
for($round = 0; $round < 65536; $round++){ $password = hash('sha256', $password . $salt); } 
    $query_params = array( 
        ':username' => $_POST['username'], 
        ':password' => $password, 
        ':salt' => $salt, 
        ':email' => $_POST['email'] 
        ); 
try {  
    $stmt = $db->prepare($query); 
    $result = $stmt->execute($query_params); 
} 
catch(PDOException $ex){  }



}

function ifCorrect() {
  //print "logging in with email=$email pass=$password";

  if(isset($_SESSION['user'])) 
  {
    return true;
}
else {
	return false;}
}


?>
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

<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">

            <ul class="nav pull-right">



              <?php if (!ifCorrect()){?>
              <li class="divider-vertical"></li>
              <li class="dropdown">

                <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="registerLogin" >Register <strong class="caret"></strong></a>
                <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                  <form id="registerform" name="registerform" method="post" accept-charset="UTF-8">
                      Username: <input id="user_username" style="margin-bottom: 15px;" type="text" name="username" value="" size="30" />
                      Email: <input id="user_email" style="margin-bottom: 15px;" type="text" name="email" value="" size="30" />
                      Password: <input id="user_password" style="margin-bottom: 15px;" type="password" name="password" value="" size="30" />
                      <input type="hidden" name="registerform" value="registerform">

                      <input class="btn btn-primary" style="clear: left; width: 100%; height: 32px; font-size: 13px;" type="submit" name="commit" value="Sign In" />
                  </form>
              </div>
          </li>


          <li class="dropdown">

            <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="registerLogin" >Login <strong class="caret"></strong></a>
            <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                <form id="loginform" name="loginform" method="post" accept-charset="UTF-8">
                  Username: <input id="user_username" style="margin-bottom: 15px;" type="text" name="username" value="" size="30" />
                  Password: <input id="user_password" style="margin-bottom: 15px;" type="password" name="password" value="" size="30" />
                  <input type="hidden" name="loginform" value="loginform">
                  <input class="btn btn-primary" style="clear: left; width: 100%; height: 32px; font-size: 13px;" type="submit" name="commit" value="Sign In" />
              </form>
          </div>
      </li>
      <?php }
      else { ?>      
      <li class="divider-vertical"></li>
      <!--<a class="logout-saved" id="savedroutes">Saved Routes</a>-->
      <li id="savedroutes"><a>My Activity</a></li>
      <li id="registerLogin"><a href="/routescout/login/logout.php">Logout</a></li>
      <?php } ?>
  </ul>
  <div id="center-this-navbar">
    <div id="header">
        RouteScout
    </div>
</div>
</div>
</div>

<div id="user" style="display:none;"><?php echo $_SESSION['user']['username']; ?></div>

<div id="overall">
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
                
                <div id="inside">

                    <div class="container-fluid" id="containerfluid">
                        <br>
                        <h2>Possible Routes</h2><br>

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

                        <h2 style="text-align:center">Selected Route</h2>

                        <div id="directions_list" style="padding-top: 0px; padding-left: 30px; padding-bottom: 10px; padding-right: 10px;"></div>

                        <div class="row-fluid" id="bottom-buttons">
                            <div id="route-find" style="text-align:center; float: left; padding-left:20px; width: 150px;">
                                <button class="btn btn-large" data-target="#saveModal" data-toggle="modal" id="savedButton">Save Route</button>
                                <div class='save-alert' id="save-route-alert" style="display:none;">Successfully Saved!</div>
                            </div>

                            <div id="route-find" style="text-align:center">
                                <button class="btn btn-large" id="route-rate" type="button">Rate this Route</a></button>
                                </div>
                            </div>

                            <BR />
                            <BR />
                        </div>

                        <div id="rate-route" style="display:none">
                           <div class = "go-back" style="padding: 10px;">
                            <a id="back-to-nav"><img src="back-arrow.png"></a>
                        </div>

                        <h2 style="text-align:center">Rate this Route</h2>

                        <div style="padding-left: 50px; padding-top: 20px; padding-bottom: 20px;">
                           <div class="row-fluid">
                             <div class="span4"><h3>Safety:</h3></div>
                             <div class="span4 offset3 ">
                                 <div class="stars" id = "safety_rating"></div>
                             </div>
                         </div>


                         <div class="row-fluid">
                             <div class="span4"><h3>Efficiency</h3></div>
                             <div class="span4 offset3">
                                 <div class="stars" id = "efficiency_rating"></div>
                             </div>
                         </div>

                         <div class="row-fluid">
                             <div class="span4"><h3>Scenery</h3></div>
                             <div class="span4 offset2">
                                 <div class="stars" id = "scenery_rating"></div>
                             </div>
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
                            <h2><u>Saved Routes</h2>
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
            	<div id="green-buttons">
                    <a class="popup-button btn btn-success btn-large" id=
                    "report-button">Report Accident</a> <a class=
                    "popup-button btn btn-success btn-large" id=
                    "tip-button">Add Tip</a>
                </div>

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

            <div id="googleMap" style="width:700px;height:530px;">
            </div>
        </div><!--/.fluid-container-->
    </div>
</div>
</div>
</div>


 
</body>
</html>
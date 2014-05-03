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
    <link href="../files/style.css" rel="stylesheet">
    <link href="../files/bootstrap.css" rel="stylesheet">
    <link href="../files/example-fluid-layout.css" rel="stylesheet">
    <link href="../files/jquery-ui-1.10.4/themes/base/jquery-ui.css" rel="stylesheet"><!-- Load any supplemental Javascript libraries here -->

    <script src="../files/jquery.js"></script>
    <script src="../files/bootstrap-transition.js"></script>
    <script src="../files/bootstrap-alert.js"></script>
    <script src="../files/bootstrap-modal.js"></script>
    <script src="../files/bootstrap-dropdown.js"></script>
    <script src="../files/bootstrap-scrollspy.js"></script>
    <script src="../files/bootstrap-tab.js"></script>
    <script src="../files/bootstrap-tooltip.js"></script>
    <script src="../files/bootstrap-popover.js"></script>
    <script src="../files/bootstrap-button.js"></script>
    <script src="../files/bootstrap-collapse.js"></script>
    <script src="../files/bootstrap-carousel.js"></script>
    <script src="../files/bootstrap-typeahead.js"></script>
    <script src="../ratePlugin/jquery.raty.min.js"></script>
    <script src="../files/jquery-ui-1.10.4/ui/jquery-ui.js" type=
    "text/javascript"></script>
    <script src="../files/index.js"></script>
</head>

<body>
<h2>Sign up!!!!!</h2>



<?php 
   $path = $_SERVER['DOCUMENT_ROOT'];
   $path .= "/routescout/login/signup.php";
   include_once($path);


?>
<form action="../login/register.php" method="post"> 
    <label>Username:</label> 
    <input type="text" name="username" value="" /> 
    <label>Email:</label> 
    <input type="text" name="email" value="" /> 
    <label>Password:</label> 
    <input type="password" name="password" value="" /> <br /><br />
    <input type="submit" class="btn btn-info" value="Register" /> 
</form>
</body>
</html>

<?php

//pretty print
function pp($obj) {
  echo '<pre>';
  print_r($obj);
  echo '</pre>';
}

session_start();

pp("sessid cookie: " . $_COOKIE['PHPSESSID']);

if (session_id() == '') {
  pp("session id missing");
} else {
  pp("user:");
  pp($_SESSION['user']);
}

if (isset($_SESSION['user'])) {
  pp("logged in as " . $_SESSION['user']);
} else {
  pp("not logged in");
}

?>

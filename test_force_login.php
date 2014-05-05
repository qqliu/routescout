<?php

//pretty print
function pp($obj) {
  echo '<pre>';
  print_r($obj);
  echo '</pre>';
}

session_start();
//session_start();  //ok to do this twice
if (session_id() == '') {
  pp("started new session: " . session_id());
} else {
  pp("using existing session: " . session_id());
}

$_SESSION['user'] = $_REQUEST['user'];

pp("user: " . $_SESSION['user']);

?>

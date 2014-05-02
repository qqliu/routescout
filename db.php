<?php

/*
TODO (if time): to deal with malicious users/XSS:
  add email validation (so it's not javascript)
  add cgi.escape to responses
*/

$resp = array(
  "error" => ""
);

$db_link = NULL;

//pretty print
function pp($obj) {
  echo '<pre>';
  print_r($obj);
  echo '</pre>';
}

//resp functions

function has_error() {
  global $resp;
  return $resp["error"] !== "";
}

function on_error($msg) {
  global $resp;
  $resp["error"] = $msg;
}

//db functions

function db_connect() {
  global $db_link;
  if (is_null($db_link)) {
    $db_link = mysqli_connect("sql.mit.edu", "leoliu", "westbrooksql", "leoliu+rsdb1");
    if (!$db_link) {
      on_error("db_connect: " . mysqli_error($db_link));
    }
  }
  return $db_link;
}

//other stuff

//ensures user is logged in and returns his email
function ensure_logged_in() {
  return "kobe@mit.edu";
}

//ensures params exist and escapes them
function ensure_and_escape_params($params) {
  foreach ($params as $p) {
    if (!isset($_REQUEST[$p])) {
      on_error("missing param: $p");
      return;
    }
    if ($_REQUEST[$p] === "") {
      on_error("empty param: $p");
      return;
    }
    $_REQUEST[$p] = mysql_escape_string($_REQUEST[$p]);
  }
}

function dbtest() {
  $link = db_connect();
  $query = "select * from users where email = 'leoliu@mit.edu'";
  $result = $link->query($query);
  $row = mysqli_fetch_array($result);
  print_r($row);
  print "connected";
}

//example row insert
/*
  //insert row
  $first_name = mysql_escape_string($first_name);
  
  $link = db_connect();
  $query = "insert into users (first_name, last_name, email, password, salt) "
  . "VALUES ('$first_name','$last_name','$email','$hashed', '$salt')";
  $result = $link->query($query);
  
  //TODO: fix this...
  if (!$result) {
    return mysqli_error($link);
  }
*/

//example reading UID
/*
  $_SESSION['email'] = $email;
  if (session_id() != '') {
*/

/* these methods are called by main() after db is connected (also by main()) */

function update_ratings() {
  global $resp, $db_link;
  
  ensure_and_escape_params(array("route_key", "safety_key", "efficiency", "scenery"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //insert row
}

function save_routes() {
  global $resp, $db_link;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
}

function delete_saved_route() {
  global $resp, $db_link;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
}

function save_ta() {
  global $resp, $db_link;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
}

function delete_ta() {
  global $resp, $db_link;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
}

function edit_ta() {
  global $resp, $db_link;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
}

function get_all_tas() {
  global $resp, $db_link;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
}

function flag_ta() {
  global $resp, $db_link;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
}

function get_saved_routes() {
  global $resp, $db_link;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
}

function get_saved_route() {
  global $resp, $db_link;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
}

function get_average_ratings() {
  global $resp, $db_link;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
}

function main() {
  pp($_REQUEST);

  if (!isset($_REQUEST["op"])) {
    on_error("missing op");
  } else {
    db_connect();
    
    if (!has_error()) {
      $op = $_REQUEST["op"];
      switch ($op) {
        case "update_ratings": update_ratings(); break;
        case "save_routes": save_routes(); break;
        case "delete_saved_route": delete_saved_route(); break;
        case "save_ta": save_ta(); break;
        case "delete_ta": delete_ta(); break;
        case "edit_ta": edit_ta(); break;
        case "get_all_tas": get_all_tas(); break;
        case "flag_ta": flag_ta(); break;
        case "get_saved_routes": get_saved_routes(); break;
        case "get_saved_route": get_saved_route(); break;
        case "get_average_ratings": get_average_ratings(); break;
        //get 3 ratings for user
        //case "": break;
        default:
          on_error("invalid op");
      }
    }
  }
  
  global $resp;
  pp($resp);
}

main();

?>

blah blah blah
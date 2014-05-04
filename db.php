<?php

/*
TODO (if time): to deal with malicious users/XSS:
  add email validation (so it's not javascript)
  add cgi.escape to responses
*/

//global variables

$debug_force_verbose = False;
$debug_pretend_single_logged_in_user = False;

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

//print only if verbose param = 1
function pp_debug($obj) {
  if (isset($_SESSION['verbose']) && $_SESSION['verbose'] == '1') {
    pp($obj);
  }
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

//retuns mysql result object on success
//false on failure (and sets error in resp)
function db_query($query) {
  pp_debug($query);

  global $db_link;
  
  $result = $db_link->query($query);
  if (!$result) {
    on_error("db_query: $query " . mysqli_error($db_link));
    return False;
  }
  return $result;
}

//other stuff

//ensures user is logged in and returns his email
function ensure_logged_in() {
  global $debug_pretend_single_logged_in_user;
  if ($debug_pretend_single_logged_in_user) {
    return "kobe@mit.edu";
  }

  if (session_id() != '' && isset($_SESSION['email'])) {
    return $_SESSION['email'];
  } else {
    on_error("not logged in");
  }
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

//
//all operations below!!!
//these methods are called by main() after db is connected
//

function update_ratings() {
  global $resp;
  
  ensure_and_escape_params(array("route_key", "safety", "efficiency", "scenery"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  if ($_REQUEST["safety"] === "0" && $_REQUEST["efficiency"] === "0" && $_REQUEST["scenery"] === "0") {
    //delete row
    db_query("delete from ratings where user='$user' and route_key='{$_REQUEST["route_key"]}'");
  } else {
    //insert row
    db_query(
      "insert into ratings
      (user, route_key, safety, efficiency, scenery)
      values ('$user', '{$_REQUEST["route_key"]}', {$_REQUEST["safety"]}, {$_REQUEST["efficiency"]}, {$_REQUEST["scenery"]})
      on duplicate key update
      user='$user',
      route_key='{$_REQUEST["route_key"]}',
      safety={$_REQUEST["safety"]},
      efficiency={$_REQUEST["efficiency"]},
      scenery={$_REQUEST["scenery"]}
    ");
  }
}

function save_route() {
  global $resp;
  
  ensure_and_escape_params(array("route_key", "name", "from_loc", "to_loc", "route_index"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
    //insert row
    db_query(
      "insert into routes
      (user, route_key, name, from_loc, to_loc, route_index)
      values ('$user', '{$_REQUEST["route_key"]}', '{$_REQUEST["name"]}', '{$_REQUEST["from_loc"]}', '{$_REQUEST["to_loc"]}', {$_REQUEST["route_index"]})
      on duplicate key update
      user='$user',
      route_key='{$_REQUEST["route_key"]}',
      name='{$_REQUEST["name"]}',
      from_loc='{$_REQUEST["from_loc"]}',
      to_loc='{$_REQUEST["to_loc"]}',
      route_index={$_REQUEST["route_index"]}
    ");
}

function delete_saved_route() {
  global $resp;
  
  ensure_and_escape_params(array("route_key"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //delete row
  db_query("delete from routes where user='$user' and route_key='{$_REQUEST["route_key"]}'");
}

function save_ta() {
  global $resp;
  
  ensure_and_escape_params(array("kind", "id", "comment", "x", "y", "flagged"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //add row
  db_query(
    "insert into tips_and_accidents
    (user, kind, id, comment, x, y, flagged)
    values ('$user', {$_REQUEST["kind"]}, '{$_REQUEST["id"]}', '{$_REQUEST["comment"]}', '{$_REQUEST["x"]}', '{$_REQUEST["y"]}', {$_REQUEST["flagged"]})
    on duplicate key update
    user='$user',
    kind={$_REQUEST["kind"]},
    id='{$_REQUEST["id"]}',
    comment='{$_REQUEST["comment"]}',
    x='{$_REQUEST["x"]}',
    y='{$_REQUEST["y"]}',
    flagged={$_REQUEST["flagged"]}
  ");
}

function delete_ta() {
  global $resp;
  
  ensure_and_escape_params(array("kind", "id"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //delete row
  db_query("delete from tips_and_accidents where user='$user' and kind={$_REQUEST["kind"]} and id='{$_REQUEST["id"]}'");
}

function edit_ta() {
  global $resp;
  
  ensure_and_escape_params(array("kind", "id", "comment"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //edit row
  db_query("
    update tips_and_accidents
    set comment='{$_REQUEST["comment"]}'
    where user='$user' and kind={$_REQUEST["kind"]} and id='{$_REQUEST["id"]}'
  ");
}

function get_all_tas() {
  global $resp;
  
  ensure_and_escape_params(array("kind"));
  if (has_error()) return;
  
  $result = db_query("
    select * from tips_and_accidents
    where kind={$_REQUEST["kind"]}
  ");
  if (has_error()) return;
  
  if ($result === True) { //when testing, db_query() returns true
    return;
  }  
  $resp["data"] = array();
  while($row = mysqli_fetch_assoc($result)) {
    array_push($resp["data"], $row);
  }
}

//TODO
function flag_ta() {
  global $resp;
  
  ensure_and_escape_params(array("owner", "kind", "id"));
  if (has_error()) return;
  
  ensure_logged_in();
  if (has_error()) return;
  
  //flag that thing
  db_query("
    update tips_and_accidents
    set flagged=1
    where user='{$_REQUEST["owner"]}' and kind={$_REQUEST["kind"]} and id='{$_REQUEST["id"]}'
  ");
}

function get_saved_routes() {
  global $resp;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //get rows for that user
  $result = db_query("select * from routes where user='$user'");
  if (has_error()) return;
  
  if ($result === True) { //when testing, db_query() returns true
    return;
  }  
  $resp["data"] = array();
  while($row = mysqli_fetch_assoc($result)) {
    array_push($resp["data"], $row);
  }
}

function get_saved_route() {
  global $resp;
  
  ensure_and_escape_params(array("route_key"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  $result = db_query("select * from routes where user='$user' and route_key='{$_REQUEST["route_key"]}'");
  if (has_error()) return;
  
  if ($result === True) { //when testing, db_query() returns true
    return;
  }  
  $resp["data"] = mysqli_fetch_assoc($result);
}

function get_average_ratings() {
  global $resp;
  
  ensure_and_escape_params(array("route_key", "route_key"));
  if (has_error()) return;
  
  //get average ratings for particular route
  
  $resp["data"] = array();
  
  foreach (array("safety", "efficiency", "scenery") as $rating_type) {
    $result = db_query("
      select AVG($rating_type) from ratings
      where route_key='{$_REQUEST["route_key"]}' and $rating_type != 0
    ");
    if (has_error()) return;
    
    if ($result === True) { //when testing, db_query() returns true
      return;
    }
    $row = mysqli_fetch_assoc($result);
    $resp["data"][$rating_type] = $row;
  }
}

function main() {
  global $debug_force_verbose;
  if ($debug_force_verbose) {
    $_SESSION['verbose'] = 1;
  }

  pp_debug($_REQUEST);

  if (!isset($_REQUEST["op"])) {
    on_error("missing op");
  } else {
    db_connect();
    
    if (!has_error()) {
      $op = $_REQUEST["op"];
      switch ($op) {
        case "update_ratings": update_ratings(); break;
        case "save_route": save_route(); break;
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
  pp_debug($resp);
  
  print json_encode($resp);
}

main();

?>

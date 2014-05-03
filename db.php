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

//retuns mysql result object on success
//false on failure (and sets error in resp)
function db_query($query) {
  pp($query);
  return True;
/*
  global $db_link;
  
  $result = $db_link>query($query);
  if (!$result) {
    on_error("db_query: $query " . mysqli_error($db_link));
    return False;
  }
  return $result;
*/
}

//other stuff

//ensures user is logged in and returns his email
function ensure_logged_in() {
  return "kobe@mit.edu";

  if (session_id() !== '' && isset($_SESSION['email'])) {
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

//http://leoliu.scripts.mit.edu/routescout/db.php?op=update_ratings&route_key=key&safety=0&efficiency=0&scenery=1
function update_ratings() {
  global $resp;
  
  ensure_and_escape_params(array("route_key", "safety", "efficiency", "scenery"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  if ($_REQUEST["safety"] === "0" && $_REQUEST["efficiency"] === "0" && $_REQUEST["scenery"] === "0") {
    //delete row
    return db_query("delete from ratings where user='$user' and route_key='{$_REQUEST["route_key"]}'");
  } else {
    //insert row
    return db_query(
      "insert into ratings
      (user, route_key, safety, efficiency, scenery)
      values ('$user', '{$_REQUEST["route_key"]}', {$_REQUEST["safety"]}, {$_REQUEST["efficiency"]}, {$_REQUEST["scenery"]})
      on duplicate key update
      user='$user',
      route_key='{$_REQUEST["route_key"]}',
      safety={$_REQUEST["safety"]}'
      efficiency={$_REQUEST["efficiency"]},
      scenery={$_REQUEST["scenery"]}
    ");
  }
}

//http://leoliu.scripts.mit.edu/routescout/db.php?op=save_route&user=user&route_key=route_key&name=name&from_loc=from&to_loc=to&order=5
function save_route() {
  global $resp;
  
  ensure_and_escape_params(array("user", "route_key", "name", "from_loc", "to_loc", "order"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
    //insert row
    return db_query(
      "insert into routes
      (user, route_key, name, from_loc, to_loc, order)
      values ('$user', '{$_REQUEST["route_key"]}', '{$_REQUEST["name"]}', '{$_REQUEST["from_loc"]}', '{$_REQUEST["to_loc"]}', {$_REQUEST["order"]})
      on duplicate key update
      user='$user',
      route_key='{$_REQUEST["route_key"]}',
      name='{$_REQUEST["name"]}',
      from_loc='{$_REQUEST["from_loc"]}',
      to_loc='{$_REQUEST["to_loc"]}',
      order={$_REQUEST["order"]}
    ");
}

//http://leoliu.scripts.mit.edu/routescout/db.php?op=delete_saved_route&user=user&route_key=route
function delete_saved_route() {
  global $resp;
  
  ensure_and_escape_params(array("user", "route_key"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //delete row
  return db_query("delete from routes where user='$user' and route_key='{$_REQUEST["route_key"]}'");
}

//http://leoliu.scripts.mit.edu/routescout/db.php?op=save_ta&user=user&kind=0&id=id&comment=comment&x=x&y=y&flagged=0
function save_ta() {
  global $resp;
  
  ensure_and_escape_params(array("user", "kind", "id", "comment", "x", "y", "flagged"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //add row
  return db_query(
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
    flagged={$_REQUEST["flagged"]},
  ");
}

//http://leoliu.scripts.mit.edu/routescout/db.php?op=delete_ta&user=user&kind=0&id=id
function delete_ta() {
  global $resp;
  
  ensure_and_escape_params(array("user", "kind", "id"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //delete row
  return db_query("delete from tips_and_accidents where user='$user' and kind={$_REQUEST["kind"]} and id='{$_REQUEST["id"]}'");
}

//http://leoliu.scripts.mit.edu/routescout/db.php?op=edit_ta&user=user&kind=0&id=id&comment=comment
function edit_ta() {
  global $resp;
  
  ensure_and_escape_params(array("kind", "id", "comment"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //edit row
  return db_query("
    update tips_and_accidents
    set comment='{$_REQUEST["comment"]}';
    where user='$user' and kind={$_REQUEST["kind"]} and id='{$_REQUEST["id"]}'
  ");
}

function get_all_tas() {
  global $resp;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
  
  
}

function flag_ta() {
  global $resp;
  
  ensure_and_escape_params(array("kind", "id"));
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //flag that thing
  return db_query("
    update tips_and_accidents
    set flagged=1
    where user='$user' and kind={$_REQUEST["kind"]} and id='{$_REQUEST["id"]}'
  ");
}

function get_saved_routes() {
  global $resp;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
  
  $user = ensure_logged_in();
  if (has_error()) return;
  
  //get rows for that user
}

function get_saved_route() {
  global $resp;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
  
  
}

function get_average_ratings() {
  global $resp;
  
  ensure_and_escape_params(array());
  if (has_error()) return;
  
  //get average ratings for particular route
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
  pp($resp);
}

main();

?>

blah blah blah
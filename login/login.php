<?php

function hello($arg) {
  echo $arg;
}

$db_link = NULL;

function db_connect() {
  global $db_link;
  if (is_null($db_link)) {
    $db_link = mysqli_connect("sql.mit.edu", "leoliu", "westbrooksql", "leoliu+rsdb1") or die("Error " . mysqli_error($link));
  }
  return $db_link;
}

function dbtest() {
  $link = db_connect();
  $query = "select * from users where email = 'leoliu@mit.edu'";
  $result = $link->query($query);
  $row = mysqli_fetch_array($result);
  print_r($row);
  print "connected";
}

function randString($length) {
  //http://stackoverflow.com/questions/19017694/1line-php-random-string-generator
  return substr(str_shuffle(md5(time())),0,$length);
}

function validate($email, $password) {
  $email = mysql_escape_string($email);
  $password = mysql_escape_string($password);

  $link = db_connect();
  $query = "select * from users where email = '$email'";
  $result = $link->query($query);
  if ($result->num_rows == 0) {
    return false;
  }
  $row = mysqli_fetch_array($result);

  return $row['password'] === hash("sha256", $password . $row['salt']);
}

function addUser($first_name, $last_name, $email, $password) {
  if ($first_name === "" ||
      $last_name === "" ||
      $email === "" ||
      $password === "") {
    return "a required field is empty";
  }
  
  //insert row
  $first_name = mysql_escape_string($first_name);
  $last_name = mysql_escape_string($last_name);
  $email = mysql_escape_string($email);
  $password = mysql_escape_string($password);
  
  $salt = randString(16);
  $hashed = hash("sha256", $password . $salt);
  
  $link = db_connect();
  $query = "insert into users (first_name, last_name, email, password, salt) "
  . "VALUES ('$first_name','$last_name','$email','$hashed', '$salt')";
  $result = $link->query($query);
  
  //TODO: fix this...
  if (!$result) {
    print "damnnnnnnnn";
    return mysqli_error($link);
  }
  
  return "ok";
}

function login($email, $password) {
  //print "logging in with email=$email pass=$password";
  
  if (validate($email, $password)) {
    session_start();
    $_SESSION['email'] = $email;
    print "logged in as " . $_SESSION['email'];
  } else {
    print "fail";
  }
}

function register($first_name, $last_name, $email, $password) {
  //print "registering with first=$first_name last=$last_name email=$email pass=$password";
  $error = addUser($first_name, $last_name, $email, $password);
  if ($error === "ok") {
    login($email, $password);
  } else {
    print $error;
  }
}

function logout() {
  if (session_id() != '') {
    session_destroy();
  }
  print "Logged out";
}

function main() {
  $op = $_REQUEST['op'];
  
  if ($op == "Login") {
    login($_REQUEST['email'], $_REQUEST['password']);
  } else if ($op == "Register") {
    register($_REQUEST['first_name'], $_REQUEST['last_name'], $_REQUEST['email'], $_REQUEST['password']);
  } else if ($op == "Logout") {
    logout();
  } else {
    print "unsupported op";
    dbtest();
  }
}

main();

?>

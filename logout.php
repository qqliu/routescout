<?php 

session_start(); 
session_unset(); 
unset($_SESSION['user']);
session_destroy(); 
//TODO: delete PHPSESSID cookie
Header("Location: index.php"); 


?>
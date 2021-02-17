<?php
session_start();

require "Util.php";
$util = new Util();

require "Auth.php";
$auth = new Auth();

//remove cookies record from db
$auth->discardToken($_COOKIE["e_username"]);
// clear cookies
$util->clearAuthCookie();

//Clear Session
$_SESSION["e_member"] = "";
session_destroy();

header("Location: ./");
?>
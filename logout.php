<?php
include('server.php');
session_destroy();
    
if (ini_get("session.use_cookies")) {
   	$params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600*24, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}
echo 'You have been logged out. <a href="index.php">Go back</a>';
//header('location: index.php');
?>
<?php # PropServer logout script - logout.php
require('includes/config.inc.php');
$page_title = "Logout";
include('includes/header.html');

// If session variables do not exist, redirect the user:
if (!isset($_SESSION['user_id'])) {
	
	$url = BASE_URL . 'login.php';
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quite the script.

} else {

	$_SESSION = []; // Delete the variables.
	session_destroy(); // Delete the session.
	setcookie(session_name(), '', time()-3600); // Delete the cookie.

}

// Tell the user they are logged out:
echo "<h3>You are now logged out.</h3>";

include('includes/footer.html');
?>
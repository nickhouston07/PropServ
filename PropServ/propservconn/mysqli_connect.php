<?php # PropServ database connection script - mysqli_connect.php
// This file contains the database access information.
// This file also establishes a connection to MySQL
// and selects the database.

// Set the database access information as constants:
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_HOST', 'localhost');
define('DB_NAME', 'propserv');

// Make the connection:
$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// If no connection could be made, trigger an error:
if (!$dbc) {
	trigger_error('Could not connect to MySQL: ' . mysqli_connect_error() );
} else { // Otherwise, set the encoding:
	mysqli_set_charset($dbc, 'utf8');
}
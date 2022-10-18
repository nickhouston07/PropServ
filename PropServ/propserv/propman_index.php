<?php # PropServ Property Manager Home Page - propman_index.php
require('includes/config.inc.php');
$page_title = "Property Manager Home Page";
include('includes/header.html');

echo "<h1>Property Manager Home Page</h1>";
echo '<h5>Important Notifications</h5>';

require(MYSQL);

// Check to see if any renters are late paying rent
$q = "SELECT u.first_name, u.last_name, r.rent_owed, r.days_past_due FROM users u INNER JOIN rent r on u.user_id = r.user_id WHERE r.days_past_due > 0";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

// If 1 or more rows are returned display all the renters with a days_past_due value greater than 0
if (@mysqli_num_rows($r) > 0) {
	
	echo '<p class="notification">Late Renters</p><br>';

	// For each row check the days_past_due value and if it is greater than 0 notifiy the property manager
	//  they are late on their rent.
	while ($late_renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	if ($late_renters['days_past_due'] > 0) {
			echo '<p class="error">Renter ' . $late_renters['first_name'] . ' ' . $late_renters['last_name'] . ' is ' . $late_renters['days_past_due'] . ' days late and owes $' . $late_renters['rent_owed'] . '!</p>';
		}
	}

	echo '<br><br>';
}

// Check for submitted repair requests that require attention
$q = "SELECT u.first_name, u.last_name, r.repair_type, r.repair_comment, r.date_submitted FROM users u INNER JOIN repair r on u.user_id = r.user_id WHERE r.landlord_approval = 0 AND r.approved = 0 AND r.denied = 0";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

if (@mysqli_num_rows($r) > 0) {
	
	echo '<p class="notification">Repair Requests</p><br>';

	while ($repairs = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo '<p class="error">Renter ' . $repairs['first_name'] . ' ' . $repairs['last_name'] . ' submitted a repair request on ' . $repairs['date_submitted'] . '. The repair type is ' . $repairs['repair_type'] . '. The renter comment is: ' . $repairs['repair_comment'] . '</p>';
	}
}

mysqli_close($dbc);

include('includes/footer.html');
?>
<?php # PropServ Landlord Home Page - landlord_index.php
require('includes/config.inc.php');
$page_title = "Landlord Home Page";
include('includes/header.html');

echo "<h1>Landlord Home Page</h1>";
echo '<h5>Important Notifications</h5>';

require(MYSQL);

// Check to see if any renters are more than 15 days late paying rent
$q = "SELECT u.first_name, u.last_name, r.days_past_due from users u INNER JOIN rent r on u.user_id = r.user_id WHERE r.days_past_due > 0";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

// If 1 or more rows are returned display all the renters with a $days_past_due value greater than 15
if (@mysqli_num_rows($r) > 0) {

	echo '<p class="notification">Late Renters</p><br>';

	// For each row check the days_past_due value and if it is greater than 15 notifiy the landlord they are 
	// more than 15 days late on their rent.
	while ($late_renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		if ($late_renters['days_past_due'] > 15) {
			echo '<p class="error">Renter ' . $late_renters['first_name'] . ' ' . $late_renters['last_name'] . ' is ' . $late_renters['days_past_due'] . ' days late!</p>';
		}
	}

	echo '<br><br>';

}

// Check for renters whose lease ends in less than 3 months
$today = date("Y-m-d");
$q = "SELECT u.first_name, u.last_name, r.lease_end from users u INNER JOIN rent r on u.user_id = r.user_id WHERE DATE_SUB(r.lease_end, INTERVAL 3 MONTH) <= '$today'";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));


// If 1 or more rows are returned display all the renters whose lease_end is less than 3 months away
if (@mysqli_num_rows($r) > 0) {
	
	echo '<p class="notification">Expiring Lease Agreements</p><br>';

	// For each row display the lease information
	while ($lease_renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

		echo '<p class="error">The lease for renter ' . $lease_renters['first_name'] . ' ' . $lease_renters['last_name'] . ' ends in less than 3 months. The end date is ' . $lease_renters['lease_end'] . '.</p>';
	}

	echo "<br><br>";
}

// Check for repair requests sent by the property manager to the landlord
$q = "SELECT u.first_name, u.last_name, r.repair_type, r.repair_comment, r.date_submitted FROM users u INNER JOIN repair r on u.user_id = r.user_id WHERE r.landlord_approval = 1 AND r.approved = 0 AND r.denied = 0";
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
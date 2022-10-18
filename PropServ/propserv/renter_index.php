<?php # PropServ Renter Home Page - renter_index.php
require('includes/config.inc.php');
$page_title = "Renter Home Page";
include('includes/header.html');
$user_id = $_SESSION['user_id'];

echo "<h1>Renter Home Page</h1>";
echo '<h5>Important Notifications</h5>';

require(MYSQL);

$today = date("Y-m-d");

// Check to see if this user has paid rent this month and whether they are late or not
$q = "SELECT rent_owed, paid, due_date, days_past_due, lease_end FROM rent WHERE user_id='$user_id'";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

if (@mysqli_num_rows($r) == 1) {
	
	echo '<p class="notification">Rent</p><br>';

	// A match was found. Save the values
	$renter = mysqli_fetch_array($r, MYSQLI_ASSOC);

	// Check to see if they have paid rent
	if ($renter['paid'] == 1) {

		// They have paid rent. Notify them that no more rent is owed this month
		echo '<p>You have paid this months rent.</p><br>';

	} else {

		// They have not paid rent. Check to see if they are past due or not.
		if (!$renter['days_past_due'] > 0) {
			// They are not late on rent. Notify them how much they owe for the month and the due date.
			echo '<p>You owe $' . $renter['rent_owed'] . ' and it is due by ' . $renter['due_date'] . '.</p><br>';

		} else {
			// They are past due notify them how much they owe and how many days past due they are.
			echo '<p class="error">You owe $' . $renter['rent_owed'] . ' and you are ' . $renter['days_past_due'] . ' days late!</p><br>';
		}
	}

	// If the lease end is less than 3 months away notify the user
	$lease = date('Y-m-d', strtotime($renter['lease_end']. ' - 3 months'));
	if ($today >= $lease) {
		echo '<p class="error">Your lease ends in less than 3 months. The lease end date is ' . $renter['lease_end'] . '</p><br>';
	}
	
	echo '<br>';

}

// If repair request has been made display it and then notify them if it has been approved or not

$q = "SELECT * FROM repair WHERE user_id='$user_id'";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

if (@mysqli_num_rows($r) > 0) {
	
	echo '<p class="notification">Repair Requests</p><br>';

	// A match was found. Save the values
	$repair = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$date_submitted = date('Y-m-d', strtotime($repair['date_submitted']. ' + 1 month'));

	// Check to see if it has been approved or not
	if ($repair['approved'] == 1 AND $today < $date_submitted) { // It has been approved and it is less than a month old. Display it
		echo '<p>Your ' . $repair['repair_type'] . ' repair request. Submitted on ' . $repair['date_submitted'] . ' was approved on ' . $repair['date_approved'] . '.</p>';

	} elseif ($repair['approved'] == 0 AND $repair['denied'] == 0) { // It has not been approved or denied and is still ongoing. Display it
		echo '<p>Your ' . $repair['repair_type'] . ' repair request. Submitted on ' . $repair['date_submitted'] . ' is still undergoing the approval process.</p>';

	} elseif ($repair['approved'] == 0 AND $repair['denied'] == 1 AND $today < $date_submitted) { // It has been denied and it is less than a month old. Display it
		echo '<p class="error">Your ' . $repair['repair_type'] . ' repair request. Submitted on ' . $repair['date_submitted'] . ' was denied on ' . $repair['date_approved'] . '.</p>';
	}
}

mysqli_close($dbc);

include('includes/footer.html');
?>
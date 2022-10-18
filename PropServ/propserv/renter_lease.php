<?php # PropServ Renter Lease Agreement Page - renter_lease.php
require('includes/config.inc.php');
$page_title = "Renter Lease Agreement";
include('includes/header.html');
$user_id = $_SESSION['user_id'];

echo "<h1>Renter Lease Agreement</h1>";

require(MYSQL);

// Get this users lease information
$q = "SELECT rent_owed, paid, due_date, days_past_due, lease_start, lease_end, lease_renew FROM rent WHERE user_id='$user_id'";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

// Save the result
$renter = mysqli_fetch_array($r, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// In the database change lease_renew to 1

	$q = "UPDATE rent SET lease_renew = 1 WHERE user_id='$user_id'";
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

	// If successful notify the user otherwise explain why it was not
	if (mysqli_affected_rows($dbc) == 1) {
		
		echo '<p>Your landlord will be notified of your wish to renew your lease.</p>';
	} else {

		echo '<p class="error">Could not submit the request because:<br>' . mysqli_error($dbc) .
		'.</p><p>The query being run was: ' . $q . '</p>';
	}

} else {
	if (@mysqli_num_rows($r) == 1) {

		// If the lease end is less than 3 months away notify the user
		$today = date("Y-m-d");
		$lease = date('Y-m-d', strtotime($renter['lease_end']. ' - 3 months'));
		if ($today >= $lease) {

			echo '<p class="error">Your lease ends in less than 3 months. The lease started on ' . $renter['lease_start'] . '. The lease end date is ' . $renter['lease_end'] . '</p>';

		} else {

			echo '<p>Your lease started on ' . $renter['lease_start'] . '. The lease end date is ' . $renter['lease_end'] . '</p>';	

		}

		// Button for renter to indicate they wish to renew their lease but only show if they have not already decided to renew.
		if ($renter['lease_renew'] == 0) {

			// This renter has not yet indicated if they wish to renew their lease or not
			// Provide button to indicate they wish to renew their lease.
			echo '<br><p><form action="renter_lease.php" method="post">
			<p><input type="submit" name="submit" value="I wish to renew my lease."></p>
			</form>';
			
		}
	}

}


mysqli_close($dbc);

include('includes/footer.html');
?>
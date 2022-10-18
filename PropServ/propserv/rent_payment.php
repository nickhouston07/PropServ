<?php # PropServ Renter Home Page - renter_index.php
require('includes/config.inc.php');
$page_title = "Rent Payment";
include('includes/header.html');
$user_id = $_SESSION['user_id'];

echo "<h1>Rent Payment</h1>";

require(MYSQL);

// Check if this user has paid rent. If not display the amount owed, when it is due, and if it is late.
$q = "SELECT user_id, rent_owed, paid, due_date, days_past_due FROM rent WHERE user_id='$user_id'";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

// Save the result
$renter = mysqli_fetch_array($r, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// In the database change paid to 1, due_date to next month, and days_past_due to 0
	$next_due_date = date('Y-m-d', strtotime($renter['due_date']. ' + 1 month'));

	$q = "UPDATE rent SET paid = 1, due_date = '$next_due_date', days_past_due = 0 WHERE user_id='$user_id'";
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

	// If successful notify the user otherwise explain why it was not
	if (mysqli_affected_rows($dbc) == 1) {
		
		echo '<p>Your rent for this month has been paid.</p>';

		$today = date("Y-m-d");
		$due_date = $renter['due_date'];
		$q = "INSERT INTO rent_history (user_id, due_date, paid_date, amount, days_late) VALUES (".$renter['user_id'].", '$due_date', '$today', ".$renter['rent_owed'].", ".$renter['days_past_due'].")";
		mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

	} else {
		echo '<p class="error">Could not submit the request because:<br>' . mysqli_error($dbc) .
		'.</p><p>The query being run was: ' . $q . '</p>';
	}
	

} else {

	// If the rent has been paid notify them. If the rent has not been paid notify them how much they owe
	// and when it is due. If the rent is not paid and it is late notify them how much they owe and how late 
	// the rent payment is.
	if ($renter['paid'] == 1) {
		// Rent is paid
		echo '<p>Your rent for this month has been paid.</p>';

	} elseif ($renter['paid'] == 0 AND !$renter['days_past_due'] > 0) {
		// Rent is not paid, but not yet late.
		echo '<p>You owe $' . $renter['rent_owed'] . ' and it is due by ' . $renter['due_date'] . '.</p>';

	} elseif ($renter['paid'] == 0 AND $renter['days_past_due'] > 0) {
		// Rent is not paid and it is late.
		echo '<p class="error">You owe $' . $renter['rent_owed'] . ' and it is ' . $renter['days_past_due'] . ' days late.</p>';
	}

	// For unpaid renters, provide a way for them to pay rent and then update the database.
	if ($renter['paid'] == 0) {
		// Provide button for paying rent.
		echo '<br><p><form action="rent_payment.php" method="post">
		<p><input type="submit" name="submit" value="Pay Rent"></p>
		</form>';
	}
}

mysqli_close($dbc);

include('includes/footer.html');
?>
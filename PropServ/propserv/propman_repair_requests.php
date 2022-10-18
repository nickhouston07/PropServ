<?php # PropServ Property Manager Repair Requests - propman_repair_requests.php
require('includes/config.inc.php');
$page_title = "Property Manager Repair Requests";
include('includes/header.html');
$user_id = $_SESSION['user_id'];

echo "<h1>Repair Requests</h1>";

require(MYSQL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// Save approval type and repair_id
	$today = date("Y-m-d");
	$approval = $_POST['approval'];
	$repair_id = $_POST['repair_id'];

	if ($approval == 'approved') {
		// Create query to update table indiciating it has been approved
		$q = "UPDATE repair SET approved = 1, approved_by = '$user_id', date_approved = '$today' WHERE repair_id ='$repair_id'";

	} elseif ($approval == 'denied') {
		// Create query to update table indiciating it has been denied
		$q = "UPDATE repair SET denied = 1, approved_by = '$user_id', date_approved = '$today' WHERE repair_id ='$repair_id'";

	} elseif ($approval == 'landlord') {
		// Create query to update table indiciating it requries landlord approval
		$q = "UPDATE repair SET landlord_approval = 1 WHERE repair_id ='$repair_id'";
	}
	
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

	// If successful notify the user otherwise explain why it was not
	if (mysqli_affected_rows($dbc) == 1) {
		// Print a message:
		echo '<p>The repair request has been updated.</p>';
	} else {
		echo '<p class="error">Could not submit the request because:<br>' . mysqli_error($dbc) .
		'.</p><p>The query being run was: ' . $q . '</p>';
	}


	// Display repair requests again
	// Check for submitted repair requests and decide approve them or send them to landlord.
	$q = "SELECT u.first_name, u.last_name, r.repair_id, r.repair_type, r.repair_comment, r.date_submitted FROM users u INNER JOIN repair r on u.user_id = r.user_id WHERE r.landlord_approval = 0 AND r.approved = 0 AND r.denied = 0";
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

	if (@mysqli_num_rows($r) > 0) {
	
		echo '<p class="notification">Repair Requests</p><br>';

		while ($repairs = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			echo '<p class="error">Renter ' . $repairs['first_name'] . ' ' . $repairs['last_name'] . ' submitted a repair request on ' . $repairs['date_submitted'] . '. The repair type is ' . $repairs['repair_type'] . '. The renter comment is: ' . $repairs['repair_comment'] . '</p>';

			// Provide dropdown with 3 options. One option to indicate landlord approval is required, one to approve the request and one to deny the request.
			echo '
			<form action="propman_repair_requests.php" method="post">
				<select name="approval" id="approval">
					<option value="approved">Approved</option>
					<option value="denied">Denied</option>
					<option value="landlord">Landlord approval required</option>
				</select><br><br>
				<input type="hidden" id="repair_id" name="repair_id" value="' . $repairs['repair_id'] . '">
				<p><input type="submit" name="submit" value="Submit"></p>
			</form>';
			
		}
	}

} else {
	
	// Check for submitted repair requests and decide approve them or send them to landlord.
	$q = "SELECT u.first_name, u.last_name, r.repair_id, r.repair_type, r.repair_comment, r.date_submitted FROM users u INNER JOIN repair r on u.user_id = r.user_id WHERE r.landlord_approval = 0 AND r.approved = 0 AND r.denied = 0";
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

	if (@mysqli_num_rows($r) > 0) {
	
		echo '<p class="notification">Repair Requests</p><br>';

		while ($repairs = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			echo '<p class="error">Renter ' . $repairs['first_name'] . ' ' . $repairs['last_name'] . ' submitted a repair request on ' . $repairs['date_submitted'] . '. The repair type is ' . $repairs['repair_type'] . '. The renter comment is: ' . $repairs['repair_comment'] . '</p>';

			// Provide dropdown with 3 options. One option to indicate landlord approval is required, one to approve the request and one to deny the request.
			echo '
			<form action="propman_repair_requests.php" method="post">
				<select name="approval" id="approval">
					<option value="approved">Approved</option>
					<option value="denied">Denied</option>
					<option value="landlord">Landlord approval required</option>
				</select><br><br>
				<input type="hidden" id="repair_id" name="repair_id" value="' . $repairs['repair_id'] . '">
				<p><input type="submit" name="submit" value="Submit"></p>
			</form><br>';
			
		}
	} else {
		echo '<p class="notification">There are no pending repair requests</p><br>';
	}	
}

mysqli_close($dbc);

include('includes/footer.html');
?>
<?php # PropServ Renter Repair Request - request_repair.php
require('includes/config.inc.php');
$page_title = "Request Repair";
include('includes/header.html');
$user_id = $_SESSION['user_id'];

echo "<h1>Request Repair</h1>";

require(MYSQL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Insert the repair request to the repair table

	// Prepare the values for storing
	$repair_type = $_POST['repair_type'];
	$comment = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['comment'])));

	$q = "INSERT INTO repair (user_id, repair_type, repair_comment) VALUES ('$user_id', '$repair_type', '$comment')";
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

	// If successful notify the user otherwise explain why it was not
	if (mysqli_affected_rows($dbc) == 1) {
		
		echo '<p>Your request has been submitted.</p>';

	} else {
		echo '<p class="error">Could not submit the request because:<br>' . mysqli_error($dbc) .
		'.</p><p>The query being run was: ' . $q . '</p>';

	}

} else { // Display the repair request form

	echo '
	<form action="request_repair.php" method="post">
		<label for="repair_type">Repair Type:</label>
		<select name="repair_type" id="repair_type">
			<option value="Plumbing">Plumbing</option>
			<option value="Heating/AC">Heating/AC</option>
			<option value="Electrical">Electrical</option>
			<option value="Appliance">Appliance</option>
			<option value="Structural">Structural</option>
		</select><br><br>
		<p><label>Comments <textarea name="comment" rows="5" cols="30"></textarea></label></p>
		<p><input type="submit" name="submit" value="Submit Repair Request"></p>
	</form>';

}

mysqli_close($dbc);

include('includes/footer.html');
?>
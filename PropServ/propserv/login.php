<?php # PropServ login script - login.php
// This is the login page for the PropServ site
require('includes/config.inc.php');
$page_title = "Login";
include('includes/header.html');
require(MYSQL);

// This section simulates a nightly update script. When the login.php page is accessed all of the rows in the
// rent table are looped through and the due_date is checked agains the current date. If the current date is later
// than the due_date then the days_past_due column is updated with the amount of days late the rent is.
$q = "SELECT * FROM rent";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

$today = new DateTime();
// Loop through all renters. Check if todays date is later than due_date if so then update days_past_due
while ($renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$due_date = DateTime::createFromFormat('Y-m-d', $renters['due_date']);
	if ($today > $due_date) {
		// Figure out how late they are 
		$days = $today->diff($due_date);
		$days_late = $days->format('%a');

		// Update days_past_due to show the new value
		$q = "UPDATE rent SET days_past_due = '$days_late' WHERE renter_id='".$renters['renter_id']."'";
		mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

	} 
}


// This poriton of the nightly update script checks to see if it is the first day of the month. If so change paid value for all renters to 0
$first = date('Y-m-01');
$today2 = date("Y-m-d");

if ($today2 == $first) {
	// Update paid to 0 for all renters
	$firstq = "UPDATE rent SET paid = 0";
	mysqli_query($dbc, $firstq) or trigger_error("Query: $firstq\n<br>MySQL Error: " . mysqli_error($dbc));
}
// End of nightly update script


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Validate the email address:
	if (!empty($_POST['email'])) {
		$e = mysqli_real_escape_string($dbc, $_POST['email']);
	} else {
		$e = FALSE;
		echo '<p class="error">You forgot to enter your email address!</p>';
	}

	// Validate the password:
	if (!empty($_POST['pass'])) {
		$p = trim($_POST['pass']);
	} else {
		$p = FALSE;
		echo '<p class="error">You forgot to enter your password!</p>';
	}

	if ($e && $p) { // If everything's OK.
		
		// Query the database:
		$q = "SELECT user_id, first_name, user_level, pass FROM users WHERE email='$e'";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

		if (@mysqli_num_rows($r) == 1) { // A match was made
			
			// Fetch the values:
			list($user_id, $first_name, $user_level, $pass) = mysqli_fetch_array($r, MYSQLI_NUM);
			mysqli_free_result($r);

			// Check the password:
			if (password_verify($p, $pass)) { // password_verify($p, $pass)
				
				// Store the info in the session:
				$_SESSION['user_id'] = $user_id;
				$_SESSION['first_name'] = $first_name;
				$_SESSION['user_level'] = $user_level;
				mysqli_close($dbc);

				// Redirect the user:
				// If they are a landlord:
				if ($user_level == 1) {
					$url = BASE_URL . 'landlord_index.php'; 
				} elseif ($user_level == 2) {
					$url = BASE_URL . 'propman_index.php'; 
				} elseif ($user_level == 3) {
					$url = BASE_URL . 'renter_index.php'; 
				}

				ob_end_clean(); // Delete the buffer.
				header("Location: $url");
				exit(); // Quit the script.

			} else {

				echo '<p class="error">The email address and password entered do not match those on file.</p>';
			}

		} else { // No match was made.
			echo '<p class="error">Please try again.</p>';
		}

	} else { // If everything wasn't OK.
		echo '<p class="error">Please try again.</p>';
	}

	mysqli_close($dbc);

} // End of SUBMIT conditional.
?>

<h1>Login</h1>
<form action="login.php" method="post">
	<fieldset>
	<p><strong>Email Address:</strong> <input type="email" name="email" size="20" maxlength="60"></p>
	<p><strong>Password:</strong> <input type="password" name="pass" size="20"></p>
	<div align="center"><input type="submit" name="submit" value="Login"></div>
	</fieldset>
</form>


<?php include('includes/footer.html'); ?>
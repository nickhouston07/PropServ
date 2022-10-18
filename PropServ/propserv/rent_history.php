<?php # PropServ Landlord Rent History - rent_history.php
require('includes/config.inc.php');
$page_title = "Rent History";
include('includes/header.html');

echo "<h1>Rent History</h1>";

require(MYSQL);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// Display repair history for selected renter
	if ($_POST['renter'] == 'All') {

		// Grab first and last name from user table and all relevant data from rent_history table
		$q = "SELECT u.first_name, u.last_name, r.due_date, r.paid_date, r.amount, r.days_late  from users u INNER JOIN rent_history r on u.user_id = r.user_id ORDER BY r.user_id";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

		if (@mysqli_num_rows($r) > 0) { // If at least one record was returned

			// Table header
			echo '<table width="60%">
			<thead>
			<tr>
				<th align="left"><strong>Name</strong></th>
				<th align="left"><strong>Date Due</strong></th>
				<th align="left"><strong>Date Paid</strong></th>
				<th align="left"><strong>Amount</strong></th>
				<th align="left"><strong>Days Late</strong></th>
			</tr>
			</thead>
			<tbody>
			';

			$bg = '#eeeeee'; // Set the initial background color.

			while ($renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

				$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.

				echo '<tr bgcolor="' . $bg . '">
						<td align="left">' . $renters['first_name'] . ' ' . $renters['last_name'] . '</td>
						<td align="left">' . $renters['due_date'] . '</td>
						<td align="left">' . $renters['paid_date'] . '</td>
						<td align="left">$' . $renters['amount'] . '</td>
						<td align="left">' . $renters['days_late'] . '</td>
					</tr>
					';
			} 

			echo '</tbody></table>';

		} else { // If no records were returned
			echo '<p>There is no rent history for any renter.</p>';
		}

		
	} else { // A specific renter was selected not all of them

		// Grab first and last name from user table and all relevant data from repair table
		$q = "SELECT u.first_name, u.last_name, r.due_date, r.paid_date, r.amount, r.days_late  from users u INNER JOIN rent_history r on u.user_id = r.user_id WHERE r.user_id='".$_POST['renter']."'";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

		if (@mysqli_num_rows($r) > 0) { // If at least one record was returned
			
			// Table header
			echo '<table width="60%">
			<thead>
			<tr>
				<th align="left"><strong>Name</strong></th>
				<th align="left"><strong>Date Due</strong></th>
				<th align="left"><strong>Date Paid</strong></th>
				<th align="left"><strong>Amount</strong></th>
				<th align="left"><strong>Days Late</strong></th>
			</tr>
			</thead>
			<tbody>
			';

			$bg = '#eeeeee'; // Set the initial background color.

			while ($renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

				$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.

				echo '<tr bgcolor="' . $bg . '">
						<td align="left">' . $renters['first_name'] . ' ' . $renters['last_name'] . '</td>
						<td align="left">' . $renters['due_date'] . '</td>
						<td align="left">' . $renters['paid_date'] . '</td>
						<td align="left">$' . $renters['amount'] . '</td>
						<td align="left">' . $renters['days_late'] . '</td>
					</tr>
					';
			}

			echo '</tbody></table>';

		} else { // If no records were returned
			echo '<p>There is no rent history for this renter.</p>';
		}

		
	}

	echo '<br><br>';

	// Select statement that grabs each user 
	$q = "SELECT user_id, first_name, last_name from users WHERE user_level = 3";
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));


	if (@mysqli_num_rows($r) > 0) {

		echo '
		<form action="rent_history.php" method="post">
			<label for="renter">Renter:</label>
			<select name="renter" id="renter">
			<option value="All">All</option>';

		// For each renter create an option in the dropdown to select that renter
		while ($renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$name = $renters['first_name'] . ' ' . $renters['last_name'];
			echo "<option value='".$renters['user_id']."'>{$name}</option>";
		}

		echo '
			</select><br><br>
			<p><input type="submit" name="submit" value="Submit"></p>
		</form>';

	}
	

} else {

	// Select statement that grabs each user 
	$q = "SELECT user_id, first_name, last_name from users WHERE user_level = 3";
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));


	if (@mysqli_num_rows($r) > 0) {

		echo '
		<form action="rent_history.php" method="post">
			<label for="renter">Renter:</label>
			<select name="renter" id="renter">
			<option value="All">All</option>';

		// For each renter create an option in the dropdown to select that renter
		while ($renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$name = $renters['first_name'] . ' ' . $renters['last_name'];
			echo "<option value='".$renters['user_id']."'>{$name}</option>";
		}

		echo '
			</select><br><br>
			<p><input type="submit" name="submit" value="Submit"></p>
		</form>';

	}

}

mysqli_close($dbc);

include('includes/footer.html');
?>
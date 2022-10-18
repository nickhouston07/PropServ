<?php # PropServ Landlord Repair History - repair_history.php
require('includes/config.inc.php');
$page_title = "Repair History";
include('includes/header.html');

echo "<h1>Repair History</h1>";

require(MYSQL);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// Display repair history
	if ($_POST['renter'] == 'All') { // Show the repair history for all renters

		// Grab first and last name from user table and all relevant data from repair table
		$q = "SELECT u.first_name, u.last_name, r.user_id, r.repair_type, r.repair_comment, r.date_submitted, r.approved, r.denied, r.approved_by, r.date_approved from users u INNER JOIN repair r on u.user_id = r.user_id ORDER BY r.user_id";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

		if (@mysqli_num_rows($r) > 0) { // If at least one record was returned

			// Table header
			echo '<table width="60%">
			<thead>
			<tr>
				<th align="left"><strong>Name</strong></th>
				<th align="left"><strong>Submitted On</strong></th>
				<th align="left"><strong>Repair Type</strong></th>
				<th align="left"><strong>Comment</strong></th>
				<th align="left"><strong>Approved/Denied</strong></th>
				<th align="left"><strong>Approved/Denied Date</strong></th>
				<th align="left"><strong>Approved/Denied By</strong></th>
			</tr>
			</thead>
			<tbody>
			';

			$bg = '#eeeeee'; // Set the initial background color.

			while ($repairs = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

				$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.

				if ($repairs['approved'] == 1 AND $repairs['denied'] == 0) {// If it was approved

					if ($repairs['approved_by'] == 2) { // If it was approved by the property manager
						
						echo '<tr bgcolor="' . $bg . '">
							<td align="left">' . $repairs['first_name'] . ' ' . $repairs['last_name'] . '</td>
							<td align="left">' . $repairs['date_submitted'] . '</td>
							<td align="left">' . $repairs['repair_type'] . '</td>
							<td align="left">' . $repairs['repair_comment'] . '</td>
							<td align="left">Approved</td>
							<td align="left">' . $repairs['date_approved'] . '</td>
							<td align="left">Property Manager</td>
						</tr>
						';

					} elseif ($repairs['approved_by'] == 1) { // If it was approved by the landlord
						
						echo '<tr bgcolor="' . $bg . '">
							<td align="left">' . $repairs['first_name'] . ' ' . $repairs['last_name'] . '</td>
							<td align="left">' . $repairs['date_submitted'] . '</td>
							<td align="left">' . $repairs['repair_type'] . '</td>
							<td align="left">' . $repairs['repair_comment'] . '</td>
							<td align="left">Approved</td>
							<td align="left">' . $repairs['date_approved'] . '</td>
							<td align="left">Landlord</td>
						</tr>
						';

					}

				} elseif ($repairs['approved'] == 0 AND $repairs['denied'] == 1) { // If it was denied
					if ($repairs['approved_by'] == 2) { // If it was denied by the property manager
						
						echo '<tr bgcolor="' . $bg . '">
							<td align="left">' . $repairs['first_name'] . ' ' . $repairs['last_name'] . '</td>
							<td align="left">' . $repairs['date_submitted'] . '</td>
							<td align="left">' . $repairs['repair_type'] . '</td>
							<td align="left">' . $repairs['repair_comment'] . '</td>
							<td align="left">Denied</td>
							<td align="left">' . $repairs['date_approved'] . '</td>
							<td align="left">Property Manager</td>
						</tr>
						';

					} elseif ($repairs['approved_by'] == 1) { // If it was denied by the landlord
						
						echo '<tr bgcolor="' . $bg . '">
							<td align="left">' . $repairs['first_name'] . ' ' . $repairs['last_name'] . '</td>
							<td align="left">' . $repairs['date_submitted'] . '</td>
							<td align="left">' . $repairs['repair_type'] . '</td>
							<td align="left">' . $repairs['repair_comment'] . '</td>
							<td align="left">Denied</td>
							<td align="left">' . $repairs['date_approved'] . '</td>
							<td align="left">Landlord</td>
						</tr>
						';

					}
				}

			}

			echo '</tbody></table>';

		} else { // If no records were returned
			echo '<p>There is no repair history for any renter.</p>';
		}

		
	} else { // A specific renter was selected, not all of them

		// Grab first and last name from user table and all relevant data from repair table
		$q = "SELECT u.first_name, u.last_name, r.user_id, r.repair_type, r.repair_comment, r.date_submitted, r.approved, r.denied, r.approved_by, r.date_approved from users u INNER JOIN repair r on u.user_id = r.user_id WHERE r.user_id='".$_POST['renter']."'";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

		if (@mysqli_num_rows($r) > 0) { // If at least one record was returned
			
			// Table header
			echo '<table width="60%">
			<thead>
			<tr>
				<th align="left"><strong>Name</strong></th>
				<th align="left"><strong>Submitted On</strong></th>
				<th align="left"><strong>Repair Type</strong></th>
				<th align="left"><strong>Comment</strong></th>
				<th align="left"><strong>Approved/Denied</strong></th>
				<th align="left"><strong>Decision Date</strong></th>
				<th align="left"><strong>Decision By</strong></th>
			</tr>
			</thead>
			<tbody>
			';

			$bg = '#eeeeee'; // Set the initial background color.

			while ($repairs = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

				$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.

				if ($repairs['approved'] == 1 AND $repairs['denied'] == 0) {// If it was approved

					if ($repairs['approved_by'] == 2) { // If it was approved by the property manager	
						
						echo '<tr bgcolor="' . $bg . '">
							<td align="left">' . $repairs['first_name'] . ' ' . $repairs['last_name'] . '</td>
							<td align="left">' . $repairs['date_submitted'] . '</td>
							<td align="left">' . $repairs['repair_type'] . '</td>
							<td align="left">' . $repairs['repair_comment'] . '</td>
							<td align="left">Approved</td>
							<td align="left">' . $repairs['date_approved'] . '</td>
							<td align="left">Property Manager</td>
						</tr>
						';

					} elseif ($repairs['approved_by'] == 1) { // If it was approved by the landlord
						
						echo '<tr bgcolor="' . $bg . '">
							<td align="left">' . $repairs['first_name'] . ' ' . $repairs['last_name'] . '</td>
							<td align="left">' . $repairs['date_submitted'] . '</td>
							<td align="left">' . $repairs['repair_type'] . '</td>
							<td align="left">' . $repairs['repair_comment'] . '</td>
							<td align="left">Approved</td>
							<td align="left">' . $repairs['date_approved'] . '</td>
							<td align="left">Landlord</td>
						</tr>
						';

					}

				} elseif ($repairs['approved'] == 0 AND $repairs['denied'] == 1) { // If it was denied
					if ($repairs['approved_by'] == 2) { // If it was denied by the property manager
						
						echo '<tr bgcolor="' . $bg . '">
							<td align="left">' . $repairs['first_name'] . ' ' . $repairs['last_name'] . '</td>
							<td align="left">' . $repairs['date_submitted'] . '</td>
							<td align="left">' . $repairs['repair_type'] . '</td>
							<td align="left">' . $repairs['repair_comment'] . '</td>
							<td align="left">Denied</td>
							<td align="left">' . $repairs['date_approved'] . '</td>
							<td align="left">Property Manager</td>
						</tr>
						';

					} elseif ($repairs['approved_by'] == 1) { // If it was denied by the landlord
						
						echo '<tr bgcolor="' . $bg . '">
							<td align="left">' . $repairs['first_name'] . ' ' . $repairs['last_name'] . '</td>
							<td align="left">' . $repairs['date_submitted'] . '</td>
							<td align="left">' . $repairs['repair_type'] . '</td>
							<td align="left">' . $repairs['repair_comment'] . '</td>
							<td align="left">Denied</td>
							<td align="left">' . $repairs['date_approved'] . '</td>
							<td align="left">Landlord</td>
						</tr>
						';

					}
				}
			}

			echo '</tbody></table>';

		} else { // If no records were returned
			echo '<p>There is no repair history for this renter.</p>';
		}

		
	}

	echo '<br><br>';

	// Select statement that grabs each renter
	$q = "SELECT user_id, first_name, last_name from users WHERE user_level = 3";
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));


	if (@mysqli_num_rows($r) > 0) {

		echo '
		<form action="repair_history.php" method="post">
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

	// Select statement that grabs each renter
	$q = "SELECT user_id, first_name, last_name from users WHERE user_level = 3";
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));


	if (@mysqli_num_rows($r) > 0) {

		echo '
		<form action="repair_history.php" method="post">
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
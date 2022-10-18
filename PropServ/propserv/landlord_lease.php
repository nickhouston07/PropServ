<?php # PropServ Landlord Lease Agreement Page - landlord_lease.php
require('includes/config.inc.php');
$page_title = "Landlord Lease Agreements";
include('includes/header.html');

echo "<h1>Landlord Lease Agreements</h1>";

require(MYSQL);

// Check for renters whose lease ends in less than 3 months
$today = date("Y-m-d");
$q = "SELECT u.first_name, u.last_name, r.lease_start, r.lease_end, r.lease_renew FROM users u INNER JOIN rent r on u.user_id = r.user_id WHERE DATE_SUB(r.lease_end, INTERVAL 3 MONTH) <= '$today'";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

// If 1 or more rows are returned display all the renters whose $lease_end is less than 3 months away
if (@mysqli_num_rows($r) > 0) {
	
	echo '<p class="notification">Expiring Lease Agreements</p><br>';

	// Table header
	echo '<table width="60%">
	<thead>
	<tr>
		<th align="left"><strong>Name</strong></th>
		<th align="left"><strong>Start Date</strong></th>
		<th align="left"><strong>End Date</strong></th>
		<th align="left"><strong>Renew</strong></th>
	</tr>
	</thead>
	<tbody>
	';

	$bg = '#eeeeee'; // Set the initial background color.

	// For each row display the lease information
	while ($lease_renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

		$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.

		// If they have not indicated they wish to renew the lease
		if ($lease_renters['lease_renew'] == 0) {
			
			echo '<tr bgcolor="' . $bg . '">
				<td align="left">' . $lease_renters['first_name'] . ' ' . $lease_renters['last_name'] . '</td>
				<td align="left">' . $lease_renters['lease_start'] . '</td>
				<td align="left">' . $lease_renters['lease_end'] . '</td>
				<td align="left"></td>
			</tr>
			';


		// If they have indicated they wish to renew the lease
		} elseif ($lease_renters['lease_renew'] == 1) {
			
			echo '<tr bgcolor="' . $bg . '">
				<td align="left">' . $lease_renters['first_name'] . ' ' . $lease_renters['last_name'] . '</td>
				<td align="left">' . $lease_renters['lease_start'] . '</td>
				<td align="left">' . $lease_renters['lease_end'] . '</td>
				<td align="left">Yes</td>
			</tr>
			';

		}
	}

	echo '</tbody></table>';
}

echo '<br><br>';

// Check for renters whose lease is not ending soon
$q = "SELECT u.first_name, u.last_name, r.lease_start, r.lease_end, r.lease_renew FROM users u INNER JOIN rent r on u.user_id = r.user_id WHERE DATE_SUB(r.lease_end, INTERVAL 3 MONTH) > '$today'";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

// If 1 or more rows are returned display all the renters whose lease_end is less than 3 months away
if (@mysqli_num_rows($r) > 0) {
	
	echo '<p class="notification">Lease Agreements</p><br>';

	// Table header
	echo '<table width="60%">
	<thead>
	<tr>
		<th align="left"><strong>Name</strong></th>
		<th align="left"><strong>Start Date</strong></th>
		<th align="left"><strong>End Date</strong></th>
		<th align="left"><strong>Renew</strong></th>
	</tr>
	</thead>
	<tbody>
	';

	$bg = '#eeeeee'; // Set the initial background color.

	// For each row display the lease information
	while ($lease_renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

		// If they have not indicated they wish to renew the lease
		if ($lease_renters['lease_renew'] == 0) {
			
			echo '<tr bgcolor="' . $bg . '">
				<td align="left">' . $lease_renters['first_name'] . ' ' . $lease_renters['last_name'] . '</td>
				<td align="left">' . $lease_renters['lease_start'] . '</td>
				<td align="left">' . $lease_renters['lease_end'] . '</td>
				<td align="left"></td>
			</tr>
			';

		// If they have indicated they wish to renew the lease
		} elseif ($lease_renters['lease_renew'] == 1) {
			
			echo '<tr bgcolor="' . $bg . '">
				<td align="left">' . $lease_renters['first_name'] . ' ' . $lease_renters['last_name'] . '</td>
				<td align="left">' . $lease_renters['lease_start'] . '</td>
				<td align="left">' . $lease_renters['lease_end'] . '</td>
				<td align="left">Yes</td>
			</tr>
			';

		}
	}

	echo '</tbody></table>';
}

mysqli_close($dbc);

include('includes/footer.html');
?>
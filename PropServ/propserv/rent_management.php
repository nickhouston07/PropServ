<?php # PropServ Property Manager Home Page - propman_index.php
require('includes/config.inc.php');
$page_title = "Rent Management";
include('includes/header.html');

echo "<h1>Rent Management</h1>";
echo '<h5>Late Renters</h5>';

require(MYSQL);

// Check to see if any renters are late paying rent
$q = "SELECT u.first_name, u.last_name, r.rent_owed, r.days_past_due FROM users u INNER JOIN rent r on u.user_id = r.user_id WHERE r.days_past_due > 0";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

// If 1 or more rows are returned display all the renters with a days_past_due value greater than 0
if (@mysqli_num_rows($r) > 0) {
	
	// Table header
	echo '<table width="60%">
	<thead>
	<tr>
		<th align="left"><strong>Name</strong></th>
		<th align="left"><strong>Paid</strong></th>
		<th align="left"><strong>Days Late</strong></th>
		<th align="left"><strong>Amount</strong></th>
	</tr>
	</thead>
	<tbody>
	';

	$bg = '#eeeeee'; // Set the initial background color.

	// For each row check the days_past_due value and if it is greater than 0 notifiy the property manager
	//  they are late on their rent.
	while ($late_renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

		$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.

		if ($late_renters['days_past_due'] > 0) {
			
			echo '<tr bgcolor="' . $bg . '">
				<td align="left">' . $late_renters['first_name'] . ' ' . $late_renters['last_name'] . '</td>
				<td align="left">No</td>
				<td align="left">' . $late_renters['days_past_due'] . '</td>
				<td align="left">$' . $late_renters['rent_owed'] . '</td>
			</tr>
			';

		}
	}

	echo '</tbody></table>';
}

echo '<br>';

echo '<h5>Unpaid Renters</h5>';

// Check to see if any renters have not paid but are not yet late.
$q = "SELECT u.first_name, u.last_name, r.rent_owed FROM users u INNER JOIN rent r on u.user_id = r.user_id WHERE r.paid = 0 AND r.days_past_due = 0";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

// If 1 or more rows are returned display all the renters with a paid value of 0 and a
// days_past_due value of 0
if (@mysqli_num_rows($r) > 0) {
	
	// Table header
	echo '<table width="60%">
	<thead>
	<tr>
		<th align="left"><strong>Name</strong></th>
		<th align="left"><strong>Paid</strong></th>
		<th align="left"><strong>Amount</strong></th>
	</tr>
	</thead>
	<tbody>
	';

	// Display all of the renters have not paid this month but are not late
	while ($unpaid_renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {

		$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
		
		echo '<tr bgcolor="' . $bg . '">
			<td align="left">' . $unpaid_renters['first_name'] . ' ' . $unpaid_renters['last_name'] . '</td>
			<td align="left">Yes</td>
			<td align="left">$' . $unpaid_renters['rent_owed'] . '</td>
		</tr>
		';
	}

	echo '</tbody></table>';
}

echo '<br>';

echo '<h5>Paid Renters</h5>';
// Check to see if any renters have paid their rent.
$q = "SELECT u.first_name, u.last_name, r.rent_owed FROM users u INNER JOIN rent r on u.user_id = r.user_id WHERE r.paid = 1";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

// If 1 or more rows are returned display all the renters with a paid value of 1
if (@mysqli_num_rows($r) > 0) {
	
	
	// Table header
	echo '<table width="60%">
	<thead>
	<tr>
		<th align="left"><strong>Name</strong></th>
		<th align="left"><strong>Paid</strong></th>
		<th align="left"><strong>Amount</strong></th>
	</tr>
	</thead>
	<tbody>
	';

	// Display all of the renters who have paid this month.
	while ($paid_renters = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		
		$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
		
		echo '<tr bgcolor="' . $bg . '">
			<td align="left">' . $paid_renters['first_name'] . ' ' . $paid_renters['last_name'] . '</td>
			<td align="left">Yes</td>
			<td align="left">$' . $paid_renters['rent_owed'] . '</td>
		</tr>
		';
	}

	echo '</tbody></table>';
}

mysqli_close($dbc);

include('includes/footer.html');
?>
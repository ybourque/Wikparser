<?php
/***********************************************************************************/
// Change the values for these variables according to the naming scheme of your own
// SQL copy of Wiktionary.
/***********************************************************************************/
	$dbHost = 'hostname'; // e.g. localhost
	$dbUser = 'username';
	$dbPass = 'password';
	$dbName = 'database';
	
/***********************************************************************************/
	$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
	
	if ($conn->connect_errno > 0) {
		die("Unable to connect to database [".$conn->connect_error."].");
	}
	
	mysqli_set_charset($conn, 'utf8');
/***********************************************************************************/
?>
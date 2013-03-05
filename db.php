<?php
	$username = 'undecided_php';
	$password = 'sp13cs411';
	try {
		$conn = new PDO('mysql:host=engr-cpanel-mysql.engr.illinois.edu;dbname=undecided_main', $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo 'Connection is good!';
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}

?>
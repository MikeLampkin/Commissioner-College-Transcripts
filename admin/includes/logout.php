<?php

echo '<br><br><br><h1>LOGGING OUT </h1>';

		if( !isset($_SESSION) ) { session_start(); }

		// Unset all of the session variables
		$_SESSION = array();

		// Destroy the session.
		session_destroy();

		$_SESSION['loggedin'] = false;
		// Redirect to login page
		header("location: https://commissionercollege.com/admin/");

?>

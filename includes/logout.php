<?php
	if( strpos($url,'logout') !== false )
	{
		if(!isset($_SESSION) ) { session_start(); }

		$return_to = isset($_SESSION['referring_url']) ? $_SESSION['referring_url'] : 'index.php';

		$_SESSION = array();

		//! LOGOUT
			$authenticate_array = array(
			'access_token',
			'admin_userID',
			'login_first_name',
			'login_last_name',
			'login_email',
			'loggedin',
			'my_initials',
		);

		$access_token = $_SESSION['access_token'] = '';
		foreach( $authenticate_array AS $auth )
		{ unset($_SESSION[$auth]); $$auth = ''; }

		//! Auth0
		// $auth0->logout();
		// $return_to = 'http://' . $_SERVER['HTTP_HOST'];

		// $logout_url = sprintf('http://%s/v2/logout?client_id=%s&returnTo=%s', 'dev-8apclk5e.us.auth0.com', 'G1fl9RGDlQPjFGdSwQwFHQ7UoiBm0DCq', $return_to);
		$logout_url = $return_to;
		header('Location: ' . $logout_url);
		die();


		// echo ' <script> setTimeout(function(){ window.location.href = "' . $_SESSION['referring_url'] . '"; }, 00); </script> ';
		// // header("location:" . $_SESSION['referring_url']);
		// exit();



	// // Initialize the session
	// session_start();

	// // Unset all of the session variables
	// $_SESSION = array();

	// // Destroy the session.
	// session_destroy();

	// // Redirect to login page
	// header("location: includes/login.php");
	// exit;
	}
?>

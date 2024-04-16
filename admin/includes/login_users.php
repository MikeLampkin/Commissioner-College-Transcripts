<?php
//! VERSION 4.0
//! FOR ADMIN USERS
//! WITH JQUERY

	session_start();

	error_reporting(E_ALL);
	ini_set('display_errors', 'On');

	include $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';

	$var_user_email = 'user_email';
	$var_user_username = 'user_login';

	$referring_url = $_GET['referring_url'] ?? $_POST['referring_url'] ?? $_SESSION['referring_url'] ?? '../index.php';

	$referring_url_array = explode('?',$referring_url);
	$referring_url =  is_array($referring_url_array) ? $referring_url_array[0] : $referring_url_array;

	// Here is the login from the login.php
	if( isset($_POST['formlogin']) )
	{
		$username = htmlspecialchars(trim($_POST['formlogin']));
		$userpass = htmlspecialchars(trim($_POST['formpass']));
		$pwd_peppered = hash_hmac("sha256",$userpass,$pepper);
	}
	else // NO LOGIN
	{
		echo '
		<script>
			setTimeout(function(){
				window.location.href = "' . $referring_url . '";
			}, 100000);
		</script>
		';
		exit();
	}


	//! FIRST, LET'S FIND THEM IN THE USERS TABLE
	$checkUser_sql = "
	SELECT  *
	FROM `users`
	WHERE 1=1
	AND	LOWER(`user_email`) LIKE '%" . strtolower($username) . "%'
	AND LOWER(`user_active`) = 'yes'
	";

	// echo 'sql ' . nl2br($checkUser_sql) . '<br />';
	$checkUser_results = mysqli_query($con,$checkUser_sql);
	$checkUser_cnt = mysqli_num_rows($checkUser_results) ?? 0;
	$login_error = '';

	//# BAD username
	if( $checkUser_cnt < 1)
	{
		$username = '';
		$login_error = 'username';
		echo '
		<script>
			setTimeout(function(){
				window.location.href = "' . $referring_url . '?error=' . $login_error . '";
			}, 500);
		</script>
		';
		exit();
	}
	//# GOOD username
	else
	{
		while( $row = mysqli_fetch_assoc($checkUser_results) )
		{
			$login_user_ID 	=  trim($row['user_ID']);

			//! LET'S SEE IF THEY ARE AN ADMIN
			$checkAdmin_sql = "
			SELECT  *
			FROM `admin_users`
			WHERE 1=1
			AND	`admin_user_ID` = '" . $login_user_ID . "'
			AND `admin_active` = 'yes'
			";

			// echo 'sql ' . nl2br($checkUser_sql) . '<br />';
			$checkAdmin_results = mysqli_query($con,$checkAdmin_sql);
			$checkAdmin_cnt = mysqli_num_rows($checkAdmin_results) ?? 0;

			if( $checkAdmin_cnt < 1 )
			{
				$login_error = 'notadmin';
				echo '
				<script>
					setTimeout(function(){
						window.location.href = "' . $referring_url . '?error=' . $login_error . '";
					}, 500);
				</script>
				';
				exit();
			}

			$login_password 	=  trim($row['user_password']);

			$rand_term 		= array_rand($pepper_array);
			$access_token1 	= str_shuffle(strtolower($pepper_array[$rand_term]));
			$rand_term 		= array_rand($pepper_array);
			$access_token2 	= str_shuffle(strtolower($pepper_array[$rand_term]));

			$access_token 		=  $access_token1.$access_token2;
			$login_first_name 	=  trim($row['user_first_name']);
			$login_last_name 	=  trim($row['user_last_name']);
			$login_email 		=  trim($row['user_email']);

			if ( $pwd_peppered == $login_password )
			//# GOOD password
			{
				$_SESSION['loggedin'] 			= true;
				$_SESSION['access_token'] 		= $access_token;
				$_SESSION['login_first_name'] 	= $login_first_name;
				$_SESSION['login_last_name'] 	= $login_last_name;
				$_SESSION['login_email'] 		= strtolower($login_email);
				$_SESSION['admin_user_ID'] 		= $login_user_ID;
				echo '
				<script>
					setTimeout(function(){
						window.location.href = "' . $referring_url . '";
					}, 500);
				</script>
				';
			}
			else
			//# BAD password
			{
				$login_error = 'password';
				echo '
				<script>
					setTimeout(function(){
						window.location.href = "' . $referring_url . '?error=' . $login_error . '";
					}, 500);
				</script>
				';
				exit();
			}
		}
	}
?>

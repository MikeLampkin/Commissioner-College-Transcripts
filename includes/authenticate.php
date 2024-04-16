<?php
//! VERSION 7.3 - 2023 - Admin
//! Authenticate
if( !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true )
{
	include "login.php";
}
else
{
	//! START LOGIN PROCESS
	$my_admin_email = $_SESSION['login_email'];

	$sql = "
	SELECT *
	FROM `admin_users`
	WHERE LOWER(`admin_email`) LIKE '" . strtolower($my_admin_email) . "'
	AND LOWER(`admin_active`) = 'yes'
	";
	$results = mysqli_query($con,$sql) ;
	$cnt=0; if($results) { $cnt = mysqli_num_rows($results); }
	if ( $cnt >= 1 )
	{
		while( $row = mysqli_fetch_assoc($results) )
		{
			$my_admin_ID  		= $row['admin_ID'];
			$my_admin_userID  	= $row['admin_userID'];
			$my_admin_first_name  = $row['admin_first_name'];
			$my_admin_last_name  = $row['admin_last_name'];
			// $admin_email  	= $row['admin_email'];
			$my_admin_group  	= $row['admin_group'];
			$my_admin_council_IDs  	= $row['admin_councilIDs'];
		}

		//!  GET MY ICONS  // GET MY ICONS  // GET MY ICONS
		$admin_sql = "
		SELECT *
		FROM `admin_levels`
		WHERE `level_code` = '" . $my_admin_group . "'
		";
		$admin_results = mysqli_query($con,$admin_sql);
		$admin_cnt=0;$admin_cnt = mysqli_num_rows($admin_results);
		while( $admin_row = mysqli_fetch_assoc($admin_results) )
		{
			$my_level_name = $admin_row['level_name'];
			$my_level_icon = $admin_row['level_icon'];
			$my_level_code = $admin_row['level_code'];
		}
	}
	else
	{
		// //! The user is LOGGED IN but is a general user.
		$sql = "
		SELECT *
		FROM `admin_users`
		WHERE `admin_userID` = '9999999'
		AND LOWER(`admin_active`) = 'yes'
		";
		$results = mysqli_query($con,$sql) ;
		$cnt=0; if($results) { $cnt = mysqli_num_rows($results); }
		if( $cnt == 1 )
		{
			$users_sql = "
			SELECT *
			FROM `users`
			WHERE LOWER(`u_email`) = '" . trim(strtolower($my_admin_email)) . "'
			";
			$users_results = mysqli_query($con_master, $users_sql);
			$nonusers_results = mysqli_query($con, $users_sql);
			$user_cnt =0; $user_cnt = mysqli_num_rows($users_results);
			$nonuser_cnt =0; $nonuser_cnt = mysqli_num_rows($nonusers_results);
			if( $users_results == 1 )
			{
				$users_results = $users_results;
			}
			else
			{
				$users_results = $nonusers_results;
			}

			while ($users_row = mysqli_fetch_assoc($users_results)) {
				foreach($users_fields_array as $field_value)
				{
					$$field_value = $users_row[$field_value];
				}

				$my_admin_ID  		= '5';
				$my_admin_userID  	= $u_employeeID;
				$my_admin_first_name  = $u_first_name;
				$my_admin_last_name  = $u_last_name;
				// $admin_email  	= $row['admin_email'];
				$my_admin_group  	= '100';
				$my_admin_council_IDs  	= '';
			}

			//!  GET MY ICONS  // GET MY ICONS  // GET MY ICONS
			$admin_sql = "
			SELECT *
			FROM `admin_levels`
			WHERE `level_code` = '" . $my_admin_group . "'
			";
			$admin_results = mysqli_query($con,$admin_sql);
			$admin_cnt=0;$admin_cnt = mysqli_num_rows($admin_results);
			while( $admin_row = mysqli_fetch_assoc($admin_results) )
			{
				$my_level_name = $admin_row['level_name'];
				$my_level_icon = $admin_row['level_icon'];
				$my_level_code = $admin_row['level_code'];
			}
		}
		else
		{
			echo '<div class="alert alert-danger"><h4>Deactivated</h4><p>Your account has been deactivated. Please contact the administrator with any further questions.</div>';
			include "includes/footer.php";
			exit();
		}
	}

} // end of login check

?>

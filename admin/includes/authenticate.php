<?php
//! VERSION 8.1 - 2024 - Admin
//! Authenticate
//# This script runs every single time a page is loaded and if "page_is_protected" is 'yes'

if( !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true )
{
	include "login.php";
}
else
{
	//! START LOGIN PROCESS
	$login_user_ID = $_SESSION['admin_user_ID'];

	$sql = "
	SELECT *
	FROM `admin_users`
		JOIN `users`
		ON `admin_user_ID` = `user_ID`
	WHERE `admin_user_ID` = '" . $login_user_ID . "'
	AND LOWER(`admin_active`) = 'yes'
	";
	$results = mysqli_query($con,$sql);
	$cnt = mysqli_num_rows($results) ?? 0;
	if ( $cnt > 0 )
	{
		while( $users_row = mysqli_fetch_assoc($results) )
		{
			foreach($admin_users_fields_array as $field_value)
			{
				// $auth_field_name = 'my_' . $field_value;
				$$field_value = $users_row[$field_value];
			}
			foreach($users_fields_array as $field_value)
			{
				$auth_field_name = 'admin_' . $field_value;
				$$auth_field_name = $users_row[$field_value];
			}

			$level_sql = "
			SELECT *
			FROM `admin_levels`
			WHERE `level_code` = '" . $admin_level . "'
			";
			$level_results = mysqli_query($con,$level_sql);
			$level_cnt = mysqli_num_rows($level_results) ?? 0;
			while( $admin_row = mysqli_fetch_assoc($level_results) )
			{
				foreach($admin_levels_fields_array as $field_value)
				{
					$$field_value = $admin_row[$field_value];
				}
			}
		}
	}
	else
	{
		echo '<div class="alert alert-danger"><h4>Deactivated</h4><p>Your account has been deactivated. Please contact the administrator with any further questions.</div>';
		include "includes/footer.php";
		exit();
	}
} // end of login check

?>

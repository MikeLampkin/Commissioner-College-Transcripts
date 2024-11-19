#!/usr/bin/php
<?php date_default_timezone_set('America/Chicago'); ?>
<?php
//! Version 1.0 -- 10/2024
//! This scripts changes user_pro to yes, for those who have @scouting.org

	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	// include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$sql = "
	SELECT *
	FROM `users`
	WHERE `user_email` LIKE '%@scouting.org'
	AND `user_pro` <> 'yes'
	";
	$results = mysqli_query($con,$sql);
	$cnt=0; $cnt = mysqli_num_rows($results);

	if ( $cnt > 0 )
	{
		// LOOP OUTPUT TO FILL VARIABLES
		while( $row = mysqli_fetch_assoc($results) )
		{
			$user_ID = $row['user_ID'];
			$user_email = $row['user_email'];

			$update_sql = "
			UPDATE `users`
			SET `user_district` = 2,
			`user_positions` = 'Pro',
			`user_pro` = 'yes'
			WHERE `user_ID` = '" . $user_ID . "'
			";
			mysqli_query($con,$update_sql);
		}
	}

?>

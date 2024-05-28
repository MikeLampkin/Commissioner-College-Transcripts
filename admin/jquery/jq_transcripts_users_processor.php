<?php  // ** Lampkin 2024 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/admin/config/config.php';
	include 	 $config_functions;
	include 	 $config_query;
	include 	 $config_form_elements;
	include 	 $config_arrays;
	include 	 $config_functions_custom;

	$today_d = date("Y-m-d");
	$today_t = date("H:i:s");
	$today_dt = date("Y-m-d") . ' ' . date("H:i:s");
	$rightnow = date("Y-m-d") . ' ' . date("H:i:s");

	$data_results = 'success';

	$pg 		= 'transcripts_users';
	$db_table 	= 'users';

	$var_ID 	= 'user_ID';
	$var_active = 'user_active';
	// $action 	= 'insert';
	// $thisID 	= '';

	$data = file_get_contents("php://input");
	// $mydata = json_decode($data, true);
		// $admin_user = $mydata['adminUser'];
		// $form_data = $mydata['formData'];

	//! ---::: set values and create a post_array :::---
$post_cnt = count($_POST);

	unset($_POST['db_name']);
	unset($_POST['process']);

	$id_field = $_POST['id_field'];
		unset($_POST['id_field']);
	$db_table = $_POST['db_table'];
		unset($_POST['db_table']);
	$action = $_POST['action'];
		unset($_POST['action']);

	$user_ID = $_POST['user_ID'];

	$awards_cnt=0;
	foreach ( $_POST as $name => $value )
	{
		if ( in_array($name, $users_fields_array) && !empty($name) )
		{
			$awards_cnt++;
		}
	}

	$x=0;
	$awards_fields_array = array('user_council_ID','user_basic','user_arrowhead','user_comm_key','user_distinguished','user_excellence','user_bcs','user_mcs','user_dcs');
	$transcript_fields_array = array('course_ID', 'course_type','course_number', 'course_council_ID','course_year');

	$course_ID_array = $_POST['course_ID'];
	$course_type_array = $_POST['course_type'];
	$course_number_array = $_POST['course_number'];
	$course_council_ID_array = $_POST['course_council_ID'];
	$course_year_array = $_POST['course_year'];

	$awards_cnt = count($awards_fields_array);
	$transcript_cnt = count($course_ID_array);

	//! ---::: set values and create a _POST :::---
	$awards_sql = "
	UPDATE `users`
	SET ";
	$x=0;
	foreach( $awards_fields_array AS $field )
	{
		$x++;
		$awards_sql .= "`" . $field . "` = '" . $_POST[$field] . "'";
		if( $x < $awards_cnt )
		{
			$awards_sql .= ", ";
		}
	};
	$awards_sql .= "
	WHERE `user_ID` = '" . $user_ID . "'
	";

	// echo nl2br($awards_sql);
	mysqli_query($con,$awards_sql);

	$safety_sql = "
	SELECT *
	FROM `transcripts`
	WHERE `transcript_user_ID` = '" . $user_ID . "'
	";
	// echo nl2br($safety_sql);
	$safety_results = mysqli_query($con,$safety_sql);
	$safety_cnt = mysqli_num_rows($safety_results) ?? 0;

	$transcripts_safety_sql_fields_array = $transcripts_safety_fields_array;
	$key = array_search('transcript_ID', $transcripts_safety_sql_fields_array);
	unset($transcripts_safety_sql_fields_array[$key]);

	if( $safety_cnt > 0 )
	{
		while( $safety_row = mysqli_fetch_assoc($safety_results) )
		{
			$transcript_ID = $safety_row['transcript_ID'];

			$safety_insert_sql = "";
			$safety_insert_sql .= "
			INSERT INTO
			`transcripts_safety` (`";
			$safety_insert_sql .=  implode("`, `",$transcripts_safety_sql_fields_array);
			$safety_insert_sql .= "`) VALUES (";
			$x=0;
			foreach( $transcripts_safety_sql_fields_array AS $field_keys => $field_value )
			{
				$x++;
				$safety_insert_sql .= "'" . $safety_row[$field_value] . "'";
				if( $x < count($transcripts_safety_sql_fields_array) )
				{
					$safety_insert_sql .= ", ";
				}
			}
			$safety_insert_sql .= ")";
			// echo nl2br($safety_insert_sql);
			if( mysqli_query($con,$safety_insert_sql) )
			{
				$delete_sql = "DELETE FROM `transcripts` WHERE `transcript_ID` = '" . $transcript_ID . "'";
				// echo nl2br($delete_sql);
				mysqli_query($con,$delete_sql);
			}
		}
	}

	for( $x=0; $x<$transcript_cnt; $x++ )
	{
		if( strlen($course_year_array[$x] ?? 0) > 0 )
		{
			$transcript_insert_sql  = "
			INSERT INTO `transcripts`
			(
				`transcript_user_ID`,
				`transcript_course_ID`,
				`transcript_council_ID`,
				`transcript_year`
			)
			VALUES
			(
				'" . $user_ID . "',
				'" . $course_ID_array[$x] . "',
				'" . $course_council_ID_array[$x] . "',
				'" . $course_year_array[$x] . "'
			)";

			// echo $transcript_insert_sql . '<br />';

			if( mysqli_query($con,$transcript_insert_sql) )
			{
				$data_results = 'success';
			}
			else
			{
				$data_results = 'error';
			}
		}
	}

	echo trim($data_results);

	?>

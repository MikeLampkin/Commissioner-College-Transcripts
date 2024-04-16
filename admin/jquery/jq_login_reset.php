<?php  // ** Lampkin 2023 ** // ?>
<?php // ** v 5.00 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/query.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/arrays.php';

	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/functions_custom.php";

$today_d = date("Y-m-d");
$today_t = date("H:i:s");
$today_dt = date("Y-m-d") . ' ' . date("H:i:s");
$rightnow = date("Y-m-d") . ' ' . date("H:i:s");

$db_table = 'users';

$data_results = 'error';

$data = file_get_contents("php://input");
$mydata = json_decode($data, true);
	$login_email = filter_var($mydata['emailVal'], FILTER_SANITIZE_EMAIL);
	$ip_address = $mydata['ipVal'];

	if( strlen($login_email) > 10 )
	{
		$message_ok = 'no';

		// $message
		$message = date('M d, Y g:i a') . '<br />';

		$subject = 'Commissioner College: Reset Password ';

		$sender_name = $config_sender_name;
		$sender_address = $config_sender_address;
		$replyto_name = $config_sender_name;
		$replyto_address = $config_sender_address;

		// $recipient_name
		// $recipient_address
		$sql = "
		SELECT  *
		FROM `users`
		WHERE 1=1
		AND LOWER(`user_email`) LIKE '%" . strtolower($login_email) . "%'
		";
		$results = mysqli_query($con,$sql);
		$results_cnt = '0'; @$results_cnt = mysqli_num_rows($results);

		if( $results_cnt > 0 )
		{
			while( $row = mysqli_fetch_assoc($results) )
			{
				foreach($users_fields_array as $field_key => $field_value)
				{
					$$field_value = $row[$field_value];
				}
				$user_full_name = fullName($user_prefix,$user_first_name,$user_nick_name,$user_middle_name,$user_last_name,$user_suffix);

				$recipient_name = $user_full_name;
				$recipient_address = $user_email;
				$coded_date = urlencode(strtotime('now'));
				$coded_email = urlencode(scramble($user_email));
				$my_code = $coded_date . '||' . $coded_email;
				$my_link = 'https://commissionercollege.com/password-reset';
				$my_link_code = 'https://commissionercollege.com/password-reset?reset='. $my_code;
				$message .= '
				Good news! We found "' . $login_email . '" in the database. <br /><br />

				To reset your password, please follow this link:
				<br />
				<a href="' . $my_link_code . '">' . $my_link . '</a>
				<br /><br />
				Or visit this page:
				<br />
				' . $my_link . '
				<br />
				and paste this code into the form:
				<br />
				' . $my_code . '
				<br />
				<br />
				Please note: This code is only good for 48 hours.
				<br />
				<br />
				If you find this response in error, please contact the system administrator.<br />
				If you did not request this information, please disregard.
				';

				$attempt_sql = "
				INSERT INTO `attempts` ( `attempt_email`, `attempt_type`, `attempt_success`, `attempt_IP`)
				VALUES ( '" . $login_email . "', 'Password', 'yesSent', '" . $ip_address . "')
				";
				@mysqli_query($con,$attempt_sql);
			}
		}
		else
		{
				$recipient_name = '';
				$recipient_address = $login_email;
				// $message .= '
				// Unfortunately we did not find "' . $login_email . '" in the database.
				// <br />
				// <br />
				// If you find this response in error, please contact the system administrator.<br />
				// If you did not request this information, please disregard.
				// ';

				$attempt_sql = "
				INSERT INTO `attempts` ( `attempt_email`, `attempt_type`, `attempt_success`, `attempt_IP`)
				VALUES ( '" . $login_email . "', 'Password', 'notFound', '" . $ip_address . "')
				";
				@mysqli_query($con,$attempt_sql);
		}

		$message_ok = 'yes';

		if ( $message_ok == 'yes' )
		{
			$data_results = 'success';

			include  $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/mailme.php';
		}
	}

echo $data_results;

?>

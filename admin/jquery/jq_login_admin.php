<?php  // ** Lampkin 2023 ** // ?>
<?php // ** v 5.00 ** // ?>

<?php
	include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/query.php';
	// include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
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
	$this_email = filter_var($mydata['formLogin'], FILTER_SANITIZE_EMAIL);
	$password_raw = filter_var($mydata['formPass'], FILTER_SANITIZE_EMAIL);

	$password_new = hash_hmac("sha256", $password_raw, $pepper);

	if( strlen($this_email) < 4 )
	{
		$data_results = 'email';
	}
	else
	{
		$sql = "
		SELECT *
		FROM `users`
		WHERE LOWER(`user_email`) LIKE '" . $this_email . "'
		";
		$results = mysqli_query($con, $sql);
		$cnt = mysqli_num_rows($results) ?? 0;
		if( $cnt < 1 )
		{
			$data_results = 'noadmin';
		}
		else
		{
			while ($row = mysqli_fetch_assoc($results))
			{
				foreach ($users_fields_array as $field_key => $field_value)
				{
					$$field_value = $row[$field_value];
				}

				if( $user_password !== $password_new )
				{
					$data_results = 'password';
				}
				else
				{
					$data_results = 'success';
					$_SESSION['loggedin'] = true;
					$_SESSION['login_email'] = $this_email;
				}
			}
		}
	}

	echo $data_results;
?>

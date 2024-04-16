<?php  // ** Lampkin 2023 ** // ?>
<?php // ** v 5.00 ** // ?>

<?php
include 	 $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/query.php';
// include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/form_elements.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/arrays.php';

$today_d = date("Y-m-d");
$today_t = date("H:i:s");
$today_dt = date("Y-m-d") . ' ' . date("H:i:s");
$rightnow = date("Y-m-d") . ' ' . date("H:i:s");

$db_table = 'users';
$processor_sql = '';// EMPTY THIS VARIABLE

$data = file_get_contents("php://input");
$mydata = json_decode($data, true);
	$this_email 			= strtolower(trim($mydata['email']));
	$password_raw 	= trim($mydata['data']);

	$password_new = hash_hmac("sha256", $password_raw, $pepper);

	if( strlen($this_email) > 4 )
	{
		$sql = "
		SELECT *
		FROM `users`
		WHERE LOWER(`user_email`) LIKE '" . $this_email . "'
		";
		$results = mysqli_query($con, $sql);
		$results_cnt = '0';
		@$results_cnt = mysqli_num_rows($results);
		while ($row = mysqli_fetch_assoc($results))
		{
			foreach ($users_fields_array as $field_key => $field_value)
			{
				$$field_value = $row[$field_value];
			}

			$processor_sql = "
			UPDATE `users`
			SET `user_password` = '" . $password_new . "'
			WHERE `user_ID` = '" . $user_ID . "'
			";
			// $data_results = $processor_sql;

			if( mysqli_query($con,$processor_sql) )
			{
				$data_results = 'success';
			}
			else
			{
				$data_results = 'error';
			}
		}

	}
echo $data_results;
?>

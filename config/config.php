<?php  // ** CommissionerCollege Lampkin 2024 ** // ?>
<?php // ** CONFIG 8.0 ** // ?>

<?php
	date_default_timezone_set('America/Chicago');

	$domain_name 		= 'commissionercollege.com';
	$domain_email 		= 'commissionercollege.com';

	$app_name 			= 'transcripts';
	$app_short_name 	= 'Commissioner Transcripts';
	$app_full_name 		= 'Commissioner College Transcript System';
	$db_name 			= 'transcript';

	$authenticate_methods = array('Simple','COH','Google','Facebook','Instagram','Reddit','Twitter','Yahoo');
	$public_authenticate_methods = array('Simple');
	$admin_authenticate_methods = array('Simple');

	$login_logo 	= 'img/commissioner-college-logo.png';
	$app_logo 		= 'img/commissioner-college-logo.png';

	$app_icon 		= '<i class="fa-solid fa-truck-monster"></i>';

	$show_errors = 'yes';

	$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

	$today_d = date("Y-m-d");
	$today_t = date("H:i:s");
	$today_dt = date("Y-m-d") . ' ' . date("H:i:s");
	$rightnow = date("Y-m-d") . ' ' . date("H:i:s");

	//! ITEMS BELOW REQUIRE config_private.php
	require 'config_private.php';
	$db_name 		= 'transcripts';
	$db_user        = 'webapp';
	$db_pass        = 'Cost@Ric@2017!';

	$db_host        = 'localhost';
	$db_database    = $db_name;
	$db_port        = '3306';
	$con = mysqli_connect($db_host,$db_user,$db_pass,$db_database,$db_port);

	$db_master 	= 'master';
	$con_master = mysqli_connect($db_host,$db_user,$db_pass,$db_master,$db_port);

	if (mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error()); echo 'ERRRORRORRRR'; //exit();
	}

?>

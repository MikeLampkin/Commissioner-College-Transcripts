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

$db_table = 'events';

// $fields_array = query('getColumns', $db_table, $db_name, '', '', '');
$processor_sql = '';// EMPTY THIS VARIABLE

$data = file_get_contents("php://input");
$mydata = json_decode($data, true);
	$e_validate = $mydata['eValidate'];
	$e_date 	= $mydata['eDate'];
	$e_council_ID 	= $mydata['eCouncil'];
	$e_virtual = $mydata['eVirtual'];
	$e_location = $mydata['eLocation'];
	$e_time = $mydata['eTime'];
	$e_cost = $mydata['eCost'];
	$e_offerings = $mydata['eOfferings'];
	$e_info = $mydata['eInfo'];
	$e_url = $mydata['eUrl'];
	$e_time_start = $mydata['eTimeStart'];
	$e_time_end = $mydata['eTimeEnd'];
	$e_submit_name = $mydata['eSubmitName'];
	$e_submit_email = $mydata['eSubmitEmail'];

$processor_sql = "
INSERT INTO `events`
(
	`e_date`,
	`e_council_ID`,
	`e_virtual`,
	`e_location`,
	`e_time`,
	`e_cost`,
	`e_offerings`,
	`e_info`,
	`e_url`,
	`e_time_start`,
	`e_time_end`,
	`e_submit_name`,
	`e_submit_email`,
	`e_first_update`,
	`e_active`
)
VALUES
(
'" . $e_date . "',
'" . $e_council_ID . "',
'" . $e_virtual . "',
'" . $e_location . "',
'" . $e_time . "',
'" . $e_cost . "',
'" . $e_offerings . "',
'" . $e_info . "',
'" . $e_url . "',
'" . $e_time_start . "',
'" . $e_time_end . "',
'" . $e_submit_name . "',
'" . $e_submit_email . "',
'" . $today_d . " " . $today_t . "',
'uap'
)
";

// echo nl2br($processor_sql) . '<br />';
// echo $processor_sql;
if( mysqli_query($con,$processor_sql) )
{
	$data_results = 'success';

	//! MAILME =====================
	$subject = 'CCS Event: ' . $e_council_ID . '(' . date('M d, Y') . ')';

	$message = '
	Date: ' . date('M d, Y h:i a') . '<br /><br />

	Council: ' . $e_council_ID . '<br />
	Event: ' . $e_date . '<br />
	Virtual: ' . $e_virtual . '<br />
	URL: ' . $e_url . '<br />
	Start: ' . $e_time_start . '<br />
	End: ' . $e_time_end . '<br /><br />

	Name: ' . $e_submit_name . '<br />
	Email: ' . $e_submit_email . '<br />
	===== <br />
	<a href="https://commissionercollege.com/admin">https://commissionercollege.com/admin</a> <br />
	';
	$message_ok = 'yes';
	$mail_active = 'yes';


	$replyto_name = 'CCS Event';
	$replyto_address ='no-reply@commmissionercollege.com';

	$recipient_array = array(
		 'mike@lampkin.net' => 'Mike Lampkin',
		 'mike.lampkin@houstontx.gov' => 'Mike Lampkin',
	);
	// $cc_array = array('');
	// $bcc_array = array('');

	if ( $message_ok == 'yes' && $mail_active == 'yes' )
	{
		include  $_SERVER['DOCUMENT_ROOT'] . '/includes/mailme.php';
	}
}
else
{
	$data_results = 'error';
}
echo $data_results;

$processor_sql = '';// EMPTY THIS VARIABLE
?>

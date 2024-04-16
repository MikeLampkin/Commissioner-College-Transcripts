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

$waiting_list_fields_array = query('getColumns', 'waiting_list', $db_name, '', '', '');
$processor_sql = '';// EMPTY THIS VARIABLE

$data = file_get_contents("php://input");
$mydata = json_decode($data, true);
	$wl_validate = $mydata['wlValidate'];
	$wl_submit_name 	= $mydata['wlName'];
	$wl_submit_email 	= $mydata['wlEmail'];
	$wl_council_ID = $mydata['wlCouncil'];

$processor_sql = "
INSERT INTO `waiting_list`
(
	`wl_submit_name`,
	`wl_submit_email`,
	`wl_council_ID`,
	`wl_qty`,
	`wl_first_update`,
	`wl_active`
)
VALUES
(
'" . $wl_submit_name . "',
'" . $wl_submit_email . "',
'" . $wl_council_ID . "',
'100',
'" . $today_d . " " . $today_t . "',
'uap'
)
";

// echo nl2br($processor_sql) . '<br />';
if( mysqli_query($con,$processor_sql) )
{
	$data_results = 'success';

	//! MAILME =====================
	$message = '
	Date: ' . date('M d, Y h:i a') . '<br />
	Name: ' . $wl_submit_name . '<br />
	Email: ' . $wl_submit_email . '<br />
	Council: ' . $wl_council_ID . '<br />
	===== <br />
	<a href="https://commissionercollege.com/admin">https://commissionercollege.com/admin</a> <br />
	';
	$message_ok = 'yes';
	$mail_active = 'yes';

	$subject = 'Waiting List: ' . date('M d, Y') . ' ' . $wl_submit_name ;

	$replyto_name = 'Waiting List';
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

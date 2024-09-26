<?php date_default_timezone_set('America/Chicago'); ?>
<!-- START - <?php //$message .= date("Y-m-d") . ' ' . date("h:i:s A"); ?><br /> -->
degree_review.php -- S T A R T - <?php echo date("Y-m-d") . ' ' . date("h:i:s A"); ?><br />

<?php
//! This script evaluates the participants who have earned degrees;
//! Then it emails the list to the people listed in the table "emailers"

$message_ok = 'no';

// $message
$message = date('M d, Y g:i a') . '<br />';
$message .= 'Automatic Transcript Review' . '<br />';


?>
<script>
function calcDegree(obj,element)
{
	let postData = {type:obj};
	$.ajax({
		url: 		"degree_calculator.php",
		method: 	"POST",
		dataType:	"text",
		data: 		JSON.stringify(postData),
		success:	function(response)
		{
			var returndata = '';
			try
			{
				$('#'+element).html(response);
			}
			catch(e)
			{
				alert('Problem');
			}
		},
		error: function(response)
		{
			console.log('PROCESSOR ERROR: ' + response);
		}
	});
}

calcDegree('bcs','bcs');

$(document).ready(function()
{
});
</script>
<input type="hidden" name="bcs" id="bcs_input">

<?php
// $subject
$subject = 'Data processed ';

// $sender_name
// $sender_address
$sender_name = 'Commissioner Transcripts';
$sender_address = 'no-reply@commissionercollege.com';

// $replyto_name
// $replyto_address
$replyto_name = 'Commissioner Transcripts';
$replyto_address = 'no-reply@commissionercollege.com';

// $recipient_name
// $recipient_address
function getAddresses($type)
{
	global $con;
	$getRecipients_sql = "
	SELECT  *
	FROM `degree_team`
	WHERE `dt_active` = 'yes'
	AND `dt_type` = '" . $type . "'
	";
	$getRecipients = mysqli_query($con,$getRecipients_sql);
	$getRecipients_cnt = '0'; @$getRecipients_cnt = mysqli_num_rows($getRecipients);
	$array = [];
	while( $row = mysqli_fetch_assoc($getRecipients) )
	{
		$name = addslashes(trim($row['dt_name']));
		$address = trim($row['dt_address']);
		$array[$address] = $name;
	}
	return $array;
}

// $recipient_array = getAddresses('to');
// $cc_array = getAddresses('cc');
// $bcc_array = getAddresses('bcc');

$recipient_array = array(
	'mike@lampkin.net' => 'Mike Lampkin',
	'mike.lampkin@houstontx.gov' => 'Mike Lampkin',
	'mike@lampkin.net' => 'Mike Lampkin',
);
// $recipient_name = 'Mike Lampkin';
// $recipient_address = 'mike.lampkin@houstontx.gov';

$message_ok = 'no';

?>






<?php
	if ( $message_ok == 'yes' )
	{
		include "mailme.php";
	}
?>
<?php $message .= date("Y-m-d") . ' ' . date("h:i:s A"); ?><br />
